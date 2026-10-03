document.addEventListener('DOMContentLoaded', () => {
    const carousel = document.getElementById('carouselAvis');
    const indicateurs = document.querySelectorAll('[data-avis-slide]');

    if (!carousel) {
        return;
    }

    carousel.addEventListener('slid.bs.carousel', (event) => {
        indicateurs.forEach((indicateur, index) => {
            indicateur.style.backgroundColor =
                index === event.to ? '#5DA99A' : '#ccc';
        });
    });

    indicateurs.forEach((indicateur) => {
        indicateur.addEventListener('click', () => {
            const index = Number(indicateur.dataset.avisSlide);

            if (!Number.isInteger(index)) {
                return;
            }

            bootstrap.Carousel.getOrCreateInstance(carousel).to(index);
        });
    });
});
