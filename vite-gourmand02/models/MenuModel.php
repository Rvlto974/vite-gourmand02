<?php
require_once 'config/database.php';

class MenuModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    // Récupérer tous les menus
    public function getAll() {
        $sql = "SELECT * FROM menus WHERE actif = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un menu par son ID
    public function getById($id) {
        $sql = "SELECT * FROM menus WHERE id = :id AND actif = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}