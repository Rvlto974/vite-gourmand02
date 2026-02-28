<?php
require_once 'vendor/autoload.php';

class MongoService {
    private $db;

    public function __construct() {
        $client = new MongoDB\Client('mongodb://mongodb:27017');
        $this->db = $client->vite_gourmand;
    }

    // Enregistrer une stat de commande
    public function enregistrerCommande($data) {
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
    }

    // Stats par menu
    public function getStatsByMenu() {
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
    }

    // CA total
    public function getCaTotal() {
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
    }

    // Stats par mois
    public function getStatsByMois() {
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
    }
}
