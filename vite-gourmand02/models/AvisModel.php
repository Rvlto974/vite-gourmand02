<?php
require_once __DIR__ . '/../config/database_sqlite.php';

class AvisModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function getAvisValides() {
        // Récupère tous les avis validés (colonne 'valide' = 1)
        $sql = "SELECT a.id, a.utilisateur_id, a.commande_id, a.note, a.commentaire, 
                       a.valide, a.created_at, u.nom, u.prenom
                FROM avis a
                LEFT JOIN utilisateurs u ON a.utilisateur_id = u.id
                WHERE a.valide = 1
                ORDER BY a.created_at DESC
                LIMIT 6";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $sql = "SELECT a.*, u.nom, u.prenom FROM avis a
                LEFT JOIN utilisateurs u ON a.utilisateur_id = u.id
                ORDER BY a.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT a.*, u.nom, u.prenom FROM avis a
                LEFT JOIN utilisateurs u ON a.utilisateur_id = u.id
                WHERE a.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
