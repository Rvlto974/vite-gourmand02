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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
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

            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ms-auto" role="list">
                    <li role="listitem">
                        <a class="nav-link" href="/">Accueil</a>
                    </li>
                    <li role="listitem">
                        <a class="nav-link" href="/menus">Nos Menus</a>
                    </li>
                    <li role="listitem">
                        <a class="nav-link" href="/contact">Contact</a>
                    </li>
                </ul>

                <?php if (isset($_SESSION['user_id'])) : ?>
                    <a href="/auth/deconnexion" 
                        class="btn btn-connexion ms-2"
                        aria-label="Se déconnecter">
                        Déconnexion
                    </a>
                <?php else : ?>
                    <a href="/auth/connexion" 
                        class="btn btn-connexion ms-2"
                        aria-label="Se connecter">
                        Connexion
                    </a>
                    <a href="/auth/inscription" 
                        class="btn btn-inscription ms-2"
                        aria-label="Créer un compte">
                        S'inscrire
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main id="contenu-principal" role="main">
