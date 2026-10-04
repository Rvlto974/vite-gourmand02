<?php
// Charge l’en-tête commun du site.
require_once 'views/layouts/header.php';
?>

<div class="container mt-5 mb-5">
    <h1 class="mb-4" style="color:#5DA99A">📋 Mes commandes</h1>

    <?php if (empty($commandes)) : ?>
        <!-- Message affiché lorsqu’il n’y a encore aucune commande -->
        <div class="alert alert-info">
            Vous n'avez pas encore de commandes.
            <a href="/menus">Découvrir nos menus</a>
        </div>
    <?php else : ?>

        <!-- Affiche chaque commande du client -->
        <?php foreach ($commandes as $commande) : ?>
            <?php
            // Récupère le statut actuel de la commande.
            $statut = $commande['statut'];

            // Associe une couleur de badge à chaque statut.
            $badge = match ($statut) {
                'nouvelle'         => 'bg-warning text-dark',
                'acceptee'         => 'bg-info text-dark',
                'en_preparation'   => 'bg-primary',
                'en_livraison'     => 'bg-primary',
                'livree'           => 'bg-success',
                'terminee'         => 'bg-success',
                'attente_materiel' => 'bg-danger',
                'annulee'          => 'bg-secondary',
                default            => 'bg-secondary'
            };

            // Associe un libellé lisible à chaque statut.
            $label = match ($statut) {
                'nouvelle'         => 'Nouvelle',
                'acceptee'         => 'Acceptée',
                'en_preparation'   => 'En préparation',
                'en_livraison'     => 'En livraison',
                'livree'           => 'Livrée',
                'terminee'         => 'Terminée',
                'attente_materiel' => 'Attente matériel',
                'annulee'          => 'Annulée',
                default            => $statut
            };

            // Définit l’ordre des étapes dans la timeline.
            $etapes = [
                'nouvelle'       => 0,
                'acceptee'       => 1,
                'en_preparation' => 2,
                'en_livraison'   => 3,
                'livree'         => 4,
                'terminee'       => 5,
            ];

            // Détermine l’étape correspondant au statut actuel.
            $etapeActuelle = $etapes[$statut] ?? 0;

            // Récupère l’historique des statuts de cette commande.
            $historique = $historiquesStatuts[$commande['id']] ?? [];

            // Indexe les dates de changement par statut pour les retrouver facilement.
            $dateParStatut = [];
            foreach ($historique as $h) {
                $dateParStatut[$h['statut']] = $h['created_at'];
            }

            // Définit les étapes qui seront affichées dans la timeline.
            $etapesLabels = [
                ['icone' => '📋', 'label' => 'Nouvelle',     'statut' => 'nouvelle'],
                ['icone' => '✅', 'label' => 'Acceptée',     'statut' => 'acceptee'],
                ['icone' => '👨‍🍳', 'label' => 'Préparation', 'statut' => 'en_preparation'],
                ['icone' => '🚚', 'label' => 'Livraison',    'statut' => 'en_livraison'],
                ['icone' => '📦', 'label' => 'Livrée',       'statut' => 'livree'],
                ['icone' => '🎉', 'label' => 'Terminée',     'statut' => 'terminee'],
            ];
            ?>

            <!-- Carte récapitulative de la commande -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <strong>#<?= $commande['id'] ?></strong> —
                        <?= htmlspecialchars($commande['menu_titre']) ?>
                    </div>

                    <!-- Badge indiquant le statut actuel -->
                    <span class="badge <?= $badge ?>"><?= htmlspecialchars($label) ?></span>
                </div>

                <div class="card-body">
                    <!-- Informations principales de la commande -->
                    <div class="row mb-3">
                        <div class="col-md-3 text-muted small">
                            📅 Prestation :
                            <strong><?= date('d/m/Y', strtotime($commande['date_prestation'])) ?></strong>
                        </div>

                        <div class="col-md-3 text-muted small">
                            👥 Personnes :
                            <strong><?= $commande['nb_personnes'] ?></strong>
                        </div>

                        <div class="col-md-3 text-muted small">
                            💶 Prix :
                            <strong><?= number_format($commande['prix_total'], 2) ?> €</strong>
                        </div>

                        <div class="col-md-3 text-muted small">
                            📍 Adresse :
                            <strong><?= htmlspecialchars($commande['adresse_livraison'] ?? '-') ?></strong>
                        </div>
                    </div>

                    <?php if ($statut === 'annulee') : ?>
                        <!-- Affiche un message particulier pour une commande annulée -->
                        <div class="alert alert-secondary mt-2 mb-2">
                            ❌ Cette commande a été annulée.
                        </div>

                        <!-- Affiche les dates de l’historique de la commande annulée -->
                        <?php if (!empty($historique)) : ?>
                            <div class="mt-2">
                                <?php foreach ($historique as $h) : ?>
                                    <small class="text-muted d-block">
                                        🕐 <?= date('d/m/Y H:i', strtotime($h['created_at'])) ?>
                                        — <?= htmlspecialchars($h['statut']) ?>
                                    </small>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                    <?php else : ?>
                        <!-- Timeline de progression de la commande -->
                        <div class="d-flex align-items-center justify-content-between mt-3 mb-2 flex-wrap gap-2">
                            <?php foreach ($etapesLabels as $i => $e) :
                                // Indique si l’étape est terminée ou actuellement active.
                                $fait = $i <= $etapeActuelle;
                                $actif = $i === $etapeActuelle;

                                // Récupère la date associée à cette étape, si elle existe.
                                $dateEtape = $dateParStatut[$e['statut']] ?? null;
                            ?>
                                <div class="text-center" style="flex:1; min-width:60px;">
                                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-1"
                                         style="width:40px; height:40px; font-size:1.2rem;
                                                background: <?= $fait ? '#5DA99A' : '#e9ecef' ?>;
                                                border: <?= $actif ? '3px solid #2E6B5E' : 'none' ?>;">
                                        <?= $e['icone'] ?>
                                    </div>

                                    <small style="color: <?= $fait ? '#2E6B5E' : '#adb5bd' ?>;
                                                  font-weight: <?= $actif ? 'bold' : 'normal' ?>;">
                                        <?= htmlspecialchars($e['label']) ?>
                                    </small>

                                    <!-- Affiche la date de l’étape si elle est disponible -->
                                    <?php if ($dateEtape) : ?>
                                        <small class="d-block text-muted" style="font-size:0.65rem;">
                                            <?= date('d/m H:i', strtotime($dateEtape)) ?>
                                        </small>
                                    <?php endif; ?>
                                </div>

                                <!-- Trait reliant les étapes de la timeline -->
                                <?php if ($i < count($etapesLabels) - 1) : ?>
                                    <div style="flex:0.5; height:2px;
                                                background: <?= $i < $etapeActuelle ? '#5DA99A' : '#e9ecef' ?>;
                                                margin-bottom:20px;">
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Actions disponibles selon le statut de la commande -->
                    <div class="mt-3 d-flex gap-2">
                        <?php if (in_array($statut, ['livree', 'terminee'], true)) : ?>
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
                               data-confirm="Annuler cette commande ?">
                                ❌ Annuler
                            </a>

                        <?php elseif ($statut === 'acceptee') : ?>
                            <a href="/commandes/annuler?id=<?= $commande['id'] ?>"
                               class="btn btn-sm btn-danger"
                               data-confirm="Annuler cette commande ?">
                                ❌ Annuler
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Lien pour revenir à la liste des menus -->
    <a href="/menus" class="btn mt-3" style="background-color:#5DA99A; color:white; border-radius:8px;">
        ← Voir les menus
    </a>
</div>

<?php
// Charge le pied de page commun du site.
require_once 'views/layouts/footer.php';
?>