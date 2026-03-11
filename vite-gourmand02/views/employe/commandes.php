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

            <!-- Filtres -->
            <div class="card p-3 mt-3 mb-3">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">🔍 Rechercher un client</label>
                        <input type="text" id="filtreClient" class="form-control form-control-sm" placeholder="Nom ou prénom...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">📋 Filtrer par statut</label>
                        <select id="filtreStatut" class="form-select form-select-sm">
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
                    <div class="col-md-2">
                        <button onclick="reinitialiserFiltres()" class="btn btn-sm btn-outline-secondary w-100">
                            ↺ Réinitialiser
                        </button>
                    </div>
                    <div class="col-md-2">
                        <span class="badge bg-secondary" id="compteurResultats"></span>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle" id="tableCommandes" aria-label="Toutes les commandes">
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
                    <tbody id="tbodyCommandes">
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
                        <tr <?= $statut === 'annulee' ? 'class="table-secondary"' : '' ?>
                            data-client="<?= strtolower($commande['prenom'] . ' ' . $commande['nom']) ?>"
                            data-statut="<?= $statut ?>">
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
                <p id="aucunResultat" class="text-muted text-center" style="display:none;">Aucune commande trouvée.</p>
            </div>
        </div>
    </div>
</div>

<script>
function filtrer() {
    const client = document.getElementById('filtreClient').value.toLowerCase();
    const statut = document.getElementById('filtreStatut').value;
    const rows = document.querySelectorAll('#tbodyCommandes tr');
    let count = 0;

    rows.forEach(row => {
        const clientData = row.dataset.client || '';
        const statutData = row.dataset.statut || '';
        const matchClient = clientData.includes(client);
        const matchStatut = statut === '' || statutData === statut;

        if (matchClient && matchStatut) {
            row.style.display = '';
            count++;
        } else {
            row.style.display = 'none';
        }
    });

    document.getElementById('compteurResultats').textContent = count + ' résultat(s)';
    document.getElementById('aucunResultat').style.display = count === 0 ? 'block' : 'none';
}

function reinitialiserFiltres() {
    document.getElementById('filtreClient').value = '';
    document.getElementById('filtreStatut').value = '';
    filtrer();
}

document.getElementById('filtreClient').addEventListener('input', filtrer);
document.getElementById('filtreStatut').addEventListener('change', filtrer);

// Initialiser le compteur
filtrer();
</script>

<?php require_once 'views/layouts/footer.php'; ?>