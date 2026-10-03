<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="mb-4" style="color:#5DA99A">🔑 Mot de passe oublié</h1>

            <?php if (isset($succes)) : ?>
                <div class="alert alert-success"><?= $succes ?></div>
            <?php endif; ?>

            <div class="card p-4">
                <p class="text-muted">Entrez votre email pour recevoir un lien de réinitialisation.</p>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <button type="submit" class="btn w-100" style="background-color:#5DA99A; color:white;">
                        Envoyer le lien
                    </button>
                </form>
            </div>

            <div class="mt-3 text-center">
                <a href="/auth/connexion">← Retour à la connexion</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>