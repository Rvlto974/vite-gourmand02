// Attend que le HTML soit chargé avant d'utiliser les éléments de la page.
document.addEventListener('DOMContentLoaded', function () {
    // Cherche le formulaire de filtres, présent sur la page des menus.
    const filtreForm = document.getElementById('filtres-form');

    if (filtreForm) {
        // Empêche l'envoi classique du formulaire et le rechargement de la page.
        filtreForm.addEventListener('submit', function (event) {
            event.preventDefault();
            appliquerFiltres();
        });

        // Relance le filtrage lorsque l'utilisateur modifie un champ.
        const champsFiltres = filtreForm.querySelectorAll('select, input');

        champsFiltres.forEach(function (champ) {
            champ.addEventListener('change', function () {
                // Les curseurs de prix sont gérés avec l'événement "input" ci-dessous.
                if (champ.type !== 'range') {
                    appliquerFiltres();
                }
            });
        });
    }

    // Récupère les curseurs de prix minimum et maximum.
    const prixMax = document.getElementById('prix_max');
    const prixMin = document.getElementById('prix_min');

    // Actualise le libellé et les menus pendant le déplacement du curseur maximum.
    if (prixMax) {
        prixMax.addEventListener('input', function () {
            const label = document.getElementById('prix-max-label');

            if (label) {
                label.textContent = `${prixMax.value} €`;
            }

            appliquerFiltres();
        });
    }

    // Actualise le libellé et les menus pendant le déplacement du curseur minimum.
    if (prixMin) {
        prixMin.addEventListener('input', function () {
            const label = document.getElementById('prix-min-label');

            if (label) {
                label.textContent = `${prixMin.value} €`;
            }

            appliquerFiltres();
        });
    }

    // Active les animations et ajoute le bouton de retour en haut de page.
    animerAuScroll();
    boutonRetourHaut();
});


// Demande au serveur les menus correspondant aux filtres sélectionnés.
function appliquerFiltres() {
    // Lit les valeurs des champs ; utilise une chaîne vide si un champ manque.
    const theme = document.getElementById('theme')?.value ?? '';
    const regime = document.getElementById('regime')?.value ?? '';
    const prixMin = document.getElementById('prix_min')?.value ?? '';
    const prixMax = document.getElementById('prix_max')?.value ?? '';
    const nbPersonnes = document.getElementById('nb_personnes')?.value ?? '';
    const tri = document.getElementById('tri')?.value ?? 'recent';

    // Prépare les paramètres à ajouter à l'adresse de la requête.
    const parametres = new URLSearchParams({
        theme: theme,
        regime: regime,
        prix_min: prixMin,
        prix_max: prixMax,
        nb_personnes: nbPersonnes,
        tri: tri
    });

    // Récupère les menus filtrés au format JSON.
    fetch(`/menus/filtrer?${parametres}`)
        .then(function (response) {
            // Interrompt le traitement si le serveur a renvoyé une erreur.
            if (!response.ok) {
                throw new Error('La requête de filtrage a échoué.');
            }

            return response.json();
        })
        .then(function (menus) {
            // Affiche les menus reçus du serveur.
            afficherMenus(menus);

            // Met à jour le compteur de menus, s'il est présent.
            const compteur = document.querySelector('.count-badge');

            if (compteur) {
                compteur.textContent = `${menus.length} menu(s)`;
            }
        })
        .catch(function (erreur) {
            // Affiche l'erreur dans la console pour faciliter le diagnostic.
            console.error('Erreur lors du filtrage des menus :', erreur);
        });
}


// Construit et affiche les cartes des menus reçus du serveur.
function afficherMenus(menus) {
    const conteneur = document.getElementById('liste-menus');

    // Quitte la fonction si la page ne contient pas la liste des menus.
    if (!conteneur) {
        return;
    }

    // Vérifie que la réponse du serveur est bien une liste.
    if (!Array.isArray(menus)) {
        console.error('La réponse reçue pour les menus n’est pas une liste.');
        conteneur.innerHTML =
            '<p class="text-danger">Impossible de charger les menus.</p>';
        return;
    }

    // Affiche un message si aucun menu ne correspond aux critères.
    if (menus.length === 0) {
        conteneur.innerHTML =
            '<p class="text-muted">Aucun menu trouvé.</p>';
        return;
    }

    // Associe chaque thème à la classe CSS de son badge.
    const classesBadges = {
        noel: 'badge-noel',
        paques: 'badge-paques',
        evenement: 'badge-evenement',
        saisonnier: 'badge-saisonnier'
    };

    // Crée une carte HTML pour chaque menu.
    conteneur.innerHTML = menus.map(function (menu) {
        // Normalise le thème pour gérer les majuscules et les accents.
        const themeNormalise = String(menu.theme ?? 'classique')
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .trim();

        // Définit le style et le texte du badge du thème.
        const classeBadge =
            classesBadges[themeNormalise] ?? 'badge-classique';
        const libelleBadge = menu.theme ?? 'Classique';

        // Prépare les autres informations du menu et leurs valeurs par défaut.
        const image = menu.image ?? '/assets/images/menu-default.jpg';
        const titre = menu.titre ?? 'Menu';
        const note = Number.parseFloat(menu.note_moyenne ?? 0) || 0;
        const nombreAvis = menu.nb_avis ?? 0;
        const description = String(menu.description ?? '').substring(0, 100);
        const prix = Number.parseFloat(menu.prix_base ?? 0);
        const stock = Number.parseInt(menu.stock ?? 0, 10) || 0;
        const id = encodeURIComponent(menu.id ?? '');

        // Calcule l'âge du menu pour afficher le badge « Nouveau » pendant 7 jours.
        const dateCreation = new Date(menu.created_at);
        const joursDepuisCreation =
            (Date.now() - dateCreation.getTime()) / (1000 * 60 * 60 * 24);

        const badgeNouveau =
            Number.isFinite(joursDepuisCreation) &&
            joursDepuisCreation >= 0 &&
            joursDepuisCreation <= 7
                ? '<span class="badge-nouveau">🆕 Nouveau</span>'
                : '';

        // Génère les étoiles correspondant à la note moyenne.
        let etoiles = '';

        for (let i = 1; i <= 5; i++) {
            etoiles += i <= Math.round(note) ? '★' : '☆';
        }

        // Prépare le badge de disponibilité en fonction du stock.
        let badgeStock;

        if (stock <= 0) {
            badgeStock =
                '<span class="badge bg-danger mb-2">❌ Stock épuisé</span>';
        } else if (stock <= 3) {
            badgeStock =
                `<span class="badge bg-warning text-dark mb-2">⚠️ Plus que ${stock}</span>`;
        } else {
            badgeStock =
                '<span class="badge bg-success mb-2">✅ Disponible</span>';
        }

        // Prépare le lien vers la page détaillée du menu.
        const boutonVoir = `
            <a href="/menus/detail?id=${id}" class="btn btn-voir">
                👁 Voir le menu
            </a>
        `;

        // Retourne le code HTML de la carte.
        return `
            <div class="col-md-4 mb-4">
                <div class="card menu-card h-100 animate-scroll">
                    <div class="card-img-wrapper">
                        <img
                            src="${escapeHtml(image)}"
                            alt="Photo du ${escapeHtml(titre)}"
                        >
                        <span class="badge-theme ${classeBadge}">
                            ${escapeHtml(libelleBadge)}
                        </span>
                        ${badgeNouveau}
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold" style="color: #2E6B5E">
                            ${escapeHtml(titre)}
                        </h5>

                        <div class="stars mb-1">
                            ${etoiles}
                            <small class="text-muted">
                                (${escapeHtml(nombreAvis)})
                            </small>
                        </div>

                        <p class="card-text text-muted small flex-grow-1">
                            ${escapeHtml(description)}...
                        </p>

                        <div class="d-flex justify-content-between meta-info mb-2">
                            <span>
                                👥 ${escapeHtml(menu.nb_personnes_min ?? '')}
                                pers. min
                            </span>
                            <span>${escapeHtml(menu.regime ?? '')}</span>
                        </div>

                        <p class="prix-color mb-2">
                            À partir de
                            ${Number.isFinite(prix) ? prix.toFixed(2) : '0.00'} €
                        </p>

                        ${badgeStock}
                        ${boutonVoir}
                    </div>
                </div>
            </div>
        `;
    }).join('');

    // Active les animations sur les cartes qui viennent d'être créées.
    setTimeout(function () {
        animerAuScroll();
    }, 50);
}


// Échappe les caractères spéciaux avant d'insérer une valeur dans le HTML.
function escapeHtml(value) {
    // Associe chaque caractère spécial à son entité HTML correspondante.
    const caracteresAConvertir = {
        '&': '&',
        '<': '<',
        '>': '>',
        '"': '"',
        "'": '&#039;'
    };

    // Convertit la valeur en texte puis remplace les caractères spéciaux.
    return String(value ?? '').replace(/[&<>"']/g, function (caractere) {
        return caracteresAConvertir[caractere];
    });
}


// Anime les éléments lorsqu'ils deviennent visibles à l'écran.
function animerAuScroll() {
    const elements = document.querySelectorAll(
        '.card, .menu-card, .section-card, .alert, .avis-card, .commande-card'
    );

    // Affiche immédiatement les éléments si IntersectionObserver n'est pas disponible.
    if (!('IntersectionObserver' in window)) {
        elements.forEach(function (element) {
            element.classList.add('visible');
        });

        return;
    }

    // Ajoute la classe nécessaire à l'animation.
    elements.forEach(function (element) {
        if (!element.classList.contains('visible')) {
            element.classList.add('animate-scroll');
        }
    });

    // Observe l'apparition des éléments dans la fenêtre.
    const observateur = new IntersectionObserver(function (entrees) {
        entrees.forEach(function (entree, index) {
            if (entree.isIntersecting) {
                // Ajoute la classe visible avec un léger décalage.
                setTimeout(function () {
                    entree.target.classList.add('visible');
                }, index * 100);

                // Arrête d'observer l'élément une fois qu'il est visible.
                observateur.unobserve(entree.target);
            }
        });
    }, {
        rootMargin: '0px 0px -30px 0px'
    });

    // Observe les éléments qui n'ont pas encore été animés.
    elements.forEach(function (element) {
        if (!element.classList.contains('visible')) {
            observateur.observe(element);
        }
    });
}


// Crée le bouton qui permet de revenir en haut de la page.
function boutonRetourHaut() {
    const bouton = document.createElement('button');

    // Définit le texte et le nom accessible du bouton.
    bouton.textContent = '↑';
    bouton.setAttribute('aria-label', 'Retour en haut de page');

    // Définit l'apparence du bouton.
    bouton.style.cssText = `
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        width: 45px;
        height: 45px;
        border: none;
        border-radius: 50%;
        background-color: #5DA99A;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        display: none;
        z-index: 999;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        transition: opacity 0.3s ease, background-color 0.2s;
    `;

    // Assombrit le bouton au survol.
    bouton.addEventListener('mouseover', function () {
        bouton.style.backgroundColor = '#2E6B5E';
    });

    // Rétablit sa couleur lorsque le pointeur quitte le bouton.
    bouton.addEventListener('mouseout', function () {
        bouton.style.backgroundColor = '#5DA99A';
    });

    // Fait remonter la page en douceur au clic.
    bouton.addEventListener('click', function () {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Ajoute le bouton à la page.
    document.body.appendChild(bouton);

    // Affiche le bouton après 300 pixels de défilement.
    window.addEventListener('scroll', function () {
        bouton.style.display = window.scrollY > 300 ? 'block' : 'none';
    });
}