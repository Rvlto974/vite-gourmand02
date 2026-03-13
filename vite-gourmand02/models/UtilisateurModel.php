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
                (nom, prenom, email, mot_de_passe, gsm, adresse)
                VALUES (:nom, :prenom, :email, :mot_de_passe, :gsm, :adresse)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nom'          => $data['nom'],
            ':prenom'       => $data['prenom'],
            ':email'        => $data['email'],
            ':mot_de_passe' => $data['mot_de_passe'],
            ':gsm'          => $data['gsm'] ?? null,
            ':adresse'      => $data['adresse'] ?? null
        ]);
    }
    public function creerAvecRole($data) {
        $sql = "INSERT INTO utilisateurs
                (nom, prenom, email, mot_de_passe, role)
                VALUES (:nom, :prenom, :email, :mot_de_passe, :role)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nom'          => $data['nom'],
            ':prenom'       => $data['prenom'],
            ':email'        => $data['email'],
            ':mot_de_passe' => $data['mot_de_passe'],
            ':role'         => $data['role']
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
    public function mettreAJour($data) {
        $sql = "UPDATE utilisateurs SET nom=:nom, prenom=:prenom, email=:email, gsm=:gsm, adresse=:adresse";
        $params = [
            ':nom'     => $data['nom'],
            ':prenom'  => $data['prenom'],
            ':email'   => $data['email'],
            ':gsm'     => $data['gsm'],
            ':adresse' => $data['adresse'],
            ':id'      => $data['id']
        ];
        if (!empty($data['mot_de_passe'])) {
            $sql .= ", mot_de_passe=:mot_de_passe";
            $params[':mot_de_passe'] = $data['mot_de_passe'];
        }
        $sql .= " WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    public function sauvegarderToken($id, $token, $expiration) {
        $sql = "UPDATE utilisateurs SET reset_token = :token, token_expiration = :expiration WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':token' => $token, ':expiration' => $expiration, ':id' => $id]);
    }
    public function getByToken($token) {
        $sql = "SELECT * FROM utilisateurs WHERE reset_token = :token";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function mettreAJourMdp($id, $hash) {
        $sql = "UPDATE utilisateurs SET mot_de_passe = :hash WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':hash' => $hash, ':id' => $id]);
    }
    public function supprimerToken($id) {
        $sql = "UPDATE utilisateurs SET reset_token = NULL, token_expiration = NULL WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}