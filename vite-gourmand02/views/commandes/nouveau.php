<?php require_once 'views/layouts/header.php'; ?>

<style>
.commande-card { border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
.prix-box { background: #f8f9fa; border-radius: 8px; padding: 15px; }
.reduction-badge { background: #27ae60; color: white; border-radius: 20px; padding: 3px 10px; font-size: 0.8rem; }
</style>

<div class="container mt-5 mb-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item"><a href="/menus">Nos Menus</a></li>
            <li class="breadcrumb-item active">Commander</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Formulaire -->
        <div class="col-md-7">
            <div class="card commande-card p-4">
                <h1 class="h3 mb-4" style="color:#5DA99A">🛒 Passer une commande</h1>

                <form method="POST" action="/commandes/nouveau?menu_id=<?= $menu['id'] ?>" 
                      aria-label="Formulaire de commande" novalidate>

                    <div class="mb-3">
                        <label for="nb_personnes" class="form-label fw-semibold">
                            Nombre de personnes <span aria-hidden="true">*</span>
                        </label>
                        <input type="number" 
                               class="form-control" 
                               id="nb_personnes" 
                               name="nb_personnes"
                               value="<?= $menu['nb_personnes_min'] ?>"
                               min="<?= $menu['nb_personnes_min'] ?>"
                               aria-required="true"
                               required>
                        <small class="text-muted">Minimum <?= $menu['nb_personnes_min'] ?> personnes</small>
                    </div>

                    <div class="mb-3">
                        <label for="date_prestation" class="form-label fw-semibold">
                            Date de prestation <span aria-hidden="true">*</span>
                        </label>
                        <input type="date" 
                               class="form-control" 
                               id="date_prestation" 
                               name="date_prestation"
                               min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                               aria-required="true"
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="heure_livraison" class="form-label fw-semibold">
                            Heure de livraison <span aria-hidden="true">*</span>
                        </label>
                        <input type="time" 
                               class="form-control" 
                               id="heure_livraison" 
                               name="heure_livraison"
                               aria-required="true"
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="adresse_livraison" class="form-label fw-semibold">
                            Adresse de livraison <span aria-hidden="true">*</span>
                        </label>
                        <textarea class="form-control" 
                                  id="adresse_livraison" 
                                  name="adresse_livraison"
                                  rows="3"
                                  aria-required="true"
                                  required
                                  placeholder="Votre adresse complète..."></textarea>
                    </div>

                    <!-- Prix estimé -->
                    <div class="prix-box mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Prix estimé :</span>
                            <span class="fw-bold fs-5" style="color:#e67e22" id="prix-total"><?= number_format($menu['prix_base'], 2) ?> €</span>
                        </div>
                        <div id="reduction-msg" class="mt-1" style="display:none;">
                            <span class="reduction-badge">✓ Réduction 10% appliquée</span>
                        </div>
                        <small class="text-muted">Réduction de 10% pour <?= $menu['nb_personnes_min'] + 5 ?>+ personnes</small>
                    </div>

                    <button type="submit" class="btn w-100 py-2" 
                            style="background-color:#5DA99A; color:white; border-radius:8px; font-size:1.1rem;">
                        ✅ Confirmer la commande
                    </button>
                </form>
            </div>
        </div>

        <!-- Résumé du menu -->
        <div class="col-md-4 offset-md-1">
            <div class="card commande-card p-4">
                <img src="<?= !empty($menu['image']) ? htmlspecialchars($menu['image']) : '/assets/images/menu-default.jpg' ?>" 
                     alt="<?= htmlspecialchars($menu['titre']) ?>"
                     style="border-radius:8px; width:100%; height:180px; object-fit:cover;" class="mb-3">
                <h5 style="color:#5DA99A"><?= htmlspecialchars($menu['titre']) ?></h5>
                <p class="text-muted small"><?= htmlspecialchars(mb_substr($menu['description'], 0, 120)) ?>...</p>
                <hr>
                <p class="mb-1"><strong>Prix de base :</strong> <?= number_format($menu['prix_base'], 2) ?> €</p>
                <p class="mb-1"><strong>Minimum :</strong> <?= $menu['nb_personnes_min'] ?> personnes</p>
                <p class="mb-0"><strong>Thème :</strong> <?= ucfirst($menu['theme']) ?></p>
            </div>
        </div>
    </div>
</div>

<script>
const prixBase = <?= $menu['prix_base'] ?>;
const nbMin = <?= $menu['nb_personnes_min'] ?>;

document.getElementById('nb_personnes').addEventListener('input', function() {
    const nb = parseInt(this.value) || nbMin;
    let prix = prixBase;
    
    if (nb >= nbMin + 5) {
        prix = prix * 0.90;
        document.getElementById('reduction-msg').style.display = 'block';
    } else {
        document.getElementById('reduction-msg').style.display = 'none';
    }
    
    document.getElementById('prix-total').textContent = prix.toFixed(2) + ' €';
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>