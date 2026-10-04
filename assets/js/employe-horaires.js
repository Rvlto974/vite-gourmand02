// Attend que le document HTML soit entièrement chargé.
document.addEventListener('DOMContentLoaded', () => {
    // Repère chaque case permettant d’indiquer qu’un jour est fermé.
    document.querySelectorAll('.toggle-ferme').forEach((checkbox) => {
        // Met à jour les champs horaires lorsque la case change d’état.
        checkbox.addEventListener('change', function () {
            // Récupère la ligne du tableau associée à cette case.
            const row = this.closest('tr');

            // Arrête le traitement si aucune ligne correspondante n’existe.
            if (!row) return;

            // Désactive les champs horaires si le jour est fermé.
            row.querySelectorAll('input[type="time"]').forEach((input) => {
                input.disabled = this.checked;

                // Efface les horaires lorsque le jour est marqué comme fermé.
                if (this.checked) input.value = '';
            });
        });
    });
});