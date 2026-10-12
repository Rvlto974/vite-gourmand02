
<?php

// Charge les classes MongoDB installées avec Composer.
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Service chargé de l'enregistrement et de la lecture
 * des statistiques de commandes dans MongoDB.
 *
 * MySQL reste la source officielle des commandes.
 * MongoDB est utilisé uniquement pour les statistiques.
 */
class MongoService
{
    private $db = null;
    private $connected = false;

    /**
     * Initialise et vérifie la connexion MongoDB.
     */
    public function __construct()
    {
        try {
            // Récupère les paramètres de connexion.
            // Les valeurs par défaut correspondent à Docker local.
            $mongoUri = getenv('MONGO_URI') ?: 'mongodb://mongodb:27017';
            $dbName = getenv('MONGO_DB') ?: 'vite_gourmand_mongo_generally';

            // Crée le client avec un délai de connexion maximal
            // de cinq secondes.
            $client = new \MongoDB\Client(
                $mongoUri,
                [],
                ['serverSelectionTimeoutMS' => 5000]
            );

            // Sélectionne la base contenant les statistiques.
            $this->db = $client->selectDatabase($dbName);

            // Vérifie que le serveur répond réellement.
            // Une exception est déclenchée en cas d'échec.
            $this->db->command(['ping' => 1]);

            // Active le service uniquement après vérification.
            $this->connected = true;

        } catch (\Throwable $e) {
            // Ne bloque pas l'application si MongoDB est indisponible.
            $this->connected = false;

            // Conserve une trace technique dans les journaux PHP.
            error_log(
                '[MongoService] Connexion impossible : ' .
                $e->getMessage()
            );
        }
    }

    /**
     * Indique si la connexion MongoDB est disponible.
     */
    public function isConnected()
    {
        return $this->connected;
    }

    /**
     * Enregistre ou actualise une statistique de commande.
     *
     * L'identifiant MySQL sert de clé de synchronisation :
     * une même commande ne doit pas être comptée deux fois.
     *
     * Retourne true si l'opération réussit, false sinon.
     */
    public function enregistrerCommande($data)
    {
        // Interrompt uniquement la synchronisation si MongoDB
        // n'est pas disponible.
        if (!$this->connected) {
            return false;
        }

        try {
            // Vérifie les informations indispensables.
            $champsRequis = [
                'commande_id',
                'menu_id',
                'menu_titre',
                'prix_total',
                'nb_personnes',
                'date'
            ];

            foreach ($champsRequis as $champ) {
                if (!isset($data[$champ])) {
                    throw new \InvalidArgumentException(
                        'Champ manquant : ' . $champ
                    );
                }
            }

            // Vérifie la validité de la date de prestation.
            $timestamp = strtotime($data['date']);

            if ($timestamp === false) {
                throw new \InvalidArgumentException(
                    'Date de prestation invalide.'
                );
            }

            // Prépare les données nécessaires aux statistiques.
            $document = [
                'type' => 'commande',
                'commande_id' => (int) $data['commande_id'],
                'menu_id' => (int) $data['menu_id'],
                'menu_titre' => (string) $data['menu_titre'],
                'prix_total' => (float) $data['prix_total'],
                'nb_personnes' => (int) $data['nb_personnes'],

                // Date prévue pour la prestation.
                'date' => new \MongoDB\BSON\UTCDateTime(
                    $timestamp * 1000
                )
            ];

            // Recherche la commande par son identifiant MySQL.
            // Si elle existe, ses statistiques sont actualisées.
            // Sinon, un nouveau document est créé.
            $this->db->statistiques->updateOne(
                [
                    'type' => 'commande',
                    'commande_id' => (int) $data['commande_id']
                ],
                [
                    '$set' => $document,

                    // Conserve la date de création initiale.
                    '$setOnInsert' => [
                        'created_at' => new \MongoDB\BSON\UTCDateTime()
                    ]
                ],
                ['upsert' => true]
            );

            return true;

        } catch (\Throwable $e) {
            // Journalise l'erreur sans interrompre la commande MySQL.
            error_log(
                '[MongoService] Synchronisation impossible : ' .
                $e->getMessage()
            );

            return false;
        }
    }

    /**
     * Retourne les statistiques regroupées par menu.
     *
     * Chaque document de type "commande" représente
     * une commande enregistrée dans MongoDB.
     */
    public function getStatsByMenu()
    {
        if (!$this->connected) {
            return [];
        }

        try {
            $pipeline = [
                [
                    '$match' => [
                        'type' => 'commande'
                    ]
                ],
                [
                    '$group' => [
                        '_id' => '$menu_titre',
                        'nb_commandes' => ['$sum' => 1],
                        'ca_total' => ['$sum' => '$prix_total'],
                        'nb_personnes' => ['$sum' => '$nb_personnes']
                    ]
                ],
                [
                    '$sort' => [
                        'ca_total' => -1
                    ]
                ]
            ];

            // Exécute l'agrégation et retourne les résultats.
            return iterator_to_array(
                $this->db->statistiques->aggregate($pipeline)
            );

        } catch (\Throwable $e) {
            error_log(
                '[MongoService] Statistiques par menu : ' .
                $e->getMessage()
            );

            return [];
        }
    }

    /**
     * Calcule le montant total des commandes synchronisées.
     *
     * Attention : ce montant ne correspond pas nécessairement
     * au chiffre d'affaires encaissé. Les règles de prise en
     * compte des statuts devront être définies séparément.
     */
    public function getCaTotal()
    {
        if (!$this->connected) {
            return 0;
        }

        try {
            $pipeline = [
                [
                    '$match' => [
                        'type' => 'commande'
                    ]
                ],
                [
                    '$group' => [
                        '_id' => null,
                        'total' => [
                            '$sum' => '$prix_total'
                        ]
                    ]
                ]
            ];

            $result = iterator_to_array(
                $this->db->statistiques->aggregate($pipeline)
            );

            return $result[0]['total'] ?? 0;

        } catch (\Throwable $e) {
            error_log(
                '[MongoService] Calcul du total : ' .
                $e->getMessage()
            );

            return 0;
        }
    }

    /**
     * Retourne les statistiques regroupées par mois et année.
     *
     * Le regroupement utilise la date de création de
     * l'enregistrement statistique, et non la date de prestation.
     */
    public function getStatsByMois()
    {
        if (!$this->connected) {
            return [];
        }

        try {
            $pipeline = [
                [
                    '$match' => [
                        'type' => 'commande'
                    ]
                ],
                [
                    '$group' => [
                        '_id' => [
                            'mois' => [
                                '$month' => '$created_at'
                            ],
                            'annee' => [
                                '$year' => '$created_at'
                            ]
                        ],
                        'nb_commandes' => ['$sum' => 1],
                        'ca_total' => ['$sum' => '$prix_total']
                    ]
                ],
                [
                    '$sort' => [
                        '_id.annee' => 1,
                        '_id.mois' => 1
                    ]
                ]
            ];

            return iterator_to_array(
                $this->db->statistiques->aggregate($pipeline)
            );

        } catch (\Throwable $e) {
            error_log(
                '[MongoService] Statistiques mensuelles : ' .
                $e->getMessage()
            );

            return [];
        }
    }
}
