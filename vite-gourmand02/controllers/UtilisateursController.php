<?php
require_once 'models/UtilisateurModel.php';

class UtilisateursController {
    private $utilisateurModel;

    public function __construct() {
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

    public function profil() {
        $this->verifierConnexion();
        $utilisateur = $this->utilisateurModel->getById($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom     = $_POST['nom'] ?? '';
            $prenom  = $_POST['prenom'] ?? '';
            $gsm     = $_POST['gsm'] ?? '';
            $adresse = $_POST['adresse'] ?? '';

            $this->utilisateurModel->mettreAJour([
                'id'      => $_SESSION['user_id'],
                'nom'     => $nom,
                'prenom'  => $prenom,
                'email'   => $utilisateur['email'],
                'gsm'     => $gsm,
                'adresse' => $adresse
            ]);

            $_SESSION['user_nom']    = $nom;
            $_SESSION['user_prenom'] = $prenom;
            $_SESSION['flash'] = ['type' => 'success', 'message' => '✅ Profil mis à jour !'];
            header('Location: /utilisateurs/profil');
            exit;
        }

        require_once 'views/utilisateurs/profil.php';
    }
}