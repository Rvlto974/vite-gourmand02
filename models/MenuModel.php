<?php
require_once 'config/database.php';
class MenuModel {
    private $db;
    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }
    public function getAll($filtres = []) {
        $sql = "SELECT * FROM menus WHERE (actif = 1 OR actif IS NULL)";
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
            $sql .= " AND prix_base >= :prix_min";
            $params[':prix_min'] = $filtres['prix_min'];
        }
        if (!empty($filtres['prix_max'])) {
            $sql .= " AND prix_base <= :prix_max";
            $params[':prix_max'] = $filtres['prix_max'];
        }
        if (!empty($filtres['nb_personnes'])) {
            $sql .= " AND nb_personnes_min >= :nb_personnes";
            $params[':nb_personnes'] = $filtres['nb_personnes'];
        }
        $tri = $filtres['tri'] ?? 'recent';
        $sql .= match($tri) {
            'prix_asc'  => " ORDER BY prix_base ASC",
            'prix_desc' => " ORDER BY prix_base DESC",
            default     => " ORDER BY id DESC"
        };
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
        $sql = "SELECT * FROM plats WHERE menu_id = :menu_id ORDER BY FIELD(type, 'entree', 'plat', 'dessert')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':menu_id' => $menu_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAvisById($menu_id) {
        $sql = "SELECT a.*, u.nom, u.prenom
                FROM avis a
                JOIN commandes c ON a.commande_id = c.id
                JOIN utilisateurs u ON a.utilisateur_id = u.id
                WHERE c.menu_id = :menu_id AND a.valide = 1
                ORDER BY a.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':menu_id' => $menu_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function toggleActif($id) {
        $sql = "UPDATE menus SET actif = NOT actif WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    public function getAllAdmin() {
        $sql = "SELECT * FROM menus ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function modifier($id, $data) {
        $sql = "UPDATE menus SET 
                    titre = :titre,
                    description = :description,
                    theme = :theme,
                    regime = :regime,
                    prix_base = :prix_base,
                    nb_personnes_min = :nb_personnes_min,
                    stock = :stock
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':titre'           => $data['titre'],
            ':description'     => $data['description'],
            ':theme'           => $data['theme'],
            ':regime'          => $data['regime'],
            ':prix_base'       => $data['prix_base'],
            ':nb_personnes_min'=> $data['nb_personnes_min'],
            ':stock'           => $data['stock'],
            ':id'              => $id
        ]);
    }
    public function supprimer($id) {
        $sql = "DELETE FROM menus WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    public function creer($data) {
    $sql = "INSERT INTO menus (titre, description, theme, regime, prix_base, nb_personnes_min, stock, image, actif)
            VALUES (:titre, :description, :theme, :regime, :prix_base, :nb_personnes_min, :stock, :image, 1)";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([
        ':titre'           => $data['titre'],
        ':description'     => $data['description'],
        ':theme'           => $data['theme'],
        ':regime'          => $data['regime'],
        ':prix_base'       => $data['prix_base'],
        ':nb_personnes_min'=> $data['nb_personnes_min'],
        ':stock'           => $data['stock'],
        ':image'           => $data['image'] ?? null,
    ]);
}
public function decrementerStock($id) {
    $sql = "UPDATE menus SET stock = stock - 1 WHERE id = :id AND stock > 0";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([':id' => $id]);
}
    public function getLastInsertId() {
    return $this->db->lastInsertId();
}
public function getImagesById($menu_id) {
    $sql = "SELECT * FROM menu_images WHERE menu_id = :menu_id ORDER BY ordre ASC";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':menu_id' => $menu_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function ajouterImage($menu_id, $url, $ordre = 0) {
    $sql = "INSERT INTO menu_images (menu_id, url, ordre) VALUES (:menu_id, :url, :ordre)";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([':menu_id' => $menu_id, ':url' => $url, ':ordre' => $ordre]);
}

public function supprimerImages($menu_id) {
    $sql = "DELETE FROM menu_images WHERE menu_id = :menu_id";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([':menu_id' => $menu_id]);
}
}