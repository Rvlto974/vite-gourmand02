<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4 shadow-sm" style="border-radius:12px;">
                <h1 class="h3 mb-4" style="color:#5DA99A">👤 Mon profil</h1>

                <form method="POST" action="/utilisateurs/profil">
                    <div class="mb-3">
                        <label for="nom" class="form-label fw-semibold">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom"
                               value="<?= htmlspecialchars($utilisateur['nom']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="prenom" class="form-label fw-semibold">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom"
                               value="<?= htmlspecialchars($utilisateur['prenom']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" id="email"
                               value="<?= htmlspecialchars($utilisateur['email']) ?>" disabled>
                        <small class="text-muted">L'email ne peut pas être modifié.</small>
                    </div>

                    <div class="mb-3">
                        <label for="gsm" class="form-label fw-semibold">GSM</label>
                        <input type="tel" class="form-control" id="gsm" name="gsm"
                               value="<?= htmlspecialchars($utilisateur['gsm'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="adresse" class="form-label fw-semibold">Adresse postale</label>
                        <textarea class="form-control" id="adresse" name="adresse" rows="3"><?= htmlspecialchars($utilisateur['adresse'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn w-100 py-2"
                            style="background-color:#5DA99A; color:white; border-radius:8px;">
                        💾 Enregistrer les modifications
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>