<?php
require_once 'config/database.php';

class MenuModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getAll($filtres = []) {
        $sql = "SELECT * FROM menus WHERE actif = 1";
        $params = [];

        if (!empty($filtres['theme'])) {
            $sql .= " AND theme = :theme";
            $params[':theme'] = $filtres['theme'];
        }

        if (!empty($filtres['regime'])) {
            $sql .= " AND regime = :regime";
            $params[':regime'] = $filtres['regime'];
        }

        if (!empty($filtres['prix_max'])) {
            $sql .= " AND prix_base <= :prix_max";
            $params[':prix_max'] = $filtres['prix_max'];
        }

        $sql .= " ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM menus WHERE id = :id AND actif = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}<?php
require_once 'models/CommandeModel.php';
require_once 'models/MenuModel.php';

class CommandesController {
    private $commandeModel;
    private $menuModel;

    public function __construct() {
        $this->commandeModel = new CommandeModel();
        $this->menuModel = new MenuModel();
    }

    // Vérifier si l'utilisateur est connecté
    private function verifierConnexion() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/connexion');
            exit;
        }
    }

    // Formulaire nouvelle commande
    public function nouveau() {
        $this->verifierConnexion();

        $menu_id = $_GET['menu_id'] ?? null;
        $nb_personnes = $_GET['nb_personnes'] ?? 1;

        if (!$menu_id) {
            header('Location: /menus');
            exit;
        }

        $menu = $this->menuModel->getById($menu_id);

        if (!$menu) {
            header('Location: /menus');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nb_personnes = $_POST['nb_personnes'] ?? $menu['nb_personnes_min'];
            $adresse = $_POST['adresse_livraison'] ?? '';
            $date = $_POST['date_prestation'] ?? '';

            $prix_total = ($menu['prix_base'] / $menu['nb_personnes_min']) * $nb_personnes;

            $commande_id = $this->commandeModel->creer([
                'utilisateur_id' => $_SESSION['user_id'],
                'menu_id' => $menu_id,
                'nb_personnes' => $nb_personnes,
                'prix_total' => $prix_total,
                'adresse_livraison' => $adresse,
                'date_prestation' => $date
            ]);

            header('Location: /commandes/confirmation?id=' . $commande_id);
            exit;
        }

        require_once 'views/commandes/nouveau.php';
    }

    // Page confirmation
    public function confirmation() {
        $this->verifierConnexion();
        $id = $_GET['id'] ?? null;
        $commande = $this->commandeModel->getById($id);
        require_once 'views/commandes/confirmation.php';
    }
}