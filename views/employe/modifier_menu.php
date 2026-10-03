<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card p-4 shadow-sm" style="border-radius:12px;">
                <h1 class="h3 mb-4" style="color:#5DA99A">✏️ Modifier le menu</h1>

                <form method="POST" action="/employe/modifierMenu?id=<?= $menu['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Titre</label>
                        <input type="text" name="titre" class="form-control"
                               value="<?= htmlspecialchars($menu['titre']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($menu['description']) ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Thème</label>
                            <select name="theme" class="form-select" required>
                                <option value="noel" <?= $menu['theme'] === 'noel' ? 'selected' : '' ?>>Noël</option>
                                <option value="paques" <?= $menu['theme'] === 'paques' ? 'selected' : '' ?>>Pâques</option>
                                <option value="classique" <?= $menu['theme'] === 'classique' ? 'selected' : '' ?>>Classique</option>
                                <option value="evenement" <?= $menu['theme'] === 'evenement' ? 'selected' : '' ?>>Événement</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Régime</label>
                            <select name="regime" class="form-select" required>
                                <option value="classique" <?= $menu['regime'] === 'classique' ? 'selected' : '' ?>>Classique</option>
                                <option value="vegetarien" <?= $menu['regime'] === 'vegetarien' ? 'selected' : '' ?>>Végétarien</option>
                                <option value="vegan" <?= $menu['regime'] === 'vegan' ? 'selected' : '' ?>>Vegan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Prix de base (€)</label>
                            <input type="number" name="prix_base" class="form-control" step="0.01"
                                   value="<?= $menu['prix_base'] ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Personnes minimum</label>
                            <input type="number" name="nb_personnes_min" class="form-control"
                                   value="<?= $menu['nb_personnes_min'] ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Stock disponible</label>
                            <input type="number" name="stock" class="form-control"
                                   value="<?= $menu['stock'] ?>" required>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn py-2 px-4"
                                style="background-color:#5DA99A; color:white; border-radius:8px;">
                            💾 Enregistrer
                        </button>
                        <a href="/employe/menus" class="btn btn-outline-secondary py-2 px-4">
                            ← Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>