// Attend que le HTML de la page soit entièrement chargé.
document.addEventListener('DOMContentLoaded', () => {
    // Récupère les deux canvas qui afficheront les graphiques.
    const canvasMenus = document.getElementById('graphMenus');
    const canvasCA = document.getElementById('graphCA');

    // Arrête le script si les canvas ou la bibliothèque Chart.js sont absents.
    if (!canvasMenus || !canvasCA || typeof Chart === 'undefined') {
        return;
    }

    // Récupère les données enregistrées dans les attributs data-* du premier canvas.
    const labels = JSON.parse(canvasMenus.dataset.labels);
    const nbCommandes = JSON.parse(canvasMenus.dataset.commandes);
    const caTotal = JSON.parse(canvasMenus.dataset.ca);

    // Définit les couleurs utilisées pour les menus dans les graphiques.
    const colors = [
        '#5DA99A',
        '#e67e22',
        '#9b59b6',
        '#e74c3c',
        '#2980b9',
        '#27ae60'
    ];

    // Crée un graphique en barres montrant le nombre de commandes par menu.
    new Chart(canvasMenus, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Nombre de commandes',
                data: nbCommandes,
                backgroundColor: colors,
                borderRadius: 6
            }]
        },
        options: {
            // Le graphique s’adapte à la taille de son conteneur.
            responsive: true,
            plugins: {
                // Masque la légende, car elle n’est pas nécessaire ici.
                legend: { display: false }
            },
            scales: {
                // L’axe vertical commence à zéro et affiche des nombres entiers.
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    // Crée un graphique en anneau montrant le chiffre d’affaires par menu.
    new Chart(canvasCA, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data: caTotal,
                backgroundColor: colors,
                borderWidth: 2
            }]
        },
        options: {
            // Le graphique s’adapte à la taille de son conteneur.
            responsive: true,
            plugins: {
                // Affiche la légende à droite du graphique.
                legend: { position: 'right' },
                tooltip: {
                    callbacks: {
                        // Affiche le nom du menu et son chiffre d’affaires en euros.
                        label: ctx =>
                            `${ctx.label}: ${ctx.parsed.toFixed(2)} \u20AC`
                    }
                }
            }
        }
    });
});