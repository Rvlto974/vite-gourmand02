<?php
require_once 'config/database.php';

class CommandeModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    // Créer une commande
    public function creer($data) {
        $sql = "INSERT INTO commandes 
                (utilisateur_id, menu_id, nb_personnes, prix_total, statut, adresse_livraison, date_prestation) 
                VALUES (:utilisateur_id, :menu_id, :nb_personnes, :prix_total, 'nouvelle', :adresse_livraison, :date_prestation)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':utilisateur_id' => $data['utilisateur_id'],
            ':menu_id' => $data['menu_id'],
            ':nb_personnes' => $data['nb_personnes'],
            ':prix_total' => $data['prix_total'],
            ':adresse_livraison' => $data['adresse_livraison'],
            ':date_prestation' => $data['date_prestation']
        ]);
        return $this->db->lastInsertId();
    }

    // Récupérer les commandes d'un utilisateur
    public function getByUtilisateur($utilisateur_id) {
        $sql = "SELECT c.*, m.titre as menu_titre 
                FROM commandes c 
                JOIN menus m ON c.menu_id = m.id 
                WHERE c.utilisateur_id = :utilisateur_id 
                ORDER BY c.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':utilisateur_id' => $utilisateur_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer une commande par ID
    public function getById($id) {
        $sql = "SELECT c.*, m.titre as menu_titre 
                FROM commandes c 
                JOIN menus m ON c.menu_id = m.id 
                WHERE c.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Annuler une commande
    public function annuler($id) {
        $sql = "UPDATE commandes SET statut = 'annulee' WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}