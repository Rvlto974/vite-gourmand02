<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="mb-4" style="color:#5DA99A">🔑 Nouveau mot de passe</h1>

            <?php if (isset($erreur)) : ?>
                <div class="alert alert-danger"><?= $erreur ?></div>
            <?php endif; ?>

            <?php if (!isset($erreur)) : ?>
            <div class="card p-4">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control" name="mot_de_passe" required>
                    </div>
                    <button type="submit" class="btn w-100" style="background-color:#5DA99A; color:white;">
                        Réinitialiser
                    </button>
                </form>
            </div>
            <?php endif; ?>

            <div class="mt-3 text-center">
                <a href="/auth/connexion">← Retour à la connexion</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>