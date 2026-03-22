<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center text-center">
        <div class="col-md-6">
            <div class="card p-5 shadow-sm" style="border-radius:16px;">
                <div style="font-size:5rem;">🍽️</div>
                <h1 class="mt-3" style="font-size:4rem; font-weight:900; color:#5DA99A;">404</h1>
                <h2 class="h4 mb-3" style="color:#2E6B5E;">Page introuvable</h2>
                <p class="text-muted mb-4">
                    Oups ! Cette page semble avoir disparu comme un plat trop délicieux.
                </p>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="/" class="btn py-2 px-4"
                        style="background-color:#5DA99A; color:white; border-radius:8px;">
                        🏠 Retour à l'accueil
                    </a>
                    <a href="/menus" class="btn btn-outline-secondary py-2 px-4"
                        style="border-radius:8px;">
                        🍴 Voir nos menus
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>