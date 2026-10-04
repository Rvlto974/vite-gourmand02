<?php

// Charge l’autoloader de Composer pour rendre le pilote MongoDB disponible.
require_once 'vendor/autoload.php';

class MongoService
{
    private $db;
    private $connected = false;

    public function __construct()
    {
        try {
            // Récupère l’URI MongoDB depuis l’environnement, avec une valeur par défaut.
            $mongoUri = getenv('MONGO_URI') ?: 'mongodb://mongodb:27017';

            // Crée le client et définit un délai maximal de 5 secondes pour la connexion.
            $client = new MongoDB\Client(
                $mongoUri,
                [],
                ['serverSelectionTimeoutMS' => 5000]
            );

            // Récupère le nom de la base depuis l’environnement.
            $dbName = getenv('MONGO_DB') ?: 'vite_gourmand_mongo_generally';

            // Sélectionne la base de données et indique que le service est disponible.
            $this->db = $client->$dbName;
            $this->connected = true;
        } catch (Exception $e) {
            // Indique que la connexion n’a pas pu être établie.
            $this->connected = false;
        }
    }

    // Retourne l’état de la connexion à MongoDB.
    public function isConnected()
    {
        return $this->connected;
    }

    // Enregistre une commande dans la collection des statistiques.
    public function enregistrerCommande($data)
    {
        // Ne fait rien si la connexion à MongoDB n’est pas disponible.
        if (!$this->connected) {
            return;
        }

        try {
            $collection = $this->db->statistiques;

            $collection->insertOne([
                'type' => 'commande',
                'menu_id' => $data['menu_id'],
                'menu_titre' => $data['menu_titre'],
                'prix_total' => (float) $data['prix_total'],
                'nb_personnes' => (int) $data['nb_personnes'],
                'date' => new MongoDB\BSON\UTCDateTime(
                    strtotime($data['date']) * 1000
                ),
                'created_at' => new MongoDB\BSON\UTCDateTime()
            ]);
        } catch (Exception $e) {
            // Ignore l’erreur d’enregistrement pour ne pas interrompre l’application.
        }
    }

    // Retourne les statistiques regroupées par menu.
    public function getStatsByMenu()
    {
        if (!$this->connected) {
            return [];
        }

        try {
            $collection = $this->db->statistiques;

            $pipeline = [
                ['$match' => ['type' => 'commande']],
                ['$group' => [
                    '_id' => '$menu_titre',
                    'nb_commandes' => ['$sum' => 1],
                    'ca_total' => ['$sum' => '$prix_total'],
                    'nb_personnes' => ['$sum' => '$nb_personnes']
                ]],
                ['$sort' => ['ca_total' => -1]]
            ];

            return iterator_to_array($collection->aggregate($pipeline));
        } catch (Exception $e) {
            // Retourne une liste vide si la requête échoue.
            return [];
        }
    }

    // Calcule le chiffre d’affaires total des commandes enregistrées.
    public function getCaTotal()
    {
        if (!$this->connected) {
            return 0;
        }

        try {
            $collection = $this->db->statistiques;

            $pipeline = [
                ['$match' => ['type' => 'commande']],
                ['$group' => [
                    '_id' => null,
                    'total' => ['$sum' => '$prix_total']
                ]]
            ];

            $result = iterator_to_array($collection->aggregate($pipeline));

            return $result[0]['total'] ?? 0;
        } catch (Exception $e) {
            // Retourne zéro si le calcul échoue.
            return 0;
        }
    }

    // Retourne les statistiques regroupées par mois et par année.
    public function getStatsByMois()
    {
        if (!$this->connected) {
            return [];
        }

        try {
            $collection = $this->db->statistiques;

            $pipeline = [
                ['$match' => ['type' => 'commande']],
                ['$group' => [
                    '_id' => [
                        'mois' => ['$month' => '$created_at'],
                        'annee' => ['$year' => '$created_at']
                    ],
                    'nb_commandes' => ['$sum' => 1],
                    'ca_total' => ['$sum' => '$prix_total']
                ]],
                ['$sort' => ['_id.annee' => 1, '_id.mois' => 1]]
            ];

            return iterator_to_array($collection->aggregate($pipeline));
        } catch (Exception $e) {
            // Retourne une liste vide si la requête échoue.
            return [];
        }
    }
}