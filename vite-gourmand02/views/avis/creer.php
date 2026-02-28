<?php require_once 'views/layouts/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="mb-4" style="color:#5DA99A">⭐ Laisser un avis</h1>

            <div class="card p-4">
                <p class="text-muted">Menu : <strong><?= htmlspecialchars($commande['menu_titre']) ?></strong></p>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Note</label>
                        <select name="note" class="form-select" required>
                            <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                            <option value="4">⭐⭐⭐⭐ Très bien</option>
                            <option value="3">⭐⭐⭐ Bien</option>
                            <option value="2">⭐⭐ Moyen</option>
                            <option value="1">⭐ Décevant</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Commentaire</label>
                        <textarea name="commentaire" class="form-control" rows="4" 
                                  placeholder="Partagez votre expérience..." required></textarea>
                    </div>
                    <button type="submit" class="btn w-100" style="background-color:#5DA99A; color:white;">
                        Envoyer mon avis
                    </button>
                </form>
            </div>

            <div class="mt-3">
                <a href="/commandes/historique" class="btn btn-outline-secondary">← Retour</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>