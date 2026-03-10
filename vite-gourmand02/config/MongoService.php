<?php
require_once 'vendor/autoload.php';

class MongoService {
    private $db;
    private $connected = false;

    public function __construct() {
        try {
            
            $mongoUri = getenv('MONGO_URI') ?: 'mongodb://mongodb:27017';
                $client = new MongoDB\Client($mongoUri, [], ['serverSelectionTimeoutMS' => 5000]);
                $dbName = getenv('MONGO_DB') ?: 'vite_gourmand_mongo_generally';
            $this->db = $client->$dbName;
            $this->connected = true;
            } catch (Exception $e) {
            $this->connected = false;
        }
    }

    public function isConnected() { return $this->connected; }

    public function enregistrerCommande($data) {
        if (!$this->connected) return;
        try {
            $collection = $this->db->statistiques;
            $collection->insertOne([
                'type' => 'commande',
                'menu_id' => $data['menu_id'],
                'menu_titre' => $data['menu_titre'],
                'prix_total' => (float) $data['prix_total'],
                'nb_personnes' => (int) $data['nb_personnes'],
                'date' => new MongoDB\BSON\UTCDateTime(strtotime($data['date']) * 1000),
                'created_at' => new MongoDB\BSON\UTCDateTime()
            ]);
        } catch (Exception $e) {}
    }

    public function getStatsByMenu() {
        if (!$this->connected) return [];
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
        } catch (Exception $e) { return []; }
    }

    public function getCaTotal() {
        if (!$this->connected) return 0;
        try {
            $collection = $this->db->statistiques;
            $pipeline = [
                ['$match' => ['type' => 'commande']],
                ['$group' => ['_id' => null, 'total' => ['$sum' => '$prix_total']]]
            ];
            $result = iterator_to_array($collection->aggregate($pipeline));
            return $result[0]['total'] ?? 0;
        } catch (Exception $e) { return 0; }
    }

    public function getStatsByMois() {
        if (!$this->connected) return [];
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
        } catch (Exception $e) { return []; }
    }
}