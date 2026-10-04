// Attend que le document soit chargé avant de rechercher le message flash.
document.addEventListener('DOMContentLoaded', () => {
    const toast = document.getElementById('flashToast');

    // Si aucun message flash n'est présent, il n'y a rien à masquer.
    if (!toast) {
        return;
    }

    // Masque le message après trois secondes.
    setTimeout(() => {
        toast.style.display = 'none';
    }, 3000);
});
