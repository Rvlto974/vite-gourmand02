<?php
require_once 'models/CommandeModel.php';
require_once 'models/MenuModel.php';
require_once 'models/UtilisateurModel.php';
require_once 'models/CommandeStatutModel.php';
require_once 'config/EmailService.php';

class CommandesController {
    private $commandeModel;
    private $menuModel;
    private $utilisateurModel;
    private $emailService;
    private $commandeStatutModel;

    public function __construct() {
        $this->commandeModel = new CommandeModel();
        $this->menuModel = new MenuModel();
        $this->utilisateurModel = new UtilisateurModel();
        $this->emailService = new EmailService();
        $this->commandeStatutModel = new CommandeStatutModel();
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

    public function nouveau() {
        $this->verifierConnexion();

        $menu_id = $_GET['menu_id'] ?? null;
        $nb_personnes = $_GET['nb_personnes'] ?? 1;

        if (!$menu_id) {
            header('Location: /menus');
            exit;
        }

        $menu = $this->menuModel->getById($menu_id);

        if (!$menu) {
            header('Location: /menus');
            exit;
        }

        $utilisateur = $this->utilisateurModel->getById($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nb_personnes    = $_POST['nb_personnes'] ?? $menu['nb_personnes_min'];
            $adresse         = $_POST['adresse_livraison'] ?? '';
            $date            = $_POST['date_prestation'] ?? '';
            $heure           = $_POST['heure_livraison'] ?? '12:00';
            $frais_livraison = floatval($_POST['frais_livraison'] ?? 0);

            if (empty($date)) {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => '❌ Veuillez saisir une date de prestation.'];
                header('Location: /commandes/nouveau?menu_id=' . $menu_id);
                exit;
            }

            if ($menu['stock'] <= 0) {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => '❌ Ce menu n\'est plus disponible (stock épuisé).'];
                header('Location: /commandes/nouveau?menu_id=' . $menu_id);
                exit;
            }

            if (empty($heure)) {
                $heure = '12:00';
            }

            $date = $date . ' ' . $heure . ':00';

            $prix_total = $menu['prix_base'];
            if ($nb_personnes >= $menu['nb_personnes_min'] + 5) {
                $prix_total = $prix_total * 0.90;
            }
            $prix_total += $frais_livraison;

            $commande_id = $this->commandeModel->creer([
                'utilisateur_id'   => $_SESSION['user_id'],
                'menu_id'          => $menu_id,
                'nb_personnes'     => $nb_personnes,
                'prix_total'       => $prix_total,
                'adresse_livraison'=> $adresse,
                'date_prestation'  => $date
            ]);

            $this->menuModel->decrementerStock($menu_id);
            $this->commandeStatutModel->enregistrer($commande_id, 'nouvelle');

            require_once 'config/MongoService.php';
            $mongo = new MongoService();
            $mongo->enregistrerCommande([
                'menu_id'     => $menu_id,
                'menu_titre'  => $menu['titre'],
                'prix_total'  => $prix_total,
                'nb_personnes'=> $nb_personnes,
                'date'        => $date
            ]);

            $commande = $this->commandeModel->getById($commande_id);
            $this->emailService->envoyerConfirmationCommande(
                $utilisateur['email'],
                $utilisateur['prenom'],
                $commande
            );

            header('Location: /commandes/confirmation?id=' . $commande_id);
            exit;
        }

        require_once 'views/commandes/nouveau.php';
    }

    public function confirmation() {
        $this->verifierConnexion();
        $id = $_GET['id'] ?? null;
        $commande = $this->commandeModel->getById($id);
        require_once 'views/commandes/confirmation.php';
    }

    public function historique() {
        $this->verifierConnexion();
        $commandes = $this->commandeModel->getByUtilisateur($_SESSION['user_id']);

        $historiquesStatuts = [];
        foreach ($commandes as $commande) {
            $historiquesStatuts[$commande['id']] = $this->commandeStatutModel->getByCommande($commande['id']);
        }

        require_once 'views/commandes/historique.php';
    }

    public function annuler() {
        $this->verifierConnexion();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /commandes/historique');
            exit;
        }

        $commande = $this->commandeModel->getById($id);

        if (!$commande || $commande['utilisateur_id'] != $_SESSION['user_id']) {
            header('Location: /commandes/historique');
            exit;
        }

        if (in_array($commande['statut'], ['nouvelle', 'acceptee'])) {
            $this->commandeModel->annuler($id);
            $this->commandeStatutModel->enregistrer($id, 'annulee');

            $utilisateur = $this->utilisateurModel->getById($_SESSION['user_id']);

            $this->emailService->envoyerAnnulationClient(
                $utilisateur['email'],
                $utilisateur['prenom'],
                $commande
            );

            $this->emailService->envoyerAnnulationAdmin(
                $utilisateur['prenom'],
                $utilisateur['email'],
                $commande
            );
        }

        header('Location: /commandes/historique');
        exit;
    }

    public function modifier() {
        $this->verifierConnexion();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /commandes/historique');
            exit;
        }

        $commande = $this->commandeModel->getById($id);

        if (!$commande || $commande['utilisateur_id'] != $_SESSION['user_id']) {
            header('Location: /commandes/historique');
            exit;
        }

        if ($commande['statut'] !== 'nouvelle') {
            header('Location: /commandes/historique');
            exit;
        }

        $menu = $this->menuModel->getById($commande['menu_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nb_personnes = $_POST['nb_personnes'] ?? $commande['nb_personnes'];
            $adresse      = $_POST['adresse_livraison'] ?? $commande['adresse_livraison'];
            $date         = $_POST['date_prestation'] ?? $commande['date_prestation'];

            $prix_total = $menu['prix_base'];
            if ($nb_personnes >= $menu['nb_personnes_min'] + 5) {
                $prix_total = $prix_total * 0.90;
            }

            $this->commandeModel->modifier($id, [
                'nb_personnes'     => $nb_personnes,
                'adresse_livraison'=> $adresse,
                'date_prestation'  => $date,
                'prix_total'       => $prix_total
            ]);

            $_SESSION['flash'] = ['type' => 'success', 'message' => '✅ Commande modifiée avec succès !'];
            header('Location: /commandes/historique');
            exit;
        }

        require_once 'views/commandes/modifier.php';
    }
}