<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5 mb-5">
    <h1 class="mb-4" style="color:#5DA99A">📋 Mes commandes</h1>

    <?php if (empty($commandes)) : ?>
        <div class="alert alert-info">
            Vous n'avez pas encore de commandes. 
            <a href="/menus">Découvrir nos menus</a>
        </div>
    <?php else : ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead style="background-color:#5DA99A; color:white;">
                    <tr>
                        <th>#</th>
                        <th>Menu</th>
                        <th>Date prestation</th>
                        <th>Personnes</th>
                        <th>Prix total</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commandes as $commande) : ?>
                    <?php
                        $statut = $commande['statut'];
                        $badge = match($statut) {
                            'nouvelle'        => 'bg-warning text-dark',
                            'acceptee'        => 'bg-info text-dark',
                            'en_preparation'  => 'bg-primary',
                            'en_livraison'    => 'bg-primary',
                            'livree'          => 'bg-success',
                            'terminee'        => 'bg-success',
                            'attente_materiel'=> 'bg-danger',
                            default           => 'bg-secondary'
                        };
                        $label = match($statut) {
                            'nouvelle'        => 'Nouvelle',
                            'acceptee'        => 'Acceptée',
                            'en_preparation'  => 'En préparation',
                            'en_livraison'    => 'En livraison',
                            'livree'          => 'Livrée',
                            'terminee'        => 'Terminée',
                            'attente_materiel'=> 'Attente matériel',
                            default           => $statut
                        };
                    ?>
                    <tr>
                        <td>#<?= $commande['id'] ?></td>
                        <td><?= htmlspecialchars($commande['menu_titre']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($commande['date_prestation'])) ?></td>
                        <td><?= $commande['nb_personnes'] ?> pers.</td>
                        <td><strong><?= number_format($commande['prix_total'], 2) ?> €</strong></td>
                        <td><span class="badge <?= $badge ?>"><?= $label ?></span></td>
                        <td>
                            <?php if (in_array($statut, ['livree', 'terminee'])) : ?>
                                <a href="/avis/creer?commande_id=<?= $commande['id'] ?>" 
                                   class="btn btn-sm btn-warning">
                                    ⭐ Avis
                                </a>
                            <?php elseif (in_array($statut, ['nouvelle', 'acceptee'])) : ?>
                                <a href="/commandes/annuler?id=<?= $commande['id'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Annuler cette commande ?')">
                                    Annuler
                                </a>
                            <?php else : ?>
                                <span class="text-muted small">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <a href="/menus" class="btn mt-3" style="background-color:#5DA99A; color:white; border-radius:8px;">
        ← Voir les menus
    </a>
</div>

<?php require_once 'views/layouts/footer.php'; ?>