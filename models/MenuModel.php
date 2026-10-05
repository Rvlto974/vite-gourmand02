<?php

// Charge la classe de connexion à la base de données.
require_once 'config/database.php';

class MenuModel
{
    // Connexion PDO utilisée par les méthodes du modèle.
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    /**
     * Récupère les menus actifs, avec filtres et tri facultatifs.
     *
     * Filtres possibles :
     * - theme
     * - regime
     * - prix_min
     * - prix_max
     * - nb_personnes
     * - tri : recent, prix_asc ou prix_desc
     */
    public function getAll($filtres = [])
    {
        $sql = "SELECT *
                FROM menus
                WHERE (actif = 1 OR actif IS NULL)";

        $params = [];

        // Filtre par thème.
        if (!empty($filtres['theme'])) {
            $sql .= " AND theme = :theme";
            $params[':theme'] = $filtres['theme'];
        }

        // Filtre par régime alimentaire.
        if (!empty($filtres['regime'])) {
            $sql .= " AND regime = :regime";
            $params[':regime'] = $filtres['regime'];
        }

        // Prix minimum.
        if (isset($filtres['prix_min']) && $filtres['prix_min'] !== '') {
            $sql .= " AND prix_base >= :prix_min";
            $params[':prix_min'] = $filtres['prix_min'];
        }

        // Prix maximum.
        if (isset($filtres['prix_max']) && $filtres['prix_max'] !== '') {
            $sql .= " AND prix_base <= :prix_max";
            $params[':prix_max'] = $filtres['prix_max'];
        }

        // Nombre de personnes : menus dont le minimum requis
        // est supérieur ou égal au nombre demandé.
        if (isset($filtres['nb_personnes']) && $filtres['nb_personnes'] !== '') {
            $sql .= " AND nb_personnes_min >= :nb_personnes";
            $params[':nb_personnes'] = $filtres['nb_personnes'];
        }

        // Le choix du tri est limité à ces options prédéfinies.
        $tri = $filtres['tri'] ?? 'recent';

        switch ($tri) {
            case 'prix_asc':
                $sql .= " ORDER BY prix_base ASC";
                break;

            case 'prix_desc':
                $sql .= " ORDER BY prix_base DESC";
                break;

            default:
                $sql .= " ORDER BY id DESC";
                break;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un menu à partir de son identifiant.
     * Retourne false si le menu n'existe pas.
     */
    public function getById($id)
    {
        $sql = "SELECT *
                FROM menus
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les plats associés à un menu via la table menu_plats.
     */
    public function getPlatsById($menu_id)
    {
        $sql = "
            SELECT p.*
            FROM menu_plats AS mp
            INNER JOIN plats AS p ON p.id = mp.plat_id
            WHERE mp.menu_id = :menu_id
            ORDER BY
                mp.ordre ASC,
                FIELD(p.type, 'entree', 'plat', 'dessert')
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':menu_id' => $menu_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les avis validés associés aux commandes de ce menu.
     */
    public function getAvisById($menu_id)
    {
        $sql = "
            SELECT a.*, u.nom, u.prenom
            FROM avis AS a
            INNER JOIN commandes AS c ON a.commande_id = c.id
            INNER JOIN utilisateurs AS u ON a.utilisateur_id = u.id
            WHERE c.menu_id = :menu_id
              AND a.valide = 1
            ORDER BY a.id DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':menu_id' => $menu_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Inverse l'état actif/inactif d'un menu.
     */
    public function toggleActif($id)
    {
        $sql = "UPDATE menus
                SET actif = NOT actif
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }

    /**
     * Récupère tous les menus pour l'administration,
     * y compris ceux qui sont inactifs.
     */
    public function getAllAdmin()
    {
        $sql = "SELECT *
                FROM menus
                ORDER BY id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Modifie les informations principales d'un menu.
     */
    public function modifier($id, $data)
    {
        $sql = "
            UPDATE menus
            SET titre = :titre,
                description = :description,
                theme = :theme,
                regime = :regime,
                prix_base = :prix_base,
                nb_personnes_min = :nb_personnes_min,
                stock = :stock
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':titre'            => $data['titre'],
            ':description'      => $data['description'],
            ':theme'            => $data['theme'],
            ':regime'           => $data['regime'],
            ':prix_base'        => $data['prix_base'],
            ':nb_personnes_min' => $data['nb_personnes_min'],
            ':stock'            => $data['stock'],
            ':id'               => $id,
        ]);
    }

    /**
     * Supprime un menu à partir de son identifiant.
     */
    public function supprimer($id)
    {
        $sql = "DELETE FROM menus
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }

    /**
     * Crée un menu et utilise les valeurs fournies dans $data.
     * L'image est facultative.
     */
    public function creer($data)
    {
        $sql = "
            INSERT INTO menus (
                titre,
                description,
                theme,
                regime,
                prix_base,
                nb_personnes_min,
                stock,
                image,
                actif
            )
            VALUES (
                :titre,
                :description,
                :theme,
                :regime,
                :prix_base,
                :nb_personnes_min,
                :stock,
                :image,
                1
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':titre'            => $data['titre'],
            ':description'      => $data['description'],
            ':theme'            => $data['theme'],
            ':regime'           => $data['regime'],
            ':prix_base'        => $data['prix_base'],
            ':nb_personnes_min' => $data['nb_personnes_min'],
            ':stock'            => $data['stock'],
            ':image'            => $data['image'] ?? null,
        ]);
    }

    /**
     * Diminue le stock d'un menu d'une unité, uniquement s'il est positif.
     */
    public function decrementerStock($id)
    {
        $sql = "
            UPDATE menus
            SET stock = stock - 1
            WHERE id = :id
              AND stock > 0
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }

    /**
     * Retourne l'identifiant de la dernière ligne insérée.
     */
    public function getLastInsertId()
    {
        return $this->db->lastInsertId();
    }

    /**
     * Récupère les images d'un menu dans leur ordre d'affichage.
     */
    public function getImagesById($menu_id)
    {
        $sql = "
            SELECT *
            FROM menu_images
            WHERE menu_id = :menu_id
            ORDER BY ordre ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':menu_id' => $menu_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ajoute une image à un menu.
     */
    public function ajouterImage($menu_id, $url, $ordre = 0)
    {
        $sql = "
            INSERT INTO menu_images (menu_id, url, ordre)
            VALUES (:menu_id, :url, :ordre)
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':menu_id' => $menu_id,
            ':url'     => $url,
            ':ordre'   => $ordre,
        ]);
    }

    /**
     * Supprime toutes les images associées à un menu.
     */
    public function supprimerImages($menu_id)
    {
        $sql = "
            DELETE FROM menu_images
            WHERE menu_id = :menu_id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([':menu_id' => $menu_id]);
    }
}