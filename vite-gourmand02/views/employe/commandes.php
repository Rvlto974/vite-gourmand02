<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3">
                <h5>Espace employé</h5>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="/employe/dashboard">Tableau de bord</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/employe/commandes">Commandes</a></li>
                    <li class="nav-item"><a class="nav-link" href="/employe/menus">Menus</a></li>
                    <li class="nav-item"><a class="nav-link" href="/employe/avis">Avis clients</a></li>
                    <li class="nav-item"><a class="nav-link" href="/auth/deconnexion">Déconnexion</a></li>
                </ul>
            </div>
        </div>

        <div class="col-md-9">
            <h1>Gestion des commandes</h1>

            <!-- Filtres -->
            <div class="row mb-3 mt-3">
                <div class="col-md-5">
                    <input type="text" id="filtre-client" class="form-control"
                           placeholder="🔍 Rechercher un client...">
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
                <div class="col-md-3">
                    <span class="badge bg-secondary mt-2" id="compteur-resultats">
                        <?= count($commandes) ?> commande(s)
                    </span>
                </div>
            </div>

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
                        <tr data-client="<?= strtolower(htmlspecialchars($commande['prenom'] . ' ' . $commande['nom'])) ?>"
                            data-statut="<?= $commande['statut'] ?>">
                            <td><?= htmlspecialchars($commande['prenom'] . ' ' . $commande['nom']) ?></td>
                            <td><?= htmlspecialchars($commande['menu_titre']) ?></td>
                            <td><?= date('d/m/Y', strtotime($commande['date_prestation'])) ?></td>
                            <td><?= number_format($commande['prix_total'], 2) ?> €</td>
                            <td>
                                <?php
                                $badges = [
                                    'nouvelle'        => 'primary',
                                    'acceptee'        => 'info',
                                    'en_preparation'  => 'warning',
                                    'en_livraison'    => 'warning',
                                    'livree'          => 'success',
                                    'terminee'        => 'success',
                                    'attente_materiel'=> 'danger',
                                    'annulee'         => 'secondary',
                                ];
                                $badge = $badges[$commande['statut']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $badge ?>">
                                    <?= ucfirst(str_replace('_', ' ', $commande['statut'])) ?>
                                </span>
                                <?php if ($commande['statut'] === 'annulee' && !empty($commande['mode_contact'])) : ?>
                                    <span class="ms-1" data-bs-toggle="tooltip"
                                          title="<?= htmlspecialchars($commande['motif_annulation'] ?? '') ?>">
                                        📞 <?= htmlspecialchars($commande['mode_contact']) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($commande['statut'] !== 'annulee' && $commande['statut'] !== 'terminee') : ?>
                                <div class="d-flex gap-1 flex-wrap">
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
                                    <button type="button" class="btn btn-danger btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#modalAnnuler"
                                            data-id="<?= $commande['id'] ?>">
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

<!-- Modal annulation -->
<div class="modal fade" id="modalAnnuler" tabindex="-1" aria-labelledby="modalAnnulerLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/employe/annulerCommande">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAnnulerLabel">❌ Annuler la commande</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
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
                        <textarea name="motif_annulation" class="form-control" rows="3"
                                  placeholder="Expliquez le motif..." required></textarea>
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

<script>
// Filtres
function filtrerCommandes() {
    const client = document.getElementById('filtre-client').value.toLowerCase();
    const statut = document.getElementById('filtre-statut').value;
    const rows = document.querySelectorAll('#table-commandes tbody tr');
    let count = 0;
    rows.forEach(row => {
        const matchClient = row.dataset.client.includes(client);
        const matchStatut = !statut || row.dataset.statut === statut;
        const visible = matchClient && matchStatut;
        row.style.display = visible ? '' : 'none';
        if (visible) count++;
    });
    document.getElementById('compteur-resultats').textContent = count + ' commande(s)';
}
document.getElementById('filtre-client').addEventListener('input', filtrerCommandes);
document.getElementById('filtre-statut').addEventListener('change', filtrerCommandes);

// Modal annulation
document.getElementById('modalAnnuler').addEventListener('show.bs.modal', function(e) {
    const id = e.relatedTarget.getAttribute('data-id');
    document.getElementById('modal-commande-id').value = id;
});

// Tooltips
const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
tooltips.forEach(t => new bootstrap.Tooltip(t));
</script>

<?php require_once 'views/layouts/footer.php'; ?>