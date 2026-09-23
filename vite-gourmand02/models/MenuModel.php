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

        if (!empty($filtres['saison'])) {
            $sql .= " AND saison = :saison";
            $params[':saison'] = $filtres['saison'];
        }

        $sql .= " ORDER BY id DESC";
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
        // ✅ SQLite compatible - pas de FIELD()
        $sql = "SELECT * FROM plats WHERE menu_id = :menu_id ORDER BY 
                CASE 
                    WHEN type = 'entree' THEN 1
                    WHEN type = 'plat' THEN 2
                    WHEN type = 'dessert' THEN 3
                    ELSE 4
                END";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':menu_id' => $menu_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAvisById($menu_id) {
        $sql = "SELECT a.*, u.nom as auteur FROM avis a 
                LEFT JOIN utilisateurs u ON a.user_id = u.id 
                WHERE a.menu_id = :menu_id AND a.valide = 1
                ORDER BY a.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':menu_id' => $menu_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getImagesById($menu_id) {
        $sql = "SELECT * FROM menu_images WHERE menu_id = :menu_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':menu_id' => $menu_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
