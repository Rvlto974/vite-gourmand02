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
                        <a class="nav-link active" href="/client/commandes">Mes commandes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/auth/deconnexion">Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Contenu -->
        <div class="col-md-9">
            <h1>Mes commandes</h1>

            <?php if (empty($commandes)) : ?>
                <p>Aucune commande pour le moment. <a href="/menus">Découvrir nos menus</a></p>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table" aria-label="Historique de mes commandes">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th>Personnes</th>
                                <th>Date</th>
                                <th>Prix</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($commandes as $commande) : ?>
                            <tr>
                                <td><?= htmlspecialchars($commande['menu_titre']) ?></td>
                                <td><?= $commande['nb_personnes'] ?></td>
                                <td><?= $commande['date_prestation'] ?></td>
                                <td><?= $commande['prix_total'] ?> €</td>
                                <td>
                                    <span class="badge bg-<?= $commande['statut'] === 'nouvelle' ? 'warning' : ($commande['statut'] === 'terminee' ? 'success' : 'danger') ?>">
                                        <?= $commande['statut'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($commande['statut'] === 'nouvelle') : ?>
                                        <a href="/client/annuler?id=<?= $commande['id'] ?>" 
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Confirmer l\'annulation ?')"
                                            aria-label="Annuler la commande">
                                            Annuler
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>