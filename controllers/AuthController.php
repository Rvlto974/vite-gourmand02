<?php

require_once 'models/UtilisateurModel.php';

class AuthController
{
    // Modèle utilisé pour lire et enregistrer les utilisateurs.
    private $utilisateurModel;

    public function __construct()
    {
        $this->utilisateurModel = new UtilisateurModel();
    }

    /**
     * Affiche le formulaire d'inscription et traite son envoi.
     */
    public function inscription()
    {
        // Démarre la session uniquement si elle n'est pas déjà active.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Traite les données lorsque le formulaire est envoyé.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom        = $_POST['nom'] ?? '';
            $prenom     = $_POST['prenom'] ?? '';
            $email      = trim($_POST['email'] ?? '');
            $gsm        = $_POST['gsm'] ?? '';
            $adresse    = $_POST['adresse'] ?? '';
            $motDePasse = $_POST['mot_de_passe'] ?? '';

            // Vérifie que le mot de passe respecte les critères demandés.
            if (!$this->validerMotDePasse($motDePasse)) {
                $erreur = 'Le mot de passe doit contenir au moins 10 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.';
                require 'views/auth/inscription.php';
                return;
            }

            // Empêche la création de plusieurs comptes avec la même adresse email.
            if ($this->utilisateurModel->getByEmail($email)) {
                $erreur = 'Un compte existe déjà avec cet email.';
                require 'views/auth/inscription.php';
                return;
            }

            // Ne stocke jamais le mot de passe en clair dans la base de données.
            $hash = password_hash($motDePasse, PASSWORD_BCRYPT);

            // Enregistre le nouvel utilisateur.
            $this->utilisateurModel->creer([
                'nom'          => $nom,
                'prenom'       => $prenom,
                'email'        => $email,
                'gsm'          => $gsm,
                'adresse'      => $adresse,
                'mot_de_passe' => $hash,
            ]);

            // Affiche un message après la redirection vers la connexion.
            $_SESSION['flash'] = [
                'type'    => 'success',
                'message' => '🎉 Compte créé ! Vous pouvez vous connecter.',
            ];

            header('Location: /auth/connexion');
            exit;
        }

        // Affiche le formulaire si la page est simplement consultée.
        require 'views/auth/inscription.php';
    }

    /**
     * Vérifie les critères de complexité du mot de passe.
     */
    private function validerMotDePasse($motDePasse)
    {
        return preg_match(
            '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{10,}$/',
            $motDePasse
        );
    }

    /**
     * Affiche le formulaire de connexion et vérifie les identifiants.
     */
    public function connexion()
    {
        // Démarre la session uniquement si elle n'est pas déjà active.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Vérifie les identifiants à la réception du formulaire.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email      = trim($_POST['email'] ?? '');
            $motDePasse = $_POST['mot_de_passe'] ?? '';

            // Recherche le compte correspondant à l'adresse email.
            $utilisateur = $this->utilisateurModel->getByEmail($email);

            // Compare le mot de passe saisi avec son empreinte enregistrée.
            if (
                $utilisateur
                && password_verify($motDePasse, $utilisateur['mot_de_passe'])
            ) {
                // Renouvelle l'identifiant de session après la connexion.
                session_regenerate_id(true);

                // Enregistre les informations utiles de l'utilisateur en session.
                $_SESSION['user_id']     = $utilisateur['id'];
                $_SESSION['user_role']   = $utilisateur['role'];
                $_SESSION['user_nom']    = $utilisateur['nom'];
                $_SESSION['user_prenom'] = $utilisateur['prenom'];

                // Prépare le message de bienvenue affiché après la redirection.
                $_SESSION['flash'] = [
                    'type'    => 'success',
                    'message' => '✅ Bienvenue ' . $utilisateur['prenom'] . ' !',
                ];

                header('Location: /');
                exit;
            }

            // Affiche une erreur générique sans révéler si l'email existe.
            $erreur = 'Email ou mot de passe incorrect';
            require 'views/auth/connexion.php';
            return;
        }

        // Affiche le formulaire lors d'une consultation normale de la page.
        require 'views/auth/connexion.php';
    }

    /**
     * Déconnecte l'utilisateur et redirige vers l'accueil.
     */
    public function deconnexion()
    {
        // Démarre la session uniquement si elle n'est pas déjà active.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Supprime les données de session de l'utilisateur.
        $_SESSION = [];

        // Supprime également le cookie de session du navigateur, s'il est utilisé.
        if (ini_get('session.use_cookies')) {
            $parametresCookie = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $parametresCookie['path'],
                $parametresCookie['domain'],
                $parametresCookie['secure'],
                $parametresCookie['httponly']
            );
        }

        // Détruit l'ancienne session.
        session_destroy();

        // Crée une nouvelle session pour conserver le message de déconnexion.
        session_start();
        $_SESSION['flash'] = [
            'type'    => 'secondary',
            'message' => '👋 À bientôt !',
        ];

        header('Location: /');
        exit;
    }
}