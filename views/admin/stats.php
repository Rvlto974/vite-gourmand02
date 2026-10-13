
<?php

// Chargement de l'en-tête du site.

require_once 'views/layouts/header.php';

// Préparation des indicateurs à partir des statistiques existantes.

// Aucun calcul MongoDB ou contrôleur n'est modifié.

$nombreCommandes = array_sum(array_map(

    fn($stat) => (int) ($stat['nb_commandes'] ?? 0),

    $statsByMenu

));

$panierMoyen = $nombreCommandes > 0

    ? (float) $caTotal / $nombreCommandes

    : 0;

$classementMenus = $statsByMenu;

usort(

    $classementMenus,

    fn($a, $b) => (int) $b['nb_commandes'] <=> (int) $a['nb_commandes']

);

$meilleurMenu = !empty($classementMenus)

    ? (string) $classementMenus[0]['_id']

    : 'Aucune vente';

?>

<!-- Feuille de style dédiée aux statistiques administrateur -->

<link rel="stylesheet" href="/assets/css/admin-stats.css">

<div class="container-fluid mt-4 mb-5 px-3 px-lg-4">

    <div class="stats-premium">

        <div class="row g-4">

            <!-- Navigation administrateur -->

            <aside class="col-12 col-lg-3">

                <div class="stats-sidebar">

                    <h5 class="fw-bold mb-4" style="color: #235844;">

                        <span aria-hidden="true">✦</span> Espace admin

                    </h5>

                    <ul class="nav flex-column gap-1">

                        <li class="nav-item">

                            <a class="nav-link" href="/admin/dashboard">Tableau de bord</a>

                        </li>

                        <li class="nav-item">

                            <a class="nav-link" href="/admin/utilisateurs">Utilisateurs</a>

                        </li>

                        <li class="nav-item">

                            <a class="nav-link" href="/admin/menus">Menus</a>

                        </li>

                        <li class="nav-item">

                            <a class="nav-link" href="/employe/commandes">Commandes</a>

                        </li>

                        <li class="nav-item">

                            <a class="nav-link active" aria-current="page" href="/admin/stats">Statistiques</a>

                        </li>

                        <li class="nav-item">

                            <a class="nav-link" href="/auth/deconnexion">Déconnexion</a>

                        </li>

                    </ul>

                </div>

            </aside>

            <!-- Contenu principal du dashboard -->

            <main class="col-12 col-lg-9">

                <!-- En-tête premium -->

                <header class="stats-hero">

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                        <div>

                            <p class="mb-2 fw-semibold" style="color: #e9d29b; letter-spacing: 1px;">

                                VITE & GOURMAND · ANALYTICS

                            </p>

                            <h1 class="mb-2">Performance & Analyse</h1>

                            <p class="mb-0" style="color: #e0efe5;">

                                Retrouvez vos chiffres clés et suivez les performances de vos menus.

                            </p>

                        </div>

                        <div

                            class="rounded-4 p-3"

                            style="background: rgba(255,255,255,.12); color: #e9d29b; font-size: 2rem;"

                            aria-hidden="true"

                        >

                            📊

                        </div>

                    </div>

                </header>

                <!-- Cartes des indicateurs principaux -->

                <section class="row g-3 mb-4" aria-label="Indicateurs principaux">

                    <!-- Chiffre d'affaires -->

                    <div class="col-12 col-sm-6">

                        <div class="stats-card h-100">

                            <div class="d-flex justify-content-between align-items-start mb-3">

                                <span class="stats-kpi-label">Chiffre d'affaires</span>

                                <span aria-hidden="true" style="font-size: 1.6rem;">💶</span>

                            </div>

                            <div class="stats-kpi-value">

                                <?= number_format((float) $caTotal, 2, ',', ' ') ?> €

                            </div>

                            <p class="mb-0 mt-2 small" style="color: #557361;">

                                Revenu total enregistré

                            </p>

                        </div>

                    </div>

                    <!-- Nombre de commandes -->

                    <div class="col-12 col-sm-6">

                        <div class="stats-card h-100">

                            <div class="d-flex justify-content-between align-items-start mb-3">

                                <span class="stats-kpi-label">Commandes</span>

                                <span aria-hidden="true" style="font-size: 1.6rem;">🛍️</span>

                            </div>

                            <div class="stats-kpi-value">

                                <?= number_format($nombreCommandes, 0, ',', ' ') ?>

                            </div>

                            <p class="mb-0 mt-2 small" style="color: #557361;">

                                Commandes comptabilisées par menu

                            </p>

                        </div>

                    </div>

                    <!-- Panier moyen -->

                    <div class="col-12 col-sm-6">

                        <div class="stats-card h-100">

                            <div class="d-flex justify-content-between align-items-start mb-3">

                                <span class="stats-kpi-label">Panier moyen</span>

                                <span aria-hidden="true" style="font-size: 1.6rem;">🧾</span>

                            </div>

                            <div class="stats-kpi-value">

                                <?= number_format($panierMoyen, 2, ',', ' ') ?> €

                            </div>

                            <p class="mb-0 mt-2 small" style="color: #557361;">

                                Chiffre d'affaires par commande

                            </p>

                        </div>

                    </div>

                    <!-- Menu le plus commandé -->

                    <div class="col-12 col-sm-6">

                        <div class="stats-card h-100">

                            <div class="d-flex justify-content-between align-items-start mb-3">

                                <span class="stats-kpi-label">Menu le plus commandé</span>

                                <span aria-hidden="true" style="font-size: 1.6rem;">🏆</span>

                            </div>

                            <div class="fw-bold fs-4" style="color: #235844; overflow-wrap: anywhere;">

                                <?= htmlspecialchars($meilleurMenu, ENT_QUOTES, 'UTF-8') ?>

                            </div>

                            <p class="mb-0 mt-2 small" style="color: #557361;">

                                Selon le nombre de commandes

                            </p>

                        </div>

                    </div>

                </section>

                <!-- ======================================================
                     GRAPHIQUE : ÉVOLUTION DU CHIFFRE D'AFFAIRES
                     Les trois séries sont préparées côté PHP depuis MongoDB.
                     Le changement de période sera géré par stats.js.
                     ====================================================== -->
                <section class="stats-card mb-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                        <div>
                            <h4 class="mb-1">📈 Évolution du chiffre d'affaires</h4>
                            <p class="mb-0" style="color: #557361;">
                                Suivi des montants des commandes par jour, semaine ou mois
                            </p>
                        </div>

                        <div class="d-flex flex-wrap gap-3">
                            <!-- Nouveau sélecteur : granularité du graphique. -->
                            <div>
                                <label for="filtreGranularite" class="form-label fw-semibold">
                                    Affichage
                                </label>
                                <select id="filtreGranularite" class="form-select">
                                    <option value="jour">Par jour</option>
                                    <option value="semaine">Par semaine</option>
                                    <option value="mois" selected>Par mois</option>
                                </select>
                            </div>

                            <!-- Filtre historique conservé pour la vue mensuelle. -->
                            <div id="blocFiltreMois">
                                <label for="filtreMois" class="form-label fw-semibold">
                                    Période
                                </label>
                                <select id="filtreMois" class="form-select">
                                    <option value="3">3 derniers mois</option>
                                    <option value="6">6 derniers mois</option>
                                    <option value="12" selected>12 derniers mois</option>
                                    <option value="all">Toute la période</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div style="position: relative; height: 320px; width: 100%;">
                        <!--
                            IMPORTANT : l'ID graphMensuel et les attributs
                            data-mois / data-ca sont conservés pour le JS actuel.

                            Les nouveaux attributs contiennent les séries
                            journalières et hebdomadaires au format JSON.
                            htmlspecialchars protège les attributs HTML.
                        -->
                        <canvas
                            id="graphMensuel"
                            data-mois="<?= htmlspecialchars(json_encode(array_map(
                                fn($stat) => sprintf('%02d/%04d',
                                    $stat['_id']['mois'], $stat['_id']['annee']),
                                $statsByMois
                            )), ENT_QUOTES, 'UTF-8') ?>"
                            data-ca="<?= htmlspecialchars(json_encode(array_map(
                                fn($stat) => (float) $stat['ca_total'],
                                $statsByMois
                            )), ENT_QUOTES, 'UTF-8') ?>"
                            data-jours="<?= htmlspecialchars(json_encode(array_map(
                                fn($stat) => sprintf('%02d/%02d/%04d',
                                    $stat['_id']['jour'],
                                    $stat['_id']['mois'],
                                    $stat['_id']['annee']),
                                $statsByJour ?? []
                            )), ENT_QUOTES, 'UTF-8') ?>"
                            data-ca-jours="<?= htmlspecialchars(json_encode(array_map(
                                fn($stat) => (float) $stat['ca_total'],
                                $statsByJour ?? []
                            )), ENT_QUOTES, 'UTF-8') ?>"
                            data-semaines="<?= htmlspecialchars(json_encode(array_map(
                                fn($stat) => sprintf('S%02d/%04d',
                                    $stat['_id']['semaine'],
                                    $stat['_id']['annee']),
                                $statsBySemaine ?? []
                            )), ENT_QUOTES, 'UTF-8') ?>"
                            data-ca-semaines="<?= htmlspecialchars(json_encode(array_map(
                                fn($stat) => (float) $stat['ca_total'],
                                $statsBySemaine ?? []
                            )), ENT_QUOTES, 'UTF-8') ?>"
                        ></canvas>
                    </div>
                </section>

                <!-- Graphiques : commandes et chiffre d'affaires par menu -->

                <div class="row g-4 mb-4">

                    <!-- Nombre de commandes par menu -->

                    <div class="col-12 col-xl-6">

                        <section class="stats-card h-100">

                            <h4>📊 Commandes par menu</h4>

                            <div style="position: relative; min-height: 280px;">

                                <canvas

                                    id="graphMenus"

                                    height="180"

                                    data-labels="<?= htmlspecialchars(json_encode(array_map(fn($s) => $s['_id'], $statsByMenu)), ENT_QUOTES, 'UTF-8') ?>"

                                    data-commandes="<?= htmlspecialchars(json_encode(array_map(fn($s) => $s['nb_commandes'], $statsByMenu)), ENT_QUOTES, 'UTF-8') ?>"

                                    data-ca="<?= htmlspecialchars(json_encode(array_map(fn($s) => $s['ca_total'], $statsByMenu)), ENT_QUOTES, 'UTF-8') ?>"

                                ></canvas>

                            </div>

                        </section>

                    </div>

                    <!-- Répartition du chiffre d'affaires par menu -->

                    <div class="col-12 col-xl-6">

                        <section class="stats-card h-100">

                            <h4>🍽️ Répartition du chiffre d'affaires</h4>

                            <div style="position: relative; height: 320px; max-width: 650px; margin: 0 auto;">

                                <canvas

                                    id="graphCA"

                                    height="120"

                                    data-labels="<?= htmlspecialchars(json_encode(array_map(fn($s) => $s['_id'], $statsByMenu)), ENT_QUOTES, 'UTF-8') ?>"

                                    data-commandes="<?= htmlspecialchars(json_encode(array_map(fn($s) => $s['nb_commandes'], $statsByMenu)), ENT_QUOTES, 'UTF-8') ?>"

                                    data-ca="<?= htmlspecialchars(json_encode(array_map(fn($s) => $s['ca_total'], $statsByMenu)), ENT_QUOTES, 'UTF-8') ?>"

                                ></canvas>

                            </div>

                        </section>

                    </div>

                </div>

                <!-- Classement des menus -->

                <section class="stats-card mb-4">

                    <h4>🏆 Classement des menus</h4>

                    <p style="color: #557361;">

                        Menus classés selon leur nombre de commandes.

                    </p>

                    <?php if (empty($classementMenus)): ?>

                        <p class="mb-0">Aucune donnée disponible pour le moment.</p>

                    <?php else: ?>

                        <?php

                        $maximumCommandes = max(

                            1,

                            (int) $classementMenus[0]['nb_commandes']

                        );

                        ?>

                        <?php foreach ($classementMenus as $index => $menu): ?>

                            <?php

                            $commandes = (int) $menu['nb_commandes'];

                            $pourcentage = ($commandes / $maximumCommandes) * 100;

                            ?>

                            <div class="mb-3">

                                <div class="d-flex flex-wrap justify-content-between gap-2 mb-2">

                                    <strong style="color: #235844;">

                                        <?= $index + 1 ?>.

                                        <?= htmlspecialchars((string) $menu['_id'], ENT_QUOTES, 'UTF-8') ?>

                                    </strong>

                                    <span style="color: #557361;">

                                        <?= $commandes ?> commande(s)

                                    </span>

                                </div>

                                <div

                                    class="progress"

                                    style="height: 12px; background: #dfe9dc; border-radius: 20px;"

                                >

                                    <div

                                        class="progress-bar"

                                        role="progressbar"

                                        style="width: <?= number_format($pourcentage, 2, '.', '') ?>%; background: #347257;"

                                        aria-valuenow="<?= $commandes ?>"

                                        aria-valuemin="0"

                                        aria-valuemax="<?= $maximumCommandes ?>"

                                    ></div>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </section>

                <!-- Tableau détaillé par menu -->

                <section class="stats-card mb-4">

                    <h4>📋 Détails par menu</h4>

                    <div class="table-responsive stats-table">

                        <table class="table table-hover mb-0">

                            <thead>

                                <tr>

                                    <th>Menu</th>

                                    <th>Nb commandes</th>

                                    <th>Nb personnes</th>

                                    <th>CA total</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php foreach ($statsByMenu as $stat): ?>

                                    <tr>

                                        <td>

                                            <?= htmlspecialchars((string) $stat['_id'], ENT_QUOTES, 'UTF-8') ?>

                                        </td>

                                        <td><?= (int) $stat['nb_commandes'] ?></td>

                                        <td><?= (int) $stat['nb_personnes'] ?></td>

                                        <td>

                                            <strong>

                                                <?= number_format((float) $stat['ca_total'], 2, ',', ' ') ?> €

                                            </strong>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                </section>

                <!-- Tableau détaillé par mois -->

                <section class="stats-card">

                    <h4>📅 Détails mensuels</h4>

                    <div class="table-responsive stats-table">

                        <table class="table table-hover mb-0">

                            <thead>

                                <tr>

                                    <th>Mois</th>

                                    <th>Nb commandes</th>

                                    <th>CA total</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php foreach ($statsByMois as $stat): ?>

                                    <tr>

                                        <td>

                                            <?= sprintf(

                                                '%02d/%04d',

                                                (int) $stat['_id']['mois'],

                                                (int) $stat['_id']['annee']

                                            ) ?>

                                        </td>

                                        <td><?= (int) $stat['nb_commandes'] ?></td>

                                        <td>

                                            <strong>

                                                <?= number_format((float) $stat['ca_total'], 2, ',', ' ') ?> €

                                            </strong>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                </section>

            </main>

        </div>

    </div>

</div>

<!-- Bibliothèque Chart.js et script existant : inchangés -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js" defer></script>

<script src="/assets/js/stats.js" defer></script>

<?php require_once 'views/layouts/footer.php'; ?>
