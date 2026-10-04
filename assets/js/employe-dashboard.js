// Anime progressivement un compteur jusqu’à sa valeur cible.
function animerCompteur(el) {
    const target = parseInt(el.dataset.target);
    const duration = 1200;
    const step = Math.ceil(target / (duration / 16));
    let current = 0;

    const timer = setInterval(() => {
        current += step;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        el.textContent = current;
    }, 16);
}

// Lance l’animation lorsqu’un compteur entre dans la zone visible.
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            animerCompteur(entry.target);
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.5 });

// Observe chaque compteur du tableau de bord.
document.querySelectorAll('.stat-number[data-target]').forEach(el => {
    observer.observe(el);
});
