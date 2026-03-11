<?php
require_once 'config/database.php';

class CommandeStatutModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function enregistrer($commande_id, $statut) {
        $sql = "INSERT INTO commande_statuts (commande_id, statut) VALUES (:commande_id, :statut)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':commande_id' => $commande_id, ':statut' => $statut]);
    }

    public function getByCommande($commande_id) {
        $sql = "SELECT * FROM commande_statuts WHERE commande_id = :commande_id ORDER BY created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':commande_id' => $commande_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}