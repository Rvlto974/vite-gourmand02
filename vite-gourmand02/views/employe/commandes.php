<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3">
                <h5>Espace employé</h5>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="/employe/dashboard">Tableau de bord</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/employe/commandes">Commandes</a></li>
                    <li class="nav-item"><a class="nav-link" href="/employe/avis">Avis clients</a></li>
                    <li class="nav-item"><a class="nav-link" href="/auth/deconnexion">Déconnexion</a></li>
                </ul>
            </div>
        </div>

        <div class="col-md-9">
            <h1>Gestion des commandes</h1>

            <div class="table-responsive mt-3">
                <table class="table align-middle" aria-label="Toutes les commandes">
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
                        <?php
                            $statut = $commande['statut'];
                            $badge = match($statut) {
                                'nouvelle'        => 'warning text-dark',
                                'acceptee'        => 'info text-dark',
                                'en_preparation'  => 'primary',
                                'en_livraison'    => 'primary',
                                'livree'          => 'success',
                                'terminee'        => 'success',
                                'attente_materiel'=> 'danger',
                                'annulee'         => 'secondary',
                                default           => 'secondary'
                            };
                            $label = match($statut) {
                                'nouvelle'        => 'Nouvelle',
                                'acceptee'        => 'Acceptée',
                                'en_preparation'  => 'En préparation',
                                'en_livraison'    => 'En livraison',
                                'livree'          => 'Livrée',
                                'terminee'        => 'Terminée',
                                'attente_materiel'=> 'Attente matériel',
                                'annulee'         => 'Annulée',
                                default           => $statut
                            };
                        ?>
                        <tr <?= $statut === 'annulee' ? 'class="table-secondary"' : '' ?>>
                            <td><?= htmlspecialchars($commande['prenom'] . ' ' . $commande['nom']) ?></td>
                            <td><?= htmlspecialchars($commande['menu_titre']) ?></td>
                            <td><?= $commande['nb_personnes'] ?></td>
                            <td><?= date('d/m/Y', strtotime($commande['date_prestation'])) ?></td>
                            <td><?= number_format($commande['prix_total'], 2) ?> €</td>
                            <td><span class="badge bg-<?= $badge ?>"><?= $label ?></span></td>
                            <td>
                                <?php if ($statut === 'annulee') : ?>
                                    <span class="text-muted small">❌ Annulée par le client</span>
                                <?php else : ?>
                                <form method="POST" action="/employe/updateStatut">
                                    <input type="hidden" name="id" value="<?= $commande['id'] ?>">
                                    <select name="statut" class="form-select form-select-sm d-inline w-auto">
                                        <option value="nouvelle" <?= $statut === 'nouvelle' ? 'selected' : '' ?>>Nouvelle</option>
                                        <option value="acceptee" <?= $statut === 'acceptee' ? 'selected' : '' ?>>Acceptée</option>
                                        <option value="en_preparation" <?= $statut === 'en_preparation' ? 'selected' : '' ?>>En préparation</option>
                                        <option value="en_livraison" <?= $statut === 'en_livraison' ? 'selected' : '' ?>>En livraison</option>
                                        <option value="livree" <?= $statut === 'livree' ? 'selected' : '' ?>>Livrée</option>
                                        <option value="terminee" <?= $statut === 'terminee' ? 'selected' : '' ?>>Terminée</option>
                                        <option value="attente_materiel" <?= $statut === 'attente_materiel' ? 'selected' : '' ?>>Attente matériel</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm" style="background:#5DA99A; color:white;">OK</button>
                                </form>
                                <?php endif; ?>
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