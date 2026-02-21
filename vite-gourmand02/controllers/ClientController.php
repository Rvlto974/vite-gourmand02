<?php
require_once 'models/CommandeModel.php';

class ClientController {
    private $commandeModel;

    public function __construct() {
        $this->commandeModel = new CommandeModel();
    }

    private function verifierConnexion() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/connexion');
            exit;
        }
    }

    // Dashboard client
    public function dashboard() {
        $this->verifierConnexion();
        $commandes = $this->commandeModel->getByUtilisateur($_SESSION['user_id']);
        require_once 'views/client/dashboard.php';
    }

    // Historique commandes
    public function commandes() {
        $this->verifierConnexion();
        $commandes = $this->commandeModel->getByUtilisateur($_SESSION['user_id']);
        require_once 'views/client/commandes.php';
    }

    // Annuler une commande
    public function annuler() {
        $this->verifierConnexion();
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->commandeModel->annuler($id);
        }
        header('Location: /client/commandes');
        exit;
    }
}