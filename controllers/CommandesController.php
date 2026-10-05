<?php

// Charge les modèles et services nécessaires au contrôleur.
require_once 'models/CommandeModel.php';
require_once 'models/MenuModel.php';
require_once 'models/UtilisateurModel.php';
require_once 'models/CommandeStatutModel.php';
require_once 'config/EmailService.php';

class CommandesController
{
    // Modèles et services utilisés par les différentes actions.
    private $commandeModel;
    private $menuModel;
    private $utilisateurModel;
    private $emailService;
    private $commandeStatutModel;

    // Initialise les dépendances du contrôleur.
    public function __construct()
    {
        $this->commandeModel = new CommandeModel();
        $this->menuModel = new MenuModel();
        $this->utilisateurModel = new UtilisateurModel();
        $this->emailService = new EmailService();
        $this->commandeStatutModel = new CommandeStatutModel();
    }

    // Vérifie que l'utilisateur est connecté.
    private function verifierConnexion()
    {
        // Démarre la session uniquement si elle n'est pas déjà active.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Redirige vers la page de connexion si aucun utilisateur n'est connecté.
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/connexion');
            exit;
        }
    }

    // Affiche le formulaire et traite la création d'une commande.
    public function nouveau()
    {
        $this->verifierConnexion();

        // Récupère l'identifiant du menu demandé.
        $menu_id = $_GET['menu_id'] ?? null;
        $nb_personnes = $_GET['nb_personnes'] ?? 1;

        // Retourne à la liste des menus si aucun menu n'est indiqué.
        if (!$menu_id) {
            header('Location: /menus');
            exit;
        }

        // Charge les informations du menu.
        $menu = $this->menuModel->getById($menu_id);

        // Retourne à la liste si le menu n'existe pas.
        if (!$menu) {
            header('Location: /menus');
            exit;
        }

        // Charge les informations de l'utilisateur connecté.
        $utilisateur = $this->utilisateurModel->getById($_SESSION['user_id']);

        // Traite le formulaire envoyé par le client.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nb_personnes = $_POST['nb_personnes'] ?? $menu['nb_personnes_min'];
            $adresse = $_POST['adresse_livraison'] ?? '';
            $date = $_POST['date_prestation'] ?? '';
            $heure = $_POST['heure_livraison'] ?? '12:00';

            // Récupère les frais de livraison transmis par le formulaire.
            $frais_livraison = floatval($_POST['frais_livraison'] ?? 0);

            // Vérifie que la date de prestation a été renseignée.
            if (empty($date)) {
                $_SESSION['flash'] = [
                    'type' => 'danger',
                    'message' => '❌ Veuillez saisir une date de prestation.'
                ];
                header('Location: /commandes/nouveau?menu_id=' . $menu_id);
                exit;
            }

            // Vérifie que le menu est encore en stock.
            if ($menu['stock'] <= 0) {
                $_SESSION['flash'] = [
                    'type' => 'danger',
                    'message' => '❌ Ce menu n\'est plus disponible (stock épuisé).'
                ];
                header('Location: /commandes/nouveau?menu_id=' . $menu_id);
                exit;
            }

            // Utilise midi par défaut si aucune heure n'a été saisie.
            if (empty($heure)) {
                $heure = '12:00';
            }

            // Combine la date et l'heure pour l'enregistrement.
            $date = $date . ' ' . $heure . ':00';

            // Calcule le prix de départ à partir du prix du menu.
            $prix_total = $menu['prix_base'];

            // Applique une réduction de 10 % à partir du nombre de personnes requis.
            if ($nb_personnes >= $menu['nb_personnes_min'] + 5) {
                $prix_total *= 0.90;
            }

            // Ajoute les frais de livraison au prix calculé.
            $prix_total += $frais_livraison;

            // Enregistre la commande dans la base de données.
            $commande_id = $this->commandeModel->creer([
                'utilisateur_id' => $_SESSION['user_id'],
                'menu_id' => $menu_id,
                'nb_personnes' => $nb_personnes,
                'prix_total' => $prix_total,
                'adresse_livraison' => $adresse,
                'date_prestation' => $date
            ]);

            // Décrémente le stock et crée le premier statut de la commande.
            $this->menuModel->decrementerStock($menu_id);
            $this->commandeStatutModel->enregistrer($commande_id, 'nouvelle');

            // Recharge les informations de la commande pour l'e-mail de confirmation.
            $commande = $this->commandeModel->getById($commande_id);

            // Envoie un e-mail de confirmation au client.
            $this->emailService->envoyerConfirmationCommande(
                $utilisateur['email'],
                $utilisateur['prenom'],
                $commande
            );

            // Redirige le client vers la page de confirmation.
            header('Location: /commandes/confirmation?id=' . $commande_id);
            exit;
        }

        // Affiche le formulaire de commande.
        require_once 'views/commandes/nouveau.php';
    }

    // Affiche la confirmation d'une commande.
    public function confirmation()
    {
        $this->verifierConnexion();

        $id = $_GET['id'] ?? null;
        $commande = $this->commandeModel->getById($id);

        require_once 'views/commandes/confirmation.php';
    }

    // Affiche l'historique des commandes de l'utilisateur connecté.
    public function historique()
    {
        $this->verifierConnexion();

        $commandes = $this->commandeModel->getByUtilisateur($_SESSION['user_id']);

        // Charge l'historique des statuts pour chaque commande.
        $historiquesStatuts = [];
        foreach ($commandes as $commande) {
            $historiquesStatuts[$commande['id']] =
                $this->commandeStatutModel->getByCommande($commande['id']);
        }

        require_once 'views/commandes/historique.php';
    }

    // Annule une commande lorsque son statut le permet.
    public function annuler()
    {
        $this->verifierConnexion();

        $id = $_GET['id'] ?? null;

        // Retourne à l'historique si l'identifiant est absent.
        if (!$id) {
            header('Location: /commandes/historique');
            exit;
        }

        $commande = $this->commandeModel->getById($id);

        // Vérifie que la commande existe et appartient à l'utilisateur connecté.
        if (!$commande || $commande['utilisateur_id'] != $_SESSION['user_id']) {
            header('Location: /commandes/historique');
            exit;
        }

        // Seules les commandes nouvelles ou acceptées peuvent être annulées.
        if (in_array($commande['statut'], ['nouvelle', 'acceptee'])) {
            $this->commandeModel->annuler($id);
            $this->commandeStatutModel->enregistrer($id, 'annulee');

            $utilisateur = $this->utilisateurModel->getById($_SESSION['user_id']);

            // Informe le client de l'annulation.
            $this->emailService->envoyerAnnulationClient(
                $utilisateur['email'],
                $utilisateur['prenom'],
                $commande
            );

            // Informe également l'administrateur.
            $this->emailService->envoyerAnnulationAdmin(
                $utilisateur['prenom'],
                $utilisateur['email'],
                $commande
            );
        }

        header('Location: /commandes/historique');
        exit;
    }

    // Affiche et traite la modification d'une commande.
    public function modifier()
    {
        $this->verifierConnexion();

        $id = $_GET['id'] ?? null;

        // Retourne à l'historique si aucun identifiant n'est fourni.
        if (!$id) {
            header('Location: /commandes/historique');
            exit;
        }

        $commande = $this->commandeModel->getById($id);

        // Vérifie que la commande existe et appartient à l'utilisateur connecté.
        if (!$commande || $commande['utilisateur_id'] != $_SESSION['user_id']) {
            header('Location: /commandes/historique');
            exit;
        }

        // Seules les commandes ayant le statut « nouvelle » sont modifiables.
        if ($commande['statut'] !== 'nouvelle') {
            header('Location: /commandes/historique');
            exit;
        }

        // Charge le menu associé à la commande.
        $menu = $this->menuModel->getById($commande['menu_id']);

        // Traite le formulaire de modification.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nb_personnes = $_POST['nb_personnes'] ?? $commande['nb_personnes'];
            $adresse = $_POST['adresse_livraison'] ?? $commande['adresse_livraison'];
            $date = $_POST['date_prestation'] ?? $commande['date_prestation'];

            // Recalcule le prix selon le nombre de personnes.
            $prix_total = $menu['prix_base'];

            if ($nb_personnes >= $menu['nb_personnes_min'] + 5) {
                $prix_total *= 0.90;
            }

            // Enregistre les modifications de la commande.
            $this->commandeModel->modifier($id, [
                'nb_personnes' => $nb_personnes,
                'adresse_livraison' => $adresse,
                'date_prestation' => $date,
                'prix_total' => $prix_total
            ]);

            // Affiche un message de réussite après la redirection.
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => '✅ Commande modifiée avec succès !'
            ];

            header('Location: /commandes/historique');
            exit;
        }

        // Affiche le formulaire de modification.
        require_once 'views/commandes/modifier.php';
    }
}