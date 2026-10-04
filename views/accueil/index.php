<?php
// Charge l’en-tête commun du site.
require_once 'views/layouts/header.php';
?>

<!-- Section d’accueil avec l’image de fond et le lien vers les menus. -->
<section class="hero" style="
    background-image: linear-gradient(rgba(0,0,0,0.1), rgba(0,0,0,0.1)), url('/assets/images/hero.jpg');
    background-size: cover;
    background-position: center;
    min-height: 500px;
    display: flex;
    align-items: center;
">
    <div class="container text-center text-white">
        <h1 class="display-4 fw-bold">Vite & Gourmand</h1>
        <h2 class="lead fs-4" style="color:white;">
            Traiteur d'exception depuis 25 ans à Bordeaux
        </h2>

        <!-- Bouton permettant de consulter les menus disponibles. -->
        <a
            href="/menus"
            class="btn btn-lg mt-3"
            style="background-color: #2E6B5E; color: white; border: none;"
            aria-label="Découvrir nos menus"
        >
            Découvrir nos menus
        </a>
    </div>
</section>

<!-- Présente les principaux avantages du service traiteur. -->
<section class="container my-5">
    <h2 class="text-center mb-5" style="color: #2E6B5E;">Pourquoi nous choisir</h2>

    <div class="row text-center">
        <!-- Mise en avant de l’expérience de l’entreprise. -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm p-4 h-100">
                <div class="d-flex justify-content-center mb-3">
                    <div
                        class="rounded-circle d-flex align-items-center justify-content-center"
                        style="width:80px;height:80px;background-color:#5DA99A;"
                    >
                        <span style="font-size:2rem;" aria-hidden="true">🏆</span>
                    </div>
                </div>
                <h3 style="color:#2E6B5E;">25 ans d'expérience</h3>
                <p class="text-muted">
                    Une expertise reconnue dans l'art culinaire et le service traiteur haut de gamme depuis 1999.
                </p>
            </div>
        </div>

        <!-- Mise en avant de la sélection des produits. -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm p-4 h-100">
                <div class="d-flex justify-content-center mb-3">
                    <div
                        class="rounded-circle d-flex align-items-center justify-content-center"
                        style="width:80px;height:80px;background-color:#5DA99A;"
                    >
                        <span style="font-size:2rem;" aria-hidden="true">🌿</span>
                    </div>
                </div>
                <h3 style="color:#2E6B5E;">Produits locaux</h3>
                <p class="text-muted">
                    Ingrédients frais et de saison, issus de producteurs locaux rigoureusement sélectionnés.
                </p>
            </div>
        </div>

        <!-- Présentation de la zone de livraison. -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm p-4 h-100">
                <div class="d-flex justify-content-center mb-3">
                    <div
                        class="rounded-circle d-flex align-items-center justify-content-center"
                        style="width:80px;height:80px;background-color:#5DA99A;"
                    >
                        <span style="font-size:2rem;" aria-hidden="true">🚚</span>
                    </div>
                </div>
                <h3 style="color:#2E6B5E;">Livraison Bordeaux</h3>
                <p class="text-muted">
                    Service de livraison professionnel et ponctuel dans Bordeaux et ses environs.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Carrousel présentant les avis des clients. -->
<section style="background-color:#fff; padding: 3rem 0 0 0;">
    <div class="container">
        <h2 class="text-center mb-5" style="color: #2E6B5E;">Avis clients</h2>

        <div id="carouselAvis" class="carousel slide position-relative" data-bs-ride="carousel">

            <!-- Bouton pour afficher l’avis précédent. -->
            <button
                class="carousel-control-prev d-flex align-items-center justify-content-center"
                type="button"
                data-bs-target="#carouselAvis"
                data-bs-slide="prev"
                aria-label="Avis précédent"
                style="width:50px;height:50px;background:white;border-radius:50%;
                       box-shadow:0 4px 15px rgba(0,0,0,0.2);position:absolute;
                       left:0;top:50%;transform:translateY(-50%);border:1px solid #eee;"
            >
                <span aria-hidden="true" style="color:#2E6B5E;font-size:1.5rem;font-weight:bold;line-height:1;">
                    ‹
                </span>
            </button>

            <!-- Contenu du carrousel : une diapositive est créée pour chaque avis. -->
            <div class="carousel-inner px-5">
                <?php foreach ($avis as $index => $unAvis) : ?>
                    <?php
                    // Sélectionne un avatar en faisant défiler les trois images disponibles.
                    $avatars = ['avatar1.jpg', 'avatar2.jpg', 'avatar3.jpg'];
                    $avatar = $avatars[$index % 3];
                    ?>

                    <!-- La première diapositive est activée au chargement de la page. -->
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <div class="row justify-content-center align-items-center py-4">
                            <!-- Photo associée à l’avis. -->
                            <div class="col-auto">
                                <img
                                    src="/assets/images/avis/<?= htmlspecialchars($avatar) ?>"
                                    alt="Photo de <?= htmlspecialchars($unAvis['prenom']) ?>"
                                    style="width:100px;height:100px;border-radius:50%;
                                           border:3px solid #5DA99A;object-fit:cover;"
                                >
                            </div>

                            <!-- Commentaire, note et nom de la personne. -->
                            <div class="col-md-6 text-start ps-4">
                                <p class="fst-italic fs-5 text-muted mb-3">
                                    "<?= htmlspecialchars($unAvis['commentaire']) ?>"
                                </p>

                                <div
                                    class="mb-2"
                                    style="color: #B86E00; font-size: 1.2rem;"
                                    aria-label="Note : <?= (int) $unAvis['note'] ?> étoiles sur 5"
                                >
                                    <?= str_repeat('★', (int) $unAvis['note']) ?>
                                    <span style="color:#555">
                                        <?= str_repeat('☆', 5 - (int) $unAvis['note']) ?>
                                    </span>
                                </div>

                                <p class="fw-bold mb-0">
                                    <?= htmlspecialchars($unAvis['prenom'] . ' ' . $unAvis['nom']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Bouton pour afficher l’avis suivant. -->
            <button
                class="carousel-control-next d-flex align-items-center justify-content-center"
                type="button"
                data-bs-target="#carouselAvis"
                data-bs-slide="next"
                aria-label="Avis suivant"
                style="width:50px;height:50px;background:white;border-radius:50%;
                       box-shadow:0 4px 15px rgba(0,0,0,0.2);position:absolute;
                       right:0;top:50%;transform:translateY(-50%);border:1px solid #eee;"
            >
                <span aria-hidden="true" style="color:#2E6B5E;font-size:1.5rem;font-weight:bold;line-height:1;">
                    ›
                </span>
            </button>

            <!-- Indicateurs cliquables pour accéder directement à un avis. -->
            <div class="d-flex justify-content-center gap-2 mt-4 pb-4" id="avisIndicateurs">
                <?php foreach ($avis as $index => $unAvis) : ?>
                    <button
                        type="button"
                        data-avis-slide="<?= (int) $index ?>"
                        id="dot-<?= (int) $index ?>"
                        aria-label="Aller à l'avis <?= (int) ($index + 1) ?>"
                        style="width:10px;height:10px;border-radius:50%;border:none;cursor:pointer;
                               background-color:<?= $index === 0 ? '#5DA99A' : '#ccc' ?>;"
                    ></button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Charge le script qui gère les indicateurs du carrousel. -->
<script src="/assets/js/accueil.js" defer></script>

<?php
// Charge le pied de page commun du site.
require_once 'views/layouts/footer.php';
?>