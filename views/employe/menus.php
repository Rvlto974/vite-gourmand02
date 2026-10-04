<?php
// Charge l’en-tête commun du site.
require_once 'views/layouts/header.php';
?>

<div class="container mt-5 mb-5">
    <div class="row">
        <!-- Menu de navigation de l’espace employé -->
        <div class="col-md-3">
            <div class="card p-3">
                <h5>Espace employé</h5>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="/employe/dashboard">Tableau de bord</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/employe/commandes">Commandes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/employe/menus">Menus</a>
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

        <!-- Tableau de gestion des menus -->
        <div class="col-md-9">
            <h1>Gestion des menus</h1>

            <div class="table-responsive mt-3">
                <table class="table" aria-label="Liste des menus">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Thème</th>
                            <th>Prix</th>
                            <th>Stock</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <!-- Affiche une ligne pour chaque menu reçu -->
                        <?php foreach ($menus as $menu) : ?>
                            <tr>
                                <!-- htmlspecialchars protège l’affichage contre l’injection HTML -->
                                <td><?= htmlspecialchars($menu['titre']) ?></td>
                                <td><?= htmlspecialchars($menu['theme']) ?></td>

                                <!-- Formate le prix avec deux décimales -->
                                <td><?= number_format($menu['prix_base'], 2) ?> €</td>

                                <td><?= $menu['stock'] ?></td>

                                <!-- Adapte la couleur et le texte du badge au statut du menu -->
                                <td>
                                    <span class="badge bg-<?= $menu['actif'] ? 'success' : 'danger' ?>">
                                        <?= $menu['actif'] ? 'Actif' : 'Inactif' ?>
                                    </span>
                                </td>

                                <!-- Actions disponibles pour ce menu -->
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        <a href="/employe/modifierMenu?id=<?= $menu['id'] ?>"
                                           class="btn btn-warning btn-sm">
                                            ✏️ Modifier
                                        </a>

                                        <!-- Le formulaire POST demande confirmation avant la suppression -->
                                        <form method="POST"
                                              action="/employe/supprimerMenu"
                                              class="d-inline"
                                              data-confirm="Supprimer ce menu ?">
                                            <input type="hidden" name="id" value="<?= $menu['id'] ?>">

                                            <button type="submit" class="btn btn-danger btn-sm">
                                                🗑️ Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
// Charge le pied de page commun, qui contient notamment les scripts du site.
require_once 'views/layouts/footer.php';
?>