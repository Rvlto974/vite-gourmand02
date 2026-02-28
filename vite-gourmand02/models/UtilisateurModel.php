<?php
require_once 'config/database.php';

class UtilisateurModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function creer($data) {
        $sql = "INSERT INTO utilisateurs 
                (nom, prenom, email, mot_de_passe) 
                VALUES (:nom, :prenom, :email, :mot_de_passe)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email' => $data['email'],
            ':mot_de_passe' => $data['mot_de_passe']
        ]);
    }

    public function creerAvecRole($data) {
        $sql = "INSERT INTO utilisateurs 
                (nom, prenom, email, mot_de_passe, role) 
                VALUES (:nom, :prenom, :email, :mot_de_passe, :role)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email' => $data['email'],
            ':mot_de_passe' => $data['mot_de_passe'],
            ':role' => $data['role']
        ]);
    }

    public function getByEmail($email) {
        $sql = "SELECT * FROM utilisateurs 
                WHERE email = :email AND actif = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $sql = "SELECT * FROM utilisateurs ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function toggleActif($id) {
        $sql = "UPDATE utilisateurs SET actif = NOT actif WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function getById($id) {
        $sql = "SELECT * FROM utilisateurs WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}