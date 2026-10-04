<?php require_once 'views/layouts/header.php'; ?>

<style>
/* Badges des thèmes et régimes alimentaires */
.badge-noel       { background-color: #e74c3c; color: white; }
.badge-paques     { background-color: #9b59b6; color: white; }
.badge-classique  { background-color: #7f8c8d; color: white; }
.badge-evenement  { background-color: #2980b9; color: white; }
.badge-vegetarien { background-color: #27ae60; color: white; }
.badge-vegan      { background-color: #16a085; color: white; }

/* Image principale du menu */
.detail-image {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 12px;
}

/* Mise en forme du prix et du bouton de commande */
.prix-color {
    color: #e67e22;
    font-weight: bold;
    font-size: 1.5rem;
}

.btn-commander {
    background-color: #5DA99A;
    color: white;
    border: none;
    border-radius: 8px;
    width: 100%;
    padding: 12px;
    font-size: 1rem;
}

.btn-commander:hover {
    background-color: #3D7A6E;
    color: white;
}

/* Cartes des différentes sections de la page */
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

/* Badge pour les allergènes */
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

/* Cartes des plats */
.plat-card {
    background: white;
    border-radius: 8px;
    padding: 16px;
    border-left: 4px solid #5DA99A;
}

.plat-card h6 {
    color: #5DA99A;
    font-weight: 700;
    margin-bottom: 4px;
}

/* Carte d'un avis client */
.avis-card {
    background: white;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 12px;
}

/* Étoiles des avis */
.stars {
    color: #f39c12;
}

/* Badge annonçant la réduction */
.badge-reduction {
    background-color: #27ae60;
    color: white;
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 0.85rem;
}
</style>

<div class="container mt-4 mb-5">

    <!-- Fil d’Ariane -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/">Accueil</a>
            </li>
            <li class="breadcrumb-item">
                <a href="/menus">Nos Menus</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <?= htmlspecialchars($menu['titre']) ?>
            </li>
        </ol>
    </nav>

    <div class="row">

        <!-- Galerie d’images du menu -->
        <div class="col-md-6">
            <?php
                // Rassemble les images du menu et de sa galerie.
                $touteImages = [];

                if (!empty($images)) {
                    foreach ($images as $img) {
                        $touteImages[] = $img['url'];
                    }
                }

                if (!empty($menu['image'])) {
                    $touteImages[] = $menu['image'];
                }

                // Affiche une image par défaut si aucune image n’est disponible.
                if (empty($touteImages)) {
                    $touteImages[] = '/assets/images/menu-default.jpg';
                }

                // Élimine les images en double.
                $touteImages = array_unique($touteImages);
            ?>

            <?php if (count($touteImages) > 1): ?>
                <!-- Plusieurs images : affichage d’un carrousel Bootstrap -->
                <div id="carouselMenu" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <?php foreach ($touteImages as $i => $img): ?>
                            <button
                                type="button"
                                data-bs-target="#carouselMenu"
                                data-bs-slide-to="<?= $i ?>"
                                <?= $i === 0 ? 'class="active" aria-current="true"' : '' ?>
                                aria-label="Afficher l’image <?= $i + 1 ?>">
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <div class="carousel-inner" style="border-radius:12px;">
                        <?php foreach ($touteImages as $i => $img): ?>
                            <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                                <img
                                    src="<?= htmlspecialchars($img) ?>"
                                    alt="Photo du menu <?= $i + 1 ?>"
                                    class="detail-image">
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button
                        class="carousel-control-prev"
                        type="button"
                        data-bs-target="#carouselMenu"
                        data-bs-slide="prev"
                        aria-label="Image précédente">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>

                    <button
                        class="carousel-control-next"
                        type="button"
                        data-bs-target="#carouselMenu"
                        data-bs-slide="next"
                        aria-label="Image suivante">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                </div>
            <?php else: ?>
                <!-- Une seule image : affichage direct -->
                <img
                    src="<?= htmlspecialchars($touteImages[0]) ?>"
                    alt="Photo du menu <?= htmlspecialchars($menu['titre']) ?>"
                    class="detail-image">
            <?php endif; ?>
        </div>

        <!-- Informations principales du menu -->
        <div class="col-md-6">
            <h1 class="fw-bold">
                <?= htmlspecialchars($menu['titre']) ?>
            </h1>

            <?php
                // Détermine la classe CSS du badge associé au thème.
                $theme = strtolower($menu['theme'] ?? 'classique');

                $badgeTheme = match ($theme) {
                    'noel'      => 'badge-noel',
                    'paques'    => 'badge-paques',
                    'evenement' => 'badge-evenement',
                    default     => 'badge-classique',
                };

                // Détermine la classe CSS du badge associé au régime.
                $regime = strtolower($menu['regime'] ?? 'classique');

                $badgeRegime = match ($regime) {
                    'vegetarien' => 'badge-vegetarien',
                    'vegan'      => 'badge-vegan',
                    default      => 'badge-classique',
                };
            ?>

            <!-- Badges du thème et du régime -->
            <span class="badge <?= $badgeTheme ?> me-1">
                <?= htmlspecialchars($menu['theme']) ?>
            </span>

            <span class="badge <?= $badgeRegime ?> me-1">
                <?= htmlspecialchars($menu['regime']) ?>
            </span>

            <!-- Note moyenne et nombre d’avis, si des avis existent -->
            <?php if (!empty($avis)): ?>
                <?php
                    $totalNote = array_sum(array_column($avis, 'note'));
                    $nbAvis = count($avis);
                    $moyenneNote = $nbAvis > 0
                        ? round($totalNote / $nbAvis, 1)
                        : 0;
                ?>

                <div class="mt-2">
                    <span class="stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?= $i <= $moyenneNote ? '★' : '☆' ?>
                        <?php endfor; ?>
                    </span>

                    <span class="text-muted ms-1">
                        <?= $moyenneNote ?>/5 (<?= $nbAvis ?> avis)
                    </span>
                </div>
            <?php else: ?>
                <div class="mt-2 text-muted small">
                    Aucun avis pour ce menu
                </div>
            <?php endif; ?>

            <!-- Prix et nombre minimal de personnes -->
            <p class="prix-color mt-3">
                <?= number_format($menu['prix_base'], 2) ?> EUR
            </p>

            <p class="text-muted mb-1">
                👥 Pour <?= (int) $menu['nb_personnes_min'] ?> personnes minimum
                (+<?= number_format(
                    $menu['prix_base'] / $menu['nb_personnes_min'],
                    2
                ) ?> EUR/personne supplémentaire)
            </p>

            <span class="badge-reduction">
                ✓ -10% à partir de +5 personnes
            </span>

            <!-- Indique le stock si cette information est disponible -->
            <?php if (isset($menu['stock'])): ?>
                <p class="mt-2 <?= $menu['stock'] > 0 ? 'text-success' : 'text-danger' ?>">
                    <?= $menu['stock'] > 0
                        ? '✅ ' . (int) $menu['stock'] . ' disponible(s)'
                        : '❌ Stock épuisé' ?>
                </p>
            <?php endif; ?>

            <!-- Liste les allergènes présents dans les plats -->
            <?php if (!empty($plats)): ?>
                <?php
                    $tousAllergenes = [];

                    foreach ($plats as $plat) {
                        if (
                            !empty($plat['allergenes'])
                            && strtolower(trim($plat['allergenes'])) !== 'aucun'
                        ) {
                            $allergenesPlat = array_map(
                                'trim',
                                explode(',', $plat['allergenes'])
                            );

                            $tousAllergenes = array_unique(
                                array_merge($tousAllergenes, $allergenesPlat)
                            );
                        }
                    }
                ?>

                <?php if (!empty($tousAllergenes)): ?>
                    <div class="mt-3">
                        <p class="fw-semibold mb-1">⚠️ Allergènes</p>

                        <?php foreach ($tousAllergenes as $allergene): ?>
                            <span class="badge-allergene">
                                <?= htmlspecialchars($allergene) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Calculateur : les données PHP sont transmises au fichier JavaScript via data-* -->
            <div
                class="card p-3 mt-3"
                style="border-radius:12px; border: 1px solid #dee2e6;"
                id="calculateur-menu"
                data-prix-base="<?= htmlspecialchars(
                    (string) $menu['prix_base'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
                data-nb-min="<?= (int) $menu['nb_personnes_min'] ?>"
                data-menu-id="<?= (int) $menu['id'] ?>">

                <div class="row align-items-center">
                    <!-- Contrôles pour modifier le nombre de personnes -->
                    <div class="col">
                        <label for="nb_personnes" class="form-label fw-semibold">
                            Nombre de personnes
                        </label>

                        <div class="input-group">
                            <button
                                class="btn btn-outline-secondary"
                                type="button"
                                id="btn-moins"
                                aria-label="Diminuer le nombre de personnes">
                                −
                            </button>

                            <input
                                type="number"
                                class="form-control text-center"
                                id="nb_personnes"
                                value="<?= (int) $menu['nb_personnes_min'] ?>"
                                min="<?= (int) $menu['nb_personnes_min'] ?>"
                                aria-label="Nombre de personnes">

                            <button
                                class="btn btn-outline-secondary"
                                type="button"
                                id="btn-plus"
                                aria-label="Augmenter le nombre de personnes">
                                +
                            </button>
                        </div>
                    </div>

                    <!-- Le prix estimé est mis à jour par menu-detail.js -->
                    <div class="col text-end">
                        <p class="mb-0 text-muted">Prix estimé</p>
                        <h3 id="prix-estime" class="prix-color">
                            <?= number_format($menu['prix_base'], 2) ?> €
                        </h3>
                    </div>
                </div>

                <!-- Lien de commande proposé aux utilisateurs connectés -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a
                        href="/commandes/nouveau?menu_id=<?= (int) $menu['id'] ?>&nb_personnes=<?= (int) $menu['nb_personnes_min'] ?>"
                        class="btn btn-commander mt-3"
                        id="btn-commander"
                        aria-label="Commander le menu <?= htmlspecialchars($menu['titre']) ?>">
                        🛒 Commander ce menu
                    </a>
                <?php else: ?>
                    <!-- Les visiteurs doivent se connecter avant de commander -->
                    <a
                        href="/auth/connexion"
                        class="btn btn-outline-secondary mt-3 w-100">
                        Connectez-vous pour commander
                    </a>
                <?php endif; ?>
            </div>
            <!-- Fin du calculateur -->

        </div>
    </div>

    <!-- Description du menu -->
    <div class="section-card">
        <h4>📋 Description du menu</h4>
        <p><?= nl2br(htmlspecialchars($menu['description'])) ?></p>
    </div>

    <!-- Plats répartis par catégorie -->
    <?php if (!empty($plats)): ?>
        <?php
            $entrees = array_filter(
                $plats,
                fn($plat) => $plat['type'] === 'entree'
            );

            $platsPrincipaux = array_filter(
                $plats,
                fn($plat) => $plat['type'] === 'plat'
            );

            $desserts = array_filter(
                $plats,
                fn($plat) => $plat['type'] === 'dessert'
            );
        ?>

        <div class="section-card">
            <div class="row g-3">
                <!-- Entrées -->
                <div class="col-md-4">
                    <h4>🥗 Entrées</h4>

                    <?php foreach ($entrees as $plat): ?>
                        <div class="plat-card mb-2">
                            <h6><?= htmlspecialchars($plat['nom']) ?></h6>
                            <p class="text-muted mb-0 small">
                                <?= htmlspecialchars($plat['description']) ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Plats principaux -->
                <div class="col-md-4">
                    <h4>🍽️ Plats</h4>

                    <?php foreach ($platsPrincipaux as $plat): ?>
                        <div class="plat-card mb-2">
                            <h6><?= htmlspecialchars($plat['nom']) ?></h6>
                            <p class="text-muted mb-0 small">
                                <?= htmlspecialchars($plat['description']) ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Desserts -->
                <div class="col-md-4">
                    <h4>🍰 Desserts</h4>

                    <?php foreach ($desserts as $plat): ?>
                        <div class="plat-card mb-2">
                            <h6><?= htmlspecialchars($plat['nom']) ?></h6>
                            <p class="text-muted mb-0 small">
                                <?= htmlspecialchars($plat['description']) ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Conditions importantes avant la commande -->
    <div
        class="alert alert-warning mt-4"
        role="alert"
        style="border-left: 5px solid #e67e22; border-radius:10px;">

        <h5 class="fw-bold">
            ⚠️ Conditions importantes à lire avant de commander
        </h5>

        <div class="row g-3 mt-1">
            <!-- Délai nécessaire avant la prestation -->
            <div class="col-md-4">
                <div class="d-flex align-items-start gap-2">
                    <span style="font-size:1.5rem;">🕐</span>
                    <div>
                        <strong>Délai de commande</strong>
                        <p class="mb-0 small">
                            Ce menu doit être commandé au minimum
                            <strong>3 jours</strong> avant la date de prestation.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Conditions de conservation -->
            <div class="col-md-4">
                <div class="d-flex align-items-start gap-2">
                    <span style="font-size:1.5rem;">❄️</span>
                    <div>
                        <strong>Conservation</strong>
                        <p class="mb-0 small">
                            Conserver entre <strong>0 et 4°C</strong>.
                            Consommer dans les <strong>48h</strong> suivant la livraison.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Informations de livraison -->
            <div class="col-md-4">
                <div class="d-flex align-items-start gap-2">
                    <span style="font-size:1.5rem;">🚚</span>
                    <div>
                        <strong>Livraison</strong>
                        <p class="mb-0 small">
                            Gratuite à Bordeaux. Hors Bordeaux :
                            <strong>5€ + 0,59€/km</strong>.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-2">

        <p class="mb-0 small text-muted">
            En passant commande, vous acceptez nos
            <a href="/pages/cgv">Conditions Générales de Vente</a>.
        </p>
    </div>

    <!-- Avis laissés par les clients -->
    <?php if (!empty($avis)): ?>
        <?php
            // Calcule la moyenne si elle n’a pas déjà été déterminée plus haut.
            if (!isset($moyenneNote)) {
                $totalNote = array_sum(array_column($avis, 'note'));
                $nbAvis = count($avis);
                $moyenneNote = $nbAvis > 0
                    ? round($totalNote / $nbAvis, 1)
                    : 0;
            }
        ?>

        <div class="section-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">💬 Avis clients</h4>

                <span class="badge bg-warning text-dark">
                    ⭐ <?= $moyenneNote ?>/5 (<?= $nbAvis ?> avis)
                </span>
            </div>

            <?php foreach ($avis as $av): ?>
                <div class="avis-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <!-- Affiche le prénom et l’initiale du nom -->
                            <span class="fw-bold">
                                <?= htmlspecialchars(
                                    ($av['prenom'] ?? 'Client')
                                    . ' '
                                    . substr($av['nom'] ?? '', 0, 1)
                                    . '.'
                                ) ?>
                            </span>

                            <!-- Date de publication de l’avis -->
                            <span class="text-muted ms-2 small">
                                <?= !empty($av['created_at'])
                                    ? date('d/m/Y', strtotime($av['created_at']))
                                    : '' ?>
                            </span>
                        </div>

                        <!-- Note individuelle -->
                        <div class="stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?= $i <= $av['note'] ? '★' : '☆' ?>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <!-- Commentaire du client -->
                    <p class="mt-2 mb-0 fst-italic">
                        « <?= htmlspecialchars($av['commentaire']) ?> »
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<!-- Script externe du calculateur de prix -->
<script src="/assets/js/menu-detail.js" defer></script>

<?php require_once 'views/layouts/footer.php'; ?>