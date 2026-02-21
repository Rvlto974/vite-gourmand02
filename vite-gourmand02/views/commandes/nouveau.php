<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5">
    <h1>Passer une commande</h1>

    <div class="row">
        <div class="col-md-7">
            <div class="card p-4">
                <h5><?= htmlspecialchars($menu['titre']) ?></h5>
                <p><?= htmlspecialchars($menu['description']) ?></p>
                <p><strong>Prix de base : <?= $menu['prix_base'] ?> €</strong></p>

                <form method="POST" action="/commandes/nouveau?menu_id=<?= $menu['id'] ?>" 
                      aria-label="Formulaire de commande" novalidate>

                    <div class="mb-3">
                        <label for="nb_personnes" class="form-label">
                            Nombre de personnes <span aria-hidden="true">*</span>
                        </label>
                        <input type="number" 
                               class="form-control" 
                               id="nb_personnes" 
                               name="nb_personnes"
                               value="<?= $nb_personnes ?>"
                               min="<?= $menu['nb_personnes_min'] ?>"
                               aria-required="true"
                               required>
                        <small class="text-muted">Minimum <?= $menu['nb_personnes_min'] ?> personnes</small>
                    </div>

                    <div class="mb-3">
                        <label for="date_prestation" class="form-label">
                            Date de prestation <span aria-hidden="true">*</span>
                        </label>
                        <input type="datetime-local" 
                               class="form-control" 
                               id="date_prestation" 
                               name="date_prestation"
                               aria-required="true"
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="adresse_livraison" class="form-label">
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

                    <div class="card p-3 mb-3 bg-light">
                        <p class="mb-0">Prix estimé : <strong id="prix-total"><?= $menu['prix_base'] ?> €</strong></p>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Confirmer la commande
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const prixBase = <?= $menu['prix_base'] ?>;
const nbMin = <?= $menu['nb_personnes_min'] ?>;

document.getElementById('nb_personnes').addEventListener('input', function() {
    const nb = parseInt(this.value) || nbMin;
    const prix = (prixBase / nbMin * nb).toFixed(2);
    document.getElementById('prix-total').textContent = prix + ' €';
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>