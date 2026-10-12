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
            // Valeurs par défaut pour l'environnement Docker local.
            $mongoUri = getenv('MONGO_URI') ?: 'mongodb://mongodb:27017';
            $dbName = getenv('MONGO_DB') ?: 'vite_gourmand_mongo_generally';

            // Limite le temps d'attente si MongoDB ne répond pas.
            $client = new \MongoDB\Client(
                $mongoUri,
                [],
                ['serverSelectionTimeoutMS' => 5000]
            );

            $this->db = $client->selectDatabase($dbName);

            // Vérifie réellement la disponibilité du serveur.
            $this->db->command(['ping' => 1]);
            $this->connected = true;

        } catch (\Throwable $e) {
            // Une panne MongoDB ne doit pas empêcher les commandes MySQL.
            $this->connected = false;
            error_log('[MongoService] Connexion impossible : ' . $e->getMessage());
        }
    }

    /**
     * Indique si MongoDB est disponible.
     */
    public function isConnected()
    {
        return $this->connected;
    }

    /**
     * Crée ou met à jour les statistiques d'une commande.
     *
     * commande_id correspond à l'identifiant MySQL et sert de clé
     * de synchronisation pour éviter de compter deux fois la commande.
     *
     * Le statut est obligatoire : il permet de conserver les commandes
     * annulées dans MongoDB sans les inclure dans les totaux actifs.
     */
    public function enregistrerCommande($data)
    {
        if (!$this->connected) {
            return false;
        }

        try {
            // Refuse une synchronisation incomplète plutôt que d'enregistrer
            // des données statistiques trompeuses.
            $champsRequis = [
                'commande_id',
                'menu_id',
                'menu_titre',
                'prix_total',
                'nb_personnes',
                'date',
                'statut'
            ];

            foreach ($champsRequis as $champ) {
                if (!isset($data[$champ])) {
                    throw new \InvalidArgumentException('Champ manquant : ' . $champ);
                }
            }

            // La date de prestation est conservée au format BSON MongoDB.
            $timestamp = strtotime((string) $data['date']);
            if ($timestamp === false) {
                throw new \InvalidArgumentException('Date de prestation invalide.');
            }

            // Vérifie que le statut n'est pas une chaîne vide.
            $statut = trim((string) $data['statut']);
            if ($statut === '') {
                throw new \InvalidArgumentException('Statut de commande vide.');
            }

            // Ces champs reflètent les valeurs officielles de MySQL.
            $document = [
                'type' => 'commande',
                'commande_id' => (int) $data['commande_id'],
                'menu_id' => (int) $data['menu_id'],
                'menu_titre' => (string) $data['menu_titre'],
                'prix_total' => (float) $data['prix_total'],
                'nb_personnes' => (int) $data['nb_personnes'],
                'statut' => $statut,
                'date' => new \MongoDB\BSON\UTCDateTime($timestamp * 1000)
            ];

            // Met à jour le document existant ou l'insère s'il est absent.
            // created_at reste inchangé pour les commandes déjà présentes.
            $this->db->statistiques->updateOne(
                [
                    'type' => 'commande',
                    'commande_id' => (int) $data['commande_id']
                ],
                [
                    '$set' => $document,
                    '$setOnInsert' => [
                        'created_at' => new \MongoDB\BSON\UTCDateTime()
                    ]
                ],
                ['upsert' => true]
            );

            return true;

        } catch (\Throwable $e) {
            // Journalise l'erreur sans interrompre le traitement MySQL.
            error_log('[MongoService] Synchronisation impossible : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Retourne le filtre commun aux statistiques de commandes actives.
     *
     * Une commande annulée reste enregistrée pour l'historique,
     * mais n'entre pas dans les calculs.
     *
     * Les anciens documents sans statut sont exclus par précaution :
     * ils devront être resynchronisés à partir de MySQL.
     */
    private function filtreCommandesActives()
    {
        return [
            'type' => 'commande',
            'statut' => [
                '$exists' => true,
                '$nin' => ['annulee', 'annulée', '']
            ]
        ];
    }

    /**
     * Retourne les statistiques des commandes non annulées par menu.
     * Le montant indiqué n'est pas nécessairement un paiement encaissé.
     */
    public function getStatsByMenu()
    {
        if (!$this->connected) {
            return [];
        }

        try {
            $pipeline = [
                ['$match' => $this->filtreCommandesActives()],
                [
                    '$group' => [
                        '_id' => '$menu_titre',
                        'nb_commandes' => ['$sum' => 1],
                        'ca_total' => ['$sum' => '$prix_total'],
                        'nb_personnes' => ['$sum' => '$nb_personnes']
                    ]
                ],
                ['$sort' => ['ca_total' => -1]]
            ];

            return iterator_to_array($this->db->statistiques->aggregate($pipeline));

        } catch (\Throwable $e) {
            error_log('[MongoService] Statistiques par menu : ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Calcule le montant total des commandes non annulées.
     *
     * Le nom getCaTotal() est conservé pour ne pas casser le contrôleur
     * et la vue existants. Ce montant ne prouve pas un encaissement.
     */
    public function getCaTotal()
    {
        if (!$this->connected) {
            return 0;
        }

        try {
            $pipeline = [
                ['$match' => $this->filtreCommandesActives()],
                [
                    '$group' => [
                        '_id' => null,
                        'total' => ['$sum' => '$prix_total']
                    ]
                ]
            ];

            $result = iterator_to_array($this->db->statistiques->aggregate($pipeline));
            return $result[0]['total'] ?? 0;

        } catch (\Throwable $e) {
            error_log('[MongoService] Calcul du total : ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Retourne les statistiques mensuelles des commandes non annulées.
     *
     * Le regroupement reste basé sur created_at (date de création du
     * document statistique), conformément au comportement existant.
     * Il ne correspond pas forcément au mois de la prestation.
     */
    public function getStatsByMois()
    {
        if (!$this->connected) {
            return [];
        }

        try {
            $pipeline = [
                ['$match' => $this->filtreCommandesActives()],
                [
                    '$group' => [
                        '_id' => [
                            'mois' => ['$month' => '$created_at'],
                            'annee' => ['$year' => '$created_at']
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

            return iterator_to_array($this->db->statistiques->aggregate($pipeline));

        } catch (\Throwable $e) {
            error_log('[MongoService] Statistiques mensuelles : ' . $e->getMessage());
            return [];
        }
    }
}
