// Récupère le champ du nombre de personnes.
const champPersonnes = document.getElementById('nb_personnes');

if (champPersonnes) {
    // Lit les valeurs transmises par les attributs data-* du champ.
    const prixBase = parseFloat(champPersonnes.dataset.prixBase);
    const nbMin = parseInt(champPersonnes.dataset.nbMin, 10);

    const prixTotal = document.getElementById('prix-total');
    const messageReduction = document.getElementById('reduction-msg');

    // Recalcule le prix à chaque changement du nombre de personnes.
    champPersonnes.addEventListener('input', function () {
        const nbPersonnes = parseInt(this.value, 10) || nbMin;
        const reductionAppliquee = nbPersonnes >= nbMin + 5;
        const prix = reductionAppliquee ? prixBase * 0.90 : prixBase;

        // Met à jour le prix affiché et la visibilité du message de réduction.
        prixTotal.textContent = prix.toFixed(2) + ' €';
        messageReduction.style.display = reductionAppliquee ? 'block' : 'none';
    });
}
