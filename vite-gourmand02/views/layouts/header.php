<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite & Gourmand</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
    .btn-connexion {
        border: 2px solid #5DA99A !important;
        color: #5DA99A !important;
        border-radius: 50px !important;
        background: white !important;
    }
    .btn-connexion:hover {
        background: #5DA99A !important;
        color: white !important;
    }
    .btn-inscription,
    .btn-inscription:link,
    .btn-inscription:visited {
        background-color: #5DA99A !important;
        color: white !important;
        border-radius: 50px !important;
        border: none !important;
    }
    .btn-inscription:hover {
        background-color: #3D7A6E !important;
        color: white !important;
    }
    </style>
</head>
<body>

    <a href="#contenu-principal" class="visually-hidden-focusable">
        Aller au contenu principal
    </a>

    <nav class="navbar navbar-expand-lg py-3" 
        role="navigation" 
        aria-label="Menu principal">
        <div class="container">
            <a class="navbar-brand" 
                href="/" 
                aria-label="Vite et Gourmand - Retour à l'accueil">
                Vite & Gourmand
            </a>
            
            <button class="navbar-toggler" 
                    type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#menu"
                    aria-controls="menu"
                    aria-expanded="false" 
                    aria-label="Ouvrir le menu de navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse align-items-center" id="menu">
                <ul class="navbar-nav ms-auto align-items-center" role="list">
                    <li role="listitem">
                        <a class="nav-link" href="/">Accueil</a>
                    </li>
                    <li role="listitem">
                        <a class="nav-link" href="/menus">Nos Menus</a>
                    </li>
                    <li role="listitem">
                        <a class="nav-link" href="/contact">Contact</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])) : ?>
                    <li role="listitem">
                        <a class="nav-link" href="/commandes/historique">Mes commandes</a>
                    </li>
                    <li role="listitem">
                        <a class="nav-link" href="/client/profil">Mon profil</a>
                    </li>
                    <?php endif; ?>
                </ul>

                <div class="d-flex flex-column flex-lg-row align-items-center gap-2 mt-2 mt-lg-0 ms-lg-2">
                    <?php if (isset($_SESSION['user_id'])) : ?>
                        <a href="/auth/deconnexion" 
                            class="btn btn-connexion"
                            aria-label="Se déconnecter">
                            Déconnexion
                        </a>
                    <?php else : ?>
                        <a href="/auth/connexion" 
                            class="btn btn-connexion"
                            aria-label="Se connecter">
                            Connexion
                        </a>
                        <a href="/auth/inscription" 
                            class="btn btn-inscription"
                            aria-label="Créer un compte">
                            S'inscrire
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main id="contenu-principal" role="main">