<?php require_once 'views/layouts/header.php'; ?>

<main>
    <!-- Hero -->
    <section class="hero">
        <div class="container text-center py-5">
            <h1>Vite & Gourmand</h1>
            <p>Traiteur d'exception depuis 25 ans à Bordeaux</p>
            <a href="/menus" class="btn btn-primary">
                Découvrir nos menus
            </a>
        </div>
    </section>

    <!-- Présentation entreprise -->
    <section class="container my-5">
        <h2 class="text-center">Pourquoi nous choisir</h2>
        <div class="row mt-4">
            <div class="col-md-4 text-center">
                <h5>25 ans d'expérience</h5>
                <p>Une expertise reconnue dans l'art culinaire</p>
            </div>
            <div class="col-md-4 text-center">
                <h5>Produits locaux</h5>
                <p>Ingrédients frais issus de producteurs locaux</p>
            </div>
            <div class="col-md-4 text-center">
                <h5>Livraison Bordeaux</h5>
                <p>Service de livraison dans Bordeaux et environs</p>
            </div>
        </div>
    </section>

    <!-- Avis clients validés -->
    <section class="container my-5">
        <h2 class="text-center">Avis clients</h2>
        <div class="row mt-4">
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