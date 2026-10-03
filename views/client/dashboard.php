<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card p-3">
                <h5>Mon espace</h5>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="/client/dashboard">Tableau de bord</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/client/commandes">Mes commandes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/auth/deconnexion">Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Contenu -->
        <div class="col-md-9">
            <h1>Tableau de bord</h1>

            <div class="row mt-3">
                <div class="col-md-4">
                    <div class="card p-3 text-center">
                        <h3><?= count($commandes) ?></h3>
                        <p>Commandes totales</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 text-center">
                        <h3><?= count(array_filter($commandes, fn($c) => $c['statut'] === 'nouvelle')) ?></h3>
                        <p>En cours</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 text-center">
                        <h3><?= count(array_filter($commandes, fn($c) => $c['statut'] === 'terminee')) ?></h3>
                        <p>Terminées</p>
                    </div>
                </div>
            </div>

            <!-- Dernières commandes -->
            <h4 class="mt-4">Dernières commandes</h4>
            <?php if (empty($commandes)) : ?>
                <p>Aucune commande pour le moment. <a href="/menus">Découvrir nos menus</a></p>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table" aria-label="Mes dernières commandes">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th>Date</th>
                                <th>Prix</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($commandes, 0, 3) as $commande) : ?>
                            <tr>
                                <td><?= htmlspecialchars($commande['menu_titre']) ?></td>
                                <td><?= $commande['date_prestation'] ?></td>
                                <td><?= $commande['prix_total'] ?> €</td>
                                <td>
                                    <span class="badge bg-<?= $commande['statut'] === 'nouvelle' ? 'warning' : ($commande['statut'] === 'terminee' ? 'success' : 'danger') ?>">
                                        <?= $commande['statut'] ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <a href="/client/commandes" class="btn btn-primary">Voir toutes mes commandes</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>