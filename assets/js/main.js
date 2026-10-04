// Exécute le code une fois que le HTML de la page est chargé.
document.addEventListener('DOMContentLoaded', function() {
    // Récupère le formulaire des filtres, s'il existe sur la page.
    const filtreForm = document.getElementById('filtres-form');

    if (filtreForm) {
        // Empêche le rechargement de la page lors de l'envoi du formulaire.
        filtreForm.addEventListener('submit', function(e) {
            e.preventDefault();
            appliquerFiltres();
        });

        // Applique les filtres lorsque l'utilisateur modifie un champ.
        const inputs = filtreForm.querySelectorAll('select, input');

        inputs.forEach(input => {
            input.addEventListener('change', function() {
                // Les curseurs de prix sont gérés plus bas avec l'événement "input".
                if (input.type !== 'range') {
                    appliquerFiltres();
                }
            });
        });
    }

    // Récupère les curseurs de prix, s'ils sont présents sur la page.
    const prixMax = document.getElementById('prix_max');
    const prixMin = document.getElementById('prix_min');

    // Met à jour le prix maximal et rafraîchit les résultats pendant le déplacement.
    if (prixMax) {
        prixMax.addEventListener('input', function() {
            const label = document.getElementById('prix-max-label');

            if (label) {
                label.textContent = this.value + ' €';
            }

            appliquerFiltres();
        });
    }

    // Met à jour le prix minimal et rafraîchit les résultats pendant le déplacement.
    if (prixMin) {
        prixMin.addEventListener('input', function() {
            const label = document.getElementById('prix-min-label');

            if (label) {
                label.textContent = this.value + ' €';
            }

            appliquerFiltres();
        });
    }

    // Active les animations au défilement et le bouton de retour en haut.
    animerAuScroll();
    boutonRetourHaut();
});

// Récupère les menus correspondant aux filtres sélectionnés.
function appliquerFiltres() {
    // Lit les valeurs des filtres. Si un élément n'existe pas, utilise une valeur vide.
    const theme       = document.getElementById('theme')?.value ?? '';
    const regime      = document.getElementById('regime')?.value ?? '';
    const prixMin     = document.getElementById('prix_min')?.value ?? '';
    const prixMax     = document.getElementById('prix_max')?.value ?? '';
    const nbPersonnes = document.getElementById('nb_personnes')?.value ?? '';
    const tri         = document.getElementById('tri')?.value ?? 'recent';

    // Construit l'adresse de la requête avec les filtres.
    const parametres = new URLSearchParams({
        theme: theme,
        regime: regime,
        prix_min: prixMin,
        prix_max: prixMax,
        nb_personnes: nbPersonnes,
        tri: tri
    });

    // Demande au serveur la liste des menus filtrés.
    fetch(`/menus/filtrer?${parametres}`)
        .then(response => {
            // Signale une erreur si la réponse du serveur n'est pas correcte.
            if (!response.ok) {
                throw new Error('La requête de filtrage a échoué.');
            }

            return response.json();
        })
        .then(menus => {
            // Affiche les menus reçus.
            afficherMenus(menus);

            // Met à jour le nombre de menus affichés.
            const badge = document.querySelector('.count-badge');

            if (badge) {
                badge.textContent = menus.length + ' menu(s)';
            }
        })
        .catch(error => {
            // Affiche l'erreur dans la console du navigateur.
            console.error('Erreur AJAX :', error);
        });
}

// Crée et affiche les cartes des menus reçus du serveur.
function afficherMenus(menus) {
    const container = document.getElementById('liste-menus');

    // Arrête la fonction si la page ne contient pas la liste des menus.
    if (!container) {
        return;
    }

    // Affiche un message si aucun menu ne correspond aux filtres.
    if (menus.length === 0) {
        container.innerHTML = '<p class="text-muted">Aucun menu trouvé.</p>';
        return;
    }

    // Associe certains thèmes à leurs classes CSS.
    const badgeClasses = {
        'noel': 'badge-noel',
        'paques': 'badge-paques',
        'evenement': 'badge-evenement',
        'saisonnier': 'badge-saisonnier'
    };

    // Construit une carte HTML pour chaque menu.
    container.innerHTML = menus.map(menu => {
        // Prépare les informations du menu avec des valeurs par défaut.
        const theme       = (menu.theme ?? 'classique').toLowerCase();
        const badgeClass  = badgeClasses[theme] ?? 'badge-classique';
        const badgeLabel  = menu.theme ?? 'Classique';
        const image       = menu.image ?? '/assets/images/menu-default.jpg';
        const note        = parseFloat(menu.note_moyenne ?? 0);
        const nbAvis      = menu.nb_avis ?? 0;
        const description = (menu.description ?? '').substring(0, 100);
        const prix        = parseFloat(menu.prix_base ?? 0).toFixed(2);
        const stock       = parseInt(menu.stock ?? 0, 10);

        // Calcule l'âge du menu pour afficher le badge « Nouveau » pendant 7 jours.
        const created = new Date(menu.created_at);
        const jours = (Date.now() - created.getTime()) / (1000 * 60 * 60 * 24);

        const badgeNouveau = jours >= 0 && jours <= 7
            ? `<span style="position:absolute; top:12px; left:12px; background:#e67e22; color:white;
                            border-radius:20px; padding:3px 10px; font-size:0.75rem; font-weight:bold;">
                   🆕 Nouveau
               </span>`
            : '';

        // Crée les étoiles correspondant à la note moyenne.
        let etoiles = '';

        for (let i = 1; i <= 5; i++) {
            etoiles += i <= Math.round(note) ? '★' : '☆';
        }

        // Choisit le badge de disponibilité selon le stock.
        const stockBadge = stock <= 0
            ? `<span class="badge bg-danger mb-2">❌ Stock épuisé</span>`
            : stock <= 3
                ? `<span class="badge bg-warning text-dark mb-2">⚠️ Plus que ${stock}</span>`
                : `<span class="badge bg-success mb-2">✅ Disponible</span>`;

        // Désactive le lien de consultation si le menu est en rupture de stock.
        const btnVoir = stock <= 0
            ? `<a href="/menus/detail?id=${encodeURIComponent(menu.id)}" class="btn btn-voir disabled" aria-disabled="true">👁 Voir le menu</a>`
            : `<a href="/menus/detail?id=${encodeURIComponent(menu.id)}" class="btn btn-voir">👁 Voir le menu</a>`;

        // Retourne le code HTML de la carte du menu.
        return `
        <div class="col-md-4 mb-4">
            <div class="card menu-card h-100 animate-scroll">
                <div class="card-img-wrapper">
                    <img src="${escapeHtml(image)}" alt="Photo du ${escapeHtml(menu.titre)}">
                    <span class="badge-theme ${badgeClass}">${escapeHtml(badgeLabel)}</span>
                    ${badgeNouveau}
                </div>

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold" style="color:#2E6B5E">
                        ${escapeHtml(menu.titre)}
                    </h5>

                    <div class="stars mb-1">
                        ${etoiles}
                        <small class="text-muted">(${escapeHtml(nbAvis)})</small>
                    </div>

                    <p class="card-text text-muted small flex-grow-1">
                        ${escapeHtml(description)}...
                    </p>

                    <div class="d-flex justify-content-between meta-info mb-2">
                        <span>👥 ${escapeHtml(menu.nb_personnes_min)} pers. min</span>
                        <span>${escapeHtml(menu.regime ?? '')}</span>
                    </div>

                    <p class="prix-color mb-2">À partir de ${prix} €</p>

                    ${stockBadge}
                    ${btnVoir}
                </div>
            </div>
        </div>`;
    }).join('');

    // Lance les animations sur les cartes nouvellement créées.
    setTimeout(() => animerAuScroll(), 50);
}

// Protège les valeurs insérées dans le HTML contre l'interprétation de caractères spéciaux.
function escapeHtml(str) {
    if (str === null || str === undefined) {
        return '';
    }

    return String(str)
        .replace(/&/g, '&')
        .replace(/</g, '<')
        .replace(/>/g, '>')
        .replace(/"/g, '"')
        .replace(/'/g, '&#039;');
}

// Anime les cartes et les éléments lorsqu'ils apparaissent à l'écran.
function animerAuScroll() {
    const elements = document.querySelectorAll(
        '.card, .menu-card, .section-card, .alert, .avis-card, .commande-card'
    );

    // Ajoute la classe qui prépare l'animation aux éléments non animés.
    elements.forEach(el => {
        if (!el.classList.contains('visible')) {
            el.classList.add('animate-scroll');
        }
    });

    // Observe les éléments pour détecter leur entrée dans la zone visible.
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                // Ajoute la classe visible avec un léger décalage.
                setTimeout(() => {
                    entry.target.classList.add('visible');
                }, index * 100);

                // Arrête d'observer l'élément après son apparition.
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -30px 0px'
    });

    // Commence l'observation des éléments qui ne sont pas encore visibles.
    elements.forEach(el => {
        if (!el.classList.contains('visible')) {
            observer.observe(el);
        }
    });
}

// Crée le bouton qui permet de revenir en haut de la page.
function boutonRetourHaut() {
    const btn = document.createElement('button');

    btn.innerHTML = '↑';
    btn.setAttribute('aria-label', 'Retour en haut de page');

    // Définit l'apparence du bouton.
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

    // Change la couleur au survol.
    btn.addEventListener('mouseover', () => {
        btn.style.backgroundColor = '#2E6B5E';
    });

    btn.addEventListener('mouseout', () => {
        btn.style.backgroundColor = '#5DA99A';
    });

    // Fait remonter la page en douceur au clic.
    btn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Ajoute le bouton à la page.
    document.body.appendChild(btn);

    // Affiche le bouton après un défilement de 300 pixels.
    window.addEventListener('scroll', () => {
        btn.style.display = window.scrollY > 300 ? 'block' : 'none';
    });
}