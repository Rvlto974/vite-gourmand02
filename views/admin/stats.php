<?php require_once 'views/layouts/header.php'; ?>
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3">
                <h5>Espace admin</h5>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Tableau de bord</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/utilisateurs">Utilisateurs</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/menus">Menus</a></li>
                    <li class="nav-item"><a class="nav-link" href="/employe/commandes">Commandes</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/admin/stats">Statistiques</a></li>
                    <li class="nav-item"><a class="nav-link" href="/auth/deconnexion">Déconnexion</a></li>
                </ul>
            </div>
        </div>
        <div class="col-md-9">
            <h1>📊 Statistiques</h1>

            <!-- CA Total -->
            <div class="card p-4 mt-3 text-center" style="border-left: 4px solid #5DA99A;">
                <h2 style="color:#5DA99A"><?= number_format($caTotal, 2) ?> €</h2>
                <p class="text-muted mb-0">Chiffre d'affaires total</p>
            </div>

           <!-- Graphique commandes par menu -->
<div class="card p-4 mt-4">
    <h4 class="mb-3">📈 Commandes par menu</h4>
    <canvas id="graphMenus" height="120"
        data-labels="<?= htmlspecialchars(json_encode(array_map(fn($s) => $s['_id'], $statsByMenu)), ENT_QUOTES, 'UTF-8') ?>"
        data-commandes="<?= htmlspecialchars(json_encode(array_map(fn($s) => $s['nb_commandes'], $statsByMenu)), ENT_QUOTES, 'UTF-8') ?>"
        data-ca="<?= htmlspecialchars(json_encode(array_map(fn($s) => $s['ca_total'], $statsByMenu)), ENT_QUOTES, 'UTF-8') ?>">
    </canvas>
</div>

<!-- Graphique CA par menu -->
<div class="card p-4 mt-4">
    <h4 class="mb-3">💶 Chiffre d'affaires par menu</h4>
    <canvas id="graphCA" height="120"
        data-labels="<?= htmlspecialchars(json_encode(array_map(fn($s) => $s['_id'], $statsByMenu)), ENT_QUOTES, 'UTF-8') ?>"
        data-commandes="<?= htmlspecialchars(json_encode(array_map(fn($s) => $s['nb_commandes'], $statsByMenu)), ENT_QUOTES, 'UTF-8') ?>"
        data-ca="<?= htmlspecialchars(json_encode(array_map(fn($s) => $s['ca_total'], $statsByMenu)), ENT_QUOTES, 'UTF-8') ?>">
    </canvas>
</div>

            <!-- Tableau par menu -->
            <h4 class="mt-4">Par menu</h4>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background-color:#5DA99A; color:white;">
                        <tr>
                            <th>Menu</th>
                            <th>Nb commandes</th>
                            <th>Nb personnes</th>
                            <th>CA total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($statsByMenu as $stat) : ?>
                        <tr>
                            <td><?= htmlspecialchars($stat['_id']) ?></td>
                            <td><?= $stat['nb_commandes'] ?></td>
                            <td><?= $stat['nb_personnes'] ?></td>
                            <td><strong><?= number_format($stat['ca_total'], 2) ?> €</strong></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Tableau par mois -->
            <h4 class="mt-4">Par mois</h4>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead style="background-color:#5DA99A; color:white;">
                        <tr>
                            <th>Mois</th>
                            <th>Nb commandes</th>
                            <th>CA total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($statsByMois as $stat) : ?>
                        <tr>
                            <td><?= $stat['_id']['mois'] ?>/<?= $stat['_id']['annee'] ?></td>
                            <td><?= $stat['nb_commandes'] ?></td>
                            <td><strong><?= number_format($stat['ca_total'], 2) ?> €</strong></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js" defer></script>
<script src="/assets/js/stats.js" defer></script>   

<?php require_once 'views/layouts/footer.php'; ?>