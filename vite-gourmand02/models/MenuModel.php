<?php
require_once __DIR__ . '/../config/database_sqlite.php';

class MenuModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function getAll($filtres = []) {
        $sql = "SELECT * FROM menus WHERE 1=1";
        $params = [];

        if (!empty($filtres['theme'])) {
            $sql .= " AND theme = :theme";
            $params[':theme'] = $filtres['theme'];
        }

        if (!empty($filtres['regime'])) {
            $sql .= " AND regime = :regime";
            $params[':regime'] = $filtres['regime'];
        }

        if (!empty($filtres['prix_min'])) {
            $sql .= " AND prix >= :prix_min";
            $params[':prix_min'] = $filtres['prix_min'];
        }

        if (!empty($filtres['prix_max'])) {
            $sql .= " AND prix <= :prix_max";
            $params[':prix_max'] = $filtres['prix_max'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM menus WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPlatsById($menu_id) {
        $sql = "SELECT * FROM plats WHERE menu_id = :menu_id ORDER BY type";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':menu_id' => $menu_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAvisById($menu_id) {
        $sql = "SELECT a.*, u.nom, u.prenom FROM avis a
                LEFT JOIN utilisateurs u ON a.utilisateur_id = u.id
                WHERE a.commande_id IN (SELECT id FROM commandes WHERE menu_id = :menu_id)
                ORDER BY a.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':menu_id' => $menu_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getImagesById($menu_id) {
        $sql = "SELECT * FROM menu_images WHERE menu_id = :menu_id ORDER BY ordre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':menu_id' => $menu_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
