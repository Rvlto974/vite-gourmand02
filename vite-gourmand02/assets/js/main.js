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
                if (input.type !== 'range') appliquerFiltres();
            });
        });
    }

    animerAuScroll();
    boutonRetourHaut();
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
        const theme       = (menu.theme ?? 'classique').toLowerCase();
        const badgeClass  = badgeClasses[theme] ?? 'badge-classique';
        const badgeLabel  = menu.theme ?? 'Classique';
        const image       = menu.image ?? '/assets/images/menu-default.jpg';
        const note        = parseFloat(menu.note_moyenne ?? 0);
        const nbAvis      = menu.nb_avis ?? 0;
        const description = (menu.description ?? '').substring(0, 100);
        const prix        = parseFloat(menu.prix_base).toFixed(2);
        const stock       = parseInt(menu.stock ?? 0);

        // Badge Nouveau
        const created = new Date(menu.created_at);
        const jours = (Date.now() - created.getTime()) / (1000 * 60 * 60 * 24);
        const badgeNouveau = jours <= 7
            ? `<span style="position:absolute; top:12px; left:12px; background:#e67e22; color:white;
                            border-radius:20px; padding:3px 10px; font-size:0.75rem; font-weight:bold;">
                   🆕 Nouveau
               </span>`
            : '';

        let etoiles = '';
        for (let i = 1; i <= 5; i++) {
            etoiles += i <= Math.round(note) ? '★' : '☆';
        }

        const stockBadge = stock <= 0
            ? `<span class="badge bg-danger mb-2">❌ Stock épuisé</span>`
            : stock <= 3
                ? `<span class="badge bg-warning text-dark mb-2">⚠️ Plus que ${stock}</span>`
                : `<span class="badge bg-success mb-2">✅ Disponible</span>`;

        const btnVoir = stock <= 0
            ? `<a href="/menus/detail?id=${menu.id}" class="btn btn-voir disabled">👁 Voir le menu</a>`
            : `<a href="/menus/detail?id=${menu.id}" class="btn btn-voir">👁 Voir le menu</a>`;

        return `
        <div class="col-md-4 mb-4">
            <div class="card menu-card h-100 animate-scroll">
                <div class="card-img-wrapper">
                    <img src="${image}" alt="Photo du ${escapeHtml(menu.titre)}">
                    <span class="badge-theme ${badgeClass}">${escapeHtml(badgeLabel)}</span>
                    ${badgeNouveau}
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold" style="color:#2E6B5E">${escapeHtml(menu.titre)}</h5>
                    <div class="stars mb-1">${etoiles} <small class="text-muted">(${nbAvis})</small></div>
                    <p class="card-text text-muted small flex-grow-1">${escapeHtml(description)}...</p>
                    <div class="d-flex justify-content-between meta-info mb-2">
                        <span>👥 ${menu.nb_personnes_min} pers. min</span>
                        <span>${escapeHtml(menu.regime ?? '')}</span>
                    </div>
                    <p class="prix-color mb-2">A partir de ${prix} EUR</p>
                    ${stockBadge}
                    ${btnVoir}
                </div>
            </div>
        </div>`;
    }).join('');

    setTimeout(() => animerAuScroll(), 50);
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function animerAuScroll() {
    const elements = document.querySelectorAll(
        '.card, .menu-card, .section-card, .alert, .avis-card, .commande-card'
    );

    elements.forEach(el => {
        if (el.classList.contains('visible')) return;
        el.classList.add('animate-scroll');
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add('visible');
                }, i * 100);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -30px 0px' });

    elements.forEach(el => {
        if (!el.classList.contains('visible')) {
            observer.observe(el);
        }
    });
}

function boutonRetourHaut() {
    const btn = document.createElement('button');
    btn.innerHTML = '↑';
    btn.setAttribute('aria-label', 'Retour en haut de page');
    btn.style.cssText = `
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background-color: #5DA99A;
        color: white;
        border: none;
        font-size: 1.2rem;
        font-weight: bold;
        cursor: pointer;
        display: none;
        z-index: 999;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        transition: opacity 0.3s ease, background-color 0.2s;
    `;
    btn.addEventListener('mouseover', () => btn.style.backgroundColor = '#2E6B5E');
    btn.addEventListener('mouseout',  () => btn.style.backgroundColor = '#5DA99A');
    btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    document.body.appendChild(btn);

    window.addEventListener('scroll', () => {
        btn.style.display = window.scrollY > 300 ? 'block' : 'none';
    });
}