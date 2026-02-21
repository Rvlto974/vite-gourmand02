<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card p-3">
                <h5>Espace employé</h5>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="/employe/dashboard">Tableau de bord</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/employe/commandes">Commandes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/employe/avis">Avis clients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/auth/deconnexion">Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Contenu -->
        <div class="col-md-9">
            <h1>Tableau de bord employé</h1>

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
                        <p>Nouvelles commandes</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 text-center">
                        <h3><?= count($avis) ?></h3>
                        <p>Avis en attente</p>
                    </div>
                </div>
            </div>

            <!-- Nouvelles commandes -->
            <h4 class="mt-4">Nouvelles commandes</h4>
            <?php $nouvelles = array_filter($commandes, fn($c) => $c['statut'] === 'nouvelle'); ?>
            <?php if (empty($nouvelles)) : ?>
                <p>Aucune nouvelle commande.</p>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table" aria-label="Nouvelles commandes">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Menu</th>
                                <th>Date</th>
                                <th>Prix</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($nouvelles as $commande) : ?>
                            <tr>
                                <td><?= htmlspecialchars($commande['prenom'] . ' ' . $commande['nom']) ?></td>
                                <td><?= htmlspecialchars($commande['menu_titre']) ?></td>
                                <td><?= $commande['date_prestation'] ?></td>
                                <td><?= $commande['prix_total'] ?> €</td>
                                <td>
                                    <form method="POST" action="/employe/updateStatut">
                                        <input type="hidden" name="id" value="<?= $commande['id'] ?>">
                                        <select name="statut" class="form-select form-select-sm d-inline w-auto">
                                            <option value="nouvelle">Nouvelle</option>
                                            <option value="confirmee">Confirmée</option>
                                            <option value="en_preparation">En préparation</option>
                                            <option value="livree">Livrée</option>
                                            <option value="terminee">Terminée</option>
                                            <option value="annulee">Annulée</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            Mettre à jour
                                        </button>
                                    </form>
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