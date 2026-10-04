<?php
// Charge l’en-tête commun de l’espace employé.
require_once 'views/layouts/header.php';
?>

<div class="container mt-5 mb-5">
    <div class="row">
        <!-- Menu de navigation de l’espace employé. -->
        <div class="col-md-3">
            <div class="card p-3">
                <h5>Espace employé</h5>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="/employe/dashboard">Tableau de bord</a></li>
                    <li class="nav-item"><a class="nav-link" href="/employe/commandes">Commandes</a></li>
                    <li class="nav-item"><a class="nav-link" href="/employe/menus">Menus</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/employe/horaires">Horaires</a></li>
                    <li class="nav-item"><a class="nav-link" href="/employe/avis">Avis clients</a></li>
                    <li class="nav-item"><a class="nav-link" href="/auth/deconnexion">Déconnexion</a></li>
                </ul>
            </div>
        </div>

        <!-- Tableau permettant de consulter et modifier les horaires. -->
        <div class="col-md-9">
            <div class="card p-4 shadow-sm" style="border-radius:12px;">
                <h1 class="h3 mb-4" style="color:#5DA99A">🕐 Gestion des horaires</h1>

                <!-- Envoie les horaires modifiés au contrôleur. -->
                <form method="POST" action="/employe/modifierHoraires">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Jour</th>
                                    <th>Ouverture</th>
                                    <th>Fermeture</th>
                                    <th>Fermé</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($horaires as $h) : ?>
                                <tr>
                                    <!-- Affiche le jour correspondant à cet horaire. -->
                                    <td><strong><?= htmlspecialchars($h['jour']) ?></strong></td>

                                    <!-- Heure d’ouverture, désactivée si l’établissement est fermé. -->
                                    <td>
                                        <input type="time" name="horaires[<?= $h['id'] ?>][heure_ouverture]"
                                               class="form-control form-control-sm"
                                               value="<?= htmlspecialchars($h['heure_ouverture'] ?? '') ?>"
                                               <?= $h['ferme'] ? 'disabled' : '' ?>>
                                    </td>

                                    <!-- Heure de fermeture, désactivée si l’établissement est fermé. -->
                                    <td>
                                        <input type="time" name="horaires[<?= $h['id'] ?>][heure_fermeture]"
                                               class="form-control form-control-sm"
                                               value="<?= htmlspecialchars($h['heure_fermeture'] ?? '') ?>"
                                               <?= $h['ferme'] ? 'disabled' : '' ?>>
                                    </td>

                                    <!-- Cette case indique si l’établissement est fermé ce jour-là. -->
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input toggle-ferme"
                                                   name="horaires[<?= $h['id'] ?>][ferme]"
                                                   <?= $h['ferme'] ? 'checked' : '' ?>>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Enregistre les modifications du formulaire. -->
                    <button type="submit" class="btn py-2 px-4"
                            style="background-color:#5DA99A; color:white; border-radius:8px;">
                        💾 Enregistrer les horaires
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Charge le script qui active ou désactive les champs d’heures selon la case « Fermé ». -->
<script src="/assets/js/employe-horaires.js" defer></script>

<?php
// Charge le pied de page commun.
require_once 'views/layouts/footer.php';
?>