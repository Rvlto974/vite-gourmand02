<?php

/**
 * ============================================================
 * VITE & GOURMAND — CONTRÔLEUR ADMINISTRATEUR
 * ============================================================
 *
 * Ce contrôleur gère les fonctionnalités réservées
 * aux administrateurs :
 *
 * - Tableau de bord
 * - Gestion des utilisateurs
 * - Création des comptes employés
 * - Gestion des menus
 * - Statistiques MongoDB
 * - Modération des avis clients
 *
 * Les données principales sont gérées par MySQL.
 * Les statistiques sont récupérées depuis MongoDB.
 * ============================================================
 */


// Chargement des modèles nécessaires.
require_once 'models/UtilisateurModel.php';
require_once 'models/MenuModel.php';
require_once 'models/CommandeModel.php';

// Chargement du service d'envoi des emails.
require_once 'config/EmailService.php';


class AdminController
{
    // Modèle de gestion des utilisateurs.
    private $utilisateurModel;

    // Modèle de gestion des menus.
    private $menuModel;

    // Modèle de gestion des commandes.
    private $commandeModel;

    // Service d'envoi des emails.
    private $emailService;


    /**
     * ========================================================
     * CONSTRUCTEUR
     * ========================================================
     *
     * Initialise les modèles et les services nécessaires
     * au fonctionnement du contrôleur administrateur.
     */
    public function __construct()
    {
        $this->utilisateurModel = new UtilisateurModel();

        $this->menuModel = new MenuModel();

        $this->commandeModel = new CommandeModel();

        $this->emailService = new EmailService();
    }


    /**
     * ========================================================
     * SÉCURITÉ — VÉRIFICATION ADMINISTRATEUR
     * ========================================================
     *
     * Vérifie que l'utilisateur est connecté
     * et possède le rôle administrateur.
     *
     * Si ce n'est pas le cas, il est redirigé
     * vers la page de connexion.
     */
    private function verifierAdmin()
    {
        // Démarrer la session uniquement si nécessaire.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Vérifier la connexion et le rôle administrateur.
        if (
            !isset($_SESSION['user_id']) ||
            ($_SESSION['user_role'] ?? null) !== 'admin'
        ) {
            header('Location: /auth/connexion');
            exit;
        }
    }


    /**
     * ========================================================
     * TABLEAU DE BORD ADMINISTRATEUR
     * ========================================================
     *
     * Récupère les utilisateurs, commandes et menus
     * pour les afficher dans le tableau de bord.
     */
    public function dashboard()
    {
        // Vérification des droits.
        $this->verifierAdmin();

        // Récupération des données.
        $utilisateurs = $this->utilisateurModel->getAll();

        $commandes = $this->commandeModel->getAllCommandes();

        $menus = $this->menuModel->getAll();

        // Affichage de la vue.
        require_once 'views/admin/dashboard.php';
    }


    /**
     * ========================================================
     * GESTION DES UTILISATEURS
     * ========================================================
     *
     * Affiche la liste des utilisateurs enregistrés.
     */
    public function utilisateurs()
    {
        $this->verifierAdmin();

        // Récupérer tous les utilisateurs.
        $utilisateurs = $this->utilisateurModel->getAll();

        // Afficher la liste.
        require_once 'views/admin/utilisateurs.php';
    }


    /**
     * ========================================================
     * ACTIVER / DÉSACTIVER UN UTILISATEUR
     * ========================================================
     *
     * Récupère l'identifiant envoyé par formulaire POST.
     * Modifie l'état actif de l'utilisateur.
     */
    public function toggleActif()
    {
        $this->verifierAdmin();

        // Identifiant transmis par le formulaire.
        $id = $_POST['id'] ?? null;

        // Modifier l'état uniquement si un ID est présent.
        if ($id) {
            $this->utilisateurModel->toggleActif($id);
        }

        // Retour vers la liste des utilisateurs.
        header('Location: /admin/utilisateurs');
        exit;
    }


    /**
     * ========================================================
     * GESTION DES MENUS
     * ========================================================
     *
     * Affiche tous les menus dans l'espace administrateur,
     * y compris ceux qui sont désactivés.
     */
    public function menus()
    {
        $this->verifierAdmin();

        // Récupérer les menus destinés à l'administration.
        $menus = $this->menuModel->getAllAdmin();

        // Afficher la vue.
        require_once 'views/admin/menus.php';
    }


    /**
     * ========================================================
     * ACTIVER / DÉSACTIVER UN MENU
     * ========================================================
     *
     * Permet de modifier la disponibilité d'un menu.
     */
    public function toggleActifMenu()
    {
        $this->verifierAdmin();

        // Récupération de l'identifiant.
        $id = $_POST['id'] ?? null;

        // Modification de l'état actif.
        if ($id) {
            $this->menuModel->toggleActif($id);
        }

        // Retour vers les menus.
        header('Location: /admin/menus');
        exit;
    }


    /**
     * ========================================================
     * CRÉATION D'UN COMPTE EMPLOYÉ
     * ========================================================
     *
     * Permet à l'administrateur de créer un employé.
     *
     * Le mot de passe est haché avec bcrypt.
     * Un email de création de compte est envoyé.
     */
    public function creerEmploye()
    {
        $this->verifierAdmin();

        // Traitement du formulaire de création.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Récupération des informations.
            $nom = $_POST['nom'] ?? '';

            $prenom = $_POST['prenom'] ?? '';

            $email = $_POST['email'] ?? '';

            $motDePasse = $_POST['mot_de_passe'] ?? '';

            // Hachage sécurisé du mot de passe.
            $hash = password_hash(
                $motDePasse,
                PASSWORD_BCRYPT
            );

            // Création du compte avec le rôle employé.
            $this->utilisateurModel->creerAvecRole([
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'mot_de_passe' => $hash,
                'role' => 'employe'
            ]);

            // Envoi de l'email de création de compte.
            $this->emailService->envoyerCreationCompteEmploye(
                $email,
                $prenom
            );

            // Message de confirmation.
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => '✅ Compte employé créé et mail envoyé.'
            ];

            // Retour vers les utilisateurs.
            header('Location: /admin/utilisateurs');
            exit;
        }

        // Afficher le formulaire.
        require_once 'views/admin/creer_employe.php';
    }


    /**
     * ========================================================
     * STATISTIQUES ADMINISTRATEUR — MONGODB
     * ========================================================
     *
     * Récupère les statistiques des commandes non annulées.
     *
     * Données disponibles :
     *
     * 1. Montant total des commandes
     * 2. Statistiques par menu
     * 3. Statistiques par jour (NOUVEAU)
     * 4. Statistiques par semaine (NOUVEAU)
     * 5. Statistiques par mois
     *
     * Les données sont ensuite transmises à stats.php.
     *
     * IMPORTANT :
     * Le montant total ne représente pas nécessairement
     * des paiements effectivement encaissés.
     */
    public function stats()
    {
        // Vérifier que l'utilisateur est administrateur.
        $this->verifierAdmin();

        // Charger le service de statistiques MongoDB.
        require_once 'config/MongoService.php';

        // Initialiser le service MongoDB.
        $mongo = new MongoService();

        // Montant total des commandes non annulées.
        $caTotal = $mongo->getCaTotal();

        // Nombre de commandes, personnes et montants par menu.
        $statsByMenu = $mongo->getStatsByMenu();

        // NOUVEAU : statistiques regroupées par jour.
        $statsByJour = $mongo->getStatsByJour();

        // NOUVEAU : statistiques regroupées par semaine ISO.
        $statsBySemaine = $mongo->getStatsBySemaine();

        // Statistiques regroupées par mois.
        $statsByMois = $mongo->getStatsByMois();

        // Afficher la page des statistiques.
        require_once 'views/admin/stats.php';
    }


    /**
     * ========================================================
     * MODÉRATION DES AVIS CLIENTS
     * ========================================================
     *
     * Récupère les avis en attente de validation.
     */
    public function avis()
    {
        $this->verifierAdmin();

        // Charger le modèle des avis.
        require_once 'models/AvisModel.php';

        $avisModel = new AvisModel();

        // Récupérer les avis en attente.
        $avis = $avisModel->getAvisEnAttente();

        // Afficher la vue.
        require_once 'views/admin/avis.php';
    }


    /**
     * ========================================================
     * VALIDATION D'UN AVIS CLIENT
     * ========================================================
     *
     * Permet à l'administrateur de publier un avis.
     */
    public function validerAvis()
    {
        $this->verifierAdmin();

        require_once 'models/AvisModel.php';

        $avisModel = new AvisModel();

        // Récupérer l'identifiant de l'avis.
        $id = $_POST['id'] ?? null;

        // Valider l'avis si un identifiant est fourni.
        if ($id) {
            $avisModel->valider($id);
        }

        // Retour à la modération.
        header('Location: /admin/avis');
        exit;
    }


    /**
     * ========================================================
     * REFUS D'UN AVIS CLIENT
     * ========================================================
     *
     * Permet à l'administrateur de refuser un avis.
     */
    public function refuserAvis()
    {
        $this->verifierAdmin();

        require_once 'models/AvisModel.php';

        $avisModel = new AvisModel();

        // Identifiant de l'avis.
        $id = $_POST['id'] ?? null;

        // Refuser l'avis.
        if ($id) {
            $avisModel->refuser($id);
        }

        // Retour à la liste des avis.
        header('Location: /admin/avis');
        exit;
    }


    /**
     * ========================================================
     * MODIFICATION D'UN MENU
     * ========================================================
     *
     * Permet de modifier :
     * - Les informations générales du menu
     * - Les images
     * - Les plats associés
     */
    public function modifierMenu()
    {
        $this->verifierAdmin();

        // Récupérer l'identifiant du menu.
        $id = $_GET['id'] ?? null;

        // Rediriger si aucun identifiant n'est fourni.
        if (!$id) {
            header('Location: /admin/menus');
            exit;
        }

        // Rechercher le menu.
        $menu = $this->menuModel->getById($id);

        // Rediriger si le menu n'existe pas.
        if (!$menu) {
            header('Location: /admin/menus');
            exit;
        }

        // Traitement du formulaire de modification.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Modifier les informations générales.
            $this->menuModel->modifier($id, [
                'titre' => $_POST['titre'] ?? '',
                'description' => $_POST['description'] ?? '',
                'theme' => $_POST['theme'] ?? '',
                'regime' => $_POST['regime'] ?? '',
                'prix_base' => $_POST['prix_base'] ?? 0,
                'nb_personnes_min' => $_POST['nb_personnes_min'] ?? 1,
                'stock' => $_POST['stock'] ?? 0,
            ]);

            // Supprimer les anciennes images.
            $this->menuModel->supprimerImages($id);

            // Récupérer les nouvelles images.
            $images = $_POST['images'] ?? [];

            // Ajouter les images non vides.
            foreach ($images as $ordre => $url) {
                if (!empty(trim($url))) {
                    $this->menuModel->ajouterImage(
                        $id,
                        trim($url),
                        $ordre
                    );
                }
            }

            // Connexion à la base de données MySQL.
            $db = (new Database())->connect();

            // Supprimer les anciens plats du menu.
            $db->prepare(
                "DELETE FROM plats WHERE menu_id = ?"
            )->execute([$id]);

            // Récupérer les nouveaux plats.
            $plats = $_POST['plats'] ?? [];

            // Ajouter chaque plat non vide.
            foreach ($plats as $plat) {
                if (!empty(trim($plat['nom']))) {

                    $stmt = $db->prepare(
                        "INSERT INTO plats
                        (menu_id, nom, type, description, allergenes)
                        VALUES (?,?,?,?,?)"
                    );

                    $stmt->execute([
                        $id,
                        trim($plat['nom']),
                        $plat['type'] ?? 'plat',
                        trim($plat['description'] ?? ''),
                        trim($plat['allergenes'] ?? ''),
                    ]);
                }
            }

            // Message de confirmation.
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => '✅ Menu modifié avec succès.'
            ];

            // Retour vers les menus.
            header('Location: /admin/menus');
            exit;
        }

        // Récupérer les plats et images du menu.
        $plats = $this->menuModel->getPlatsById($id);

        $images = $this->menuModel->getImagesById($id);

        // Afficher le formulaire de modification.
        require_once 'views/admin/modifier_menu.php';
    }


    /**
     * ========================================================
     * SUPPRESSION D'UN MENU
     * ========================================================
     *
     * Supprime un menu à partir de son identifiant.
     */
    public function supprimerMenu()
    {
        $this->verifierAdmin();

        // Identifiant transmis par POST.
        $id = $_POST['id'] ?? null;

        if ($id) {

            // Suppression via le modèle.
            $this->menuModel->supprimer($id);

            // Message de confirmation.
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => '✅ Menu supprimé.'
            ];
        }

        // Retour vers les menus.
        header('Location: /admin/menus');
        exit;
    }


    /**
     * ========================================================
     * CRÉATION D'UN MENU
     * ========================================================
     *
     * Permet à l'administrateur de créer :
     * - Un nouveau menu
     * - Ses images
     * - Ses plats associés
     */
    public function creerMenu()
    {
        $this->verifierAdmin();

        // Traitement du formulaire.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Enregistrement des informations du menu.
            $this->menuModel->creer([
                'titre' => $_POST['titre'] ?? '',
                'description' => $_POST['description'] ?? '',
                'theme' => $_POST['theme'] ?? 'classique',
                'regime' => $_POST['regime'] ?? 'classique',
                'prix_base' => $_POST['prix_base'] ?? 0,
                'nb_personnes_min' => $_POST['nb_personnes_min'] ?? 1,
                'stock' => $_POST['stock'] ?? 0,
                'image' => $_POST['image'] ?? null,
            ]);

            // Récupérer l'identifiant du menu créé.
            $menuId = $this->menuModel->getLastInsertId();

            // Récupérer les images envoyées.
            $images = $_POST['images'] ?? [];

            // Enregistrer les images valides.
            foreach ($images as $ordre => $url) {
                if (!empty(trim($url))) {
                    $this->menuModel->ajouterImage(
                        $menuId,
                        trim($url),
                        $ordre
                    );
                }
            }

            // Connexion MySQL pour les plats.
            $db = (new Database())->connect();

            // Récupérer les plats envoyés.
            $plats = $_POST['plats'] ?? [];

            // Ajouter les plats du nouveau menu.
            foreach ($plats as $plat) {
                if (!empty(trim($plat['nom']))) {

                    $stmt = $db->prepare(
                        "INSERT INTO plats
                        (menu_id, nom, type, description, allergenes)
                        VALUES (?,?,?,?,?)"
                    );

                    $stmt->execute([
                        $menuId,
                        trim($plat['nom']),
                        $plat['type'] ?? 'plat',
                        trim($plat['description'] ?? ''),
                        trim($plat['allergenes'] ?? ''),
                    ]);
                }
            }

            // Message de confirmation.
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => '✅ Menu créé avec succès.'
            ];

            // Retour vers les menus.
            header('Location: /admin/menus');
            exit;
        }

        // Afficher le formulaire de création.
        require_once 'views/admin/creer_menu.php';
    }
}
