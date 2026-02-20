<?php require_once 'views/layouts/header.php'; ?>

<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="mb-4">Créer un compte</h1>

            <?php if (isset($erreur)) : ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($erreur) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/auth/inscription">
                <div class="mb-3">
                    <label for="nom">Nom</label>
                    <input type="text" 
                            class="form-control" 
                            id="nom" 
                            name="nom" 
                            required>
                </div>
                <div class="mb-3">
                    <label for="prenom">Prénom</label>
                    <input type="text" 
                            class="form-control" 
                            id="prenom" 
                            name="prenom" 
                            required>
                </div>
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
                    <small class="text-muted">
                        10 caractères min, 1 majuscule, 1 minuscule, 
                        1 chiffre, 1 caractère spécial
                    </small>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    Créer mon compte
                </button>
                <p class="mt-3 text-center">
                    Déjà un compte ? 
                    <a href="/auth/connexion">Se connecter</a>
                </p>
            </form>
        </div>
    </div>
</main>

<?php require_once 'views/layouts/footer.php'; ?>