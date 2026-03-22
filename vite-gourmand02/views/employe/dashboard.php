<?php require_once 'views/layouts/header.php'; ?>

<style>
.stat-card {
    border: none;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: transform 0.2s ease;
}
.stat-card:hover { transform: translateY(-4px); }
.stat-number {
    font-size: 2.5rem;
    font-weight: 900;
    color: #5DA99A;
    line-height: 1;
}
.stat-label { color: #6c757d; font-size: 0.9rem; margin-top: 0.3rem; }
</style>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3">
                <h5>Espace employé</h5>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link active" href="/employe/dashboard">Tableau de bord</a></li>
                    <li class="nav-item"><a class="nav-link" href="/employe/commandes">Commandes</a></li>
                    <li class="nav-item"><a class="nav-link" href="/employe/menus">Menus</a></li>
                    <li class="nav-item"><a class="nav-link" href="/employe/horaires">Horaires</a></li>
                    <li class="nav-item"><a class="nav-link" href="/employe/avis">Avis clients</a></li>
                    <li class="nav-item"><a class="nav-link" href="/auth/deconnexion">Déconnexion</a></li>
                </ul>
            </div>
        </div>

        <div class="col-md-9">
            <h1 class="mb-4">Tableau de bord employé</h1>

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
                                <td><?= date('d/m/Y', strtotime($commande['date_prestation'])) ?></td>
                                <td><?= number_format($commande['prix_total'], 2) ?> €</td>
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

<script>
// Compteurs animés
function animerCompteur(el) {
    const target = parseInt(el.dataset.target);
    const duration = 1200;
    const step = Math.ceil(target / (duration / 16));
    let current = 0;

    const timer = setInterval(() => {
        current += step;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        el.textContent = current;
    }, 16);
}

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            animerCompteur(entry.target);
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.5 });

document.querySelectorAll('.stat-number[data-target]').forEach(el => {
    observer.observe(el);
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>