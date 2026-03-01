<?php
require_once 'models/UtilisateurModel.php';
require_once 'config/EmailService.php';

class MotDePasseController {
    private $utilisateurModel;
    private $emailService;

    public function __construct() {
        $this->utilisateurModel = new UtilisateurModel();
        $this->emailService = new EmailService();
    }

    public function oublie() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $utilisateur = $this->utilisateurModel->getByEmail($email);

            if ($utilisateur) {
                $token = bin2hex(random_bytes(32));
                $expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));
                $this->utilisateurModel->sauvegarderToken($utilisateur['id'], $token, $expiration);
                $this->emailService->envoyerReinitialisationMdp($email, $utilisateur['prenom'], $token);
            }

            $succes = "Si cet email existe, un lien de réinitialisation a été envoyé.";
        }
        require_once 'views/mot-de-passe/oublie.php';
    }

    public function reinitialiser() {
        $token = $_GET['token'] ?? null;

        if (!$token) {
            header('Location: /auth/connexion');
            exit;
        }

        $utilisateur = $this->utilisateurModel->getByToken($token);

        if (!$utilisateur || $utilisateur['token_expiration'] < date('Y-m-d H:i:s')) {
            $erreur = "Ce lien est invalide ou expiré.";
            require_once 'views/mot-de-passe/reinitialiser.php';
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $motDePasse = $_POST['mot_de_passe'] ?? '';
            $hash = password_hash($motDePasse, PASSWORD_BCRYPT);
            $this->utilisateurModel->mettreAJourMdp($utilisateur['id'], $hash);
            $this->utilisateurModel->supprimerToken($utilisateur['id']);
            header('Location: /auth/connexion?mdp=ok');
            exit;
        }

        require_once 'views/mot-de-passe/reinitialiser.php';
    }
}