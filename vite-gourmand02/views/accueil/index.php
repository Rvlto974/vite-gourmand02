<?php require_once 'views/layouts/header.php'; ?>

<main>
    <!-- Hero -->
    <section class="hero" style="
        background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('/assets/images/hero.jpg');
        background-size: cover;
        background-position: center;
        min-height: 500px;
        display: flex;
        align-items: center;
    ">
        <div class="container text-center text-white">
            <h1 class="display-4 fw-bold">Vite & Gourmand</h1>
            <p class="lead fs-4">Traiteur d'exception depuis 25 ans à Bordeaux</p>
            <a href="/menus" class="btn btn-lg mt-3" 
                style="background-color: #5DA99A; color: white; border: none;">
                Découvrir nos menus
            </a>
        </div>
    </section>

    <!-- Pourquoi nous choisir -->
    <section class="container my-5">
        <h2 class="text-center mb-5" style="color: #5DA99A;">Pourquoi nous choisir</h2>
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm p-4 h-100">
                    <div class="d-flex justify-content-center mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                            style="width:80px;height:80px;background-color:#5DA99A;">
                            <span style="font-size:2rem;">🏆</span>
                        </div>
                    </div>
                    <h5 style="color:#5DA99A;">25 ans d'expérience</h5>
                    <p class="text-muted">Une expertise reconnue dans l'art culinaire et le service traiteur haut de gamme depuis 1999</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm p-4 h-100">
                    <div class="d-flex justify-content-center mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                            style="width:80px;height:80px;background-color:#5DA99A;">
                            <span style="font-size:2rem;">🌿</span>
                        </div>
                    </div>
                    <h5 style="color:#5DA99A;">Produits locaux</h5>
                    <p class="text-muted">Ingrédients frais et de saison, issus de producteurs locaux rigoureusement sélectionnés</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm p-4 h-100">
                    <div class="d-flex justify-content-center mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                style="width:80px;height:80px;background-color:#5DA99A;">
                            <span style="font-size:2rem;">🚚</span>
                        </div>
                    </div>
                    <h5 style="color:#5DA99A;">Livraison Bordeaux</h5>
                    <p class="text-muted">Service de livraison professionnel et ponctuel dans Bordeaux et ses environs</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Avis clients -->
    <section style="background-color:#fff; padding: 3rem 0 0 0;">
        <div class="container">
            <h2 class="text-center mb-5" style="color: #5DA99A;">Avis clients</h2>
            
            <div id="carouselAvis" class="carousel slide position-relative" data-bs-ride="carousel">
                
                <!-- Bouton précédent -->
                <button class="carousel-control-prev d-flex align-items-center justify-content-center" 
                        type="button" data-bs-target="#carouselAvis" data-bs-slide="prev"
                        style="width:50px;height:50px;background:white;border-radius:50%;
                                box-shadow:0 4px 15px rgba(0,0,0,0.2);position:absolute;
                                left:0;top:50%;transform:translateY(-50%);border:1px solid #eee;">
                    <span style="color:#5DA99A;font-size:1.5rem;font-weight:bold;line-height:1;">‹</span>
                </button>

                <div class="carousel-inner px-5">
                    <?php foreach ($avis as $index => $unAvis) : ?>
                    <?php 
                    $avatars = ['avatar1.jpg', 'avatar2.jpg', 'avatar3.jpg'];
                    $avatar = $avatars[$index % 3];
                    ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <div class="row justify-content-center align-items-center py-4">
                            <div class="col-auto">
                                <img src="/assets/images/avis/<?= $avatar ?>"
                                    alt="Photo de <?= htmlspecialchars($unAvis['prenom']) ?>"
                                    style="width:100px;height:100px;border-radius:50%;border:3px solid #5DA99A;object-fit:cover;">
                            </div>
                            <div class="col-md-6 text-start ps-4">
                                <p class="fst-italic fs-5 text-muted mb-3">
                                    "<?= htmlspecialchars($unAvis['commentaire']) ?>"
                                </p>
                                <div class="mb-2" style="color: #FF8F00; font-size: 1.2rem;">★★★★★</div>
                                <p class="fw-bold mb-0">
                                    <?= htmlspecialchars($unAvis['prenom'] . ' ' . $unAvis['nom']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Bouton suivant -->
                <button class="carousel-control-next d-flex align-items-center justify-content-center" 
                        type="button" data-bs-target="#carouselAvis" data-bs-slide="next"
                        style="width:50px;height:50px;background:white;border-radius:50%;
                                box-shadow:0 4px 15px rgba(0,0,0,0.2);position:absolute;
                                right:0;top:50%;transform:translateY(-50%);border:1px solid #eee;">
                    <span style="color:#5DA99A;font-size:1.5rem;font-weight:bold;line-height:1;">›</span>
                </button>

                <!-- Indicateurs -->
                <div class="d-flex justify-content-center gap-2 mt-4 pb-4">
                    <?php foreach ($avis as $index => $unAvis) : ?>
                    <button type="button" 
                            data-bs-target="#carouselAvis" 
                            data-bs-slide-to="<?= $index ?>"
                            style="width:10px;height:10px;border-radius:50%;border:none;
                                    background-color:<?= $index === 0 ? '#5DA99A' : '#ccc' ?>;">
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

</main>

<?php require_once 'views/layouts/footer.php'; ?>