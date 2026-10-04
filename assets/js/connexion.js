// Attend que le document HTML soit entièrement chargé avant d’exécuter le script.
document.addEventListener('DOMContentLoaded', () => {
    // Récupère le bouton d’affichage du mot de passe et le champ correspondant.
    const bouton = document.getElementById('toggleMdp');
    const input = document.getElementById('mot_de_passe');

    // Arrête le script si le bouton ou le champ est absent de la page.
    if (!bouton || !input) {
        return;
    }

    // Alterne entre l’affichage et le masquage du mot de passe au clic.
    bouton.addEventListener('click', () => {
        const afficher = input.type === 'password';

        // Change le type du champ pour afficher ou masquer le mot de passe.
        input.type = afficher ? 'text' : 'password';

        // Met à jour l’icône et le texte accessible du bouton.
        bouton.textContent = afficher ? '🙈' : '👁️';
        bouton.setAttribute(
            'aria-label',
            afficher ? 'Masquer le mot de passe' : 'Afficher le mot de passe'
        );
    });
});