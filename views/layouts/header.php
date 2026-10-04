<?php
// Démarre la session uniquement si elle n’est pas déjà active.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Définit l’encodage et l’affichage adapté aux appareils mobiles. -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Titre affiché dans l’onglet du navigateur. -->
    <title>Vite & Gourmand</title>

    <!-- Charge les feuilles de style locales. -->
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">

    <!-- Icône affichée dans l’onglet du navigateur. -->
    <link rel="icon" type="image/x-icon" href="/assets/favicon.ico">

    <!-- Styles pour les animations et les interactions visuelles. -->
    <style>
    .animate-scroll {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }

    /* Rend visibles les éléments lorsque la classe est ajoutée. */
    .animate-scroll.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* Ajoute une transition aux cartes de menu. */
    .menu-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease !important;
        overflow: hidden;
    }

    /* Anime la carte lorsqu’on la survole. */
    .menu-card:hover {
        transform: translateY(-8px) scale(1.02) !important;
        box-shadow: 0 12px 30px rgba(93,169,154,0.3) !important;
    }

    /* Anime l’image d’une carte de menu au survol. */
    .menu-card img {
        transition: transform 0.4s ease !important;
    }

    .menu-card:hover img {
        transform: scale(1.08) !important;
    }

    /* Ajoute une légère animation aux boutons. */
    .btn {
        transition: transform 0.15s ease, box-shadow 0.15s ease !important;
    }

    .btn:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
    }

    /* Anime la couleur des liens de navigation. */
    .nav-link {
        transition: color 0.2s ease !important;
    }

    /* Anime l’ombre des cartes. */
    .card {
        transition: box-shadow 0.3s ease !important;
    }
    </style>
</head>
<body>

<?php
// Récupère le message temporaire stocké en session, puis le supprime.
if (isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}
?>

<?php if (isset($flash)) : ?>
<!-- Affiche le message temporaire de succès ou d’erreur. -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div class="toast show align-items-center text-white border-0
        <?= $flash['type'] === 'success' ? 'bg-success' : 'bg-danger' ?>"
        role="alert" id="flashToast">
        <div class="d-flex">
            <!-- Affiche le contenu du message en échappant les caractères HTML. -->
            <div class="toast-body"><?= htmlspecialchars($flash['message']) ?></div>

            <!-- Bouton permettant de fermer le message. -->
            <button
                type="button"
                class="btn-close btn-close-white me-2 m-auto"
                data-bs-dismiss="toast"
                aria-label="Fermer">
            </button>
        </div>
    </div>
</div>

<!-- Charge le script qui masque automatiquement le message flash. -->
<script src="/assets/js/flash-toast.js" defer></script>
<?php endif; ?>

<!-- Barre de navigation principale du site. -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <!-- Logo et lien vers la page d’accueil. -->
        <a class="navbar-brand fw-bold" href="/" style="color:#2E6B5E;">
            Vite & Gourmand
        </a>

        <!-- Bouton d’ouverture du menu sur les petits écrans. -->
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Ouvrir le menu de navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Liens de navigation, repliés sur les petits écrans. -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="/menus">Nos Menus</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/contact">Contact</a>
                </li>

                <?php if (isset($_SESSION['user_id'])) : ?>
                    <!-- Affiche les liens correspondant au rôle de l’utilisateur connecté. -->
                    <?php if ($_SESSION['user_role'] === 'employe') : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/employe/dashboard">
                                Espace employé
                            </a>
                        </li>
                    <?php elseif ($_SESSION['user_role'] === 'admin') : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/dashboard">
                                Espace admin
                            </a>
                        </li>
                    <?php else : ?>
                        <!-- Liens réservés aux clients connectés. -->
                        <li class="nav-item">
                            <a class="nav-link" href="/commandes/historique">
                                Mes commandes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/utilisateurs/profil">
                                Mon profil
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Lien de déconnexion affiché aux utilisateurs connectés. -->
                    <li class="nav-item">
                        <a class="btn btn-outline-secondary btn-sm ms-2" href="/auth/deconnexion">
                            Déconnexion
                        </a>
                    </li>
                <?php else : ?>
                    <!-- Lien de connexion affiché aux visiteurs non connectés. -->
                    <li class="nav-item">
                        <a class="btn btn-outline-secondary btn-sm ms-2" href="/auth/connexion">
                            Connexion
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Début du contenu principal de chaque page. -->
<main>