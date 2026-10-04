<?php
// Charge l’en-tête commun du site.
require_once 'views/layouts/header.php';
?>

<style>
/* Mise en forme des cartes de statistiques. */
.stat-card {
    border: none;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: transform 0.2s ease;
}

/* Soulève légèrement la carte au survol. */
.stat-card:hover {
    transform: translateY(-4px);
}

/* Style des valeurs affichées dans les compteurs. */
.stat-number {
    font-size: 2.5rem;
    font-weight: 900;
    color: #5DA99A;
    line-height: 1;
}

/* Style des intitulés des statistiques. */
.stat-label {
    color: #6c757d;
    font-size: 0.9rem;
    margin-top: 0.3rem;
}
</style>

<div class="container mt-5">
    <div class="row">

        <!-- Menu de navigation de l’espace employé. -->
        <div class="col-md-3">
            <div class="card p-3">
                <h5>Espace employé</h5>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="/employe/dashboard">Tableau de bord</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/employe/commandes">Commandes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/employe/menus">Menus</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/employe/horaires">Horaires</a>
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

        <!-- Contenu principal du tableau de bord. -->
        <div class="col-md-9">
            <h1 class="mb-4">Tableau de bord employé</h1>

            <!-- Statistiques générales : les valeurs cibles alimentent les compteurs animés. -->
            <div class="row mt-3">
                <div class="col-md-4 mb-3">
                    <div class="stat-card" style="border-left: 4px solid #5DA99A;">
                        <div class="stat-number" data-target="<?= count($commandes) ?>">0</div>
                        <div class="stat-label">📦 Commandes totales</div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="stat-card" style="border-left: 4px solid #e67e22;">
                        <div class="stat-number" style="color:#e67e22"
                             data-target="<?= count(array_filter($commandes, fn($c) => $c['statut'] === 'nouvelle')) ?>">0</div>
                        <div class="stat-label">🆕 Nouvelles commandes</div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="stat-card" style="border-left: 4px solid #2E6B5E;">
                        <div class="stat-number" style="color:#2E6B5E"
                             data-target="<?= count($avis) ?>">0</div>
                        <div class="stat-label">💬 Avis en attente</div>
                    </div>
                </div>
            </div>

            <!-- Liste des commandes ayant le statut « nouvelle ». -->
            <h4 class="mt-4">Nouvelles commandes</h4>

            <?php
            // Filtre les commandes pour ne conserver que les nouvelles.
            $nouvelles = array_filter(
                $commandes,
                fn($c) => $c['statut'] === 'nouvelle'
            );
            ?>

            <?php if (empty($nouvelles)) : ?>
                <!-- Message affiché s’il n’y a aucune commande à traiter. -->
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
                                    <!-- Échappe les données textuelles avant leur affichage. -->
                                    <td><?= htmlspecialchars($commande['prenom'] . ' ' . $commande['nom']) ?></td>
                                    <td><?= htmlspecialchars($commande['menu_titre']) ?></td>

                                    <!-- Affiche la date de prestation au format français. -->
                                    <td><?= date('d/m/Y', strtotime($commande['date_prestation'])) ?></td>

                                    <!-- Affiche le prix total avec deux décimales. -->
                                    <td><?= number_format($commande['prix_total'], 2) ?> €</td>

                                    <!-- Formulaire permettant de modifier le statut de la commande. -->
                                    <td>
                                        <form method="POST" action="/employe/updateStatut">
                                            <input type="hidden" name="id" value="<?= $commande['id'] ?>">

                                            <select name="statut" class="form-select form-select-sm d-inline w-auto">
                                                <option value="nouvelle">Nouvelle</option>
                                                <option value="acceptee">Acceptée</option>
                                                <option value="en_preparation">En préparation</option>
                                                <option value="en_livraison">En livraison</option>
                                                <option value="livree">Livrée</option>
                                                <option value="terminee">Terminée</option>
                                                <option value="attente_materiel">Attente matériel</option>
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

<!-- Charge le script externe qui anime les compteurs du tableau de bord. -->
<script src="/assets/js/employe-dashboard.js" defer></script>

<?php
// Charge le pied de page commun du site.
require_once 'views/layouts/footer.php';
?>