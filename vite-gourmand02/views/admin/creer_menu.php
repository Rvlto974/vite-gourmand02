<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card p-4 shadow-sm" style="border-radius:12px;">
                <h1 class="h3 mb-4" style="color:#5DA99A">➕ Créer un menu</h1>

                <form method="POST" action="/admin/creerMenu">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Titre</label>
                        <input type="text" name="titre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Thème</label>
                            <select name="theme" class="form-select" required>
                                <option value="classique">Classique</option>
                                <option value="noel">Noël</option>
                                <option value="paques">Pâques</option>
                                <option value="evenement">Événement</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Régime</label>
                            <select name="regime" class="form-select" required>
                                <option value="classique">Classique</option>
                                <option value="vegetarien">Végétarien</option>
                                <option value="vegan">Vegan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Prix de base (€)</label>
                            <input type="number" name="prix_base" class="form-control"
                                    step="0.01" min="0" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Personnes minimum</label>
                            <input type="number" name="nb_personnes_min" class="form-control"
                                    min="1" value="10" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Stock disponible</label>
                            <input type="number" name="stock" class="form-control"
                                    min="0" value="10" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">URL image</label>
                        <input type="text" name="image" class="form-control"
                                placeholder="https://... (optionnel)">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn py-2 px-4"
                                style="background-color:#5DA99A; color:white; border-radius:8px;">
                            ✅ Créer le menu
                        </button>
                        <a href="/admin/menus" class="btn btn-outline-secondary py-2 px-4">
                            ← Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>