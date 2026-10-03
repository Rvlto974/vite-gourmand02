// Afficher ou masquer le mot de passe
document.getElementById('toggleMdp').addEventListener('click', function () {
    const input = document.getElementById('mot_de_passe');

    if (input.type === 'password') {
        input.type = 'text';
        this.textContent = '🙈';
        this.setAttribute('aria-label', 'Masquer le mot de passe');
    } else {
        input.type = 'password';
        this.textContent = '👁️';
        this.setAttribute('aria-label', 'Afficher le mot de passe');
    }
});

// Vérifier le mot de passe pendant la saisie
document.getElementById('mot_de_passe').addEventListener('input', function () {
    const mdp = this.value;
    const regles = [
        { test: mdp.length >= 10, label: '10 caractères minimum' },
        { test: /[A-Z]/.test(mdp), label: '1 majuscule' },
        { test: /[a-z]/.test(mdp), label: '1 minuscule' },
        { test: /\d/.test(mdp), label: '1 chiffre' },
        { test: /[\W_]/.test(mdp), label: '1 caractère spécial' },
    ];

    let html = '<ul class="list-unstyled mt-2 mb-0">';
    regles.forEach(r => {
        html += `<li style="color:${r.test ? '#2E6B5E' : '#adb5bd'}; font-size:0.85rem;">
            ${r.test ? '✅' : '❌'} ${r.label}
        </li>`;
    });
    html += '</ul>';

    document.getElementById('mdp-feedback').innerHTML = html;

    const valide = regles.every(r => r.test);
    this.classList.toggle('is-valid', valide);
    this.classList.toggle('is-invalid', mdp.length > 0 && !valide);
});