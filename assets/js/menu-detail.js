// Récupère le conteneur du calculateur et les valeurs PHP transmises en data-*.
const calculateur = document.getElementById('calculateur-menu');

if (calculateur) {
    const prixBase = Number(calculateur.dataset.prixBase);
    const nbMin = Number(calculateur.dataset.nbMin);
    const menuId = calculateur.dataset.menuId;

    // Récupère les éléments HTML utilisés par le calculateur.
    const inputPersonnes = document.getElementById('nb_personnes');
    const prixEstime = document.getElementById('prix-estime');
    const boutonPlus = document.getElementById('btn-plus');
    const boutonMoins = document.getElementById('btn-moins');
    const boutonCommander = document.getElementById('btn-commander');

    // Met à jour le prix estimé et le lien de commande.
    function calculerPrix() {
        const nbPersonnes = Number.parseInt(inputPersonnes.value, 10);

        // Évite un nombre de personnes inférieur au minimum du menu.
        if (!Number.isFinite(nbPersonnes) || nbPersonnes < nbMin) {
            inputPersonnes.value = nbMin;
            return calculerPrix();
        }

        // Calcule le prix proportionnel au nombre de personnes.
        let prix = (prixBase / nbMin) * nbPersonnes;

        // Applique une réduction de 10 % à partir de cinq personnes en plus.
        if (nbPersonnes >= nbMin + 5) {
            prix *= 0.90;
        }

        prixEstime.textContent = `${prix.toFixed(2)} €`;

        // Actualise le nombre de personnes transmis à la page de commande.
        if (boutonCommander) {
            boutonCommander.href =
                `/commandes/nouveau?menu_id=${encodeURIComponent(menuId)}`
                + `&nb_personnes=${encodeURIComponent(nbPersonnes)}`;
        }
    }

    // Augmente le nombre de personnes d’une unité.
    boutonPlus.addEventListener('click', () => {
        inputPersonnes.value =
            Number.parseInt(inputPersonnes.value, 10) + 1;

        calculerPrix();
    });

    // Diminue le nombre de personnes sans passer sous le minimum.
    boutonMoins.addEventListener('click', () => {
        const nbPersonnes = Number.parseInt(inputPersonnes.value, 10);

        if (nbPersonnes > nbMin) {
            inputPersonnes.value = nbPersonnes - 1;
            calculerPrix();
        }
    });

    // Recalcule également le prix si l’utilisateur saisit directement une valeur.
    inputPersonnes.addEventListener('input', calculerPrix);
}