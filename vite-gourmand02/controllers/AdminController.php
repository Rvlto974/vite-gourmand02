<?php
require_once 'models/UtilisateurModel.php';
require_once 'models/MenuModel.php';
require_once 'models/CommandeModel.php';

class AdminController {
    private $utilisateurModel;
    private $menuModel;
    private $commandeModel;

    public function __construct() {
        $this->utilisateurModel = new UtilisateurModel();
        $this->menuModel = new MenuModel();
        $this->commandeModel = new CommandeModel();
    }

    private function verifierAdmin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: /auth/connexion');
            exit;
        }
    }

    public function dashboard() {
        $this->verifierAdmin();
        $utilisateurs = $this->utilisateurModel->getAll();
        $commandes = $this->commandeModel->getAllCommandes();
        $menus = $this->menuModel->getAll();
        require_once 'views/admin/dashboard.php';
    }

    public function utilisateurs() {
        $this->verifierAdmin();
        $utilisateurs = $this->utilisateurModel->getAll();
        require_once 'views/admin/utilisateurs.php';
    }

    public function toggleActif() {
        $this->verifierAdmin();
        $id = $_POST['id'] ?? null;
        if ($id) {
            $this->utilisateurModel->toggleActif($id);
        }
        header('Location: /admin/utilisateurs');
        exit;
    }

    public function menus() {
        $this->verifierAdmin();
        $menus = $this->menuModel->getAll();
        require_once 'views/admin/menus.php';
    }
}