document.addEventListener('DOMContentLoaded', () => {
    const filtreClient = document.getElementById('filtre-client');
    const filtreStatut = document.getElementById('filtre-statut');
    const table = document.getElementById('table-commandes');
    const compteur = document.getElementById('compteur-resultats');
    const modalAnnuler = document.getElementById('modalAnnuler');

    // Cette page peut ne pas contenir le tableau des commandes.
    if (!filtreClient || !filtreStatut || !table || !compteur) return;

    function filtrerCommandes() {
        const client = filtreClient.value.toLowerCase();
        const statut = filtreStatut.value;
        const rows = table.querySelectorAll('tbody tr');
        let count = 0;

        rows.forEach((row) => {
            const matchClient = (row.dataset.client || '').includes(client);
            const matchStatut = !statut || row.dataset.statut === statut;
            const visible = matchClient && matchStatut;

            row.style.display = visible ? '' : 'none';
            if (visible) count++;
        });

        compteur.textContent = count + ' commande(s)';
    }

    filtreClient.addEventListener('input', filtrerCommandes);
    filtreStatut.addEventListener('change', filtrerCommandes);

    if (modalAnnuler) {
        modalAnnuler.addEventListener('show.bs.modal', (event) => {
            const bouton = event.relatedTarget;
            if (!bouton) return;

            const champId = document.getElementById('modal-commande-id');
            if (champId) {
                champId.value = bouton.getAttribute('data-id') || '';
            }
        });
    }

    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((element) => {
            new bootstrap.Tooltip(element);
        });
    }
});