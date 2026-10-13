
<?php
// Charge l'en-tête commun du site.
require_once 'views/layouts/header.php';

// Vérifie si l'utilisateur connecté possède le rôle administrateur.
// Cette variable permet d'afficher les liens réservés aux administrateurs.
$isAdmin = ($_SESSION['user_role'] ?? '') === 'admin';
?>

<!-- Conteneur principal de la page de gestion des horaires. -->
<div class="container mt-5 mb-5">
    <div class="row">

        <!-- Menu de navigation commun aux employés et administrateurs. -->
        <div class="col-md-3">
            <div class="card p-3">

                <!-- Adapte le titre du menu au rôle de l'utilisateur. -->
                <h5>
                    <?= $isAdmin ? 'Espace administrateur' : 'Espace employé' ?>
                </h5>

                <ul class="nav flex-column">

                    <!-- Accès au tableau de bord employé. -->
                    <li class="nav-item">
                        <a class="nav-link" href="/employe/dashboard">
                            Tableau de bord
                        </a>
                    </li>

                    <!-- Accès à la gestion des commandes. -->
                    <li class="nav-item">
                        <a class="nav-link" href="/employe/commandes">
                            Commandes
                        </a>
                    </li>

                    <!-- Accès à la gestion des menus. -->
                    <li class="nav-item">
                        <a class="nav-link" href="/employe/menus">
                            Menus
                        </a>
                    </li>

                    <!-- Page actuelle : gestion des horaires. -->
                    <li class="nav-item">
                        <a class="nav-link active"
                           href="/employe/horaires"
                           aria-current="page">
                            Horaires
                        </a>
                    </li>

                    <!-- Accès à la gestion des avis clients. -->
                    <li class="nav-item">
                        <a class="nav-link" href="/employe/avis">
                            Avis clients
                        </a>
                    </li>

                    <!-- Liens visibles uniquement par l'administrateur. -->
                    <?php if ($isAdmin) : ?>

                        <!-- Retour au tableau de bord administrateur. -->
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/dashboard">
                                Espace administrateur
                            </a>
                        </li>

                        <!-- Accès aux statistiques MongoDB. -->
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/stats">
                                Statistiques
                            </a>
                        </li>

                    <?php endif; ?>

                    <!-- Déconnexion de l'utilisateur connecté. -->
                    <li class="nav-item">
                        <a class="nav-link" href="/auth/deconnexion">
                            Déconnexion
                        </a>
                    </li>

                </ul>
            </div>
        </div>

        <!-- Contenu principal : consultation et modification des horaires. -->
        <div class="col-md-9">

            <!-- Carte contenant le formulaire de gestion des horaires. -->
            <div class="card p-4 shadow-sm" style="border-radius:12px;">

                <!-- Titre de la page. -->
                <h1 class="h3 mb-4" style="color:#5DA99A">
                    🕐 Gestion des horaires
                </h1>

                <!--
                    Formulaire envoyé au contrôleur EmployeController.
                    Il permet d'enregistrer les horaires modifiés.
                -->
                <form method="POST" action="/employe/modifierHoraires">

                    <!-- Rend le tableau utilisable sur les petits écrans. -->
                    <div class="table-responsive">

                        <table class="table align-middle">

                            <!-- En-têtes des colonnes du tableau. -->
                            <thead>
                                <tr>
                                    <th>Jour</th>
                                    <th>Ouverture</th>
                                    <th>Fermeture</th>
                                    <th>Fermé</th>
                                </tr>
                            </thead>

                            <tbody>

                                <!-- Parcourt les horaires transmis par le contrôleur. -->
                                <?php foreach ($horaires as $h) : ?>

                                    <tr>

                                        <!-- Affiche le jour de la semaine. -->
                                        <td>
                                            <strong>
                                                <?= htmlspecialchars($h['jour']) ?>
                                            </strong>
                                        </td>

                                        <!--
                                            Champ permettant de modifier l'heure d'ouverture.
                                            Il est désactivé si l'établissement est fermé.
                                        -->
                                        <td>
                                            <input
                                                type="time"
                                                name="horaires[<?= $h['id'] ?>][heure_ouverture]"
                                                class="form-control form-control-sm"
                                                value="<?= htmlspecialchars($h['heure_ouverture'] ?? '') ?>"
                                                <?= $h['ferme'] ? 'disabled' : '' ?>
                                            >
                                        </td>

                                        <!--
                                            Champ permettant de modifier l'heure de fermeture.
                                            Il est désactivé si l'établissement est fermé.
                                        -->
                                        <td>
                                            <input
                                                type="time"
                                                name="horaires[<?= $h['id'] ?>][heure_fermeture]"
                                                class="form-control form-control-sm"
                                                value="<?= htmlspecialchars($h['heure_fermeture'] ?? '') ?>"
                                                <?= $h['ferme'] ? 'disabled' : '' ?>
                                            >
                                        </td>

                                        <!--
                                            Case permettant d'indiquer la fermeture du jour.
                                            Le JavaScript active ou désactive les champs horaires.
                                        -->
                                        <td>
                                            <div class="form-check">

                                                <input
                                                    type="checkbox"
                                                    class="form-check-input toggle-ferme"
                                                    name="horaires[<?= $h['id'] ?>][ferme]"
                                                    <?= $h['ferme'] ? 'checked' : '' ?>
                                                >

                                            </div>
                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>
                        </table>

                    </div>

                    <!--
                        Bouton de validation du formulaire.
                        Les données sont envoyées au contrôleur pour enregistrement.
                    -->
                    <button
                        type="submit"
                        class="btn py-2 px-4"
                        style="background-color:#5DA99A; color:white; border-radius:8px;"
                    >
                        💾 Enregistrer les horaires
                    </button>

                </form>

            </div>
        </div>

    </div>
</div>

<!--
    Charge le script JavaScript de gestion des horaires.
    Il active ou désactive les champs lorsque la case "Fermé" change.
-->
<script src="/assets/js/employe-horaires.js" defer></script>

<?php
// Charge le pied de page commun du site.
require_once 'views/layouts/footer.php';
?>
