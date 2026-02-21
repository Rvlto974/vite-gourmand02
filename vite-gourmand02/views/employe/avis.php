<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card p-3">
                <h5>Espace employé</h5>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="/employe/dashboard">Tableau de bord</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/employe/commandes">Commandes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/employe/avis">Avis clients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/auth/deconnexion">Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Contenu -->
        <div class="col-md-9">
            <h1>Gestion des avis</h1>

            <?php if (empty($avis)) : ?>
                <p>Aucun avis en attente de validation.</p>
            <?php else : ?>
                <?php foreach ($avis as $unAvis) : ?>
                <div class="card mb-3 p-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-bold">
                                <?= htmlspecialchars($unAvis['prenom'] . ' ' . $unAvis['nom']) ?>
                            </p>
                            <p><?= htmlspecialchars($unAvis['commentaire']) ?></p>
                            <p>Note : <?= $unAvis['note'] ?>/5</p>
                        </div>
                        <div>
                            <form method="POST" action="/employe/validerAvis" class="d-inline">
                                <input type="hidden" name="id" value="<?= $unAvis['id'] ?>">
                                <button type="submit" class="btn btn-success btn-sm">
                                    Valider
                                </button>
                            </form>
                            <form method="POST" action="/employe/refuserAvis" class="d-inline">
                                <input type="hidden" name="id" value="<?= $unAvis['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Refuser
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>