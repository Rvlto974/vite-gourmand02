<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card p-3">
                <h5>Espace admin</h5>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/dashboard">Tableau de bord</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/utilisateurs">Utilisateurs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/admin/menus">Menus</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/employe/commandes">Commandes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/auth/deconnexion">Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Contenu -->
        <div class="col-md-9">
            <h1>Gestion des menus</h1>

            <div class="table-responsive mt-3">
                <table class="table" aria-label="Liste des menus">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Thème</th>
                            <th>Régime</th>
                            <th>Prix</th>
                            <th>Stock</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($menus as $menu) : ?>
                        <tr>
                            <td><?= htmlspecialchars($menu['titre']) ?></td>
                            <td><?= htmlspecialchars($menu['theme']) ?></td>
                            <td><?= htmlspecialchars($menu['regime']) ?></td>
                            <td><?= $menu['prix_base'] ?> €</td>
                            <td><?= $menu['stock'] ?></td>
                            <td>
                                <span class="badge bg-<?= $menu['actif'] ? 'success' : 'danger' ?>">
                                    <?= $menu['actif'] ? 'Actif' : 'Inactif' ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>