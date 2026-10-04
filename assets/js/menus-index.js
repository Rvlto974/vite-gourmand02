// Récupère les curseurs de prix et leurs libellés
const prixMax = document.getElementById('prix_max');
const prixMin = document.getElementById('prix_min');
const prixMaxLabel = document.getElementById('prix-max-label');
const prixMinLabel = document.getElementById('prix-min-label');

// Met à jour le libellé du prix maximum et relance le filtrage
if (prixMax && prixMaxLabel) {
    prixMax.addEventListener('input', function () {
        prixMaxLabel.textContent = `${this.value} €`;

        // Fonction définie dans assets/js/main.js
        if (typeof appliquerFiltres === 'function') {
            appliquerFiltres();
        }
    });
}

// Met à jour le libellé du prix minimum et relance le filtrage
if (prixMin && prixMinLabel) {
    prixMin.addEventListener('input', function () {
        prixMinLabel.textContent = `${this.value} €`;

        // Fonction définie dans assets/js/main.js
        if (typeof appliquerFiltres === 'function') {
            appliquerFiltres();
        }
    });
}