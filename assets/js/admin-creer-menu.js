document.addEventListener('DOMContentLoaded', () => {
    let imageIndex = 1;
    let platIndex = 0;

    const boutonAjouterImage = document.getElementById('btn-ajouter-image');
    const boutonAjouterPlat = document.getElementById('btn-ajouter-plat');
    const imagesContainer = document.getElementById('images-container');
    const platsContainer = document.getElementById('plats-container');

    boutonAjouterImage?.addEventListener('click', () => {
        if (!imagesContainer) return;

        const div = document.createElement('div');
        div.className = 'input-group mb-2 image-row';
        div.innerHTML = `
            <input type="url" name="images[${imageIndex}]" class="form-control"
                   placeholder="https://exemple.com/image.jpg">
            <button type="button" class="btn btn-outline-danger btn-supprimer-image">✕</button>
        `;

        imagesContainer.appendChild(div);
        imageIndex++;
    });

    boutonAjouterPlat?.addEventListener('click', () => {
        if (!platsContainer) return;

        const div = document.createElement('div');
        div.className = 'card p-3 mb-3 plat-row';
        div.innerHTML = `
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Nom</label>
                    <input type="text" name="plats[${platIndex}][nom]" class="form-control form-control-sm" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Type</label>
                    <select name="plats[${platIndex}][type]" class="form-select form-select-sm">
                        <option value="entree">🥗 Entrée</option>
                        <option value="plat" selected>🍽️ Plat</option>
                        <option value="dessert">🍰 Dessert</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label small fw-semibold">Allergènes</label>
                    <input type="text" name="plats[${platIndex}][allergenes]" class="form-control form-control-sm"
                           placeholder="gluten, lactose...">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold">Description</label>
                    <input type="text" name="plats[${platIndex}][description]" class="form-control form-control-sm"
                           placeholder="Description du plat...">
                </div>
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm mt-2 btn-supprimer-plat">
                🗑️ Supprimer ce plat
            </button>
        `;

        platsContainer.appendChild(div);
        platIndex++;
    });

    document.addEventListener('click', (event) => {
        if (!(event.target instanceof Element)) return;

        if (event.target.classList.contains('btn-supprimer-image')) {
            event.target.closest('.image-row')?.remove();
        }

        if (event.target.classList.contains('btn-supprimer-plat')) {
            event.target.closest('.plat-row')?.remove();
        }
    });
});
