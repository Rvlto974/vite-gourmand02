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
        'saisonnie