<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3">
                <h5>Espace employé</h5>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="/employe/dashboard">Tableau de bord</a></li>
                    <li class="nav-item"><a class="nav-link" href="/employe/commandes">Commandes</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/employe/menus">Menus</a></li>
                    <li class="nav-item"><a class="nav-link" href="/employe/horaires">Horaires</a></li>
                    <li class="nav-item"><a class="nav-link" href="/employe/avis">Avis clients</a></li>
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
                            <th>Prix</th>
                            <th>Stock</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($menus as $menu) : ?>
                        <tr>
                            <td><?= htmlspecialchars($menu['titre']) ?></td>
                            <td><?= htmlspecialchars($menu['theme']) ?></td>
                            <td><?= number_format($menu['prix_base'], 2) ?> €</td>
                            <td><?= $menu['stock'] ?></td>
                            <td>
                                <span class="badge bg-<?= $menu['actif'] ? 'success' : 'danger' ?>">
                                    <?= $menu['actif'] ? 'Actif' : 'Inactif' ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <a href="/employe/modifierMenu?id=<?= $menu['id'] ?>"
                                       class="btn btn-warning btn-sm">✏️ Modifier</a>
                                    <form method="POST" action="/employe/supprimerMenu" class="d-inline"
                                          onsubmit="return confirm('Supprimer ce menu ?')">
                                        <input type="hidden" name="id" value="<?= $menu['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">🗑️ Supprimer</button>
                                    </form>
                                </div>
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