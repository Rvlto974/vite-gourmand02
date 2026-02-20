<?php require_once 'views/layouts/header.php'; ?>

<main class="container mt-4">
    <h1>Nos Menus</h1>

    <div class="row">
        <?php foreach ($menus as $menu) : ?>
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <?= htmlspecialchars($menu['titre']) ?>
                    </h5>
                    <p class="card-text">
                        <?= htmlspecialchars($menu['description']) ?>
                    </p>
                    <p class="fw-bold">
                        <?= $menu['prix_base'] ?> €
                    </p>
                    <a href="/menus/detail?id=<?= $menu['id'] ?>" 
                        class="btn btn-primary">
                        Voir le menu
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</main>

<?php require_once 'views/layouts/footer.php'; ?>