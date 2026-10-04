<?php require_once 'views/layouts/header.php'; ?>

<style>
/* Style des cartes de menus */
.menu-card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: transform 0.2s, box-shadow 0.2s;
}

.menu-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.12);
}

.menu-card img {
    height: 200px;
    object-fit: cover;
    width: 100%;
}

/* Badge du thème placé sur la photo */
.badge-theme {
    position: absolute;
    top: 12px;
    right: 12px;
    font-size: 0.75rem;
    padding: 4px 10px;
    border-radius: 20px;
}

.badge-noel       { background-color: #c0392b; color: white; }
.badge-paques     { background-color: #7d3c98; color: white; }
.badge-classique  { background-color: #d35400; color: white; }
.badge-evenement  { background-color: #1a5276; color: white; }
.badge-saisonnier { background-color: #1e8449; color: white; }

.prix-color {
    color: #d35400;
    font-weight: bold;
    font-size: 1.1rem;
}

.card-img-wrapper {
    position: relative;
}

.stars {
    color: #d4800a;
    font-size: 0.9rem;
}

.meta-info {
    font-size: 0.85rem;
    color: #555;
}

/* Bouton d'accès au détail du menu */
.btn-voir {
    background-color: #2E6B5E;
    color: white;
    border: none;
    border-radius: 8px;
    width: 100%;
    padding: 8px;
}

.btn-voir:hover {
    background-color: #1D4A3E;
    color: white;
}

/* Apparence du formulaire de filtres */
.filtre-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.btn-appliquer {
    background-color: #2E6B5E;
    color: white;
    border: none;
    border-radius: 8px;
    width: 100%;
}

.btn-appliquer:hover {
    background-color: #1D4A3E;
    color: white;
}

.btn-reinit {
    background: white;
    border: 1px solid #ccc;
    border-radius: 8px;
    width: 100%;
    margin-top: 8px;
    color: #333;
}

/* Titres et compteurs */
.page-title {
    font-size: 1.8rem;
    font-weight: 700;
}

.count-badge {
    background: #2E6B5E;
    color: white;
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 0.9rem;
}

/* Badge indiquant qu'un menu est récent */
.badge-nouveau {
    position: absolute;
    top: 12px;
    left: 12px;
    background: #e67e22;
    color: white;
    border-radius: 20px;
    padding: 3px 10px;
    font-size: 0.75rem;
    font-weight: bold;
}
</style>

<div class="container mt-4">

    <!-- Fil d'Ariane pour faciliter la navigation -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/" style="color:#2E6B5E">Accueil</a>
            </li>
            <li class="breadcrumb-item active">Nos Menus</li>
        </ol>
    </nav>

    <div class="row">

        <!-- Colonne des filtres -->
        <div class="col-md-3">
            <div class="filtre-card">
                <form id="filtres-form" aria-label="Filtres des menus">

                    <!-- Filtre par thème -->
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

                    <!-- Filtre par régime alimentaire -->
                    <div class="mb-3">
                        <label for="regime" class="form-label fw-semibold">
                            Régime alimentaire
                        </label>
                        <select class="form-select" id="regime" name="regime">
                            <option value="">Tous les régimes</option>
                            <option value="classique">Classique</option>
                            <option value="vegetarien">Végétarien</option>
                            <option value="vegan">Vegan</option>
                        </select>
                    </div>

                    <!-- Filtres de prix minimum et maximum -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Prix maximum :
                            <span id="prix-max-label" style="color:#5DA99A;">500 €</span>
                        </label>

                        <input
                            type="range"
                            class="form-range"
                            id="prix_max"
                            name="prix_max"
                            min="0"
                            max="1000"
                            step="10"
                            value="500"
                            aria-label="Prix maximum"
                        >

                        <div class="d-flex justify-content-between">
                            <small class="text-muted">0 €</small>
                            <small class="text-muted">1000 €</small>
                        </div>

                        <label class="form-label fw-semibold mt-2">
                            Prix minimum :
                            <span id="prix-min-label" style="color:#5DA99A;">0 €</span>
                        </label>

                        <input
                            type="range"
                            class="form-range"
                            id="prix_min"
                            name="prix_min"
                            min="0"
                            max="1000"
                            step="10"
                            value="0"
                            aria-label="Prix minimum"
                        >

                        <div class="d-flex justify-content-between">
                            <small class="text-muted">0 €</small>
                            <small class="text-muted">1000 €</small>
                        </div>
                    </div>

                    <!-- Filtre par nombre minimum de convives -->
                    <div class="mb-3">
                        <label for="nb_personnes" class="form-label fw-semibold">
                            Nombre de convives minimum
                        </label>
                        <select class="form-select" id="nb_personnes" name="nb_personnes">
                            <option value="">Peu importe</option>
                            <option value="2">2+</option>
                            <option value="4">4+</option>
                            <option value="6">6+</option>
                            <option value="10">10+</option>
                        </select>
                    </div>

                    <!-- Choix de l'ordre de tri -->
                    <div class="mb-3">
                        <label for="tri" class="form-label fw-semibold">Trier par</label>
                        <select class="form-select" id="tri" name="tri">
                            <option value="recent">Plus récents</option>
                            <option value="prix_asc">Prix croissant</option>
                            <option value="prix_desc">Prix décroissant</option>
                        </select>
                    </div>

                    <!-- Boutons de recherche et de réinitialisation -->
                    <button type="submit" class="btn btn-appliquer">
                        🔍 Appliquer
                    </button>
                    <a href="/menus" class="btn btn-reinit">
                        ✕ Réinitialiser
                    </a>
                </form>
            </div>
        </div>

        <!-- Colonne affichant les menus -->
        <div class="col-md-9">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h1 class="page-title">🍽 Nos Menus</h1>
                <span class="count-badge"><?= count($menus) ?> menu(s)</span>
            </div>

            <div class="row" id="liste-menus">

                <!-- Message affiché si aucun menu n'est disponible -->
                <?php if (empty($menus)) : ?>
                    <p class="text-muted">Aucun menu trouvé.</p>

                <?php else : ?>

                    <!-- Affichage de chaque menu -->
                    <?php foreach ($menus as $menu) : ?>
                        <?php
                            // Préparation des informations utilisées dans la carte
                            $theme = strtolower($menu['theme'] ?? 'classique');

                            // Classe CSS associée au thème du menu
                            $badgeClass = match ($theme) {
                                'noel'       => 'badge-noel',
                                'paques'     => 'badge-paques',
                                'evenement'  => 'badge-evenement',
                                'saisonnier' => 'badge-saisonnier',
                                default      => 'badge-classique',
                            };

                            $badgeLabel = ucfirst($menu['theme'] ?? 'Classique');
                            $note = $menu['note_moyenne'] ?? 0;
                            $nbAvis = $menu['nb_avis'] ?? 0;
                            $stock = $menu['stock'] ?? 0;

                            // Calcul du nombre de jours depuis la création du menu
                            $joursDepuisCreation = isset($menu['created_at'])
                                ? (time() - strtotime($menu['created_at'])) / (60 * 60 * 24)
                                : 999;
                        ?>

                        <div class="col-md-4 mb-4">
                            <div class="card menu-card h-100">
                                <div class="card-img-wrapper">

                                    <!-- Image du menu ou image par défaut -->
                                    <img
                                        src="<?= !empty($menu['image'])
                                            ? htmlspecialchars($menu['image'])
                                            : '/assets/images/menu-default.jpg' ?>"
                                        alt="Photo du <?= htmlspecialchars($menu['titre']) ?>"
                                    >

                                    <!-- Badge indiquant le thème -->
                                    <span class="badge-theme <?= $badgeClass ?>">
                                        <?= $badgeLabel ?>
                                    </span>

                                    <!-- Badge affiché pour un menu créé depuis moins de 7 jours -->
                                    <?php if ($joursDepuisCreation <= 7) : ?>
                                        <span class="badge-nouveau">🆕 Nouveau</span>
                                    <?php endif; ?>
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title fw-bold" style="color:#2E6B5E">
                                        <?= htmlspecialchars($menu['titre']) ?>
                                    </h5>

                                    <!-- Note moyenne et nombre d'avis -->
                                    <div
                                        class="stars mb-1"
                                        aria-label="Note : <?= round($note) ?> étoiles sur 5"
                                    >
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                            <?= $i <= round($note) ? '★' : '☆' ?>
                                        <?php endfor; ?>

                                        <small class="text-muted ms-1">
                                            (<?= $nbAvis ?> avis)
                                        </small>
                                    </div>

                                    <!-- Description du menu, limitée à 100 caractères -->
                                    <p class="card-text text-muted small flex-grow-1">
                                        <?= htmlspecialchars(mb_substr($menu['description'], 0, 100)) ?>...
                                    </p>

                                    <!-- Informations pratiques du menu -->
                                    <div class="d-flex justify-content-between meta-info mb-2">
                                        <span>👥 <?= $menu['nb_personnes_min'] ?> pers. min</span>
                                        <span><?= ucfirst($menu['regime'] ?? '') ?></span>
                                    </div>

                                    <!-- Prix de base -->
                                    <p class="prix-color mb-2">
                                        À partir de <?= number_format($menu['prix_base'], 2) ?> EUR
                                    </p>

                                    <!-- État du stock -->
                                    <?php if ($stock <= 0) : ?>
                                        <span class="badge bg-danger mb-2">
                                            ❌ Stock épuisé
                                        </span>
                                    <?php elseif ($stock <= 3) : ?>
                                        <span class="badge bg-warning text-dark mb-2">
                                            ⚠️ Plus que <?= $stock ?> disponible(s)
                                        </span>
                                    <?php else : ?>
                                        <span class="badge bg-success mb-2">
                                            ✅ Disponible
                                        </span>
                                    <?php endif; ?>

                                    <!-- Lien vers la page de détail du menu -->
                                    <a
                                        href="/menus/detail?id=<?= $menu['id'] ?>"
                                        class="btn btn-voir <?= $stock <= 0 ? 'disabled' : '' ?>"
                                        aria-label="Voir le menu <?= htmlspecialchars($menu['titre']) ?>"
                                    >
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

<!-- Chargement du JavaScript de cette page -->
<script src="/assets/js/menus-index.js" defer></script>

<?php require_once 'views/layouts/footer.php'; ?>