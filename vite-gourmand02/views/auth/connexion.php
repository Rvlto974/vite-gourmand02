<?php require_once 'views/layouts/header.php'; ?>

<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="mb-4">Connexion</h1>

            <?php if (isset($erreur)) : ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($erreur) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/auth/connexion">
                <div class="mb-3">
                    <label for="email">Email</label>
                    <input type="email" 
                        class="form-control" 
                        id="email" 
                        name="email" 
                        required>
                </div>
                <div class="mb-3">
                    <label for="mot_de_passe">Mot de passe</label>
                    <input type="password" 
                        class="form-control" 
                        id="mot_de_passe" 
                        name="mot_de_passe" 
                        required>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    Se connecter
                </button>
                <p class="mt-3 text-center">
                    Pas encore de compte ? 
                    <a href="/auth/inscription">S'inscrire</a>
                </p>
            </form>
        </div>
    </div>
</main>

<?php require_once 'views/layouts/footer.php'; ?>