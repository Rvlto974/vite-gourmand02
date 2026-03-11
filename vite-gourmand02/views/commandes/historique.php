<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5 mb-5">
    <h1 class="mb-4" style="color:#5DA99A">📋 Mes commandes</h1>

    <?php if (empty($commandes)) : ?>
        <div class="alert alert-info">
            Vous n'avez pas encore de commandes.
            <a href="/menus">Découvrir nos menus</a>
        </div>
    <?php else : ?>
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
                'annulee'         => 'bg-secondary',
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
                'annulee'         => 'Annulée',
                default           => $statut
            };

            $etapes = [
                'nouvelle'        => 0,
                'acceptee'        => 1,
                'en_preparation'  => 2,
                'en_livraison'    => 3,
                'livree'          => 4,
                'terminee'        => 5,
            ];
            $etapeActuelle = $etapes[$statut] ?? 0;
        ?>
        <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <strong>#<?= $commande['id'] ?></strong> — 
                    <?= htmlspecialchars($commande['menu_titre']) ?>
                </div>
                <span class="badge <?= $badge ?>"><?= $label ?></span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3 text-muted small">📅 Prestation : <strong><?= date('d/m/Y', strtotime($commande['date_prestation'])) ?></strong></div>
                    <div class="col-md-3 text-muted small">👥 Personnes : <strong><?= $commande['nb_personnes'] ?></strong></div>
                    <div class="col-md-3 text-muted small">💶 Prix : <strong><?= number_format($commande['prix_total'], 2) ?> €</strong></div>
                    <div class="col-md-3 text-muted small">📍 Adresse : <strong><?= htmlspecialchars($commande['adresse_livraison'] ?? '-') ?></strong></div>
                </div>

                <?php if ($statut === 'annulee') : ?>
                    <div class="alert alert-secondary mt-2 mb-2">❌ Cette commande a été annulée.</div>
                <?php else : ?>
                <!-- Timeline -->
                <div class="d-flex align-items-center justify-content-between mt-3 mb-2 flex-wrap gap-2">
                <?php
                $etapesLabels = [
                    ['icone' => '📋', 'label' => 'Nouvelle'],
                    ['icone' => '✅', 'label' => 'Acceptée'],
                    ['icone' => '👨‍🍳', 'label' => 'Préparation'],
                    ['icone' => '🚚', 'label' => 'Livraison'],
                    ['icone' => '📦', 'label' => 'Livrée'],
                    ['icone' => '🎉', 'label' => 'Terminée'],
                ];
                foreach ($etapesLabels as $i => $e) :
                    $fait = $i <= $etapeActuelle;
                    $actif = $i === $etapeActuelle;
                ?>
                    <div class="text-center" style="flex:1; min-width:60px;">
                        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-1"
                             style="width:40px; height:40px; font-size:1.2rem;
                                    background: <?= $fait ? '#5DA99A' : '#e9ecef' ?>;
                                    border: <?= $actif ? '3px solid #2E6B5E' : 'none' ?>;">
                            <?= $e['icone'] ?>
                        </div>
                        <small style="color: <?= $fait ? '#2E6B5E' : '#adb5bd' ?>; font-weight: <?= $actif ? 'bold' : 'normal' ?>;">
                            <?= $e['label'] ?>
                        </small>
                    </div>
                    <?php if ($i < count($etapesLabels) - 1) : ?>
                    <div style="flex:0.5; height:2px; background: <?= $i < $etapeActuelle ? '#5DA99A' : '#e9ecef' ?>; margin-bottom:20px;"></div>
                    <?php endif; ?>
                <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Actions -->
                <div class="mt-3 d-flex gap-2">
                    <?php if (in_array($statut, ['livree', 'terminee'])) : ?>
                        <a href="/avis/creer?commande_id=<?= $commande['id'] ?>"
                           class="btn btn-sm btn-warning">
                            ⭐ Laisser un avis
                        </a>
                    <?php elseif ($statut === 'nouvelle') : ?>
                        <a href="/commandes/modifier?id=<?= $commande['id'] ?>"
                           class="btn btn-sm btn-secondary">
                            ✏️ Modifier
                        </a>
                        <a href="/commandes/annuler?id=<?= $commande['id'] ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Annuler cette commande ?')">
                            ❌ Annuler
                        </a>
                    <?php elseif ($statut === 'acceptee') : ?>
                        <a href="/commandes/annuler?id=<?= $commande['id'] ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Annuler cette commande ?')">
                            ❌ Annuler
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="/menus" class="btn mt-3" style="background-color:#5DA99A; color:white; border-radius:8px;">
        ← Voir les menus
    </a>
</div>

<?php require_once 'views/layouts/footer.php'; ?>