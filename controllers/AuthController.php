
<?php

/**
 * Fichier : controllers/AuthController.php
 * Projet : Vite & Gourmand
 *
 * Ce contrôleur gère :
 * - L'inscription des nouveaux utilisateurs
 * - La validation des mots de passe
 * - La connexion des utilisateurs
 * - Les sessions PHP
 * - Les redirections selon les rôles
 * - La déconnexion
 * - Les messages temporaires (flash)
 */

// Charge le modèle permettant de gérer les utilisateurs.
require_once 'models/UtilisateurModel.php';

class AuthController
{
    // Modèle utilisé pour lire et enregistrer les utilisateurs.
    private $utilisateurModel;

    /**
     * Initialise le contrôleur et son modèle utilisateur.
     */
    public function __construct()
    {
        $this->utilisateurModel = new UtilisateurModel();
    }

    /**
     * INSCRIPTION
     *
     * Affiche le formulaire d'inscription et traite son envoi.
     */
    public function inscription()
    {
        // Démarre la session uniquement si elle n'est pas déjà active.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Traite le formulaire lorsqu'il est envoyé en POST.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Récupère les informations envoyées par l'utilisateur.
            $nom        = $_POST['nom'] ?? '';
            $prenom     = $_POST['prenom'] ?? '';
            $email      = trim($_POST['email'] ?? '');
            $gsm        = $_POST['gsm'] ?? '';
            $adresse    = $_POST['adresse'] ?? '';
            $motDePasse = $_POST['mot_de_passe'] ?? '';

            // Vérifie la complexité du mot de passe.
            if (!$this->validerMotDePasse($motDePasse)) {

                // Prépare le message d'erreur.
                $erreur = 'Le mot de passe doit contenir au moins 10 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.';

                // Réaffiche le formulaire avec le message d'erreur.
                require 'views/auth/inscription.php';
                return;
            }

            // Vérifie si un compte utilise déjà cette adresse email.
            if ($this->utilisateurModel->getByEmail($email)) {

                $erreur = 'Un compte existe déjà avec cet email.';

                require 'views/auth/inscription.php';
                return;
            }

            // Chiffre le mot de passe par hachage avant son stockage.
            // Le mot de passe original n'est jamais enregistré en clair.
            $hash = password_hash($motDePasse, PASSWORD_BCRYPT);

            // Enregistre le nouvel utilisateur dans la base de données.
            $this->utilisateurModel->creer([
                'nom'          => $nom,
                'prenom'       => $prenom,
                'email'        => $email,
                'gsm'          => $gsm,
                'adresse'      => $adresse,
                'mot_de_passe' => $hash,
            ]);

            // Enregistre un message temporaire de confirmation.
            $_SESSION['flash'] = [
                'type'    => 'success',
                'message' => '🎉 Compte créé ! Vous pouvez vous connecter.',
            ];

            // Redirige vers le formulaire de connexion.
            header('Location: /auth/connexion');
            exit;
        }

        // Affiche le formulaire lors d'une visite normale (GET).
        require 'views/auth/inscription.php';
    }

    /**
     * VALIDATION DU MOT DE PASSE
     *
     * Vérifie que le mot de passe contient :
     * - Au moins 10 caractères
     * - Une lettre minuscule
     * - Une lettre majuscule
     * - Un chiffre
     * - Un caractère spécial
     */
    private function validerMotDePasse($motDePasse)
    {
        return preg_match(
            '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{10,}$/',
            $motDePasse
        );
    }

    /**
     * CONNEXION
     *
     * Affiche le formulaire de connexion et vérifie
     * les identifiants envoyés par l'utilisateur.
     */
    public function connexion()
    {
        // Démarre la session uniquement si elle n'est pas déjà active.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        /**
         * CORRECTION : UTILISATEUR DÉJÀ CONNECTÉ
         *
         * Empêche un utilisateur possédant déjà une session
         * d'accéder à nouveau au formulaire de connexion.
         *
         * Il est redirigé selon son rôle.
         */
        if (isset($_SESSION['user_id'])) {

            // Récupère le rôle enregistré dans la session.
            $role = $_SESSION['user_role'] ?? '';

            // Administrateur : tableau de bord administrateur.
            if ($role === 'admin') {
                header('Location: /admin/dashboard');

            // Employé : tableau de bord employé.
            } elseif ($role === 'employe') {
                header('Location: /employe/dashboard');

            // Client : page d'accueil.
            } else {
                header('Location: /');
            }

            // Arrête immédiatement l'exécution après la redirection.
            exit;
        }

        /**
         * TRAITEMENT DU FORMULAIRE
         *
         * Vérifie les identifiants lorsque l'utilisateur
         * envoie le formulaire avec la méthode POST.
         */
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Récupère l'adresse email et le mot de passe saisis.
            $email      = trim($_POST['email'] ?? '');
            $motDePasse = $_POST['mot_de_passe'] ?? '';

            // Recherche l'utilisateur correspondant à l'adresse email.
            $utilisateur = $this->utilisateurModel->getByEmail($email);

            // Vérifie que l'utilisateur existe et que le mot de passe est correct.
            if (
                $utilisateur
                && password_verify($motDePasse, $utilisateur['mot_de_passe'])
            ) {

                /**
                 * SÉCURISATION DE LA SESSION
                 *
                 * Renouvelle l'identifiant de session après une
                 * authentification réussie pour limiter les risques
                 * de fixation de session.
                 */
                session_regenerate_id(true);

                // Enregistre les informations de l'utilisateur en session.
                $_SESSION['user_id']     = $utilisateur['id'];
                $_SESSION['user_role']   = $utilisateur['role'];
                $_SESSION['user_nom']    = $utilisateur['nom'];
                $_SESSION['user_prenom'] = $utilisateur['prenom'];

                /**
                 * MESSAGE DE BIENVENUE
                 *
                 * Ce message sera affiché par le fichier
                 * views/layouts/header.php après la redirection.
                 */
                $_SESSION['flash'] = [
                    'type'    => 'success',
                    'message' => '✅ Bienvenue ' . $utilisateur['prenom'] . ' !',
                ];

                // Redirige vers la page d'accueil après la connexion.
                header('Location: /');
                exit;
            }

            /**
             * IDENTIFIANTS INCORRECTS
             *
             * Affiche un message générique pour ne pas révéler
             * si une adresse email existe dans la base de données.
             */
            $erreur = 'Email ou mot de passe incorrect';

            // Réaffiche le formulaire avec le message d'erreur.
            require 'views/auth/connexion.php';
            return;
        }

        // Affiche le formulaire de connexion lors d'une visite GET.
        require 'views/auth/connexion.php';
    }

    /**
     * DÉCONNEXION
     *
     * Supprime la session de l'utilisateur,
     * puis le redirige vers la page d'accueil.
     */
    public function deconnexion()
    {
        // Démarre la session uniquement si elle n'est pas déjà active.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        /**
         * SUPPRESSION DES DONNÉES DE SESSION
         *
         * Efface notamment :
         * - L'identifiant de l'utilisateur
         * - Son rôle
         * - Son nom
         * - Son prénom
         */
        $_SESSION = [];

        /**
         * SUPPRESSION DU COOKIE DE SESSION
         *
         * Si PHP utilise des cookies pour gérer les sessions,
         * le cookie existant est rendu invalide.
         */
        if (ini_get('session.use_cookies')) {

            // Récupère les paramètres du cookie de session.
            $parametresCookie = session_get_cookie_params();

            // Expire le cookie dans le navigateur.
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

        // Détruit la session actuelle.
        session_destroy();

        /**
         * NOUVELLE SESSION
         *
         * Crée une nouvelle session uniquement pour afficher
         * le message temporaire de déconnexion.
         */
        session_start();

        // Prépare le message de confirmation de déconnexion.
        $_SESSION['flash'] = [
            'type'    => 'secondary',
            'message' => '👋 À bientôt !',
        ];

        // Redirige l'utilisateur vers la page d'accueil.
        header('Location: /');
        exit;
    }
}
