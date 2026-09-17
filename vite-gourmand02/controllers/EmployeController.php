<?php

require_once 'models/CommandeModel.php';
require_once 'models/AvisModel.php';
require_once 'models/UtilisateurModel.php';
require_once 'models/CommandeStatutModel.php';
require_once 'models/MenuModel.php';
require_once 'models/HoraireModel.php';
require_once 'config/EmailService.php';

class EmployeController
{
    private $commandeModel;
    private $avisModel;
    private $utilisateurModel;
    private $emailService;
    private $commandeStatutModel;
    private $menuModel;
    private $horaireModel;

    public function __construct()
    {
        $this->commandeModel = new CommandeModel();
        $this->avisModel = new AvisModel();
        $this->utilisateurModel = new UtilisateurModel();
        $this->emailService = new EmailService();
        $this->commandeStatutModel = new CommandeStatutModel();
        $this->menuModel = new MenuModel();
        $this->horaireModel = new HoraireModel();
    }

    private function verifierEmploye()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (
            !isset($_SESSION['user_id']) ||
            !isset($_SESSION['user_role']) ||
            !in_array($_SESSION['user_role'], ['employe', 'admin'], true)
        ) {
            header('Location: /auth/connexion');
            exit;
        }
    }

    public function dashboard()
    {
        $this->verifierEmploye();

        $commandes = $this->commandeModel->getAllCommandes();
        $avis = $this->avisModel->getAvisEnAttente();

        require_once 'views/employe/dashboard.php';
    }

    public function commandes()
    {
        $this->verifierEmploye();

        $commandes = $this->commandeModel->getAllCommandes();

        require_once 'views/employe/commandes.php';
    }

    public function updateStatut()
    {
        $this->verifierEmploye();

        $id = (int)($_POST["id"] ?? null);
        $statut = $_POST["statut"] ?? null;
        $statutsAutorises = [
            'nouvelle',
            'acceptee',
            'en_preparation',
            'en_livraison',
            'livree',
            'attente_materiel',
            'terminee',
            'annulee'
        ];

        if (!$id || !$statut || !in_array($statut, $statutsAutorises, true)) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Statut invalide.'
            ];

            header('Location: /employe/commandes');
            exit;
        }

        $commande = $this->commandeModel->getById($id);

        if (!$commande) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Commande introuvable.'
            ];

            header('Location: /employe/commandes');
            exit;
        }

        $this->commandeModel->updateStatut($id, $statut);
        $this->commandeStatutModel->enregistrer($id, $statut);

        $utilisateur = $this->utilisateurModel
            ->getById($commande['utilisateur_id']);

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

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => '✅ Statut de la commande mis à jour.'
        ];

        header('Location: /employe/commandes');
        exit;
    }

    public function annulerCommande()
    {
        $this->verifierEmploye();

        $id = $_POST['id'] ?? null;
        $motif = trim($_POST['motif_annulation'] ?? '');
        $modeContact = trim($_POST['mode_contact'] ?? '');

        if (!$id || $motif === '' || $modeContact === '') {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => '❌ Veuillez remplir le motif et le mode de contact.'
            ];

            header('Location: /employe/commandes');
            exit;
        }

        $commande = $this->commandeModel->getById($id);

        if (!$commande) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Commande introuvable.'
            ];

            header('Location: /employe/commandes');
            exit;
        }

        $this->commandeModel->annulerParEmploye(
            $id,
            $motif,
            $modeContact
        );

        $this->commandeStatutModel->enregistrer(
            $id,
            'annulee'
        );

        $utilisateur = $this->utilisateurModel
            ->getById($commande['utilisateur_id']);

        if ($utilisateur) {
            $this->emailService->envoyerAnnulationClient(
                $utilisateur['email'],
                $utilisateur['prenom'],
                $commande,
                $motif
            );
        }

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => '✅ Commande annulée avec succès.'
        ];

        header('Location: /employe/commandes');
        exit;
    }

    public function avis()
    {
        $this->verifierEmploye();

        $avis = $this->avisModel->getAvisEnAttente();

        require_once 'views/employe/avis.php';
    }

    public function validerAvis()
    {
        $this->verifierEmploye();

        $id = $_POST['id'] ?? null;

        if ($id) {
            $this->avisModel->valider($id);

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => '✅ Avis validé.'
            ];
        }

        header('Location: /employe/avis');
        exit;
    }

    public function refuserAvis()
    {
        $this->verifierEmploye();

        $id = $_POST['id'] ?? null;

        if ($id) {
            $this->avisModel->refuser($id);

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => '✅ Avis refusé.'
            ];
        }

        header('Location: /employe/avis');
        exit;
    }

    public function menus()
    {
        $this->verifierEmploye();

        $menus = $this->menuModel->getAllAdmin();

        require_once 'views/employe/menus.php';
    }

    public function modifierMenu()
    {
        $this->verifierEmploye();

        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: /employe/menus');
            exit;
        }

        $menu = $this->menuModel->getById($id);

        if (!$menu) {
            header('Location: /employe/menus');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->menuModel->modifier($id, [
                'titre' => trim($_POST['titre'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'theme' => trim($_POST['theme'] ?? ''),
                'regime' => trim($_POST['regime'] ?? ''),
                'prix_base' => $_POST['prix_base'] ?? 0,
                'nb_personnes_min' => $_POST['nb_personnes_min'] ?? 1,
                'stock' => $_POST['stock'] ?? 0
            ]);

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => '✅ Menu modifié avec succès.'
            ];

            header('Location: /employe/menus');
            exit;
        }

        require_once 'views/employe/modifier_menu.php';
    }

    public function supprimerMenu()
    {
        $this->verifierEmploye();

        $id = $_POST['id'] ?? null;

        if ($id) {
            $this->menuModel->supprimer($id);

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => '✅ Menu supprimé.'
            ];
        }

        header('Location: /employe/menus');
        exit;
    }

    public function horaires()
    {
        $this->verifierEmploye();

        $horaires = $this->horaireModel->getAll();

        require_once 'views/employe/horaires.php';
    }

    public function modifierHoraires()
    {
        $this->verifierEmploye();

        $horairesPost = $_POST['horaires'] ?? [];

        foreach ($horairesPost as $id => $data) {
            $this->horaireModel->modifier($id, [
                'heure_ouverture' => $data['heure_ouverture'] ?? null,
                'heure_fermeture' => $data['heure_fermeture'] ?? null,
                'ferme' => isset($data['ferme']) ? 1 : 0
            ]);
        }

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => '✅ Horaires mis à jour.'
        ];

        header('Location: /employe/horaires');
        exit;
    }
}