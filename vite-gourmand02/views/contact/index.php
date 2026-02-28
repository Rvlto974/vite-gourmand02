<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row">
        <!-- Formulaire -->
        <div class="col-md-7">
            <h1>Nous contacter</h1>
            <p>Une question sur nos menus ? N'hésitez pas à nous contacter.</p>

            <?php if ($success) : ?>
                <div class="alert alert-success" role="alert" aria-live="polite">
                    Votre message a bien été envoyé ! Nous vous répondrons rapidement.
                </div>
            <?php endif; ?>

            <?php if (!empty($erreur)) : ?>
                <div class="alert alert-danger" role="alert" aria-live="polite">
                    <?= htmlspecialchars($erreur) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/contact" aria-label="Formulaire de contact" novalidate>
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom <span aria-hidden="true">*</span></label>
                    <input type="text" class="form-control" id="nom" name="nom" 
                            aria-required="true" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email <span aria-hidden="true">*</span></label>
                    <input type="email" class="form-control" id="email" name="email"
                            aria-required="true" required>
                </div>

                <div class="mb-3">
                    <label for="sujet" class="form-label">Sujet <span aria-hidden="true">*</span></label>
                    <select class="form-select" id="sujet" name="sujet" aria-required="true" required>
                        <option value="">Choisissez un sujet...</option>
                        <option value="devis">Demande de devis</option>
                        <option value="commande">Question sur une commande</option>
                        <option value="menu">Question sur un menu</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label">Message <span aria-hidden="true">*</span></label>
                    <textarea class="form-control" id="message" name="message" 
                                rows="5" aria-required="true" required
                                placeholder="Décrivez votre demande..."></textarea>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="rgpd" name="rgpd" required>
                    <label class="form-check-label" for="rgpd">
                        J'accepte que mes données soient utilisées pour traiter ma demande.
                        <a href="/confidentialite">En savoir plus</a>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" aria-label="Envoyer le message">
                    Envoyer le message
                </button>
            </form>
        </div>

        <!-- Coordonnées -->
        <div class="col-md-4 offset-md-1">
            <div class="card p-4 mb-3">
                <h5>Nos coordonnées</h5>
                <p><strong>Adresse</strong><br>123 Rue de la Gastronomie<br>33000 Bordeaux</p>
                <p><strong>Téléphone</strong><br>05 56 78 90 12</p>
                <p><strong>Email</strong><br>contact@viteetgourmand.fr</p>
            </div>

            <div class="card p-4">
                <h5>Horaires</h5>
                <p>Lundi - Vendredi : 9h - 18h</p>
                <p>Samedi : 9h - 12h</p>
                <p>Dimanche : Fermé</p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>