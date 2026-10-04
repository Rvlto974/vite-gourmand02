document.addEventListener('DOMContentLoaded', () => {
    // Récupère le formulaire. Si la page ne le contient pas, arrête le script.
    const form = document.getElementById('form-commande');
    if (!form) return;

    // Lit les données du menu transmises dans les attributs data-* du formulaire.
    const prixBase = Number(form.dataset.prixBase);
    const nbMin = Number.parseInt(form.dataset.nbMin, 10);

    // Mémorise l'étape actuellement affichée.
    let currentStep = 1;

    // Affiche l'étape demandée et met à jour l'indicateur de progression.
    function afficherEtape(step) {
        document.getElementById(`step-${currentStep}`)?.classList.remove('active');
        document
            .querySelector(`.step[data-step="${currentStep}"]`)
            ?.classList.remove('active');

        currentStep = step;

        document.getElementById(`step-${currentStep}`)?.classList.add('active');
        document
            .querySelector(`.step[data-step="${currentStep}"]`)
            ?.classList.add('active');

        // Marque comme terminées les étapes précédant l'étape courante.
        document.querySelectorAll('.step').forEach((element) => {
            const numero = Number(element.dataset.step);
            element.classList.toggle('done', numero < currentStep);
        });

        // Ajuste la barre de progression entre les trois étapes.
        const progress = document.getElementById('stepper-progress');
        if (progress) {
            progress.style.width = `${((step - 1) / 2) * 80}%`;
        }
    }

    // Calcule le prix du menu, la réduction éventuelle et les frais de livraison.
    function calculerPrix() {
        const personnes =
            Number.parseInt(document.getElementById('nb_personnes').value, 10) || nbMin;

        const adresse = document
            .getElementById('adresse_livraison')
            .value
            .toLowerCase();

        const km =
            Number.parseFloat(document.getElementById('km_livraison').value) || 0;

        // Considère la livraison gratuite si l'adresse indique Bordeaux ou le code 33000.
        const estBordeaux =
            adresse.includes('bordeaux') || adresse.includes('33000');

        // Applique 10 % de réduction à partir de cinq personnes au-dessus du minimum.
        const reduction = personnes >= nbMin + 5;
        const prixMenu = reduction ? prixBase * 0.9 : prixBase;

        // Calcule les frais hors Bordeaux : 5 € plus 0,59 € par kilomètre.
        const fraisLivraison = estBordeaux ? 0 : 5 + km * 0.59;

        // Met à jour les montants affichés sur la page.
        document.getElementById('prix-menu').textContent =
            `${prixMenu.toFixed(2)} €`;

        document.getElementById('prix-livraison').textContent =
            `${fraisLivraison.toFixed(2)} €`;

        document.getElementById('prix-total').textContent =
            `${(prixMenu + fraisLivraison).toFixed(2)} €`;

        // Stocke les frais de livraison dans le champ envoyé au serveur.
        document.getElementById('frais-livraison-input').value =
            fraisLivraison.toFixed(2);

        // Affiche ou masque les messages et lignes de prix selon les conditions.
        document.getElementById('reduction-msg').style.display =
            reduction ? 'block' : 'none';

        document.getElementById('livraison-msg').style.display =
            estBordeaux ? 'none' : 'block';

        document.getElementById('ligne-livraison').style.display =
            estBordeaux ? 'none' : 'flex';

        // La distance est demandée uniquement si une adresse hors Bordeaux est saisie.
        document.getElementById('champ-km').style.display =
            estBordeaux || !adresse ? 'none' : 'block';

        return estBordeaux;
    }

    // Au clic, vérifie le nombre de personnes avant de passer à l'étape Livraison.
    document.getElementById('btn-step1-next')?.addEventListener('click', () => {
        const champPersonnes = document.getElementById('nb_personnes');

        if (!champPersonnes.reportValidity()) return;

        afficherEtape(2);
    });

    // Le bouton Retour de l'étape Livraison ramène aux informations du client.
    document.getElementById('btn-step2-prev')?.addEventListener('click', () => {
        afficherEtape(1);
    });

    // Vérifie les informations de livraison avant d'afficher la confirmation.
    document.getElementById('btn-step2-next')?.addEventListener('click', () => {
        const date = document.getElementById('date_prestation');
        const heure = document.getElementById('heure_livraison');
        const adresse = document.getElementById('adresse_livraison');

        if (!date.reportValidity() || !heure.reportValidity() || !adresse.reportValidity()) {
            return;
        }

        const estBordeaux = calculerPrix();
        const champKm = document.getElementById('km_livraison');

        // Pour une adresse hors Bordeaux, exige une distance avant de continuer.
        if (!estBordeaux && !champKm.value) {
            champKm.setCustomValidity('Veuillez indiquer la distance de livraison.');
            champKm.reportValidity();
            champKm.setCustomValidity('');
            return;
        }

        afficherEtape(3);
    });

    // Le bouton Retour de la confirmation ramène à l'étape Livraison.
    document.getElementById('btn-step3-prev')?.addEventListener('click', () => {
        afficherEtape(2);
    });

    // Recalcule les prix lorsque le nombre de personnes, l'adresse ou la distance change.
    ['nb_personnes', 'adresse_livraison', 'km_livraison'].forEach((id) => {
        document.getElementById(id)?.addEventListener('input', calculerPrix);
    });

    // Initialise les prix et l'affichage des frais dès le chargement de la page.
    calculerPrix();
});