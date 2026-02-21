<?php
require_once 'models/CommandeModel.php';
require_once 'models/AvisModel.php';

class EmployeController {
    private $commandeModel;
    private $avisModel;

    public function __construct() {
        $this->commandeModel = new CommandeModel();
        $this->avisModel = new AvisModel();
    }

    private function verifierEmploye() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['employe', 'admin'])) {
            header('Location: /auth/connexion');
            exit;
        }
    }

    public function dashboard() {
        $this->verifierEmploye();
        $commandes = $this->commandeModel->getAllCommandes();
        $avis = $this->avisModel->getAvisEnAttente();
        require_once 'views/employe/dashboard.php';
    }

    public function commandes() {
        $this->verifierEmploye();
        $commandes = $this->commandeModel->getAllCommandes();
        require_once 'views/employe/commandes.php';
    }

    public function updateStatut() {
        $this->verifierEmploye();
        $id = $_POST['id'] ?? null;
        $statut = $_POST['statut'] ?? null;
        if ($id && $statut) {
            $this->commandeModel->updateStatut($id, $statut);
        }
        header('Location: /employe/commandes');
        exit;
    }

    public function avis() {
        $this->verifierEmploye();
        $avis = $this->avisModel->getAvisEnAttente();
        require_once 'views/employe/avis.php';
    }

    public function validerAvis() {
        $this->verifierEmploye();
        $id = $_POST['id'] ?? null;
        if ($id) {
            $this->avisModel->valider($id);
        }
        header('Location: /employe/avis');
        exit;
    }

    public function refuserAvis() {
        $this->verifierEmploye();
        $id = $_POST['id'] ?? null;
        if ($id) {
            $this->avisModel->refuser($id);
        }
        header('Location: /employe/avis');
        exit;
    }
}