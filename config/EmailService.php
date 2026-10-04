<?php

// Importe les classes PHPMailer nécessaires.
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Charge l’autoloader de Composer pour rendre PHPMailer disponible.
require_once 'vendor/autoload.php';

class EmailService
{
    // Instance de PHPMailer utilisée pour envoyer les courriels.
    private $mail;

    public function __construct()
    {
        // Crée une instance de PHPMailer qui lève des exceptions en cas d’erreur.
        $this->mail = new PHPMailer(true);

        // Configure l’envoi via le serveur SMTP de Gmail.
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'mathieujacquet97460@gmail.com';

        // Récupère le mot de passe SMTP depuis une variable d’environnement.
        $this->mail->Password = getenv('MAIL_PASS') ?: '';

        // Active le chiffrement STARTTLS et définit le port SMTP.
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = 587;

        // Configure l’encodage et l’adresse d’expédition des courriels.
        $this->mail->CharSet = 'UTF-8';
        $this->mail->setFrom('mathieujacquet97460@gmail.com', 'Vite & Gourmand');
    }

    public function envoyerConfirmationCommande($email, $prenom, $commande)
    {
        try {
            // Prépare et envoie la confirmation au client.
            $this->mail->clearAddresses();
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

            // Envoie ensuite une notification à l’administrateur.
            $this->mail->clearAddresses();
            $this->mail->addAddress('mathieujacquet97460@gmail.com', 'Admin');
            $this->mail->Subject = '🛒 Nouvelle commande — Vite & Gourmand';
            $this->mail->Body = "
                <h2>Nouvelle commande reçue !</h2>
                <p><strong>Client :</strong> {$prenom} ({$email})</p>
                <p><strong>Menu :</strong> {$commande['menu_titre']}</p>
                <p><strong>Nombre de personnes :</strong> {$commande['nb_personnes']}</p>
                <p><strong>Prix total :</strong> {$commande['prix_total']} €</p>
                <p><strong>Date :</strong> {$commande['date_prestation']}</p>
            ";
            $this->mail->send();

            return true;
        } catch (Exception $e) {
            // Indique que l’envoi d’au moins un des courriels a échoué.
            return false;
        }
    }

    public function envoyerConfirmationInscription($email, $prenom)
    {
        try {
            // Prépare et envoie le courriel de bienvenue au nouvel utilisateur.
            $this->mail->clearAddresses();
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
            // Signale l’échec de l’envoi.
            return false;
        }
    }

    public function envoyerChangementStatut($email, $prenom, $statut)
    {
        try {
            // Informe le client de la mise à jour du statut de sa commande.
            $this->mail->clearAddresses();
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
            // Signale l’échec de l’envoi.
            return false;
        }
    }

    public function envoyerReinitialisationMdp($email, $prenom, $token)
    {
        try {
            // Prépare le lien de réinitialisation contenant le jeton.
            $lien = 'https://vite-gourmand02.fly.dev/motDePasse/reinitialiser?token=' . $token;

            // Envoie au client les instructions de réinitialisation du mot de passe.
            $this->mail->clearAddresses();
            $this->mail->addAddress($email, $prenom);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Réinitialisation de votre mot de passe — Vite & Gourmand';
            $this->mail->Body = "
                <h2>Bonjour {$prenom},</h2>
                <p>Vous avez demandé la réinitialisation de votre mot de passe.</p>
                <p><a href='{$lien}'>Cliquer ici pour réinitialiser votre mot de passe</a></p>
                <p>Ce lien expire dans 1 heure.</p>
                <p>Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.</p>
                <p>L'équipe Vite & Gourmand</p>
            ";
            $this->mail->send();

            return true;
        } catch (Exception $e) {
            // Signale l’échec de l’envoi.
            return false;
        }
    }

    public function envoyerCommandeTerminee($email, $prenom, $commande, $commande_id)
    {
        try {
            // Crée le lien permettant au client de laisser un avis.
            $lien = 'https://vite-gourmand02.fly.dev/avis/creer?commande_id=' . $commande_id;

            // Informe le client que sa commande est terminée et l’invite à laisser un avis.
            $this->mail->clearAddresses();
            $this->mail->addAddress($email, $prenom);
            $this->mail->isHTML(true);
            $this->mail->Subject = '🎉 Votre commande est terminée — Vite & Gourmand';
            $this->mail->Body = "
                <h2>Bonjour {$prenom},</h2>
                <p>Votre commande <strong>{$commande['menu_titre']}</strong> est maintenant terminée !</p>
                <p>Nous espérons que vous avez passé un excellent moment.</p>
                <p>Vous pouvez laisser un avis sur votre expérience :</p>
                <p><a href='{$lien}' style='background:#5DA99A; color:white; padding:10px 20px; border-radius:5px; text-decoration:none;'>⭐ Laisser un avis</a></p>
                <p>Merci de votre confiance !</p>
                <p>L'équipe Vite & Gourmand</p>
            ";
            $this->mail->send();

            return true;
        } catch (Exception $e) {
            // Signale l’échec de l’envoi.
            return false;
        }
    }

    public function envoyerAttentesMateriel($email, $prenom, $commande)
    {
        try {
            // Rappelle au client les conditions de retour du matériel prêté.
            $this->mail->clearAddresses();
            $this->mail->addAddress($email, $prenom);
            $this->mail->isHTML(true);
            $this->mail->Subject = '📦 Retour matériel requis — Vite & Gourmand';
            $this->mail->Body = "
                <h2>Bonjour {$prenom},</h2>
                <p>Concernant votre commande <strong>{$commande['menu_titre']}</strong>,</p>
                <p>Nous vous rappelons que le matériel mis à disposition doit être retourné sous <strong>10 jours ouvrés</strong>.</p>
                <p>Passé ce délai, des frais de <strong>600 €</strong> vous seront facturés conformément aux conditions générales de vente.</p>
                <p>Pour organiser le retour, contactez-nous :</p>
                <p>📧 contact@viteetgourmand.fr</p>
                <p>📞 05 XX XX XX XX</p>
                <p>L'équipe Vite & Gourmand</p>
            ";
            $this->mail->send();

            return true;
        } catch (Exception $e) {
            // Signale l’échec de l’envoi.
            return false;
        }
    }

    public function envoyerAnnulationClient($email, $prenom, $commande, $motif = null)
    {
        try {
            // Ajoute le motif au courriel uniquement s’il a été fourni.
            $motifHtml = $motif
                ? "<p><strong>Motif :</strong> {$motif}</p>"
                : '';

            // Informe le client de l’annulation de sa commande.
            $this->mail->clearAddresses();
            $this->mail->addAddress($email, $prenom);
            $this->mail->isHTML(true);
            $this->mail->Subject = '❌ Annulation de votre commande — Vite & Gourmand';
            $this->mail->Body = "
                <h2>Bonjour {$prenom},</h2>
                <p>Votre commande a été annulée.</p>
                <p><strong>Menu :</strong> {$commande['menu_titre']}</p>
                <p><strong>Date prestation :</strong> {$commande['date_prestation']}</p>
                <p><strong>Prix :</strong> {$commande['prix_total']} €</p>
                {$motifHtml}
                <p>Nous nous excusons pour la gêne occasionnée.</p>
                <p>Si vous souhaitez passer une nouvelle commande : <a href='https://vite-gourmand02.fly.dev/menus'>Voir nos menus</a></p>
                <p>L'équipe Vite & Gourmand</p>
            ";
            $this->mail->send();

            return true;
        } catch (Exception $e) {
            // Signale l’échec de l’envoi.
            return false;
        }
    }

    public function envoyerAnnulationAdmin($prenom, $email, $commande)
    {
        try {
            // Notifie l’administrateur de l’annulation et des détails de la commande.
            $this->mail->clearAddresses();
            $this->mail->addAddress('mathieujacquet97460@gmail.com', 'Admin');
            $this->mail->isHTML(true);
            $this->mail->Subject = '❌ Annulation commande — Vite & Gourmand';
            $this->mail->Body = "
                <h2>Commande annulée !</h2>
                <p><strong>Client :</strong> {$prenom} ({$email})</p>
                <p><strong>Menu :</strong> {$commande['menu_titre']}</p>
                <p><strong>Nombre de personnes :</strong> {$commande['nb_personnes']}</p>
                <p><strong>Prix :</strong> {$commande['prix_total']} €</p>
                <p><strong>Date prestation :</strong> {$commande['date_prestation']}</p>
            ";
            $this->mail->send();

            return true;
        } catch (Exception $e) {
            // Signale l’échec de l’envoi.
            return false;
        }
    }

    public function envoyerCreationCompteEmploye($email, $prenom)
    {
        try {
            // Envoie à l’employé les informations concernant la création de son compte.
            $this->mail->clearAddresses();
            $this->mail->addAddress($email, $prenom);
            $this->mail->isHTML(true);
            $this->mail->Subject = '👋 Votre compte employé — Vite & Gourmand';
            $this->mail->Body = "
                <h2>Bonjour {$prenom},</h2>
                <p>Un compte employé a été créé pour vous sur Vite & Gourmand.</p>
                <p>Pour obtenir votre mot de passe, rapprochez-vous de l'administrateur.</p>
                <p>Vous pourrez ensuite vous connecter sur : <a href='https://vite-gourmand02.fly.dev/auth/connexion'>vite-gourmand02.fly.dev</a></p>
                <p>L'équipe Vite & Gourmand</p>
            ";
            $this->mail->send();

            return true;
        } catch (Exception $e) {
            // Signale l’échec de l’envoi.
            return false;
        }
    }

    public function envoyerContact($nom, $email, $sujet, $message)
    {
        try {
            // Envoie le message du formulaire de contact à l’adresse de l’entreprise.
            $this->mail->clearAddresses();
            $this->mail->addAddress('mathieujacquet97460@gmail.com', 'Vite & Gourmand');

            // Permet de répondre directement à la personne qui a envoyé le message.
            $this->mail->addReplyTo($email, $nom);

            $this->mail->isHTML(true);
            $this->mail->Subject = '📩 Nouveau message contact — ' . $sujet;
            $this->mail->Body = "
                <h2>Nouveau message de contact</h2>
                <p><strong>Nom :</strong> {$nom}</p>
                <p><strong>Email :</strong> {$email}</p>
                <p><strong>Sujet :</strong> {$sujet}</p>
                <p><strong>Message :</strong></p>
                <p>{$message}</p>
            ";
            $this->mail->send();

            return true;
        } catch (Exception $e) {
            // Signale l’échec de l’envoi.
            return false;
        }
    }
}