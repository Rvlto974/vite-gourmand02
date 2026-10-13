<?php

// Chargement des dépendances MongoDB installées avec Composer.
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Service MongoDB de Vite & Gourmand.
 *
 * Rôle :
 * - Enregistrer les données statistiques des commandes.
 * - Calculer les montants et les commandes par menu.
 * - Regrouper les commandes par jour, semaine et mois.
 *
 * IMPORTANT :
 * MySQL reste la source officielle des commandes.
 * MongoDB est utilisé uniquement pour les statistiques.
 */
class MongoService
{
    private $db = null;
    private $connected = false;

    /**
     * Initialise la connexion à MongoDB.
     *
     * Une panne MongoDB ne doit pas empêcher
     * l'application de fonctionner avec MySQL.
     */
    public function __construct()
    {
        try {
            // Paramètres de connexion configurables par environnement.
            $mongoUri = getenv('MONGO_URI') ?: 'mongodb://mongodb:27017';
            $dbName = getenv('MONGO_DB') ?: 'vite_gourmand_mongo_generally';

            // Création du client MongoDB avec délai de connexion limité.
            $client = new \MongoDB\Client(
                $mongoUri,
                [],
                ['serverSelectionTimeoutMS' => 5000]
            );

            // Sélection de la base de données.
            $this->db = $client->selectDatabase($dbName);

            // Vérification réelle de la disponibilité du serveur.
            $this->db->command(['ping' => 1]);

            $this->connected = true;

        } catch (\Throwable $e) {
            $this->connected = false;

            // Journalisation sans interrompre l'application.
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
     * Enregistre ou met à jour les statistiques d'une commande.
     *
     * L'identifiant commande_id correspond à MySQL.
     * Il évite de créer plusieurs documents pour une même commande.
     *
     * Les commandes annulées restent enregistrées
     * mais sont exclues des statistiques actives.
     */
    public function enregistrerCommande($data)
    {
        if (!$this->connected) {
            return false;
        }

        try {
            // Liste des champs obligatoires.
            $champsRequis = [
                'commande_id',
                'menu_id',
                'menu_titre',
                'prix_total',
                'nb_personnes',
                'date',
                'statut'
            ];

            // Vérification des données nécessaires.
            foreach ($champsRequis as $champ) {
                if (!isset($data[$champ])) {
                    throw new \InvalidArgumentException(
                        'Champ manquant : ' . $champ
                    );
                }
            }

            // Conversion de la date de prestation.
            $timestamp = strtotime((string) $data['date']);

            if ($timestamp === false) {
                throw new \InvalidArgumentException(
                    'Date de prestation invalide.'
                );
            }

            // Vérification du statut.
            $statut = trim((string) $data['statut']);

            if ($statut === '') {
                throw new \InvalidArgumentException(
                    'Statut de commande vide.'
                );
            }

            // Préparation du document statistique.
            $document = [
                'type' => 'commande',
                'commande_id' => (int) $data['commande_id'],
                'menu_id' => (int) $data['menu_id'],
                'menu_titre' => (string) $data['menu_titre'],
                'prix_total' => (float) $data['prix_total'],
                'nb_personnes' => (int) $data['nb_personnes'],
                'statut' => $statut,
                'date' => new \MongoDB\BSON\UTCDateTime(
                    $timestamp * 1000
                )
            ];

            // Mise à jour ou insertion du document.
            // created_at est défini uniquement à la création.
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
            error_log(
                '[MongoService] Synchronisation impossible : ' .
                $e->getMessage()
            );

            return false;
        }
    }

    /**
     * Filtre commun pour toutes les statistiques.
     *
     * Seules les commandes non annulées sont comptabilisées.
     * Les anciens documents sans statut sont exclus.
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
     * Statistiques par menu.
     *
     * Retourne :
     * - Le nom du menu.
     * - Le nombre de commandes.
     * - Le nombre total de personnes.
     * - Le montant total des commandes.
     */
    public function getStatsByMenu()
    {
        if (!$this->connected) {
            return [];
        }

        try {
            $pipeline = [
                // Exclure les commandes annulées.
                [
                    '$match' => $this->filtreCommandesActives()
                ],

                // Regrouper les commandes par nom de menu.
                [
                    '$group' => [
                        '_id' => '$menu_titre',
                        'nb_commandes' => ['$sum' => 1],
                        'ca_total' => ['$sum' => '$prix_total'],
                        'nb_personnes' => ['$sum' => '$nb_personnes']
                    ]
                ],

                // Trier par montant décroissant.
                [
                    '$sort' => ['ca_total' => -1]
                ]
            ];

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
     * Calcule le montant total des commandes non annulées.
     *
     * Le nom getCaTotal() est conservé pour assurer
     * la compatibilité avec le contrôleur existant.
     *
     * Ce montant ne représente pas nécessairement
     * des paiements réellement encaissés.
     */
    public function getCaTotal()
    {
        if (!$this->connected) {
            return 0;
        }

        try {
            $pipeline = [
                // Conserver uniquement les commandes actives.
                [
                    '$match' => $this->filtreCommandesActives()
                ],

                // Additionner les montants.
                [
                    '$group' => [
                        '_id' => null,
                        'total' => ['$sum' => '$prix_total']
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
     * NOUVEAU : statistiques par jour.
     *
     * Regroupe les commandes selon created_at :
     * - Jour
     * - Mois
     * - Année
     *
     * Le regroupement utilise UTC, comme les
     * statistiques mensuelles existantes.
     */
    public function getStatsByJour()
    {
        if (!$this->connected) {
            return [];
        }

        try {
            $pipeline = [
                // Conserver les commandes non annulées.
                [
                    '$match' => $this->filtreCommandesActives()
                ],

                // Regrouper par date d'enregistrement.
                [
                    '$group' => [
                        '_id' => [
                            'jour' => [
                                '$dayOfMonth' => '$created_at'
                            ],
                            'mois' => [
                                '$month' => '$created_at'
                            ],
                            'annee' => [
                                '$year' => '$created_at'
                            ]
                        ],

                        // Nombre de commandes du jour.
                        'nb_commandes' => ['$sum' => 1],

                        // Montant total des commandes du jour.
                        'ca_total' => ['$sum' => '$prix_total']
                    ]
                ],

                // Affichage chronologique.
                [
                    '$sort' => [
                        '_id.annee' => 1,
                        '_id.mois' => 1,
                        '_id.jour' => 1
                    ]
                ]
            ];

            return iterator_to_array(
                $this->db->statistiques->aggregate($pipeline)
            );

        } catch (\Throwable $e) {
            error_log(
                '[MongoService] Statistiques journalières : ' .
                $e->getMessage()
            );

            return [];
        }
    }

    /**
     * NOUVEAU : statistiques par semaine.
     *
     * Utilise le calendrier ISO :
     * - La semaine commence le lundi.
     * - Les semaines sont numérotées de 1 à 53.
     * - L'année ISO est utilisée pour éviter
     *   les erreurs autour du Nouvel An.
     */
    public function getStatsBySemaine()
    {
        if (!$this->connected) {
            return [];
        }

        try {
            $pipeline = [
                // Conserver uniquement les commandes actives.
                [
                    '$match' => $this->filtreCommandesActives()
                ],

                // Regrouper par semaine ISO et année ISO.
                [
                    '$group' => [
                        '_id' => [
                            'semaine' => [
                                '$isoWeek' => '$created_at'
                            ],
                            'annee' => [
                                '$isoWeekYear' => '$created_at'
                            ]
                        ],

                        // Nombre de commandes de la semaine.
                        'nb_commandes' => ['$sum' => 1],

                        // Montant total de la semaine.
                        'ca_total' => ['$sum' => '$prix_total']
                    ]
                ],

                // Trier les semaines dans l'ordre.
                [
                    '$sort' => [
                        '_id.annee' => 1,
                        '_id.semaine' => 1
                    ]
                ]
            ];

            return iterator_to_array(
                $this->db->statistiques->aggregate($pipeline)
            );

        } catch (\Throwable $e) {
            error_log(
                '[MongoService] Statistiques hebdomadaires : ' .
                $e->getMessage()
            );

            return [];
        }
    }

    /**
     * Statistiques par mois.
     *
     * Cette méthode existait déjà.
     * Elle reste compatible avec ton graphique actuel.
     *
     * Les regroupements utilisent created_at,
     * et non la date de prestation.
     */
    public function getStatsByMois()
    {
        if (!$this->connected) {
            return [];
        }

        try {
            $pipeline = [
                // Conserver les commandes non annulées.
                [
                    '$match' => $this->filtreCommandesActives()
                ],

                // Regrouper par mois et par année.
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

                // Trier les résultats chronologiquement.
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
