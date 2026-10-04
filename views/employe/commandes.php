<?php
// Charge l’en-tête commun : ouverture du HTML, navigation et styles.
require_once 'views/layouts/header.php';
?>

<div class="container mt-5 mb-5">
    <div class="row">

        <!-- Menu de navigation de l’espace employé -->
        <div class="col-md-3">
            <div class="card p-3">
                <h5>Espace employé</h5>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="/employe/dashboard">Tableau de bord</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/employe/commandes">Commandes</a></li>
                    <li class="nav-item"><a class="nav-link" href="/employe/menus">Menus</a></li>
                    <li class="nav-item"><a class="nav-link" href="/employe/horaires">Horaires</a></li>
                    <li class="nav-item"><a class="nav-link" href="/employe/avis">Avis clients</a></li>
                    <li class="nav-item"><a class="nav-link" href="/auth/deconnexion">Déconnexion</a></li>
                </ul>
            </div>
        </div>

        <!-- Contenu principal : filtres et liste des commandes -->
        <div class="col-md-9">
            <h1>Gestion des commandes</h1>

            <!-- Filtres utilisés par le script employe-commandes.js -->
            <div class="row mb-3 mt-3">
                <div class="col-md-5">
                    <input
                        type="text"
                        id="filtre-client"
                        class="form-control"
                        placeholder="🔍 Rechercher un client..."
                    >
                </div>

                <div class="col-md-4">
                    <select id="filtre-statut" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="nouvelle">Nouvelle</option>
                        <option value="acceptee">Acceptée</option>
                        <option value="en_preparation">En préparation</option>
                        <option value="en_livraison">En livraison</option>
                        <option value="livree">Livrée</option>
                        <option value="terminee">Terminée</option>
                        <option value="attente_materiel">Attente matériel</option>
                        <option value="annulee">Annulée</option>
                    </select>
                </div>

                <!-- Le compteur est mis à jour lors du filtrage -->
                <div class="col-md-3">
                    <span class="badge bg-secondary mt-2" id="compteur-resultats">
                        <?= count($commandes) ?> commande(s)
                    </span>
                </div>
            </div>

            <!-- Tableau des commandes reçues -->
            <div class="table-responsive">
                <table class="table" aria-label="Liste des commandes" id="table-commandes">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Menu</th>
                            <th>Date</th>
                            <th>Prix</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($commandes as $commande) : ?>
                            <!-- Les attributs data-* permettent au JavaScript de filtrer les lignes -->
                            <tr
                                data-client="<?= strtolower(htmlspecialchars($commande['prenom'] . ' ' . $commande['nom'])) ?>"
                                data-statut="<?= $commande['statut'] ?>"
                            >
                                <td><?= htmlspecialchars($commande['prenom'] . ' ' . $commande['nom']) ?></td>
                                <td><?= htmlspecialchars($commande['menu_titre']) ?></td>
                                <td><?= date('d/m/Y', strtotime($commande['date_prestation'])) ?></td>
                                <td><?= number_format($commande['prix_total'], 2) ?> €</td>

                                <!-- Affichage du statut avec un badge coloré -->
                                <td>
                                    <?php
                                    $badges = [
                                        'nouvelle'         => 'primary',
                                        'acceptee'         => 'info',
                                        'en_preparation'   => 'warning',
                                        'en_livraison'     => 'warning',
                                        'livree'           => 'success',
                                        'terminee'         => 'success',
                                        'attente_materiel' => 'danger',
                                        'annulee'          => 'secondary',
                                    ];

                                    $badge = $badges[$commande['statut']] ?? 'secondary';
                                    ?>

                                    <span class="badge bg-<?= $badge ?>">
                                        <?= ucfirst(str_replace('_', ' ', $commande['statut'])) ?>
                                    </span>

                                    <!-- Pour une commande annulée, affiche le mode de contact et le motif en infobulle -->
                                    <?php if ($commande['statut'] === 'annulee' && !empty($commande['mode_contact'])) : ?>
                                        <span
                                            class="ms-1"
                                            data-bs-toggle="tooltip"
                                            title="<?= htmlspecialchars($commande['motif_annulation'] ?? '') ?>"
                                        >
                                            📞 <?= htmlspecialchars($commande['mode_contact']) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Les commandes terminées ou annulées ne proposent plus d’actions -->
                                <td>
                                    <?php if ($commande['statut'] !== 'annulee' && $commande['statut'] !== 'terminee') : ?>
                                        <div class="d-flex gap-1 flex-wrap">

                                            <!-- Formulaire de changement de statut -->
                                            <form method="POST" action="/employe/updateStatut" class="d-inline">
                                                <input type="hidden" name="id" value="<?= $commande['id'] ?>">

                                                <select name="statut" class="form-select form-select-sm d-inline w-auto">
                                                    <option value="nouvelle" <?= $commande['statut'] === 'nouvelle' ? 'selected' : '' ?>>Nouvelle</option>
                                                    <option value="acceptee" <?= $commande['statut'] === 'acceptee' ? 'selected' : '' ?>>Acceptée</option>
                                                    <option value="en_preparation" <?= $commande['statut'] === 'en_preparation' ? 'selected' : '' ?>>En préparation</option>
                                                    <option value="en_livraison" <?= $commande['statut'] === 'en_livraison' ? 'selected' : '' ?>>En livraison</option>
                                                    <option value="livree" <?= $commande['statut'] === 'livree' ? 'selected' : '' ?>>Livrée</option>
                                                    <option value="terminee" <?= $commande['statut'] === 'terminee' ? 'selected' : '' ?>>Terminée</option>
                                                    <option value="attente_materiel" <?= $commande['statut'] === 'attente_materiel' ? 'selected' : '' ?>>Attente matériel</option>
                                                </select>

                                                <button type="submit" class="btn btn-primary btn-sm">✔</button>
                                            </form>

                                            <!-- Ouvre la fenêtre modale d’annulation ; le JavaScript transmet l’ID de la commande -->
                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalAnnuler"
                                                data-id="<?= $commande['id'] ?>"
                                            >
                                                ❌ Annuler
                                            </button>
                                        </div>
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

<!-- Fenêtre modale contenant le formulaire d’annulation -->
<div
    class="modal fade"
    id="modalAnnuler"
    tabindex="-1"
    aria-labelledby="modalAnnulerLabel"
    aria-hidden="true"
>
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/employe/annulerCommande">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAnnulerLabel">❌ Annuler la commande</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <!-- L’ID est renseigné à l’ouverture de la modale par employe-commandes.js -->
                    <input type="hidden" name="id" id="modal-commande-id">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mode de contact *</label>
                        <select name="mode_contact" class="form-select" required>
                            <option value="">Choisir...</option>
                            <option value="Appel">📞 Appel téléphonique</option>
                            <option value="Email">📧 Email</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Motif d'annulation *</label>
                        <textarea
                            name="motif_annulation"
                            class="form-control"
                            rows="3"
                            placeholder="Expliquez le motif..."
                            required
                        ></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-danger">❌ Confirmer l'annulation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Charge le script externe de filtrage, de gestion de la modale et des infobulles -->
<script src="/assets/js/employe-commandes.js" defer></script>

<?php
// Charge le pied de page commun et les scripts partagés.
require_once 'views/layouts/footer.php';
?>