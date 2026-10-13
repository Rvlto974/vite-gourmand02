
'use strict';

// ============================================================
// VITE & GOURMAND — STATISTIQUES ADMINISTRATEUR
// ============================================================
//
// Graphique 1 : Nombre de commandes par menu.
// Graphique 2 : Répartition du chiffre d'affaires par menu.
// Graphique 3 : Évolution du chiffre d'affaires.
//
// Le troisième graphique permet :
// - Un affichage par jour.
// - Un affichage par semaine ISO.
// - Un affichage par mois.
// - Un filtre sur les 3, 6 ou 12 derniers mois.
//
// Les données proviennent de MongoDB via PHP.
// ============================================================


// Attendre le chargement complet du HTML.
document.addEventListener('DOMContentLoaded', () => {

    // ========================================================
    // 1. INITIALISATION
    // ========================================================

    // Récupération des éléments HTML.
    const canvasMenus = document.getElementById('graphMenus');
    const canvasCA = document.getElementById('graphCA');
    const canvasMensuel = document.getElementById('graphMensuel');

    // Vérifier que Chart.js est disponible.
    if (typeof Chart === 'undefined') {
        console.error('Chart.js est indisponible.');
        return;
    }

    // Couleurs utilisées dans les graphiques.
    const colors = [
        '#5DA99A',
        '#e67e22',
        '#9b59b6',
        '#e74c3c',
        '#2980b9',
        '#27ae60'
    ];

    /**
     * Formater un montant en euros.
     *
     * Exemple : 1250.50 devient 1 250,50 €.
     */
    const formaterEuros = montant => {
        return new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: 'EUR'
        }).format(Number(montant) || 0);
    };

    /**
     * Lire un tableau JSON stocké dans un attribut data-*.
     *
     * En cas de données absentes ou invalides,
     * retourner un tableau vide.
     */
    const lireTableau = (element, attribut) => {
        if (!element) {
            return [];
        }

        try {
            const valeur = element.dataset[attribut];

            const resultat = JSON.parse(valeur || '[]');

            return Array.isArray(resultat) ? resultat : [];

        } catch (erreur) {
            console.error(
                'Données statistiques invalides :',
                attribut,
                erreur
            );

            return [];
        }
    };

    // ========================================================
    // 2. GRAPHIQUE : COMMANDES PAR MENU
    // ========================================================

    if (canvasMenus) {

        // Récupération des données fournies par PHP.
        const labels = lireTableau(canvasMenus, 'labels');
        const commandes = lireTableau(canvasMenus, 'commandes');

        // Création du graphique en barres.
        new Chart(canvasMenus, {
            type: 'bar',

            data: {
                labels: labels,

                datasets: [{
                    label: 'Nombre de commandes',
                    data: commandes,

                    // Couleurs et angles des barres.
                    backgroundColor: colors,
                    borderRadius: 6
                }]
            },

            options: {
                responsive: true,

                plugins: {
                    // Masquer la légende.
                    legend: {
                        display: false
                    }
                },

                scales: {
                    y: {
                        // Commencer à zéro.
                        beginAtZero: true,

                        ticks: {
                            // Afficher des nombres entiers.
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    // ========================================================
    // 3. GRAPHIQUE : CHIFFRE D'AFFAIRES PAR MENU
    // ========================================================

    if (canvasCA) {

        // Récupération des données du graphique en anneau.
        const labels = lireTableau(canvasCA, 'labels');
        const montants = lireTableau(canvasCA, 'ca');

        new Chart(canvasCA, {
            type: 'doughnut',

            data: {
                labels: labels,

                datasets: [{
                    label: "Chiffre d'affaires",
                    data: montants,

                    // Couleurs des portions.
                    backgroundColor: colors,

                    // Séparation entre les portions.
                    borderWidth: 2
                }]
            },

            options: {
                responsive: true,

                plugins: {
                    // Légende sous le graphique.
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

                    // Montant affiché au survol.
                    tooltip: {
                        callbacks: {
                            label: ctx => {
                                return `${ctx.label}: ${formaterEuros(ctx.parsed)}`;
                            }
                        }
                    }
                }
            }
        });
    }

    // ========================================================
    // 4. GRAPHIQUE : ÉVOLUTION DU CHIFFRE D'AFFAIRES
    // ========================================================

    // Ne rien faire si ce graphique n'existe pas.
    if (!canvasMensuel) {
        return;
    }

    /**
     * Récupérer les données des trois granularités.
     *
     * Les attributs attendus dans stats.php sont :
     *
     * data-jours / data-ca-jours
     * data-semaines / data-ca-semaines
     * data-mois / data-ca
     */

    const donnees = {
        jour: {
            labels: lireTableau(canvasMensuel, 'jours'),
            valeurs: lireTableau(canvasMensuel, 'caJours')
        },

        semaine: {
            labels: lireTableau(canvasMensuel, 'semaines'),
            valeurs: lireTableau(canvasMensuel, 'caSemaines')
        },

        mois: {
            labels: lireTableau(canvasMensuel, 'mois'),
            valeurs: lireTableau(canvasMensuel, 'ca')
        }
    };

    /**
     * Convertir les données en points chronologiques.
     *
     * Chaque point contient :
     * - Un libellé.
     * - Une date permettant de filtrer.
     * - Un montant.
     */

    const convertirDonnees = (mode, source) => {

        return source.labels.map((label, index) => {

            let date = null;

            if (mode === 'jour') {

                // Format attendu : JJ/MM/AAAA.
                const [jour, mois, annee] =
                    String(label).split('/').map(Number);

                date = new Date(Date.UTC(
                    annee,
                    mois - 1,
                    jour
                ));

            } else if (mode === 'semaine') {

                // Format attendu : Sxx/AAAA.
                // Exemple : S42/2026.
                const correspondance =
                    /^S?(\d{1,2})\/(\d{4})$/.exec(String(label));

                if (correspondance) {

                    const semaine = Number(correspondance[1]);
                    const annee = Number(correspondance[2]);

                    // Le 4 janvier appartient toujours
                    // à la première semaine ISO.
                    const janvier4 = new Date(
                        Date.UTC(annee, 0, 4)
                    );

                    // Jour ISO : lundi = 1, dimanche = 7.
                    const jourISO =
                        janvier4.getUTCDay() || 7;

                    // Retrouver le lundi de la semaine 1.
                    janvier4.setUTCDate(
                        janvier4.getUTCDate() - jourISO + 1
                    );

                    // Avancer jusqu'à la semaine souhaitée.
                    janvier4.setUTCDate(
                        janvier4.getUTCDate() + (semaine - 1) * 7
                    );

                    date = janvier4;
                }

            } else {

                // Format attendu : MM/AAAA.
                const [mois, annee] =
                    String(label).split('/').map(Number);

                date = new Date(Date.UTC(
                    annee,
                    mois - 1,
                    1
                ));
            }

            return {
                label: String(label),
                date: date,
                ca: Number(source.valeurs[index]) || 0
            };

        }).filter(item => {

            // Exclure les dates invalides.
            return item.date instanceof Date &&
                !Number.isNaN(item.date.getTime());

        }).sort((a, b) => {

            // Trier du plus ancien au plus récent.
            return a.date - b.date;
        });
    };

    // Préparation des trois séries de données.
    const donneesParMode = {
        jour: convertirDonnees('jour', donnees.jour),

        semaine: convertirDonnees(
            'semaine',
            donnees.semaine
        ),

        mois: convertirDonnees('mois', donnees.mois)
    };

    // ========================================================
    // 5. CRÉATION DE LA COURBE
    // ========================================================

    const graphiqueEvolution = new Chart(canvasMensuel, {
        type: 'line',

        data: {
            labels: [],

            datasets: [{
                label: "Chiffre d'affaires",
                data: [],

                // Couleur principale de la courbe.
                borderColor: '#5DA99A',

                // Couleur transparente sous la courbe.
                backgroundColor: 'rgba(93, 169, 154, 0.15)',

                // Épaisseur et remplissage.
                borderWidth: 3,
                fill: true,

                // Courbe légèrement arrondie.
                tension: 0.3,

                // Taille des points.
                pointRadius: 5,
                pointHoverRadius: 8
            }]
        },

        options: {
            responsive: true,

            // Conserver la hauteur définie en HTML.
            maintainAspectRatio: false,

            animation: {
                duration: 800,
                easing: 'easeOutQuart'
            },

            plugins: {
                // Montants en euros au survol.
                tooltip: {
                    callbacks: {
                        label: ctx => {
                            return formaterEuros(ctx.parsed.y);
                        }
                    }
                }
            },

            scales: {
                y: {
                    beginAtZero: true,

                    ticks: {
                        // Afficher les euros sur l'axe vertical.
                        callback: valeur => {
                            return formaterEuros(valeur);
                        }
                    }
                }
            }
        }
    });

    // ========================================================
    // 6. FILTRES INTERACTIFS
    // ========================================================

    // Ancien filtre : 3, 6, 12 mois ou toute la période.
    const filtreMois = document.getElementById('filtreMois');

    // Nouveau filtre : Jour, Semaine ou Mois.
    const filtreGranularite =
        document.getElementById('filtreGranularite');

    /**
     * Récupérer le mode d'affichage sélectionné.
     *
     * Par défaut, conserver le mode mensuel.
     */
    const obtenirMode = () => {

        const mode = filtreGranularite
            ? filtreGranularite.value
            : 'mois';

        return ['jour', 'semaine', 'mois'].includes(mode)
            ? mode
            : 'mois';
    };

    /**
     * Mettre à jour le graphique.
     *
     * Le filtre de période est exprimé en mois,
     * quel que soit le mode d'affichage.
     *
     * Exemple :
     * - Jour + 3 mois : détail journalier sur 3 mois.
     * - Semaine + 6 mois : détail hebdomadaire sur 6 mois.
     * - Mois + 12 mois : détail mensuel sur 12 mois.
     */
    const mettreAJourGraphique = () => {

        const mode = obtenirMode();

        // Lire la période sélectionnée.
        const periode = filtreMois
            ? filtreMois.value
            : 'all';

        // Récupérer les données du mode choisi.
        let points = donneesParMode[mode];

        // Appliquer le filtre temporel si nécessaire.
        if (periode !== 'all') {

            const nombreMois = Number(periode);

            if (Number.isFinite(nombreMois) && nombreMois > 0) {

                // Utiliser le dernier jour du mois courant
                // comme limite de référence.
                const maintenant = new Date();

                const debut = new Date(Date.UTC(
                    maintenant.getFullYear(),
                    maintenant.getMonth() - nombreMois + 1,
                    1
                ));

                // Conserver uniquement les données
                // appartenant à la période sélectionnée.
                points = points.filter(item => {
                    return item.date >= debut;
                });
            }
        }

        // Actualiser les libellés.
        graphiqueEvolution.data.labels =
            points.map(item => item.label);

        // Actualiser les montants.
        graphiqueEvolution.data.datasets[0].data =
            points.map(item => item.ca);

        // Adapter le titre de la série.
        const titres = {
            jour: "Chiffre d'affaires par jour",
            semaine: "Chiffre d'affaires par semaine",
            mois: "Chiffre d'affaires par mois"
        };

        graphiqueEvolution.data.datasets[0].label =
            titres[mode];

        // Redessiner la courbe.
        graphiqueEvolution.update();
    };

    // ========================================================
    // 7. ÉVÉNEMENTS DES FILTRES
    // ========================================================

    // Réagir au changement de période.
    if (filtreMois) {
        filtreMois.addEventListener(
            'change',
            mettreAJourGraphique
        );
    }

    // Réagir au changement Jour / Semaine / Mois.
    if (filtreGranularite) {
        filtreGranularite.addEventListener(
            'change',
            mettreAJourGraphique
        );
    }

    // Afficher les données dès le chargement.
    mettreAJourGraphique();

}); // Fin de DOMContentLoaded.
