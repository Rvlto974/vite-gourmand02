<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite & Gourmand</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <!-- Lien d'évitement pour lecteurs d'écran -->
    <a href="#contenu-principal" 
        class="visually-hidden-focusable">
        Aller au contenu principal
    </a>

    <!-- Navigation accessible -->
    <nav class="navbar navbar-expand-lg" 
        role="navigation" 
        aria-label="Menu principal">
        <div class="container">
            <a class="navbar-brand" 
                href="/" 
                aria-label="Vite et Gourmand - Retour à l'accueil">
                Vite & Gourmand
            </a>
            
            <!-- Bouton hamburger accessible -->
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
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main id="contenu-principal" role="main">