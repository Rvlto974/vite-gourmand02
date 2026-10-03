<?php
require_once 'config/EmailService.php';

class ContactController {
    public function index() {
        $success = false;
        $erreur = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom     = $_POST['nom'] ?? '';
            $email   = $_POST['email'] ?? '';
            $sujet   = $_POST['sujet'] ?? '';
            $message = $_POST['message'] ?? '';
            $rgpd    = $_POST['rgpd'] ?? '';

            if (empty($nom) || empty($email) || empty($sujet) || empty($message)) {
                $erreur = "Tous les champs sont obligatoires.";
            } elseif (!$rgpd) {
                $erreur = "Vous devez accepter la politique de confidentialité.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erreur = "L'adresse email n'est pas valide.";
            } else {
                $emailService = new EmailService();
                $emailService->envoyerContact($nom, $email, $sujet, $message);
                $success = true;
            }
        }
        require_once 'views/contact/index.php';
    }
}