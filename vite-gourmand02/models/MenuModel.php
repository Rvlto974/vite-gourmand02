<?php
require_once 'config/database.php';

class MenuModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getAll($filtres = []) {
        $sql = "SELECT * FROM menus WHERE (actif = 1 OR actif IS NULL)";
        $params = [];

        if (!empty($filtres['theme'])) {
            $sql .= " AND theme = :theme";
            $params[':theme'] = $filtres['theme'];
        }

        if (!empty($filtres['regime'])) {
            $sql .= " AND regime = :regime";
            $params[':regime'] = $filtres['regime'];
        }

        if (!empty($filtres['prix_min'])) {
            $sql .= " AND prix_base >= :prix_min";
            $params[':prix_min'] = $filtres['prix_min'];
        }

        if (!empty($filtres['prix_max'])) {
            $sql .= " AND prix_base <= :prix_max";
            $params[':prix_max'] = $filtres['prix_max'];
        }

        if (!empty($filtres['nb_personnes'])) {
            $sql .= " AND nb_personnes_min >= :nb_personnes";
            $params[':nb_personnes'] = $filtres['nb_personnes'];
        }

        $tri = $filtres['tri'] ?? 'recent';
        $sql .= match($tri) {
            'prix_asc'  => " ORDER BY prix_base ASC",
            'prix_desc' => " ORDER BY prix_base DESC",
            default     => " ORDER BY id DESC"
        };

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM menus WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}