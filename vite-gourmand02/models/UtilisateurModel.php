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
    // Récupérer tous les utilisateurs
public function getAll() {
    $sql = "SELECT * FROM utilisateurs ORDER BY created_at DESC";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Activer/désactiver un utilisateur
public function toggleActif($id) {
    $sql = "UPDATE utilisateurs SET actif = NOT actif WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([':id' => $id]);
}
// Récupérer un utilisateur par ID
public function getById($id) {
    $sql = "SELECT * FROM utilisateurs WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}