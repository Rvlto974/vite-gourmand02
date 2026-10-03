document.addEventListener('DOMContentLoaded', () => {
    const bouton = document.getElementById('toggleMdp');
    const input = document.getElementById('mot_de_passe');

    if (!bouton || !input) {
        return;
    }

    bouton.addEventListener('click', () => {
        const afficher = input.type === 'password';
        input.type = afficher ? 'text' : 'password';
        bouton.textContent = afficher ? '🙈' : '👁️';
        bouton.setAttribute(
            'aria-label',
            afficher ? 'Masquer le mot de passe' : 'Afficher le mot de passe'
        );
    });
});
