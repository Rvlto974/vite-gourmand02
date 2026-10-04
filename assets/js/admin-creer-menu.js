document.addEventListener('DOMContentLoaded', () => {
    // Récupère les boutons et les conteneurs présents sur la page.
    const boutonAjouterImage = document.getElementById('btn-ajouter-image');
    const boutonAjouterPlat = document.getElementById('btn-ajouter-plat');
    const imagesContainer = document.getElementById('images-container');
    const platsContainer = document.getElementById('plats-container');

    // Initialise les index à partir des attributs data-* des conteneurs.
    // Les valeurs par défaut servent notamment sur la page de création.
    let imageIndex = Number(imagesContainer?.dataset.nextImageIndex ?? 1);
    let platIndex = Number(platsContainer?.dataset.nextPlatIndex ?? 0);

    // Ajoute une nouvelle image lorsque le bouton correspondant est cliqué.
    boutonAjouterImage?.addEventListener('click', () => {
        // Arrête l'opération si le conteneur d'images n'existe pas.
        if (!imagesContainer) return;

        // Crée une ligne contenant le champ URL et le bouton de suppression.
        const div = document.createElement('div');
        div.className = 'input-group mb-2 image-row';
        div.innerHTML = `
            <input type="url"
                   name="images[${imageIndex}]"
                   class="form-control"
                   placeholder="https://exemple.com/image.jpg">

            <button type="button"
                    class="btn btn-outline-danger btn-supprimer-image"
                    aria-label="Supprimer cette image">
                ✕
            </button>
        `;

        // Insère la nouvelle ligne dans la galerie.
        imagesContainer.appendChild(div);

        // Incrémente l'index pour éviter les noms de champs en double.
        imageIndex++;
    });

    // Ajoute un nouveau plat lorsque le bouton correspondant est cliqué.
    boutonAjouterPlat?.addEventListener('click', () => {
        // Arrête l'opération si le conteneur des plats n'existe pas.
        if (!platsContainer) return;

        // Crée une carte contenant les champs du nouveau plat.
        const div = document.createElement('div');
        div.className = 'card p-3 mb-3 plat-row';
        div.innerHTML = `
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">
                        Nom
                    </label>
                    <input type="text"
                           name="plats[${platIndex}][nom]"
                           class="form-control form-control-sm"
                           required>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-semibold">
                        Type
                    </label>
                    <select name="plats[${platIndex}][type]"
                            class="form-select form-select-sm">
                        <option value="entree">🥗 Entrée</option>
                        <option value="plat" selected>🍽️ Plat</option>
                        <option value="dessert">🍰 Dessert</option>
                    </select>
                </div>

                <div class="col-md-5">
                    <label class="form-label small fw-semibold">
                        Allergènes
                    </label>
                    <input type="text"
                           name="plats[${platIndex}][allergenes]"
                           class="form-control form-control-sm"
                           placeholder="gluten, lactose...">
                </div>

                <div class="col-12">
                    <label class="form-label small fw-semibold">
                        Description
                    </label>
                    <input type="text"
                           name="plats[${platIndex}][description]"
                           class="form-control form-control-sm"
                           placeholder="Description du plat...">
                </div>
            </div>

            <button type="button"
                    class="btn btn-outline-danger btn-sm mt-2 btn-supprimer-plat">
                🗑️ Supprimer ce plat
            </button>
        `;

        // Insère le nouveau plat dans la liste.
        platsContainer.appendChild(div);

        // Incrémente l'index pour éviter les noms de champs en double.
        platIndex++;
    });

    // Utilise la délégation d'événements pour gérer aussi les éléments
    // ajoutés après le chargement initial de la page.
    document.addEventListener('click', (event) => {
        // Vérifie que la cible du clic est bien un élément HTML.
        if (!(event.target instanceof Element)) return;

        // Supprime la ligne d'image correspondante.
        if (event.target.classList.contains('btn-supprimer-image')) {
            event.target.closest('.image-row')?.remove();
        }

        // Supprime la carte du plat correspondante.
        if (event.target.classList.contains('btn-supprimer-plat')) {
            event.target.closest('.plat-row')?.remove();
        }
    });
});
