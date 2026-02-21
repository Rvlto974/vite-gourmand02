<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card p-5">
                <h1>🎉 Commande confirmée !</h1>
                <p class="lead">Votre commande a bien été enregistrée.</p>

                <?php if ($commande) : ?>
                <div class="card bg-light p-3 mt-3 text-start">
                    <p><strong>Menu :</strong> <?= htmlspecialchars($commande['menu_titre']) ?></p>
                    <p><strong>Personnes :</strong> <?= $commande['nb_personnes'] ?></p>
                    <p><strong>Prix total :</strong> <?= $commande['prix_total'] ?> €</p>
                    <p><strong>Adresse :</strong> <?= htmlspecialchars($commande['adresse_livraison']) ?></p>
                    <p><strong>Date :</strong> <?= $commande['date_prestation'] ?></p>
                    <p><strong>Statut :</strong> <span class="badge bg-warning">Nouvelle</span></p>
                </div>
                <?php endif; ?>

                <div class="mt-4">
                    <a href="/menus" class="btn btn-primary">Voir d'autres menus</a>
                    <a href="/" class="btn btn-outline-secondary ms-2">Retour à l'accueil</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>