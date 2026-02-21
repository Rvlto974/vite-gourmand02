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
                        <a class="nav-link active" href="/employe/commandes">Commandes</a>
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
            <h1>Gestion des commandes</h1>

            <div class="table-responsive mt-3">
                <table class="table" aria-label="Toutes les commandes">
                    <thead>
                        <tr>
                            <th>Client</th>
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
                            <td><?= htmlspecialchars($commande['prenom'] . ' ' . $commande['nom']) ?></td>
                            <td><?= htmlspecialchars($commande['menu_titre']) ?></td>
                            <td><?= $commande['nb_personnes'] ?></td>
                            <td><?= $commande['date_prestation'] ?></td>
                            <td><?= $commande['prix_total'] ?> €</td>
                            <td>
                                <span class="badge bg-<?= $commande['statut'] === 'nouvelle' ? 'warning' : ($commande['statut'] === 'terminee' ? 'success' : 'primary') ?>">
                                    <?= $commande['statut'] ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="/employe/updateStatut">
                                    <input type="hidden" name="id" value="<?= $commande['id'] ?>">
                                    <select name="statut" class="form-select form-select-sm d-inline w-auto">
                                        <option value="nouvelle" <?= $commande['statut'] === 'nouvelle' ? 'selected' : '' ?>>Nouvelle</option>
                                        <option value="confirmee" <?= $commande['statut'] === 'confirmee' ? 'selected' : '' ?>>Confirmée</option>
                                        <option value="en_preparation" <?= $commande['statut'] === 'en_preparation' ? 'selected' : '' ?>>En préparation</option>
                                        <option value="livree" <?= $commande['statut'] === 'livree' ? 'selected' : '' ?>>Livrée</option>
                                        <option value="terminee" <?= $commande['statut'] === 'terminee' ? 'selected' : '' ?>>Terminée</option>
                                        <option value="annulee" <?= $commande['statut'] === 'annulee' ? 'selected' : '' ?>>Annulée</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        OK
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