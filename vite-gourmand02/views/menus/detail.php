<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item"><a href="/menus">Nos Menus</a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($menu['titre']) ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Image et infos principales -->
        <div class="col-md-6">
            <img src="/assets/images/menu-default.jpg" 
                 alt="Photo du <?= htmlspecialchars($menu['titre']) ?>"
                 class="img-fluid rounded">
        </div>

        <div class="col-md-6">
            <h1><?= htmlspecialchars($menu['titre']) ?></h1>
            
            <span class="badge bg-primary"><?= htmlspecialchars($menu['theme']) ?></span>
            <span class="badge bg-secondary"><?= htmlspecialchars($menu['regime']) ?></span>

            <h2 class="mt-3 text-warning"><?= $menu['prix_base'] ?> €</h2>
            <p>Pour <?= $menu['nb_personnes_min'] ?> personnes minimum</p>
            
            <p><?= htmlspecialchars($menu['description']) ?></p>

            <!-- Calculateur de personnes -->
            <div class="card p-3 mt-3">
                <div class="row align-items-center">
                    <div class="col">
                        <label for="nb_personnes" class="form-label">
                            Nombre de personnes
                        </label>
                        <div class="input-group">
                            <button class="btn btn-outline-secondary" 
                                    type="button"
                                    id="btn-moins"
                                    aria-label="Diminuer le nombre de personnes">-</button>
                            <input type="number" 
                                   class="form-control text-center" 
                                   id="nb_personnes"
                                   value="<?= $menu['nb_personnes_min'] ?>"
                                   min="<?= $menu['nb_personnes_min'] ?>"
                                   aria-label="Nombre de personnes">
                            <button class="btn btn-outline-secondary" 
                                    type="button"
                                    id="btn-plus"
                                    aria-label="Augmenter le nombre de personnes">+</button>
                        </div>
                    </div>
                    <div class="col text-end">
                        <p class="mb-0">Prix estimé</p>
                        <h3 id="prix-estime"><?= $menu['prix_base'] ?> €</h3>
                    </div>
                </div>
                <a href="/commandes/nouveau?menu_id=<?= $menu['id'] ?>&nb_personnes=<?= $menu['nb_personnes_min'] ?>" 
                   class="btn btn-primary mt-3"
                   id="btn-commander"
                   aria-label="Commander le menu <?= htmlspecialchars($menu['titre']) ?>">
                    Commander ce menu
                </a>
            </div>
        </div>
    </div>
</div>

<script>
const prixBase = <?= $menu['prix_base'] ?>;
const nbMin = <?= $menu['nb_personnes_min'] ?>;

document.getElementById('btn-plus').addEventListener('click', function() {
    const input = document.getElementById('nb_personnes');
    input.value = parseInt(input.value) + 1;
    calculerPrix();
});

document.getElementById('btn-moins').addEventListener('click', function() {
    const input = document.getElementById('nb_personnes');
    if (parseInt(input.value) > nbMin) {
        input.value = parseInt(input.value) - 1;
        calculerPrix();
    }
});

function calculerPrix() {
    const nb = parseInt(document.getElementById('nb_personnes').value);
    const prix = (prixBase / nbMin * nb).toFixed(2);
    document.getElementById('prix-estime').textContent = prix + ' €';
    document.getElementById('btn-commander').href = 
        `/commandes/nouveau?menu_id=<?= $menu['id'] ?>&nb_personnes=${nb}`;
}
</script>

<?php require_once 'views/layouts/footer.php'; ?>