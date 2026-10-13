
'use strict';

// Attend que le HTML de la page soit entièrement chargé.
document.addEventListener('DOMContentLoaded', () => {

    // ============================================================
    // INITIALISATION DES GRAPHIQUES
    // ============================================================

    // Récupère les éléments HTML des graphiques.
    const canvasMenus = document.getElementById('graphMenus');
    const canvasCA = document.getElementById('graphCA');
    const canvasMensuel = document.getElementById('graphMensuel');

    // Vérifie que Chart.js et les deux graphiques existants sont disponibles.
    if (!canvasMenus || !canvasCA || typeof Chart === 'undefined') {
        return;
    }

    // Récupère les données envoyées par PHP.
    const labels = JSON.parse(canvasMenus.dataset.labels);
    const nbCommandes = JSON.parse(canvasMenus.dataset.commandes);
    const caTotal = JSON.parse(canvasMenus.dataset.ca);

    // Définit les couleurs utilisées dans les graphiques.
    const colors = [
        '#5DA99A',
        '#e67e22',
        '#9b59b6',
        '#e74c3c',
        '#2980b9',
        '#27ae60'
    ];

    // Formate un montant en euros selon les conventions françaises.
    const formaterEuros = montant => {
        return new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: 'EUR'
        }).format(montant);
    };

    // ============================================================
    // GRAPHIQUE 1 : NOMBRE DE COMMANDES PAR MENU
    // ============================================================

    // Crée un graphique en barres.
    new Chart(canvasMenus, {
        type: 'bar',

        data: {
            labels: labels,

            datasets: [{
                label: 'Nombre de commandes',
                data: nbCommandes,

                // Couleurs des barres.
                backgroundColor: colors,

                // Arrondit les angles des barres.
                borderRadius: 6
            }]
        },

        options: {
            // Adapte le graphique à son conteneur.
            responsive: true,

            plugins: {
                // Masque la légende.
                legend: {
                    display: false
                }
            },

            scales: {
                y: {
                    // Commence l'axe vertical à zéro.
                    beginAtZero: true,

                    // Affiche des nombres entiers.
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // ============================================================
    // GRAPHIQUE 2 : CHIFFRE D'AFFAIRES PAR MENUS
    // ============================================================

    // Crée un graphique en anneau.
    new Chart(canvasCA, {
        type: 'doughnut',

        data: {
            labels: labels,

            datasets: [{
                // Chiffre d'affaires de chaque menu.
                data: caTotal,

                // Couleurs des différentes portions.
                backgroundColor: colors,

                // Séparation entre les portions.
                borderWidth: 2
            }]
        },

        options: {
            responsive: true,

            plugins: {
                // Affiche les noms des menus à droite.
                // Affiche les noms des menus sous le graphique en anneau.
legend: {
    position: 'bottom',
    labels: {
        usePointStyle: true,
        padding: 20,
        pointStyleWidth: 14,
        font: {
            size: 13
        }
    }
},

                tooltip: {
                    callbacks: {
                        // Affiche le menu et son chiffre d'affaires.
                        label: ctx => {
                            return `${ctx.label}: ${formaterEuros(ctx.parsed)}`;
                        }
                    }
                }
            }
        }
    });

    // ============================================================
    // GRAPHIQUE 3 : ÉVOLUTION MENSUELLE DU CHIFFRE D'AFFAIRES
    // ============================================================

    // Vérifie que le graphique mensuel existe dans stats.php.
    if (canvasMensuel) {

        // Récupère les mois transmis par PHP.
        // Exemple : ["08/2026", "09/2026", "10/2026"]
        const mois = JSON.parse(canvasMensuel.dataset.mois);

        // Récupère le chiffre d'affaires de chaque mois.
        const chiffresAffaires = JSON.parse(canvasMensuel.dataset.ca);

        // Associe chaque mois à son chiffre d'affaires.
        const donneesMensuelles = mois.map((moisLabel, index) => {

            // Sépare le mois et l'année.
            const [moisNumero, annee] = moisLabel.split('/').map(Number);

            return {
                // Libellé affiché sur le graphique.
                mois: moisLabel,

                // Valeur utilisée pour le classement chronologique.
                date: annee * 12 + moisNumero,

                // Chiffre d'affaires correspondant.
                ca: chiffresAffaires[index]
            };

        // Trie les données du mois le plus ancien au plus récent.
        }).sort((a, b) => a.date - b.date);

        // ========================================================
        // CRÉATION DE LA COURBE MENSUELLE
        // ========================================================

        // Conserve le graphique dans une variable pour pouvoir
        // le mettre à jour lorsque l'administrateur change le filtre.
        const graphiqueMensuel = new Chart(canvasMensuel, {
            type: 'line',

            data: {
                // Affiche les mois sur l'axe horizontal.
                labels: donneesMensuelles.map(item => item.mois),

                datasets: [{
                    label: "Chiffre d'affaires",

                    // Montants mensuels.
                    data: donneesMensuelles.map(item => item.ca),

                    // Couleur de la courbe.
                    borderColor: '#5DA99A',

                    // Couleur transparente sous la courbe.
                    backgroundColor: 'rgba(93, 169, 154, 0.15)',

                    // Épaisseur de la courbe.
                    borderWidth: 3,

                    // Remplit la zone sous la courbe.
                    fill: true,

                    // Arrondit légèrement la courbe.
                    tension: 0.3,

                    // Taille des points.
                    pointRadius: 5,

                    // Agrandit les points au survol.
                    pointHoverRadius: 8
                }]
            },

            options: {
                // Adapte la largeur du graphique.
                responsive: true,

                // Respecte la hauteur du conteneur HTML.
                maintainAspectRatio: false,

                // Animation de la courbe.
                animation: {
                    duration: 800,
                    easing: 'easeOutQuart'
                },

                plugins: {
                    tooltip: {
                        callbacks: {
                            // Affiche les montants en euros au survol.
                            label: ctx => formaterEuros(ctx.parsed.y)
                        }
                    }
                },

                scales: {
                    y: {
                        // Commence l'axe vertical à zéro.
                        beginAtZero: true,

                        ticks: {
                            // Affiche les montants en euros sur l'axe.
                            callback: valeur => formaterEuros(valeur)
                        }
                    }
                }
            }
        });

        // ========================================================
        // FILTRE INTERACTIF : 3, 6, 12 MOIS OU TOUTE LA PÉRIODE
        // ========================================================

        // Récupère le menu déroulant présent dans stats.php.
        const filtreMois = document.getElementById('filtreMois');

        // Vérifie que le filtre existe.
        if (filtreMois) {

            // Fonction permettant d'actualiser la courbe.
            const mettreAJourGraphique = () => {

                // Récupère la période sélectionnée.
                const periode = filtreMois.value;

                // Sélectionne les derniers mois disponibles.
                // "all" signifie que toutes les données sont affichées.
                const donneesFiltrees = periode === 'all'
                    ? donneesMensuelles
                    : donneesMensuelles.slice(-Number(periode));

                // Met à jour les mois sur l'axe horizontal.
                graphiqueMensuel.data.labels = donneesFiltrees.map(
                    item => item.mois
                );

                // Met à jour les chiffres d'affaires.
                graphiqueMensuel.data.datasets[0].data = donneesFiltrees.map(
                    item => item.ca
                );

                // Redessine le graphique avec une animation.
                graphiqueMensuel.update();
            };

            // Exécute la fonction lorsque le filtre change.
            filtreMois.addEventListener(
                'change',
                mettreAJourGraphique
            );

            // Applique le filtre sélectionné dès le chargement.
            mettreAJourGraphique();
        }
    }

}); // Fin de DOMContentLoaded.
