// Attend que le document HTML soit entièrement chargé avant d’accéder aux éléments.
document.addEventListener('DOMContentLoaded', () => {
    // Récupère les champs de filtre, le tableau et le compteur de résultats.
    const filtreClient = document.getElementById('filtre-client');
    const filtreStatut = document.getElementById('filtre-statut');
    const table = document.getElementById('table-commandes');
    const compteur = document.getElementById('compteur-resultats');

    // Récupère la fenêtre modale d’annulation, si elle existe sur la page.
    const modalAnnuler = document.getElementById('modalAnnuler');

    // Arrête le script si les éléments nécessaires au filtrage sont absents.
    if (!filtreClient || !filtreStatut || !table || !compteur) {
        return;
    }

    // Filtre les lignes du tableau selon le nom du client et le statut choisi.
    function filtrerCommandes() {
        // Convertit la recherche client en minuscules pour ignorer la casse.
        const client = filtreClient.value.toLowerCase();

        // Récupère le statut sélectionné.
        const statut = filtreStatut.value;

        // Sélectionne toutes les lignes du corps du tableau.
        const rows = table.querySelectorAll('tbody tr');
        let count = 0;

        rows.forEach((row) => {
            // Vérifie si le nom du client contient le texte recherché.
            const matchClient = (row.dataset.client || '').includes(client);

            // Vérifie si le statut de la ligne correspond au filtre choisi.
            const matchStatut = !statut || row.dataset.statut === statut;

            // La ligne est visible si elle correspond aux deux filtres.
            const visible = matchClient && matchStatut;
            row.style.display = visible ? '' : 'none';

            // Compte uniquement les lignes visibles.
            if (visible) {
                count++;
            }
        });

        // Met à jour le nombre de commandes affichées.
        compteur.textContent = count + ' commande(s)';
    }

    // Relance le filtrage lorsque le texte recherché change.
    filtreClient.addEventListener('input', filtrerCommandes);

    // Relance le filtrage lorsque le statut sélectionné change.
    filtreStatut.addEventListener('change', filtrerCommandes);

    // Met à jour l’identifiant de commande dans la modale d’annulation.
    if (modalAnnuler) {
        modalAnnuler.addEventListener('show.bs.modal', (event) => {
            // Récupère le bouton qui a ouvert la modale.
            const bouton = event.relatedTarget;
            if (!bouton) {
                return;
            }

            // Place l’identifiant du bouton dans le champ caché de la modale.
            const champId = document.getElementById('modal-commande-id');
            if (champId) {
                champId.value = bouton.getAttribute('data-id') || '';
            }
        });
    }

    // Initialise les infobulles Bootstrap si la bibliothèque est chargée.
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((element) => {
            new bootstrap.Tooltip(element);
        });
    }
});