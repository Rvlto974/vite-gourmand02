<?php
require_once 'models/UtilisateurModel.php';
require_once 'models/MenuModel.php';
require_once 'models/CommandeModel.php';
require_once 'config/EmailService.php';

class AdminController {
    private $utilisateurModel;
    private $menuModel;
    private $commandeModel;
    private $emailService;

    public function __construct() {
        $this->utilisateurModel = new UtilisateurModel();
        $this->menuModel = new MenuModel();
        $this->commandeModel = new CommandeModel();
        $this->emailService = new EmailService();
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
        $menus = $this->menuModel->getAllAdmin();
        require_once 'views/admin/menus.php';
    }

    public function toggleActifMenu() {
        $this->verifierAdmin();
        $id = $_POST['id'] ?? null;
        if ($id) {
            $this->menuModel->toggleActif($id);
        }
        header('Location: /admin/menus');
        exit;
    }

    public function creerEmploye() {
        $this->verifierAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom       = $_POST['nom'] ?? '';
            $prenom    = $_POST['prenom'] ?? '';
            $email     = $_POST['email'] ?? '';
            $motDePasse = $_POST['mot_de_passe'] ?? '';
            $hash = password_hash($motDePasse, PASSWORD_BCRYPT);

            $this->utilisateurModel->creerAvecRole([
                'nom'        => $nom,
                'prenom'     => $prenom,
                'email'      => $email,
                'mot_de_passe' => $hash,
                'role'       => 'employe'
            ]);

            $this->emailService->envoyerCreationCompteEmploye($email, $prenom);

            $_SESSION['flash'] = ['type' => 'success', 'message' => '✅ Compte employé créé et mail envoyé.'];
            header('Location: /admin/utilisateurs');
            exit;
        }

        require_once 'views/admin/creer_employe.php';
    }

    public function stats() {
        $this->verifierAdmin();
        require_once 'config/MongoService.php';
        $mongo = new MongoService();
        $caTotal = $mongo->getCaTotal();
        $statsByMenu = $mongo->getStatsByMenu();
        $statsByMois = $mongo->getStatsByMois();
        require_once 'views/admin/stats.php';
    }
    public function avis() {
    $this->verifierAdmin();
    require_once 'models/AvisModel.php';
    $avisModel = new AvisModel();
    $avis = $avisModel->getAvisEnAttente();
    require_once 'views/admin/avis.php';
}

public function validerAvis() {
    $this->verifierAdmin();
    require_once 'models/AvisModel.php';
    $avisModel = new AvisModel();
    $id = $_POST['id'] ?? null;
    if ($id) $avisModel->valider($id);
    header('Location: /admin/avis');
    exit;
}

public function refuserAvis() {
    $this->verifierAdmin();
    require_once 'models/AvisModel.php';
    $avisModel = new AvisModel();
    $id = $_POST['id'] ?? null;
    if ($id) $avisModel->refuser($id);
    header('Location: /admin/avis');
    exit;
}
}