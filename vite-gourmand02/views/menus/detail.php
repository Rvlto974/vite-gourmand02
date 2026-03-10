<?php require_once 'views/layouts/header.php'; ?>

<style>
.badge-noel       { background-color: #e74c3c; color: white; }
.badge-paques     { background-color: #9b59b6; color: white; }
.badge-classique  { background-color: #7f8c8d; color: white; }
.badge-evenement  { background-color: #2980b9; color: white; }
.badge-vegetarien { background-color: #27ae60; color: white; }
.badge-vegan      { background-color: #16a085; color: white; }

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

.section-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 24px;
    margin-top: 24px;
}
.section-card h4 {
    color: #5DA99A;
    font-weight: 700;
    margin-bottom: 16px;
}
.badge-allergene {
    background-color: #f39c12;
    color: white;
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 0.85rem;
    margin-right: 6px;
    display: inline-block;
    margin-bottom: 4px;
}
.plat-card {
    background: white;
    border-radius: 8px;
    padding: 16px;
    border-left: 4px solid #5DA99A;
}
.plat-card h6 { color: #5DA99A; font-weight: 700; margin-bottom: 4px; }
.condition-item {
    background: white;
    border-radius: 8px;
    padding: 16px;
    text-align: center;
    height: 100%;
}
.condition-item .icon { font-size: 2rem; margin-bottom: 8px; }
.condition-item h6 { color: #5DA99A; font-weight: 700; }
.avis-card {
    background: white;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
}
.stars { color: #f39c12; }
.badge-reduction {
    background-color: #27ae60;
    color: white;
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 0.85rem;
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

            <!-- Badges thème + régime -->
            <?php
                $theme = strtolower($menu['theme'] ?? 'classique');
                $badgeTheme = match($theme) {
                    'noel'      => 'badge-noel',
                    'paques'    => 'badge-paques',
                    'evenement' => 'badge-evenement',
                    default     => 'badge-classique',
                };
                $regime = strtolower($menu['regime'] ?? 'classique');
                $badgeRegime = match($regime) {
                    'vegetarien' => 'badge-vegetarien',
                    'vegan'      => 'badge-vegan',
                    default      => 'badge-classique',
                };
            ?>
            <span class="badge <?= $badgeTheme ?> me-1"><?= htmlspecialchars($menu['theme']) ?></span>
            <span class="badge <?= $badgeRegime ?> me-1"><?= htmlspecialchars($menu['regime']) ?></span>

            <!-- Note avis -->
            <?php if (!empty($avis)): ?>
                <?php
                    $totalNote = array_sum(array_column($avis, 'note'));
                    $nbAvis = count($avis);
                    $moyenneNote = $nbAvis > 0 ? round($totalNote / $nbAvis, 1) : 0;
                ?>
                <div class="mt-2">
                    <span class="stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?= $i <= $moyenneNote ? '★' : '☆' ?>
                        <?php endfor; ?>
                    </span>
                    <span class="text-muted ms-1"><?= $moyenneNote ?>/5 (<?= $nbAvis ?> avis)</span>
                </div>
            <?php else: ?>
                <div class="mt-2 text-muted small">Aucun avis pour ce menu</div>
            <?php endif; ?>

            <!-- Prix -->
            <p class="prix-color mt-3"><?= number_format($menu['prix_base'], 2) ?> EUR</p>
            <p class="text-muted mb-1">
                👥 Pour <?= $menu['nb_personnes_min'] ?> personnes minimum
                (+<?= number_format($menu['prix_base'] / $menu['nb_personnes_min'], 2) ?> EUR/personne supplémentaire)
            </p>
            <span class="badge-reduction">%-10% à partir de +5 personnes</span>

            <!-- Stock -->
            <?php if (isset($menu['stock'])): ?>
                <p class="mt-2 <?= $menu['stock'] > 0 ? 'text-success' : 'text-danger' ?>">
                    <?= $menu['stock'] > 0 ? '✅ ' . $menu['stock'] . ' disponible(s)' : '❌ Stock épuisé' ?>
                </p>
            <?php endif; ?>

            <!-- Allergènes -->
            <?php if (!empty($plats)): ?>
                <?php
                    $tousAllergenes = [];
                    foreach ($plats as $plat) {
                        if (!empty($plat['allergenes']) && strtolower(trim($plat['allergenes'])) !== 'aucun') {
                            $al = array_map('trim', explode(',', $plat['allergenes']));
                            $tousAllergenes = array_unique(array_merge($tousAllergenes, $al));
                        }
                    }
                ?>
                <?php if (!empty($tousAllergenes)): ?>
                    <div class="mt-3">
                        <p class="fw-semibold mb-1">⚠️ Allergènes</p>
                        <?php foreach ($tousAllergenes as $al): ?>
                            <span class="badge-allergene"><?= htmlspecialchars($al) ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Calculateur de personnes -->
            <div class="card p-3 mt-3" style="border-radius:12px; border: 1px solid #dee2e6;">
                <div class="row align-items-center">
                    <div class="col">
                        <label for="nb_personnes" class="form-label fw-semibold">Nombre de personnes</label>
                        <div class="input-group">
                            <button class="btn btn-outline-secondary" type="button" id="btn-moins"
                                    aria-label="Diminuer le nombre de personnes">-</button>
                            <input type="number" class="form-control text-center" id="nb_personnes"
                                   value="<?= $menu['nb_personnes_min'] ?>"
                                   min="<?= $menu['nb_personnes_min'] ?>"
                                   aria-label="Nombre de personnes">
                            <button class="btn btn-outline-secondary" type="button" id="btn-plus"
                                    aria-label="Augmenter le nombre de personnes">+</button>
                        </div>
                    </div>
                    <div class="col text-end">
                        <p class="mb-0 text-muted">Prix estimé</p>
                        <h3 id="prix-estime" class="prix-color"><?= number_format($menu['prix_base'], 2) ?> €</h3>
                    </div>
                </div>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/commandes/nouveau?menu_id=<?= $menu['id'] ?>&nb_personnes=<?= $menu['nb_personnes_min'] ?>"
                        class="btn btn-commander mt-3"
                        id="btn-commander"
                        aria-label="Commander le menu <?= htmlspecialchars($menu['titre']) ?>">
                        🛒 Commander ce menu
                    </a>
                <?php else: ?>
                    <a href="/auth/connexion" class="btn btn-outline-secondary mt-3 w-100">
                        🔒 Se connecter pour commander
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Description -->
    <div class="section-card">
        <h4>📋 Description du menu</h4>
        <p><?= nl2br(htmlspecialchars($menu['description'])) ?></p>
    </div>

    <!-- Plats (Entrée / Plat / Dessert) -->
    <?php if (!empty($plats)): ?>
        <?php
            $entrees  = array_filter($plats, fn($p) => $p['type'] === 'entree');
            $platsP   = array_filter($plats, fn($p) => $p['type'] === 'plat');
            $desserts = array_filter($plats, fn($p) => $p['type'] === 'dessert');
        ?>
        <div class="section-card">
            <div class="row g-3">
                <div class="col-md-4">
                    <h4>🥗 Entrées</h4>
                    <?php foreach ($entrees as $p): ?>
                        <div class="plat-card mb-2">
                            <h6><?= htmlspecialchars($p['nom']) ?></h6>
                            <p class="text-muted mb-0 small"><?= htmlspecialchars($p['description']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="col-md-4">
                    <h4>🍽️ Plats</h4>
                    <?php foreach ($platsP as $p): ?>
                        <div class="plat-card mb-2">
                            <h6><?= htmlspecialchars($p['nom']) ?></h6>
                            <p class="text-muted mb-0 small"><?= htmlspecialchars($p['description']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="col-md-4">
                    <h4>🍰 Desserts</h4>
                    <?php foreach ($desserts as $p): ?>
                        <div class="plat-card mb-2">
                            <h6><?= htmlspecialchars($p['nom']) ?></h6>
                            <p class="text-muted mb-0 small"><?= htmlspecialchars($p['description']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Conditions et informations -->
    <div class="section-card">
        <h4>📌 Conditions et informations</h4>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="condition-item">
                    <div class="icon">🕐</div>
                    <h6>Délai de commande</h6>
                    <p class="text-muted mb-0">3 jours minimum</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="condition-item">
                    <div class="icon">❄️</div>
                    <h6>Conservation</h6>
                    <p class="text-muted mb-0">Conserver entre 0 et 4°C. Consommer dans les 48h.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="condition-item">
                    <div class="icon">🚚</div>
                    <h6>Livraison</h6>
                    <p class="text-muted mb-0">Gratuite à Bordeaux<br>5 EUR + 0,59 EUR/km ailleurs</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Avis clients -->
    <?php if (!empty($avis)): ?>
        <div class="section-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">💬 Avis clients</h4>
                <span class="badge bg-warning text-dark">⭐ <?= $moyenneNote ?>/5 (<?= $nbAvis ?> avis)</span>
            </div>
            <?php foreach ($avis as $av): ?>
                <div class="avis-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="fw-bold">
                                <?= htmlspecialchars(($av['prenom'] ?? 'Client') . ' ' . substr($av['nom'] ?? '', 0, 1) . '.') ?>
                            </span>
                            <span class="text-muted ms-2 small">
                                <?= !empty($av['created_at']) ? date('d/m/Y', strtotime($av['created_at'])) : '' ?>
                            </span>
                        </div>
                        <div class="stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?= $i <= $av['note'] ? '★' : '☆' ?>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <p class="mt-2 mb-0 fst-italic">"<?= htmlspecialchars($av['commentaire']) ?>"</p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<script>
const prixBase = <?= $menu['prix_base'] ?>;
const nbMin    = <?= $menu['nb_personnes_min'] ?>;

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
    const nb  = parseInt(document.getElementById('nb_personnes').value);
    let prix  = prixBase / nbMin * nb;
    if (nb >= nbMin + 5) prix = prix * 0.90;
    document.getElementById('prix-estime').textContent = prix.toFixed(2) + ' €';
    const btnCommander = document.getElementById('btn-commander');
    if (btnCommander) {
        btnCommander.href = `/commandes/nouveau?menu_id=<?= $menu['id'] ?>&nb_personnes=${nb}`;
    }
}
</script>

<?php require_once 'views/layouts/footer.php'; ?>