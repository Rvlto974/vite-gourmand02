<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="mb-4">Créer un compte</h1>

            <?php if (isset($erreur)) : ?>
                <div class="alert alert-danger" 
                    role="alert"
                    aria-live="polite">
                    <?= htmlspecialchars($erreur) ?>
                </div>
            <?php endif; ?>

            <form method="POST" 
                    action="/auth/inscription"
                    novalidate
                    aria-label="Formulaire d'inscription">

                <div class="mb-3">
                    <label for="nom" class="form-label">
                        Nom <span aria-hidden="true">*</span>
                    </label>
                    <input type="text" 
                            class="form-control" 
                            id="nom" 
                            name="nom"
                            autocomplete="family-name"
                            aria-required="true"
                            required>
                </div>

                <div class="mb-3">
                    <label for="prenom" class="form-label">
                        Prénom <span aria-hidden="true">*</span>
                    </label>
                    <input type="text" 
                            class="form-control" 
                            id="prenom" 
                            name="prenom"
                            autocomplete="given-name"
                            aria-required="true"
                            required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">
                        Email <span aria-hidden="true">*</span>
                    </label>
                    <input type="email" 
                            class="form-control" 
                            id="email" 
                            name="email"
                            autocomplete="email"
                            aria-required="true"
                            required>
                </div>

                <div class="mb-3">
                    <label for="mot_de_passe" class="form-label">
                        Mot de passe <span aria-hidden="true">*</span>
                    </label>
                    <input type="password" 
                            class="form-control" 
                            id="mot_de_passe" 
                            name="mot_de_passe"
                            autocomplete="new-password"
                            aria-required="true"
                            aria-describedby="regles-mdp"
                            required>
                    <small id="regles-mdp" class="text-muted">
                        10 caractères min, 1 majuscule, 1 minuscule, 
                        1 chiffre, 1 caractère spécial
                    </small>
                </div>

                <button type="submit" 
                        class="btn btn-primary w-100"
                        aria-label="Créer mon compte">
                    Créer mon compte
                </button>

                <p class="mt-3 text-center">
                    Déjà un compte ? 
                    <a href="/auth/connexion"
                        aria-label="Se connecter à mon compte existant">
                        Se connecter
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>