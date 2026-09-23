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
        $sql = "SELECT * FROM plats 
                WHERE menu_id = :menu_id 
                ORDER BY 
                    CASE type 
                        WHEN 'entree' THEN 1
                        WHEN 'plat' THEN 2
                        WHEN 'dessert' THEN 3
                        ELSE 4
                    END, id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':menu_id' => $menu_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAvisById($menu_id) {
        $sql = "
            SELECT a.id, a.note, a.commentaire, a.created_at, 
                   u.prenom, u.nom
            FROM avis a
            LEFT JOIN utilisateurs u ON a.utilisateur_id = u.id
            LEFT JOIN commandes c ON a.commande_id = c.id
            WHERE c.menu_id = :menu_id AND a.valide = 1
            ORDER BY a.created_at DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':menu_id' => $menu_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getImagesById($menu_id) {
        $sql = "SELECT * FROM menu_images WHERE menu_id = :menu_id ORDER BY id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':menu_id' => $menu_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function decrementerStock($menu_id) {
        $sql = "UPDATE menus SET stock = stock - 1 WHERE id = :id AND stock > 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $menu_id]);
        return $stmt->rowCount() > 0;
    }
}
