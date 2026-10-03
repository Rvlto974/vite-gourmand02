</main>

    <footer class="py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h3>Vite & Gourmand</h3>
                    <p>Traiteur d'exception à Bordeaux depuis 25 ans.</p>
                </div>
                <div class="col-md-4">
                    <h3>Horaires</h3>
                    <p>Lundi - Vendredi : 9h - 18h</p>
                    <p>Samedi : 9h - 12h</p>
                    <p>Dimanche : Fermé</p>
                </div>
                <div class="col-md-4">
                    <h3>Contact</h3>
                    <p>contact@viteetgourmand.fr</p>
                    <p>05 XX XX XX XX</p>
                </div>
            </div>
            <hr>
            <p class="text-center">
                <a href="/pages/mentions">Mentions légales</a> | 
                <a href="/pages/cgv">CGV</a> | 
                <a href="/pages/confidentialite">Politique de confidentialité</a>
            </p>
        </div>
    </footer>

    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/main.js"></script>

    <?php if (isset($_SESSION['flash'])): ?>
<div class="toast-container">
    <div class="toast show align-items-center text-white bg-<?= $_SESSION['flash']['type'] ?> border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body"><?= $_SESSION['flash']['message'] ?></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
<script>
setTimeout(() => { document.querySelector('.toast')?.classList.remove('show'); }, 3000);
</script>
<?php unset($_SESSION['flash']); endif; ?>

</body>
</html>