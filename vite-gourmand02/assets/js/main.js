document.addEventListener('DOMContentLoaded', function() {
    const filtreForm = document.getElementById('filtres-form');
    
    if (filtreForm) {
        filtreForm.addEventListener('submit', function(e) {
            e.preventDefault();
            appliquerFiltres();
        });

        const inputs = filtreForm.querySelectorAll('select, input');
        inputs.forEach(input => {
            input.addEventListener('change', function() {
                appliquerFiltres();
            });
        });
    }
});

function appliquerFiltres() {
    const theme       = document.getElementById('theme')?.value ?? '';
    const regime      = document.getElementById('regime')?.value ?? '';
    const prixMin     = document.getElementById('prix_min')?.value ?? '';
    const prixMax     = document.getElementById('prix_max')?.value ?? '';
    const nbPersonnes = document.getElementById('nb_personnes')?.value ?? '';
    const tri         = document.getElementById('tri')?.value ?? 'recent';

    const url = `/menus/filtrer?theme=${theme}&regime=${regime}&prix_min=${prixMin}&prix_max=${prixMax}&nb_personnes=${nbPersonnes}&tri=${tri}`;

    fetch(url)
        .then(response => response.json())
        .then(menus => {
            afficherMenus(menus);
            const badge = document.querySelector('.count-badge');
            if (badge) badge.textContent = menus.length + ' menu(s)';
        })
        .catch(error => console.error('Erreur AJAX:', error));
}

function afficherMenus(menus) {
    const container = document.getElementById('liste-menus');
    if (!container) return;

    if (menus.length === 0) {
        container.innerHTML = '<p class="text-muted">Aucun menu trouvé.</p>';
        return;
    }

    const badgeClasses = {
        'noel'       : 'badge-noel',
        'paques'     : 'badge-paques',
        'evenement'  : 'badge-evenement',
        'saisonnier' : 'badge-saisonnier',
    };

    container.innerHTML = menus.map(menu => {
        const theme      = (menu.theme ?? 'classique').toLowerCase();
        const badgeClass = badgeClasses[theme] ?? 'badge-classique';
        const badgeLabel = menu.theme ?? 'Classique';
        const image      = menu.image ?? '/assets/images/menu-default.jpg';
        const note       = parseFloat(menu.note_moyenne ?? 0);
        const nbAvis     = menu.nb_avis ?? 0;
        const description= (menu.description ?? '').substring(0, 100);
        const prix       = parseFloat(menu.prix_base).toFixed(2);

        let etoiles = '';
        for (let i = 1; i <= 5; i++) {
            etoiles += i <= Math.round(note) ? '★' : '☆';
        }

        return `
        <div class="col-md-4 mb-4">
            <div class="card menu-card h-100">
                <div class="card-img-wrapper">
                    <img src="${image}" alt="Photo du ${escapeHtml(menu.titre)}">
                    <span class="badge-theme ${badgeClass}">${escapeHtml(badgeLabel)