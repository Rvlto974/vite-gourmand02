<?php require_once 'views/layouts/header.php'; ?>

<style>
.badge-noel       { background-color: #e74c3c; color: white; }
.badge-paques     { background-color: #9b59b6; color: white; }
.badge-classique  { background-color: #7f8c8d; color: white; }
.badge-evenement  { background-color: #2980b9; color: white; }
.badge-saisonnier { background-color: #27ae60; color: white; }

.detail-image {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 12px;
}
.prix-color { color: #e67e22; font-weight: bold; font-size: 1.5rem; }
.btn-commander {
    background-color: #5DA99A;
    color: white;
    border: none;
    border-radius: 8px;
    width: 100%;
    padding: 12px;
    font-size: 1rem;
}
.btn-commander:hover { background-color: #3D7A6E; color: white; }
.section-description {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 24px;
    margin-top: 32px;
}
.section-description h4 {
    color: #5DA99A;
    font-weight: 700;
    margin-bottom: 12px;
}
</style>

<div class="container mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item"><a href="/menus">Nos Menus</a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($menu['titre']) ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Image -->
        <div class="col-md-6">
            <img src="<?= !empty($menu['image']) ? htmlspecialchars($menu['image']) : '/assets/images/menu-default.jpg' ?>" 
                 alt="Photo du <?= htmlspecialchars($menu['titre']) ?>"
                 class="detail-image">
        </div>

        <!-- Infos principales -->
        <div class="col-md-6">
            <h1 class="fw-bold"><?= htmlspecialchars($menu['titre']) ?></h1>

            <!-- Badges -->
            <?php
                $theme = strtolower($menu['theme'] ?? 'classique');
                $badgeClass = match($theme) {
                    'noel'       => 'badge-noel',
                    'paques'     => 'badge-paques',
                    'evenement'  => 'badge-evenement',
                    'saisonnier' => 'badge-saisonnier',
                    default      => 'badge-classique',
                };
            ?>
            <span class="badge <?= $badgeClass ?> me-1"><?= htmlspecialchars($menu['theme']) ?></span>
            

            <!-- Prix -->
            <p class="prix-color mt-3">A partir de <?= number_format($menu['prix_base'], 2) ?> EUR</p>
            <p class="text-muted">👥 Pour <?= $menu['nb_personnes_min'] ?> personnes minimum</p>

            <!-- Calculateur de personnes -->
            <div class="card p-3 mt-3" style="border-radius:12px; border: 1px solid #dee2e6;">
                <div class="row align-items-center">
                    <div class="col">
                        <label for="nb_personnes" class="form-label fw-semibold">
                            Nombre de personnes
                        </label>
                        <div class="input-group">
                            <button class="btn btn-outline-secondary" 
                                    type="button"
                                    id="btn-moins"
                                    aria-label="Diminuer le nombre de personnes">-</button>
                            <input type="number" 
                                    class="form-control text-center" 
                                    id="nb_personnes"
                                    value="<?= $menu['nb_personnes_min'] ?>"
                                    min="<?= $menu['nb_personnes_min'] ?>"
                                    aria-label="Nombre de personnes">
                            <button class="btn btn-outline-secondary" 
                                    type="button"
                                    id="btn-plus"
                                    aria-label="Augmenter le nombre de personnes">+</button>
                        </div>
                    </div>
                    <div class="col text-end">
                        <p class="mb-0 text-muted">Prix estimé</p>
                        <h3 id="prix-estime" class="prix-color"><?= number_format($menu['prix_base'], 2) ?> €</h3>
                    </div>
                </div>
                <a href="/commandes/nouveau?menu_id=<?= $menu['id'] ?>&nb_personnes=<?= $menu['nb_personnes_min'] ?>" 
                    class="btn btn-commander mt-3"
                    id="btn-commander"
                    aria-label="Commander le menu <?= htmlspecialchars($menu['titre']) ?>">
                    🛒 Commander ce menu
                </a>
            </div>
        </div>
    </div>

    <!-- Section Description -->
    <div class="section-description">
        <h4>📋 Description du menu</h4>
        <p style="text-align: left;"><?= nl2br(htmlspecialchars($menu['description'])) ?></p>
        
    </div>

</div>

<script>
const prixBase = <?= $menu['prix_base'] ?>;
const nbMin = <?= $menu['nb_personnes_min'] ?>;

document.getElementById('btn-plus').addEventListener('click', function() {
    const input = document.getElementById('nb_personnes');
    input.value = parseInt(input.value) + 1;
    calculerPrix();
});

document.getElementById('btn-moins').addEventListener('click', function() {
    const input = document.getElementById('nb_personnes');
    if (parseInt(input.value) > nbMin) {
        input.value = parseInt(input.value) - 1;
        calculerPrix();
    }
});

function calculerPrix() {
    const nb = parseInt(document.getElementById('nb_personnes').value);
    const prix = (prixBase / nbMin * nb).toFixed(2);
    document.getElementById('prix-estime').textContent = prix + ' €';
    document.getElementById('btn-commander').href = 
        `/commandes/nouveau?menu_id=<?= $menu['id'] ?>&nb_personnes=${nb}`;
}
</script>

<?php require_once 'views/layouts/footer.php'; ?>