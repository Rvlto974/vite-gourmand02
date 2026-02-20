<?php require_once 'views/layouts/header.php'; ?>

<main>
    <!-- Hero -->
    <section class="hero py-5" style="background-color: #f5f5f5;">
        <div class="container text-center">
            <h1>Vite & Gourmand</h1>
            <p class="lead">Traiteur d'exception depuis 25 ans à Bordeaux</p>
            <a href="/menus" class="btn btn-primary btn-lg">
                Découvrir nos menus
            </a>
        </div>
    </section>

    <!-- Pourquoi nous choisir -->
    <section class="container my-5">
        <h2 class="text-center mb-4">Pourquoi nous choisir</h2>
        <div class="row text-center">
            <div class="col-md-4">
                <h5>25 ans d'expérience</h5>
                <p>Une expertise reconnue dans l'art culinaire</p>
            </div>
            <div class="col-md-4">
                <h5>Produits locaux</h5>
                <p>Ingrédients frais issus de producteurs locaux</p>
            </div>
            <div class="col-md-4">
                <h5>Livraison Bordeaux</h5>
                <p>Service de livraison dans Bordeaux et environs</p>
            </div>
        </div>
    </section>

    <!-- Avis clients -->
    <section class="container my-5">
        <h2 class="text-center mb-4">Avis clients</h2>
        <div class="row">
            <?php foreach ($avis as $unAvis) : ?>
            <div class="col-md-6 mb-4">
                <div class="card p-3">
                    <p><?= htmlspecialchars($unAvis['commentaire']) ?></p>
                    <p class="fw-bold">
                        <?= htmlspecialchars($unAvis['prenom']) ?> 
                        <?= htmlspecialchars($unAvis['nom']) ?>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php require_once 'views/layouts/footer.php'; ?>