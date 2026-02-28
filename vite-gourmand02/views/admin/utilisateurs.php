<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card p-3">
                <h5>Espace admin</h5>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/dashboard">Tableau de bord</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/admin/utilisateurs">Utilisateurs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/menus">Menus</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/employe/commandes">Commandes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/auth/deconnexion">Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Contenu -->
        <div class="col-md-9">
            <h1>Gestion des utilisateurs</h1>

            <a href="/admin/creerEmploye" class="btn btn-primary mt-2 mb-3">+ Créer un employé</a>

            <div class="table-responsive mt-3">
                <table class="table" aria-label="Liste des utilisateurs">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($utilisateurs as $user) : ?>
                        <tr>
                            <td><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><span class="badge bg-primary"><?= $user['role'] ?></span></td>
                            <td>
                                <span class="badge bg-<?= $user['actif'] ? 'success' : 'danger' ?>">
                                    <?= $user['actif'] ? 'Actif' : 'Inactif' ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="/admin/toggleActif">
                                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                    <button type="submit" 
                                            class="btn btn-<?= $user['actif'] ? 'danger' : 'success' ?> btn-sm"
                                            aria-label="<?= $user['actif'] ? 'Désactiver' : 'Activer' ?> le compte">
                                        <?= $user['actif'] ? 'Désactiver' : 'Activer' ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>