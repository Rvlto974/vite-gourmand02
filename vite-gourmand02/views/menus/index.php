<?php require_once 'views/layouts/header.php'; ?>

<style>
.menu-card { border: none; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: transform 0.2s, box-shadow 0.2s; }
.menu-card:hover { transform: translateY(-4px); box-shadow: 0 6px 20px rgba(0,0,0,0.12); }
.menu-card img { height: 200px; object-fit: cover; width: 100%; }
.badge-theme { position: absolute; top: 12px; right: 12px; font-size: 0.75rem; padding: 4px 10px; border-radius: 20px; }
.badge-noel      { background-color: #e74c3c; color: white; }
.badge-paques    { background-color: #9b59b6; color: white; }
.badge-classique { background-color: #e67e22; color: white; }
.badge-evenement { background-color: #2980b9; color: white; }
.badge-saisonnier{ background-color: #27ae60; color: white; }
.prix-color { color: #e67e22; font-weight: bold; font-size: 1.1rem; }
.card-img-wrapper { position: relative; }
.stars { color: #f39c12; font-size: 0.9rem; }
.meta-info { font-size: 0.85rem; color: #666; }
.btn-voir { background-color: #5DA99A; color: white; border: none; border-radius: 8px; width: 100%; padding: 8px; }
.btn-voir:hover { background-color: #3D7A6E; color: white; }
.filtre-card { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
.btn-appliquer { background-color: #5DA99A; color: white; border: none; border-radius: 8px; width: 100%; }
.btn-appliquer:hover { background-color: #3D7A6E; color: white; }
.btn-reinit { background: white; border: 1px solid #ccc; border-radius: 8px; width: 100%; margin-top: 8px; }
.page-title { font-size: 1.8rem; font-weight: 700; }
.count-badge { background: #5DA99A; color: white; border-radius: 20px; padding: 4px 12px; font-size: 0.9rem; }
</style>

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item active">Nos Menus</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-3">
            <div class="filtre-card">
                <form id="filtres-form" aria-label="Filtres des menus">
                    <div class="mb-3">
                        <label for="theme" class="form-label fw-semibold">Thème</label>
                        <select class="form-select" id="theme" name="theme">
                            <option value="">Tous les thèmes</option>
                            <option value="Noel">Noël</option>
                            <option value="Paques">Pâques</option>
                            <option value="classique">Classique</option>
                            <option value="evenement">Événement</option>
                            <option value="saisonnier">Saisonnier</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="regime" class="form-label fw-semibold">Régime alimentaire</label>
                        <select class="form-select" id="regime" name="regime">
                            <option value="">Tous les régimes</option>
                            <option value="classique">Classique</option>
                            <option value="vegetarien">Végétarien</option>
                            <option value="vegan">Vegan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Fourchette de prix</label>
                        <div class="d-flex gap-2 align-items-center">
                            <input type="number" class="form-control" id="prix_min" name="prix_min" placeholder="Min" min="0">
                            <span>-</span>
                            <input type="number" class="form-control" id="prix_max" name="prix_max" placeholder="Max" min="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="nb_personnes" class="form-label fw-semibold">Nombre de convives minimum</label>
                        <select class="form-select" id="nb_personnes" name="nb_personnes">
                            <option value="">Peu importe</option>
                            <option value="2">2+</option>
                            <option value="4">4+</option>
                            <option value="6">6+</option>
                            <option value="10">10+</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tri" class="form-label fw-semibold">Trier par</label>
                        <select class="form-select" id="tri" name="tri">
                            <option value="recent">Plus récents</option>
                            <option value="prix_asc">Prix croissant</option>
                            <option value="prix_desc">Prix décroissant</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-appliquer">🔍 Appliquer</button>
                    <a href="/menus" class="btn btn-reinit">✕ Réinitialiser</a>
                </form>
            </div>
        </div>
        <div class="col-md-9">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h1 class="page-title">🍽 Nos Menus</h1>
                <span class="count-badge"><?= count($menus) ?> menu(s)</span>
            </div>
            <div class="row" id="liste-menus">
                <?php if (empty($menus)) : ?>
                    <p class="text-muted">Aucun menu trouvé.</p>
                <?php else : ?>
                    <?php foreach ($menus as $menu) : ?>
                    <?php
                        $theme = strtolower($menu['theme'] ?? 'classique');
                        $badgeClass = match($theme) {
                            'noel'       => 'badge-noel',
                            'paques'     => 'badge-paques',
                            'evenement'  => 'badge-evenement',
                            'saisonnier' => 'badge-saisonnier',
                            default      => 'badge-classique',
                        };
                        $badgeLabel = ucfirst($menu['theme'] ?? 'Classique');
                        $note = $menu['note_moyenne'] ?? 0;
                        $nbAvis = $menu['nb_avis'] ?? 0;
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card menu-card h-100">
                            <div class="card-img-wrapper">
                                <img src="<?= !empty($menu['image']) ? htmlspecialchars($menu['image']) : '/assets/images/menu-default.jpg' ?>" 
                                     alt="Photo du <?= htmlspecialchars($menu['titre']) ?>">
                                <span class="badge-theme <?= $badgeClass ?>"><?= $badgeLabel ?></span>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold" style="color:#5DA99A">
                                    <?= htmlspecialchars($menu['titre']) ?>
                                </h5>
                                <div class="stars mb-1">
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <?= $i <= round($note) ? '★' : '☆' ?>
                                    <?php endfor; ?>
                                    <small class="text-muted ms-1">(<?= $nbAvis ?> avis)</small>
                                </div>
                                <p class="card-text text-muted small flex-grow-1">
                                    <?= htmlspecialchars(mb_substr($menu['description'], 0, 100)) ?>...
                                </p>
                                <div class="d-flex justify-content-between meta-info mb-2">
                                    <span>👥 <?= $menu['nb_personnes_min'] ?> pers. min</span>
                                    <span><?= ucfirst($menu['regime'] ?? '') ?></span>
                                </div>
                                <p class="prix-color mb-3">
                                    A partir de <?= number_format($menu['prix_base'], 2) ?> EUR
                                </p>
                                <a href="/menus/detail?id=<?= $menu['id'] ?>" 
                                   class="btn btn-voir"
                                   aria-label="Voir le menu <?= htmlspecialchars($menu['titre']) ?>">
                                    👁 Voir le menu
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>