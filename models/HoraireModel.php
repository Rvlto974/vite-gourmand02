<?php
require_once 'config/database.php';

class HoraireModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM horaires ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function modifier($id, $data) {
        $sql = "UPDATE horaires SET heure_ouverture=:ho, heure_fermeture=:hf, ferme=:ferme WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':ho'    => $data['ferme'] ? null : $data['heure_ouverture'],
            ':hf'    => $data['ferme'] ? null : $data['heure_fermeture'],
            ':ferme' => $data['ferme'] ? 1 : 0,
            ':id'    => $id
        ]);
    }
}