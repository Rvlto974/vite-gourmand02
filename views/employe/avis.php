
<?php
// Charge l'en-tête commun du site.
require_once 'views/layouts/header.php';

// Vérifie si l'utilisateur connecté est administrateur.
// Les liens administrateur et statistiques ne seront pas affichés aux employés.
$isAdmin = ($_SESSION['user_role'] ?? '') === 'admin';
?>

<!-- Conteneur principal de la gestion des avis clients. -->
<div class="container mt-5">
    <div class="row">

        <!-- Menu de navigation adapté au rôle de l'utilisateur. -->
        <div class="col-md-3">
            <div class="card p-3">

                <!-- Affiche le titre correspondant au rôle. -->
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

                    <!-- Gestion des commandes clients. -->
                    <li class="nav-item">
                        <a class="nav-link" href="/employe/commandes">
                            Commandes
                        </a>
                    </li>

                    <!-- Gestion des menus proposés par le traiteur. -->
                    <li class="nav-item">
                        <a class="nav-link" href="/employe/menus">
                            Menus
                        </a>
                    </li>

                    <!-- Gestion des horaires de l'établissement. -->
                    <li class="nav-item">
                        <a class="nav-link" href="/employe/horaires">
                            Horaires
                        </a>
                    </li>

                    <!-- Page actuelle : validation des avis clients. -->
                    <li class="nav-item">
                        <a class="nav-link active"
                           href="/employe/avis"
                           aria-current="page">
                            Avis clients
                        </a>
                    </li>

                    <!-- Liens visibles uniquement par les administrateurs. -->
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

                    <!-- Déconnexion de l'utilisateur. -->
                    <li class="nav-item">
                        <a class="nav-link" href="/auth/deconnexion">
                            Déconnexion
                        </a>
                    </li>

                </ul>
            </div>
        </div>

        <!-- Contenu principal : gestion des avis clients. -->
        <div class="col-md-9">

            <h1>Gestion des avis</h1>

            <!-- Vérifie si des avis sont en attente de validation. -->
            <?php if (empty($avis)) : ?>

                <!-- Message affiché lorsqu'aucun avis n'est en attente. -->
                <div class="alert alert-info mt-3">
                    Aucun avis en attente de validation.
                </div>

            <?php else : ?>

                <!-- Parcourt les avis transmis par le contrôleur. -->
                <?php foreach ($avis as $unAvis) : ?>

                    <!-- Carte contenant les informations d'un avis. -->
                    <div class="card mb-3 p-3 shadow-sm"
                         style="border-radius:10px;">

                        <div class="d-flex justify-content-between align-items-start">

                            <!-- Informations sur l'auteur et le contenu de l'avis. -->
                            <div>

                                <!-- Nom, prénom et date de publication. -->
                                <p class="fw-bold mb-1">

                                    <?= htmlspecialchars(
                                        $unAvis['prenom'] . ' ' . $unAvis['nom']
                                    ) ?>

                                    <small class="text-muted fw-normal ms-2">
                                        <?= date(
                                            'd/m/Y',
                                            strtotime($unAvis['created_at'])
                                        ) ?>
                                    </small>

                                </p>

                                <!-- Affiche la note sous forme d'étoiles. -->
                                <p class="mb-1"
                                   style="color:#f39c12; font-size:1.2rem;">

                                    <?= str_repeat('★', $unAvis['note']) ?>
                                    <?= str_repeat('☆', 5 - $unAvis['note']) ?>

                                    <!-- Affiche également la note sur 5. -->
                                    <small class="text-muted"
                                           style="font-size:0.85rem;">
                                        <?= $unAvis['note'] ?>/5
                                    </small>

                                </p>

                                <!-- Commentaire rédigé par le client. -->
                                <p class="mb-0">
                                    <?= htmlspecialchars($unAvis['commentaire']) ?>
                                </p>

                            </div>

                            <!-- Boutons permettant de valider ou refuser l'avis. -->
                            <div class="d-flex gap-2">

                                <!-- Formulaire de validation d'un avis. -->
                                <form method="POST"
                                      action="/employe/validerAvis">

                                    <!-- Identifiant de l'avis à valider. -->
                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= $unAvis['id'] ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="btn btn-success btn-sm"
                                    >
                                        ✅ Valider
                                    </button>

                                </form>

                                <!-- Formulaire de refus d'un avis. -->
                                <form method="POST"
                                      action="/employe/refuserAvis">

                                    <!-- Identifiant de l'avis à refuser. -->
                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= $unAvis['id'] ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                    >
                                        ❌ Refuser
                                    </button>

                                </form>

                            </div>

                        </div>
                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>
    </div>
</div>

<?php
// Charge le pied de page commun du site.
require_once 'views/layouts/footer.php';
?>
