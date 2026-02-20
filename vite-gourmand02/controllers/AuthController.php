<?php
require_once 'models/UtilisateurModel.php';

class AuthController {
    private $utilisateurModel;

    public function __construct() {
        $this->utilisateurModel = new UtilisateurModel();
    }

    // Page inscription
    public function inscription() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            $prenom = $_POST['prenom'] ?? '';
            $email = $_POST['email'] ?? '';
            $motDePasse = $_POST['mot_de_passe'] ?? '';

            // Validation mot de passe
            if (!$this->validerMotDePasse($motDePasse)) {
                $erreur = "Le mot de passe doit contenir 10 caractères min, 
                            1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial";
                require_once 'views/auth/inscription.php';
                return;
            }

            // Hashage du mot de passe
            $hash = password_hash($motDePasse, PASSWORD_BCRYPT);

            $this->utilisateurModel->creer([
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'mot_de_passe' => $hash
            ]);