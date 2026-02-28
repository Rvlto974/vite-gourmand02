<?php
require_once 'models/CommandeModel.php';
require_once 'models/UtilisateurModel.php';

class ClientController {
    private $commandeModel;
    private $utilisateurModel;

    public function __construct() {
        $this->commandeModel = new CommandeModel();
        $this->utilisateurModel = new UtilisateurModel();
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

    public function dashboard() {
        $this->verifierConnexion();
        $commandes = $this->commandeModel->getByUtilisateur($_SESSION['user_id']);
        require_once 'views/client/dashboard.php';
    }

    public function commandes() {
        $this->verifierConnexion();
        $commandes = $this->commandeModel->getByUtilisateur($_SESSION['user_id']);
        require_once 'views/client/commandes.php';
    }

    public function annuler() {
        $this->verifierConnexion();
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->commandeModel->annuler($id);
        }
        header('Location: /client/commandes');
        exit;
    }

    public function profil() {
        $this->verifierConnexion();
        $utilisateur = $this->utilisateurModel->getById($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id' => $_SESSION['user_id'],
                'nom' => $_POST['nom'] ?? '',
                'prenom' => $_POST['prenom'] ?? '',
                'email' => $_POST['email'] ?? '',
                'gsm' => $_POST['gsm'] ?? '',
                'adresse' => $_POST['adresse'] ?? ''
            ];

            // Changer mot de passe si renseigné
            if (!empty($_POST['mot_de_passe'])) {
                $data['mot_de_passe'] = password_hash($_POST['mot_de_passe'], PASSWORD_BCRYPT);
            }

            $this->utilisateurModel->mettreAJour($data);
            $succes = "Profil mis à jour avec succès !";
            $utilisateur = $this->utilisateurModel->getById($_SESSION['user_id']);
        }

        require_once 'views/client/profil.php';
    }
}