<?php require_once 'views/layouts/header.php'; ?>

<style>
.stars { display: flex; flex-direction: row-reverse; justify-content: flex-end; }
.stars input { display: none; }
.stars label { font-size: 2rem; cursor: pointer; color: #ddd; }
.stars input:checked ~ label,
.stars label:hover,
.stars label:hover ~ label { color: #f39c12; }
</style>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="mb-4" style="color:#5DA99A">⭐ Laisser un avis</h1>
            <div class="card p-4 shadow-sm" style="border-radius:12px;">
                <p class="text-muted">Menu : <strong><?= htmlspecialchars($commande['menu_titre']) ?></strong></p>

                <form method="POST" action="/avis/creer?commande_id=<?= $commande['id'] ?>">

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Note *</label>
                        <div class="stars">
                            <input type="radio" name="note" id="star5" value="5">
                            <label for="star5" title="5 étoiles">★</label>
                            <input type="radio" name="note" id="star4" value="4">
                            <label for="star4" title="4 étoiles">★</label>
                            <input type="radio" name="note" id="star3" value="3" checked>
                            <label for="star3" title="3 étoiles">★</label>
                            <input type="radio" name="note" id="star2" value="2">
                            <label for="star2" title="2 étoiles">★</label>
                            <input type="radio" name="note" id="star1" value="1">
                            <label for="star1" title="1 étoile">★</label>
                        </div>
                        <small class="text-muted">Cliquez sur une étoile pour noter</small>
                    </div>

                    <div class="mb-3">
                        <label for="commentaire" class="form-label fw-semibold">Commentaire *</label>
                        <textarea name="commentaire" id="commentaire" class="form-control" rows="4"
                                  placeholder="Partagez votre expérience..." required></textarea>
                    </div>

                    <button type="submit" class="btn w-100 py-2"
                            style="background-color:#5DA99A; color:white; border-radius:8px;">
                        ✅ Envoyer mon avis
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