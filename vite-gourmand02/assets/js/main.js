document.addEventListener('DOMContentLoaded', function() {
    const filtreForm = document.getElementById('filtres-form');
    
    if (filtreForm) {
        // Charger les menus au démarrage
        appliquerFiltres();
        
        const inputs = filtreForm.querySelectorAll('select, input');
        inputs.forEach(input => {
            input.addEventListener('change', function() {
                appliquerFiltres();
            });
        });
    }
});

function appliquerFiltres() {
    const theme = document.getElementById('theme') ? document.getElementById('theme').value : '';
    const regime = document.getElementById('regime') ? document.getElementById('regime').value : '';
    const prixMax = document.getElementById('prix_max') ? document.getElementById('prix_max').value : '';

    const url = `/menus/filtrer?theme=${theme}&regime=${regime}&prix_max=${prixMax}`;

    fetch(url)
        .then(response => response.json())
        .then(menus => {
            afficherMenus(menus);
        })
        .catch(error => {
            console.error('Erreur AJAX:', error);
        });
}

function afficherMenus(menus) {
    const container = document.getElementById('liste-menus');
    
    if (!container) return;

    if (menus.length === 0) {
        container.innerHTML = '<p>Aucun menu trouvé.</p>';
        return;
    }

    container.innerHTML = menus.map(menu => `
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">${escapeHtml(menu.titre)}</h5>
                    <p class="card-text">${escapeHtml(menu.description)}</p>
                    <p><strong>${menu.prix_base} €</strong></p>
                    <p>Min. ${menu.nb_personnes_min} personnes</p>
                    <a href="/menus/detail?id=${menu.id}" 
                        class="btn btn-primary"
                        aria-label="Voir les détails du menu ${escapeHtml(menu.titre)}">
                        Voir les détails
                    </a>
                </div>
            </div>
        </div>
    `).join('');}