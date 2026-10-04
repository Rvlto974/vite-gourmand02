<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card p-4 shadow-sm" style="border-radius:12px;">
                <h1 class="h3 mb-4" style="color:#5DA99A">➕ Créer un menu</h1>

                <form method="POST" action="/admin/creerMenu">

                    <!-- Infos de base -->
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

                    <hr class="my-4">

                    <!-- Galerie d'images -->
                    <h5 style="color:#5DA99A">🖼️ Galerie d'images (URLs)</h5>
                    <div id="images-container">
                        <div class="input-group mb-2 image-row">
                            <input type="url" name="images[0]" class="form-control"
                                   placeholder="https://exemple.com/image.jpg">
                            <button type="button" class="btn btn-outline-danger btn-supprimer-image">✕</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-secondary btn-sm mb-4" id="btn-ajouter-image">
                        + Ajouter une image
                    </button>

                    <hr class="my-4">

                    <!-- Liste des plats -->
                    <h5 style="color:#5DA99A">🍽️ Plats (Entrées / Plats / Desserts)</h5>
                    <div id="plats-container"></div>
                    <button type="button" class="btn btn-outline-secondary btn-sm mb-4" id="btn-ajouter-plat">
                        + Ajouter un plat
                    </button>

                    <hr class="my-4">

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

<script src="/assets/js/admin-creer-menu.js" defer></script>

<?php require_once 'views/layouts/footer.php'; ?>