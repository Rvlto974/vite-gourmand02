<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'vendor/autoload.php';

class EmailService {
    private $mail;

    public function __construct() {
    $this->mail = new PHPMailer(true);
    $this->mail->isSMTP();
    $this->mail->Host = 'sandbox.smtp.mailtrap.io';
    $this->mail->SMTPAuth = true;
    $this->mail->Username = 'f72a40f6d23659';
    $this->mail->Password = $_ENV['MAIL_PASS'] ?? '';
    $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $this->mail->Port = 587;
    $this->mail->CharSet = 'UTF-8';
    $this->mail->setFrom('contact@viteetgourmand.fr', 'Vite & Gourmand');
}

    // Email confirmation de commande
    public function envoyerConfirmationCommande($email, $prenom, $commande) {
        try {
            $this->mail->addAddress($email, $prenom);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Confirmation de votre commande — Vite & Gourmand';
            $this->mail->Body = "
                <h2>Bonjour {$prenom},</h2>
                <p>Votre commande a bien été enregistrée !</p>
                <p><strong>Menu :</strong> {$commande['menu_titre']}</p>
                <p><strong>Nombre de personnes :</strong> {$commande['nb_personnes']}</p>
                <p><strong>Prix total :</strong> {$commande['prix_total']} €</p>
                <p><strong>Date :</strong> {$commande['date_prestation']}</p>
                <p>Merci de votre confiance !</p>
                <p>L'équipe Vite & Gourmand</p>
            ";
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // Email confirmation d'inscription
    public function envoyerConfirmationInscription($email, $prenom) {
        try {
            $this->mail->addAddress($email, $prenom);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Bienvenue chez Vite & Gourmand !';
            $this->mail->Body = "
                <h2>Bienvenue {$prenom} !</h2>
                <p>Votre compte a bien été créé sur Vite & Gourmand.</p>
                <p>Vous pouvez dès maintenant découvrir nos menus et passer commande.</p>
                <p>L'équipe Vite & Gourmand</p>
            ";
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // Email changement de statut
    public function envoyerChangementStatut($email, $prenom, $statut) {
        try {
            $this->mail->addAddress($email, $prenom);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Mise à jour de votre commande — Vite & Gourmand';
            $this->mail->Body = "
                <h2>Bonjour {$prenom},</h2>
                <p>Le statut de votre commande a été mis à jour.</p>
                <p><strong>Nouveau statut :</strong> {$statut}</p>
                <p>L'équipe Vite & Gourmand</p>
            ";
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}