
<?php
/**
 * Fichier : views/layouts/header.php
 * Projet : Vite & Gourmand
 *
 * Rôle :
 * - Démarrer la session PHP
 * - Charger les styles du site
 * - Afficher les notifications temporaires
 * - Afficher la barre de navigation
 * - Adapter la navigation au rôle de l'utilisateur
 */

// Démarre la session uniquement si elle n'est pas déjà active.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <!-- Encodage des caractères -->
    <meta charset="UTF-8">

    <!-- Adaptation aux écrans mobiles -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Titre de l'application -->
    <title>Vite & Gourmand</title>

    <!-- Feuilles de style locales -->
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">

    <!-- Icône du site -->
    <link rel="icon" type="image/x-icon" href="/assets/favicon.ico">

    <!-- Animations et effets visuels -->
    <style>

        /* Animation d'apparition au défilement */
        .animate-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        /* Rend l'élément visible après activation */
        .animate-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Transition des cartes de menu */
        .menu-card {
            transition: transform 0.3s ease,
                        box-shadow 0.3s ease !important;
            overflow: hidden;
        }

        /* Effet au survol des cartes */
        .menu-card:hover {
            transform: translateY(-8px) scale(1.02) !important;
            box-shadow: 0 12px 30px rgba(93, 169, 154, 0.3) !important;
        }

        /* Animation des images */
        .menu-card img {
            transition: transform 0.4s ease !important;
        }

        /* Agrandissement des images au survol */
        .menu-card:hover img {
            transform: scale(1.08) !important;
        }

        /* Transition des boutons */
        .btn {
            transition: transform 0.15s ease,
                        box-shadow 0.15s ease !important;
        }

        /* Effet au survol des boutons */
        .btn:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }

        /* Transition des liens de navigation */
        .nav-link {
            transition: color 0.2s ease !important;
        }

        /* Animation des ombres des cartes */
        .card {
            transition: box-shadow 0.3s ease !important;
        }

        /*
         * Notification temporaire :
         * - Centrée horizontalement
         * - Positionnée au niveau de la navbar
         * - Toujours visible au-dessus du contenu
         */
        .flash-toast-container {
            position: fixed;
            top: 8px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;

            width: max-content;
            max-width: calc(100vw - 20px);
        }

        /* Permet à la notification de s'adapter au mobile */
        .flash-toast-container .toast {
            max-width: 100%;
        }

    </style>
</head>

<body>

<?php
/**
 * Récupération du message temporaire.
 *
 * Le message est enregistré en session par les contrôleurs,
 * puis supprimé après sa récupération afin de ne l'afficher
 * qu'une seule fois.
 */
if (isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}
?>

<?php if (isset($flash)) : ?>

    <!-- ========================================= -->
    <!-- NOTIFICATION TEMPORAIRE / MESSAGE FLASH  -->
    <!-- ========================================= -->

    <!-- Conteneur centré au niveau de la navbar -->
    <div class="flash-toast-container">

        <!--
            Notification Bootstrap.
            La couleur dépend du type de message :
            - success : vert
            - autres types : rouge
        -->
        <div
            class="toast show align-items-center text-white border-0
                <?= $flash['type'] === 'success' ? 'bg-success' : 'bg-danger' ?>"
            role="alert"
            aria-live="polite"
            aria-atomic="true"
            id="flashToast"
        >

            <div class="d-flex align-items-center">

                <!-- Message échappé pour éviter l'injection HTML -->
                <div class="toast-body">
                    <?= htmlspecialchars(
                        (string) $flash['message'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </div>

                <!-- Bouton permettant de fermer la notification -->
                <button
                    type="button"
                    class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast"
                    aria-label="Fermer"
                ></button>

            </div>

        </div>

    </div>

    <!--
        Script chargé de masquer automatiquement
        la notification après un délai.
    -->
    <script src="/assets/js/flash-toast.js" defer></script>

<?php endif; ?>


<!-- ========================================= -->
<!-- BARRE DE NAVIGATION PRINCIPALE            -->
<!-- ========================================= -->

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">

    <div class="container">

        <!-- Logo de l'application -->
        <a
            class="navbar-brand fw-bold"
            href="/"
            style="color: #2E6B5E;"
        >
            Vite & Gourmand
        </a>

        <!-- Bouton du menu mobile -->
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Ouvrir le menu de navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Conteneur des liens de navigation -->
        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto align-items-center">

                <!-- ========================================= -->
                <!-- LIENS PUBLICS                             -->
                <!-- ========================================= -->

                <!-- Consultation des menus -->
                <li class="nav-item">
                    <a class="nav-link" href="/menus">
                        Nos Menus
                    </a>
                </li>

                <!-- Formulaire de contact -->
                <li class="nav-item">
                    <a class="nav-link" href="/contact">
                        Contact
                    </a>
                </li>


                <!-- ========================================= -->
                <!-- UTILISATEUR CONNECTÉ                     -->
                <!-- ========================================= -->

                <?php if (isset($_SESSION['user_id'])) : ?>

                    <?php
                    // Récupère le rôle de l'utilisateur connecté.
                    $userRole = $_SESSION['user_role'] ?? '';
                    ?>

                    <!-- ========================================= -->
                    <!-- NAVIGATION EMPLOYÉ                       -->
                    <!-- ========================================= -->

                    <?php if ($userRole === 'employe') : ?>

                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="/employe/dashboard"
                            >
                                Espace employé
                            </a>
                        </li>


                    <!-- ========================================= -->
                    <!-- NAVIGATION ADMINISTRATEUR                -->
                    <!-- ========================================= -->

                    <?php elseif ($userRole === 'admin') : ?>

                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="/admin/dashboard"
                            >
                                Espace admin
                            </a>
                        </li>


                    <!-- ========================================= -->
                    <!-- NAVIGATION CLIENT                        -->
                    <!-- ========================================= -->

                    <?php else : ?>

                        <!-- Historique des commandes -->
                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="/commandes/historique"
                            >
                                Mes commandes
                            </a>
                        </li>

                        <!-- Profil du client -->
                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="/utilisateurs/profil"
                            >
                                Mon profil
                            </a>
                        </li>

                    <?php endif; ?>


                    <!-- ========================================= -->
                    <!-- DÉCONNEXION                              -->
                    <!-- ========================================= -->

                    <li class="nav-item">
                        <a
                            class="btn btn-outline-secondary btn-sm ms-2"
                            href="/auth/deconnexion"
                        >
                            Déconnexion
                        </a>
                    </li>


                <!-- ========================================= -->
                <!-- VISITEUR NON CONNECTÉ                     -->
                <!-- ========================================= -->

                <?php else : ?>

                    <!-- Bouton de connexion -->
                    <li class="nav-item">
                        <a
                            class="btn btn-outline-secondary btn-sm ms-2"
                            href="/auth/connexion"
                        >
                            Connexion
                        </a>
                    </li>

                <?php endif; ?>

            </ul>

        </div>

    </div>

</nav>


<!-- ========================================= -->
<!-- DÉBUT DU CONTENU PRINCIPAL                -->
<!-- ========================================= -->

<main>
