
<?php
/**
 * Vue : Gestion des menus - Espace employé
 * Projet : Vite & Gourmand
 *
 * Fonctionnalités :
 * - Affichage des menus
 * - Modification d'un menu
 * - Suppression d'un menu
 * - Affichage du statut actif/inactif
 * - Navigation employé
 * - Accès à l'administration pour les administrateurs
 */

// Charge l'en-tête commun de l'application.
require_once 'views/layouts/header.php';

// Vérifie si l'utilisateur connecté est administrateur.
// Le rôle est enregistré dans $_SESSION['user_role'] par AuthController.
$isAdmin = ($_SESSION['user_role'] ?? '') === 'admin';
?>

<div class="container mt-5 mb-5">
    <div class="row">

        <!-- ========================================= -->
        <!-- NAVIGATION DE L'ESPACE EMPLOYÉ           -->
        <!-- ========================================= -->

        <div class="col-md-3">
            <div class="card p-3">

                <h5>Espace employé</h5>

                <ul class="nav flex-column">

                    <!-- Tableau de bord employé -->
                    <li class="nav-item">
                        <a class="nav-link" href="/employe/dashboard">
                            Tableau de bord
                        </a>
                    </li>

                    <!-- Gestion des commandes -->
                    <li class="nav-item">
                        <a class="nav-link" href="/employe/commandes">
                            Commandes
                        </a>
                    </li>

                    <!-- Gestion des menus : page actuelle -->
                    <li class="nav-item">
                        <a class="nav-link active"
                           href="/employe/menus"
                           aria-current="page">
                            Menus
                        </a>
                    </li>

                    <!-- Gestion des horaires -->
                    <li class="nav-item">
                        <a class="nav-link" href="/employe/horaires">
                            Horaires
                        </a>
                    </li>

                    <!-- Gestion des avis clients -->
                    <li class="nav-item">
                        <a class="nav-link" href="/employe/avis">
                            Avis clients
                        </a>
                    </li>

                    <!-- ========================================= -->
                    <!-- ACCÈS RÉSERVÉS AUX ADMINISTRATEURS        -->
                    <!-- ========================================= -->

                    <?php if ($isAdmin) : ?>

                        <!-- Retour au tableau de bord administrateur -->
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/dashboard">
                                Espace administrateur
                            </a>
                        </li>

                        <!-- Statistiques issues de MongoDB -->
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/stats">
                                Statistiques
                            </a>
                        </li>

                    <?php endif; ?>

                    <!-- Déconnexion -->
                    <li class="nav-item">
                        <a class="nav-link" href="/auth/deconnexion">
                            Déconnexion
                        </a>
                    </li>

                </ul>
            </div>
        </div>

        <!-- ========================================= -->
        <!-- CONTENU PRINCIPAL : GESTION DES MENUS    -->
        <!-- ========================================= -->

        <div class="col-md-9">

            <h1>Gestion des menus</h1>

            <!-- Tableau adaptatif pour les petits écrans -->
            <div class="table-responsive mt-3">

                <table class="table" aria-label="Liste des menus">

                    <!-- En-têtes du tableau -->
                    <thead>
                        <tr>
                            <th scope="col">Titre</th>
                            <th scope="col">Thème</th>
                            <th scope="col">Prix</th>
                            <th scope="col">Stock</th>
                            <th scope="col">Statut</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <!-- Parcourt les menus transmis par le contrôleur -->
                        <?php foreach ($menus as $menu) : ?>

                            <tr>

                                <!-- Titre du menu -->
                                <td>
                                    <?= htmlspecialchars(
                                        (string) $menu['titre'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>

                                <!-- Thème du menu -->
                                <td>
                                    <?= htmlspecialchars(
                                        (string) $menu['theme'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>

                                <!-- Prix formaté avec deux décimales -->
                                <td>
                                    <?= number_format(
                                        (float) $menu['prix_base'],
                                        2,
                                        ',',
                                        ' '
                                    ) ?> €
                                </td>

                                <!-- Stock disponible -->
                                <td>
                                    <?= htmlspecialchars(
                                        (string) $menu['stock'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>

                                <!-- Statut actif ou inactif -->
                                <td>
                                    <span class="badge bg-<?= $menu['actif'] ? 'success' : 'danger' ?>">
                                        <?= $menu['actif'] ? 'Actif' : 'Inactif' ?>
                                    </span>
                                </td>

                                <!-- Actions sur le menu -->
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">

                                        <!-- Modification du menu -->
                                        <a
                                            href="/employe/modifierMenu?id=<?= urlencode((string) $menu['id']) ?>"
                                            class="btn btn-warning btn-sm"
                                        >
                                            ✏️ Modifier
                                        </a>

                                        <!-- Suppression du menu -->
                                        <form
                                            method="POST"
                                            action="/employe/supprimerMenu"
                                            class="d-inline"
                                            data-confirm="Supprimer ce menu ?"
                                        >

                                            <!-- Identifiant du menu -->
                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?= htmlspecialchars(
                                                    (string) $menu['id'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>"
                                            >

                                            <!-- Bouton de suppression -->
                                            <button
                                                type="submit"
                                                class="btn btn-danger btn-sm"
                                            >
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
// Charge le pied de page commun de l'application.
require_once 'views/layouts/footer.php';
?>
