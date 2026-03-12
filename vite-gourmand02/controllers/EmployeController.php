<?php
require_once 'models/CommandeModel.php';
require_once 'models/AvisModel.php';
require_once 'models/UtilisateurModel.php';
require_once 'models/CommandeStatutModel.php';
require_once 'config/EmailService.php';

class EmployeController {
    private $commandeModel;
    private $avisModel;
    private $utilisateurModel;
    private $emailService;
    private $commandeStatutModel;

    public function __construct() {
        $this->commandeModel = new CommandeModel();
        $this->avisModel = new AvisModel();
        $this->utilisateurModel = new UtilisateurModel();
        $this->emailService = new EmailService();
        $this->commandeStatutModel = new CommandeStatutModel();
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
            $this->commandeStatutModel->enregistrer($id, $statut);

            $commande = $this->commandeModel->getById($id);
            if ($commande) {
                $utilisateur = $this->utilisateurModel->getById($commande['utilisateur_id']);
                if ($utilisateur) {
                    if ($statut === 'terminee') {
                        $this->emailService->envoyerCommandeTerminee(
                            $utilisateur['email'],
                            $utilisateur['prenom'],
                            $commande,
                            $id
                        );
                    }
                    if ($statut === 'attente_materiel') {
                        $this->emailService->envoyerAttentesMateriel(
                            $utilisateur['email'],
                            $utilisateur['prenom'],
                            $commande
                        );
                    }
                }
            }
        }

        header('Location: /employe/commandes');
        exit;
    }

    public function annulerCommande() {
        $this->verifierEmploye();

        $id           = $_POST['id'] ?? null;
        $motif        = $_POST['motif_annulation'] ?? '';
        $mode_contact = $_POST['mode_contact'] ?? '';

        if (!$id || empty($motif) || empty($mode_contact)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => '❌ Veuillez remplir le motif et le mode de contact.'];
            header('Location: /employe/commandes');
            exit;
        }

        $commande = $this->commandeModel->getById($id);
        if ($commande) {
            $this->commandeModel->annulerParEmploye($id, $motif, $mode_contact);
            $this->commandeStatutModel->enregistrer($id, 'annulee');

            $utilisateur = $this->utilisateurModel->getById($commande['utilisateur_id']);
            if ($utilisateur) {
                $this->emailService->envoyerAnnulationClient(
                    $utilisateur['email'],
                    $utilisateur['prenom'],
                    $commande,
                    $motif
                );
            }

            $_SESSION['flash'] = ['type' => 'success', 'message' => '✅ Commande annulée avec succès.'];
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