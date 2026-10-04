// Attend que le document HTML soit chargé avant d’accéder aux éléments.
document.addEventListener('DOMContentLoaded', () => {
    // Récupère le carrousel des avis et ses indicateurs de navigation.
    const carousel = document.getElementById('carouselAvis');
    const indicateurs = document.querySelectorAll('[data-avis-slide]');

    // Arrête le script si le carrousel n’est pas présent sur la page.
    if (!carousel) {
        return;
    }

    // Met à jour la couleur des indicateurs lorsque le carrousel change de diapositive.
    carousel.addEventListener('slid.bs.carousel', (event) => {
        indicateurs.forEach((indicateur, index) => {
            // Met en évidence l’indicateur actif et grise les autres.
            indicateur.style.backgroundColor =
                index === event.to ? '#5DA99A' : '#ccc';
        });
    });

    // Permet de passer à une diapositive en cliquant sur son indicateur.
    indicateurs.forEach((indicateur) => {
        indicateur.addEventListener('click', () => {
            // Lit l’index de la diapositive depuis l’attribut data-avis-slide.
            const index = Number(indicateur.dataset.avisSlide);

            // Ignore l’indicateur si sa valeur n’est pas un nombre entier.
            if (!Number.isInteger(index)) {
                return;
            }

            // Récupère ou crée l’instance Bootstrap du carrousel, puis affiche la diapositive.
            bootstrap.Carousel.getOrCreateInstance(carousel).to(index);
        });
    });
});