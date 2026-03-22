PS C:\env\workspace\vite-gourmand02> type views\employe\avis.php     
<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3">
                <h5>Espace employé</h5>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="/employe/dashboard">Tableau de bord</a></li>
                    <li class="nav-item"><a class="nav-link" href="/employe/commandes">Commandes</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/employe/avis">Avis clients</a></li>
                    <li class="nav-item"><a class="nav-link" href="/auth/deconnexion">Déconnexion</a></li>
                </ul>
            </div>
        </div>

        <div class="col-md-9">
            <h1>Gestion des avis</h1>

            <?php if (empty($avis)) : ?>
                <div class="alert alert-info mt-3">Aucun avis en attente de validation.</div>
            <?php else : ?>
                <?php foreach ($avis as $unAvis) : ?>
                <div class="card mb-3 p-3 shadow-sm" style="border-radius:10px;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="fw-bold mb-1">
                                <?= htmlspecialchars($unAvis['prenom'] . ' ' . $unAvis['nom']) ?>
                                <small class="text-muted fw-normal ms-2"><?= date('d/m/Y', strtotime($unAvis['created_at'])) ?></small>
                            </p>
                            <p class="mb-1" style="color:#f39c12; font-size:1.2rem;">
                                <?= str_repeat('★', $unAvis['note']) ?><?= str_repeat('☆', 5 - $unAvis['note']) ?>
                                <small class="text-muted" style="font-size:0.85rem;"><?= $unAvis['note'] ?>/5</small>
                            </p>
                            <p class="mb-0"><?= htmlspecialchars($unAvis['commentaire']) ?></p>
                        </div>
                        <div class="d-flex gap-2">
                            <form method="POST" action="/employe/validerAvis">
                                <input type="hidden" name="id" value="<?= $unAvis['id'] ?>">
                                <button type="submit" class="btn btn-success btn-sm">✅ Valider</button>
                            </form>
                            <form method="POST" action="/employe/refuserAvis">
                                <input type="hidden" name="id" value="<?= $unAvis['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">❌ Refuser</button>
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
PS C:\env\workspace\vite-gourmand02> 