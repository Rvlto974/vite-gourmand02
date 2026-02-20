<?php
require_once 'config/database.php';

class AvisModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    // Récupérer les avis validés
    public function getAvisValides() {
        $sql = "SELECT a.*, u.nom, u.prenom 
                FROM avis a 
                JOIN utilisateurs u ON a.utilisateur_id = u.id 
                WHERE a.valide = 1 
                ORDER BY a.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}