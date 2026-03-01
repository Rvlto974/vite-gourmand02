<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="mb-4">Connexion</h1>

            <?php if (isset($erreur)) : ?>
                <div class="alert alert-danger" role="alert" aria-live="polite">
                    <?= htmlspecialchars($erreur) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/auth/connexion" novalidate aria-label="Formulaire de connexion">
                <div class="mb-3">
                    <label for="email" class="form-label">Email <span aria-hidden="true">*</span></label>
                    <input type="email" class="form-control" id="email" name="email"
                            autocomplete="email" aria-required="true" required>
                </div>

                <div class="mb-3">
                    <label for="mot_de_passe" class="form-label">Mot de passe <span aria-hidden="true">*</span></label>
                    <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe"
                            autocomplete="current-password" aria-required="true" required>
                </div>

                <button type="submit" class="btn btn-primary w-100" aria-label="Se connecter">
                    Se connecter
                </button>

                <p class="mt-3 text-center">
                    Pas encore de compte ? 
                    <a href="/auth/inscription">S'inscrire</a>
                    <p class="mt-2 text-center">
                        <a href="/motDePasse/oublie">Mot de passe oublié ?</a>
                    </p>
                </p>
            </form>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>