document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.toggle-ferme').forEach((checkbox) => {
        checkbox.addEventListener('change', function () {
            const row = this.closest('tr');
            if (!row) return;

            row.querySelectorAll('input[type="time"]').forEach((input) => {
                input.disabled = this.checked;
                if (this.checked) input.value = '';
            });
        });
    });
});
