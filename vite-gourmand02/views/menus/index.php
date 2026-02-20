<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <!-- Filtres -->
        <div class="col-md-3">
            <form id="filtres-form" aria-label="Filtres des menus">
                <h5>Filtres</h5>

                <div class="mb-3">
                    <label for="theme" class="form-label">Thème</label>
                    <select class="form-select" id="theme" name="theme">
                        <option value="">Tous les thèmes</option>
                        <option value="Noel">Noël</option>
                        <option value="Paques">Pâques</option>
                        <option value="classique">Classique</option>
                        <option value="evenement">Événement</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="regime" class="form-label">Régime</label>
                    <select class="form-select" id="regime" name="regime">
                        <option value="">Tous les régimes</option>
                        <option value="classique">Classique</option>
                        <option value="vegetarien">Végétarien</option>
                        <option value="vegan">Vegan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="prix_max" class="form-label">Prix maximum</label>
                    <input type="number" 
                            class="form-control" 
                            id="prix_max" 
                            name="prix_max"
                            placeholder="Ex: 300"
                            aria-label="Prix maximum en euros">
                </div>

                <a href="/menus" class="btn btn-outline-secondary w-100 mt-2">
                    Réinitialiser
                </a>
            </form>
        </div>

        <!-- Liste des menus -->
        <div class="col-md-9">
            <h1>Nos Menus</h1>
            <div class="row" id="liste-menus">
                <?php if (empty($menus)) : ?>
                    <p>Aucun menu trouvé.</p>
                <?php else : ?>
                    <?php foreach ($menus as $menu) : ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?= htmlspecialchars($menu['titre']) ?>
                                </h5>
                                <p class="card-text">
                                    <?= htmlspecialchars($menu['description']) ?>
                                </p>
                                <p><strong><?= $menu['prix_base'] ?> €</strong></p>
                                <p>Min. <?= $menu['nb_personnes_min'] ?> personnes</p>
                                <a href="/menus/detail?id=<?= $menu['id'] ?>" 
                                    class="btn btn-primary"
                                    aria-label="Voir le menu <?= htmlspecialchars($menu['titre']) ?>">
                                    Voir le menu
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>