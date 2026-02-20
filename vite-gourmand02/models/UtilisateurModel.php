<?php
require_once 'config/database.php';

class UtilisateurModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    // Créer un utilisateur
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

    // Récupérer un utilisateur par email
    public function getByEmail($email) {
        $sql = "SELECT * FROM utilisateurs 
                WHERE email = :email AND actif = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}