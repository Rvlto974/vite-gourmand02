<?php
// Charge l’en-tête commun du site.
require_once 'views/layouts/header.php';
?>

<div class="container mt-5 mb-5">
    <div class="row">

        <!-- Barre de navigation de l’espace administrateur. -->
        <div class="col-md-3">
            <div class="card p-3">
                <h5>Espace admin</h5>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/dashboard">Tableau de bord</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/utilisateurs">Utilisateurs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/menus">Menus</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/employe/commandes">Commandes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/avis">Avis clients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/stats">Statistiques</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/auth/deconnexion">Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Contenu principal du tableau de bord. -->
        <div class="col-md-9">
            <h1>Tableau de bord admin</h1>

            <!-- Cartes récapitulatives avec des liens vers les sections concernées. -->
            <div class="row mt-3">
                <div class="col-md-3">
                    <a href="/admin/utilisateurs" class="card p-3 text-center text-decoration-none d-block">
                        <h3><?= count($utilisateurs) ?></h3>
                        <p class="text-muted mb-0">Utilisateurs</p>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="/employe/commandes" class="card p-3 text-center text-decoration-none d-block">
                        <h3><?= count($commandes) ?></h3>
                        <p class="text-muted mb-0">Commandes</p>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="/admin/menus" class="card p-3 text-center text-decoration-none d-block">
                        <h3><?= count($menus) ?></h3>
                        <p class="text-muted mb-0">Menus actifs</p>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="/admin/stats" class="card p-3 text-center text-decoration-none d-block">
                        <h3>📊</h3>
                        <p class="text-muted mb-0">Statistiques</p>
                    </a>
                </div>
            </div>

            <!-- Tableau des cinq premiers utilisateurs de la liste. -->
            <h4 class="mt-4">Derniers utilisateurs</h4>

            <div class="table-responsive">
                <table class="table" aria-label="Derniers utilisateurs">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($utilisateurs, 0, 5) as $user) : ?>
                            <tr>
                                <!-- Échappe les données avant de les afficher. -->
                                <td>
                                    <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?>
                                </td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td>
                                    <span class="badge bg-primary">
                                        <?= htmlspecialchars($user['role']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $user['actif'] ? 'success' : 'danger' ?>">
                                        <?= $user['actif'] ? 'Actif' : 'Inactif' ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Accès à la gestion complète des utilisateurs. -->
            <a href="/admin/utilisateurs" class="btn btn-primary">
                Gérer les utilisateurs
            </a>
        </div>
    </div>
</div>

<?php
// Charge le pied de page commun du site.
require_once 'views/layouts/footer.php';
?>