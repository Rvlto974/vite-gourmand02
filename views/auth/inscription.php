<?php
// Charge l’en-tête commun du site.
require_once 'views/layouts/header.php';
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- Titre du formulaire d’inscription. -->
            <h1 class="mb-4">Créer un compte</h1>

            <!-- Affiche le message d’erreur transmis par le contrôleur, s’il existe. -->
            <?php if (isset($erreur)) : ?>
                <div class="alert alert-danger" role="alert" aria-live="polite">
                    <?= htmlspecialchars($erreur) ?>
                </div>
            <?php endif; ?>

            <!-- Formulaire envoyé au contrôleur d’inscription. -->
            <form method="POST" action="/auth/inscription" novalidate aria-label="Formulaire d'inscription">

                <!-- Champ pour le nom de famille. -->
                <div class="mb-3">
                    <label for="nom" class="form-label">
                        Nom <span aria-hidden="true">*</span>
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="nom"
                        name="nom"
                        autocomplete="family-name"
                        aria-required="true"
                        required
                    >
                </div>

                <!-- Champ pour le prénom. -->
                <div class="mb-3">
                    <label for="prenom" class="form-label">
                        Prénom <span aria-hidden="true">*</span>
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="prenom"
                        name="prenom"
                        autocomplete="given-name"
                        aria-required="true"
                        required
                    >
                </div>

                <!-- Champ pour l’adresse e-mail. -->
                <div class="mb-3">
                    <label for="email" class="form-label">
                        Email <span aria-hidden="true">*</span>
                    </label>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        autocomplete="email"
                        aria-required="true"
                        required
                    >
                </div>

                <!-- Champ pour le numéro de téléphone. -->
                <div class="mb-3">
                    <label for="gsm" class="form-label">
                        Numéro de GSM <span aria-hidden="true">*</span>
                    </label>
                    <input
                        type="tel"
                        class="form-control"
                        id="gsm"
                        name="gsm"
                        autocomplete="tel"
                        aria-required="true"
                        placeholder="Ex : 06 12 34 56 78"
                        required
                    >
                </div>

                <!-- Champ pour l’adresse postale. -->
                <div class="mb-3">
                    <label for="adresse" class="form-label">
                        Adresse postale <span aria-hidden="true">*</span>
                    </label>
                    <textarea
                        class="form-control"
                        id="adresse"
                        name="adresse"
                        rows="3"
                        autocomplete="street-address"
                        aria-required="true"
                        placeholder="Numéro, rue, code postal, ville..."
                        required
                    ></textarea>
                </div>

                <!-- Champ pour le mot de passe, avec un bouton d’affichage/masquage. -->
                <div class="mb-3">
                    <label for="mot_de_passe" class="form-label">
                        Mot de passe <span aria-hidden="true">*</span>
                    </label>

                    <div class="input-group">
                        <input
                            type="password"
                            class="form-control"
                            id="mot_de_passe"
                            name="mot_de_passe"
                            autocomplete="new-password"
                            aria-required="true"
                            aria-describedby="regles-mdp"
                            required
                        >
                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            id="toggleMdp"
                            aria-label="Afficher le mot de passe"
                        >
                            👁️
                        </button>
                    </div>

                    <!-- Rappelle les critères attendus pour le mot de passe. -->
                    <small id="regles-mdp" class="text-muted">
                        10 caractères minimum, une majuscule, une minuscule, un chiffre et un caractère spécial.
                    </small>

                    <!-- Zone utilisée par JavaScript pour afficher un retour sur le mot de passe. -->
                    <div id="mdp-feedback"></div>
                </div>

                <!-- Bouton d’envoi du formulaire. -->
                <button type="submit" class="btn btn-primary w-100" aria-label="Créer mon compte">
                    Créer mon compte
                </button>

                <!-- Lien vers la page de connexion pour les personnes déjà inscrites. -->
                <p class="mt-3 text-center">
                    Déjà un compte ?
                    <a href="/auth/connexion" aria-label="Se connecter à mon compte existant">
                        Se connecter
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>

<!-- Charge le JavaScript de la page d’inscription après l’analyse du HTML. -->
<script src="/assets/js/inscription.js" defer></script>

<?php
// Charge le pied de page commun du site.
require_once 'views/layouts/footer.php';
?>