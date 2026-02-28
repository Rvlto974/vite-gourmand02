<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3">
                <h5>Espace admin</h5>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Tableau de bord</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/utilisateurs">Utilisateurs</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/admin/menus">Menus</a></li>
                    <li class="nav-item"><a class="nav-link" href="/employe/commandes">Commandes</a></li>
                    <li class="nav-item"><a class="nav-link" href="/auth/deconnexion">Déconnexion</a></li>
                </ul>
            </div>
        </div>

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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($menus as $menu) : ?>
                        <tr>
                            <td><?= htmlspecialchars($menu['titre']) ?></td>
                            <td><?= htmlspecialchars($menu['theme']) ?></td>
                            <td><?= htmlspecialchars($menu['regime']) ?></td>
                            <td><?= number_format($menu['prix_base'], 2) ?> €</td>
                            <td><?= $menu['stock'] ?></td>
                            <td>
                                <span class="badge bg-<?= $menu['actif'] ? 'success' : 'danger' ?>">
                                    <?= $menu['actif'] ? 'Actif' : 'Inactif' ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="/admin/toggleActifMenu">
                                    <input type="hidden" name="id" value="<?= $menu['id'] ?>">
                                    <button type="submit" 
                                            class="btn btn-<?= $menu['actif'] ? 'danger' : 'success' ?> btn-sm">
                                        <?= $menu['actif'] ? 'Désactiver' : 'Activer' ?>
                                    </button>
                                </form>
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