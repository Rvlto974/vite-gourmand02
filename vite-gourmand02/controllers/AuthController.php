<?php
require_once 'models/UtilisateurModel.php';

class AuthController {
    private $utilisateurModel;

    public function __construct() {
        $this->utilisateurModel = new UtilisateurModel();
    }

    public function inscription() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            $prenom = $_POST['prenom'] ?? '';
            $email = $_POST['email'] ?? '';
            $motDePasse = $_POST['mot_de_passe'] ?? '';

            if (!$this->validerMotDePasse($motDePasse)) {
                $erreur = "Le mot de passe doit contenir 10 caractères min, 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial";
                require_once 'views/auth/inscription.php';
                return;
            }

            $hash = password_hash($motDePasse, PASSWORD_BCRYPT);
            $this->utilisateurModel->creer([
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'mot_de_passe' => $hash
            ]);

            header('Location: /auth/connexion');
            exit;
        }
        require_once 'views/auth/inscription.php';
    }

    private function validerMotDePasse($motDePasse) {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{10,}$/', $motDePasse);
    }

    public function connexion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $motDePasse = $_POST['mot_de_passe'] ?? '';

            $utilisateur = $this->utilisateurModel->getByEmail($email);

            if ($utilisateur && password_verify($motDePasse, $utilisateur['mot_de_passe'])) {
                session_start();
                $_SESSION['user_id'] = $utilisateur['id'];
                $_SESSION['user_role'] = $utilisateur['role'];
                header('Location: /');
                exit;
            }

            $erreur = "Email ou mot de passe incorrect";
            require_once 'views/auth/connexion.php';
            return;
        }
        require_once 'views/auth/connexion.php';
    }

    public function deconnexion() {
        session_start();
        session_destroy();
        header('Location: /');
        exit;
    }
}