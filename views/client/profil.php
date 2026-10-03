<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="mb-4" style="color:#5DA99A">👤 Mon profil</h1>

            <?php if (isset($succes)) : ?>
                <div class="alert alert-success"><?= $succes ?></div>
            <?php endif; ?>

            <div class="card p-4">
                <form method="POST" action="/client/profil">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" class="form-control" name="nom" 
                                   value="<?= htmlspecialchars($utilisateur['nom']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prénom</label>
                            <input type="text" class="form-control" name="prenom" 
                                   value="<?= htmlspecialchars($utilisateur['prenom']) ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" 
                               value="<?= htmlspecialchars($utilisateur['email']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Téléphone</label>
                        <input type="text" class="form-control" name="gsm" 
                               value="<?= htmlspecialchars($utilisateur['gsm'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Adresse</label>
                        <textarea class="form-control" name="adresse" rows="2"><?= htmlspecialchars($utilisateur['adresse'] ?? '') ?></textarea>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label">Nouveau mot de passe <small class="text-muted">(laisser vide pour ne pas changer)</small></label>
                        <input type="password" class="form-control" name="mot_de_passe">
                    </div>
                    <button type="submit" class="btn w-100" style="background-color:#5DA99A; color:white;">
                        Sauvegarder
                    </button>
                </form>
            </div>

            <div class="mt-3">
                <a href="/commandes/historique" class="btn btn-outline-secondary">← Mes commandes</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>