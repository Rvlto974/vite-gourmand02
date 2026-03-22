<?php require_once 'views/layouts/header.php'; ?>

<style>
.commande-card { border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
.prix-box { background: #f8f9fa; border-radius: 8px; padding: 15px; }
.reduction-badge { background: #27ae60; color: white; border-radius: 20px; padding: 3px 10px; font-size: 0.8rem; }
.livraison-badge { background: #e67e22; color: white; border-radius: 20px; padding: 3px 10px; font-size: 0.8rem; }

.stepper { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; position: relative; }
.stepper::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 10%;
    right: 10%;
    height: 3px;
    background: #dee2e6;
    z-index: 0;
}
.stepper-progress {
    position: absolute;
    top: 20px;
    left: 10%;
    height: 3px;
    background: #5DA99A;
    z-index: 1;
    transition: width 0.4s ease;
    width: 0%;
}
.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    z-index: 2;
}
.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #dee2e6;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    border: 3px solid #dee2e6;
}
.step.active .step-circle {
    background: #5DA99A;
    color: white;
    border-color: #5DA99A;
    box-shadow: 0 0 0 4px rgba(93,169,154,0.2);
}
.step.done .step-circle {
    background: #2E6B5E;
    color: white;
    border-color: #2E6B5E;
}
.step-label {
    font-size: 0.75rem;
    margin-top: 6px;
    color: #6c757d;
    font-weight: 500;
    text-align: center;
}
.step.active .step-label { color: #5DA99A; font-weight: 700; }
.step.done .step-label  { color: #2E6B5E; }
.form-section { display: none; }
.form-section.active { display: block; }
</style>

<div class="container mt-5 mb-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item"><a href="/menus">Nos Menus</a></li>
            <li class="breadcrumb-item active">Commander</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-7">
            <div class="card commande-card p-4">
                <h1 class="h3 mb-4" style="color:#5DA99A">🛒 Passer une commande</h1>

                <!-- Barre de progression -->
                <div class="stepper" aria-label="Étapes de la commande">
                    <div class="step active" data-step="1">
                        <div class="step-circle">1</div>
                        <span class="step-label">Vos infos</span>
                    </div>
                    <div class="step" data-step="2">
                        <div class="step-circle">2</div>
                        <span class="step-label">Livraison</span>
                    </div>
                    <div class="step" data-step="3">
                        <div class="step-circle">3</div>
                        <span class="step-label">Confirmation</span>
                    </div>
                    <div class="stepper-progress" id="stepper-progress"></div>
                </div>

                <form method="POST" action="/commandes/nouveau?menu_id=<?= $menu['id'] ?>"
                      aria-label="Formulaire de commande" novalidate id="form-commande">

                    <!-- ÉTAPE 1 -->
                    <div class="form-section active" id="step-1">
                        <h5 class="mb-3" style="color:#2E6B5E;">👤 Vos informations</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="prenom" class="form-label fw-semibold">Prénom</label>
                                <input type="text" class="form-control" id="prenom" name="prenom"
                                       value="<?= htmlspecialchars($utilisateur['prenom'] ?? '') ?>"
                                       readonly aria-readonly="true">
                            </div>
                            <div class="col-md-6">
                                <label for="nom" class="form-label fw-semibold">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom"
                                       value="<?= htmlspecialchars($utilisateur['nom'] ?? '') ?>"
                                       readonly aria-readonly="true">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email_client" class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" id="email_client" name="email_client"
                                   value="<?= htmlspecialchars($utilisateur['email'] ?? '') ?>"
                                   readonly aria-readonly="true">
                        </div>
                        <div class="mb-3">
                            <label for="gsm" class="form-label fw-semibold">GSM <span aria-hidden="true">*</span></label>
                            <input type="tel" class="form-control" id="gsm" name="gsm"
                                   value="<?= htmlspecialchars($utilisateur['gsm'] ?? '') ?>"
                                   aria-required="true" required>
                        </div>
                        <div class="mb-3">
                            <label for="nb_personnes" class="form-label fw-semibold">
                                Nombre de personnes <span aria-hidden="true">*</span>
                            </label>
                            <input type="number" class="form-control" id="nb_personnes" name="nb_personnes"
                                   value="<?= $menu['nb_personnes_min'] ?>"
                                   min="<?= $menu['nb_personnes_min'] ?>"
                                   aria-required="true" required>
                            <small class="text-muted">Minimum <?= $menu['nb_personnes_min'] ?> personnes</small>
                        </div>
                        <button type="button" class="btn w-100 py-2 mt-2" id="btn-step1-next"
                                style="background-color:#5DA99A; color:white; border-radius:8px;">
                            Continuer → Livraison
                        </button>
                    </div>

                    <!-- ÉTAPE 2 -->
                    <div class="form-section" id="step-2">
                        <h5 class="mb-3" style="color:#2E6B5E;">🚚 Livraison</h5>
                        <div class="mb-3">
                            <label for="date_prestation" class="form-label fw-semibold">
                                Date de prestation <span aria-hidden="true">*</span>
                            </label>
                            <input type="date" class="form-control" id="date_prestation" name="date_prestation"
                                   min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                                   aria-required="true" required>
                        </div>
                        <div class="mb-3">
                            <label for="heure_livraison" class="form-label fw-semibold">
                                Heure de livraison <span aria-hidden="true">*</span>
                            </label>
                            <input type="time" class="form-control" id="heure_livraison" name="heure_livraison"
                                   aria-required="true" required>
                        </div>
                        <div class="mb-3">
                            <label for="adresse_livraison" class="form-label fw-semibold">
                                Adresse de livraison <span aria-hidden="true">*</span>
                            </label>
                            <textarea class="form-control" id="adresse_livraison" name="adresse_livraison"
                                      rows="3" aria-required="true" required
                                      placeholder="Votre adresse complète..."></textarea>
                            <small class="text-muted">⚠️ Frais supplémentaires si hors Bordeaux : 5€ + 0,59€/km</small>
                        </div>
                        <div class="mb-3" id="champ-km" style="display:none;">
                            <label for="km_livraison" class="form-label fw-semibold">
                                Distance depuis Bordeaux (km) <span aria-hidden="true">*</span>
                            </label>
                            <input type="number" class="form-control" id="km_livraison" name="km_livraison"
                                   min="1" step="1" placeholder="Ex: 15">
                            <small class="text-muted">Estimez la distance en km depuis le centre de Bordeaux</small>
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <button type="button" class="btn btn-outline-secondary py-2 px-4" id="btn-step2-prev">
                                ← Retour
                            </button>
                            <button type="button" class="btn py-2 flex-grow-1" id="btn-step2-next"
                                    style="background-color:#5DA99A; color:white; border-radius:8px;">
                                Continuer → Confirmation
                            </button>
                        </div>
                    </div>

                    <!-- ÉTAPE 3 -->
                    <div class="form-section" id="step-3">
                        <h5 class="mb-3" style="color:#2E6B5E;">✅ Récapitulatif</h5>
                        <div class="prix-box mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted">Prix menu :</span>
                                <span id="prix-menu"><?= number_format($menu['prix_base'], 2) ?> €</span>
                            </div>
                            <div id="ligne-livraison" style="display:none;" class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted">Frais de livraison :</span>
                                <span id="prix-livraison">0.00 €</span>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semibold">Prix total :</span>
                                <span class="fw-bold fs-5" style="color:#e67e22" id="prix-total"><?= number_format($menu['prix_base'], 2) ?> €</span>
                            </div>
                            <div id="reduction-msg" class="mt-1" style="display:none;">
                                <span class="reduction-badge">✓ Réduction 10% appliquée</span>
                            </div>
                            <div id="livraison-msg" class="mt-1" style="display:none;">
                                <span class="livraison-badge">🚚 Frais de livraison hors Bordeaux</span>
                            </div>
                            <small class="text-muted d-block mt-1">Réduction de 10% pour <?= $menu['nb_personnes_min'] + 5 ?>+ personnes</small>
                        </div>
                        <input type="hidden" name="frais_livraison" id="frais-livraison-input" value="0">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary py-2 px-4" id="btn-step3-prev">
                                ← Retour
                            </button>
                            <button type="submit" class="btn py-2 flex-grow-1"
                                    style="background-color:#5DA99A; color:white; border-radius:8px; font-size:1.1rem;">
                                ✅ Confirmer la commande
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <div class="col-md-4 offset-md-1">
            <div class="card commande-card p-4">
                <img src="<?= !empty($menu['image']) ? htmlspecialchars($menu['image']) : '/assets/images/menu-default.jpg' ?>"
                     alt="<?= htmlspecialchars($menu['titre']) ?>"
                     style="border-radius:8px; width:100%; height:180px; object-fit:cover;" class="mb-3">
                <h5 style="color:#5DA99A"><?= htmlspecialchars($menu['titre']) ?></h5>
                <p class="text-muted small"><?= htmlspecialchars(mb_substr($menu['description'], 0, 120)) ?>...</p>
                <hr>
                <p class="mb-1"><strong>Prix de base :</strong> <?= number_format($menu['prix_base'], 2) ?> €</p>
                <p class="mb-1"><strong>Minimum :</strong> <?= $menu['nb_personnes_min'] ?> personnes</p>
                <p class="mb-0"><strong>Thème :</strong> <?= ucfirst($menu['theme']) ?></p>
            </div>
            <div class="card commande-card p-3 mt-3" style="border-left: 4px solid #e67e22;">
                <h6 class="fw-semibold">🚚 Frais de livraison</h6>
                <p class="small text-muted mb-1">✅ <strong>Gratuit</strong> à Bordeaux</p>
                <p class="small text-muted mb-0">📍 Hors Bordeaux : <strong>5,00 € + 0,59 €/km</strong></p>
            </div>
        </div>
    </div>
</div>

<script>
const prixBase = <?= $menu['prix_base'] ?>;
const nbMin    = <?= $menu['nb_personnes_min'] ?>;
let currentStep = 1;

function goToStep(step) {
    document.getElementById(`step-${currentStep}`).classList.remove('active');
    document.querySelectorAll('.step').forEach(s => {
        const n = parseInt(s.dataset.step);
        s.classList.remove('active', 'done');
        if (n < step) s.classList.add('done');
        if (n === step) s.classList.add('active');
    });
    const progress = ((step - 1) / 2) * 80;
    document.getElementById('stepper-progress').style.width = progress + '%';
    currentStep = step;
    document.getElementById(`step-${currentStep}`).classList.add('active');
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

document.getElementById('btn-step1-next').addEventListener('click', function() {
    const gsm = document.getElementById('gsm').value;
    const nb  = document.getElementById('nb_personnes').value;
    if (!gsm || !nb) { alert('Veuillez remplir tous les champs obligatoires.'); return; }
    goToStep(2);
});

document.getElementById('btn-step2-prev').addEventListener('click', () => goToStep(1));
document.getElementById('btn-step2-next').addEventListener('click', function() {
    const date    = document.getElementById('date_prestation').value;
    const heure   = document.getElementById('heure_livraison').value;
    const adresse = document.getElementById('adresse_livraison').value;
    if (!date || !heure || !adresse) { alert('Veuillez remplir tous les champs obligatoires.'); return; }
    calculerPrix();
    goToStep(3);
});
document.getElementById('btn-step3-prev').addEventListener('click', () => goToStep(2));

function calculerPrix() {
    const nb      = parseInt(document.getElementById('nb_personnes').value) || nbMin;
    const adresse = document.getElementById('adresse_livraison').value.toLowerCase();
    const km      = parseFloat(document.getElementById('km_livraison').value) || 0;

    let prixMenu = prixBase;
    if (nb >= nbMin + 5) {
        prixMenu *= 0.90;
        document.getElementById('reduction-msg').style.display = 'block';
    } else {
        document.getElementById('reduction-msg').style.display = 'none';
    }

    const estBordeaux = adresse.includes('bordeaux') || adresse.includes('33000') ||
                        adresse.includes('33100') || adresse.includes('33200') ||
                        adresse.includes('33300') || adresse.includes('33800');

    let fraisLivraison = 0;
    if (!estBordeaux && adresse.length > 5) {
        document.getElementById('champ-km').style.display = 'block';
        document.getElementById('livraison-msg').style.display = 'block';
        document.getElementById('ligne-livraison').style.display = 'flex';
        fraisLivraison = 5 + (km * 0.59);
    } else {
        document.getElementById('champ-km').style.display = 'none';
        document.getElementById('livraison-msg').style.display = 'none';
        document.getElementById('ligne-livraison').style.display = 'none';
        fraisLivraison = 0;
    }

    const prixTotal = prixMenu + fraisLivraison;
    document.getElementById('prix-menu').textContent       = prixMenu.toFixed(2) + ' €';
    document.getElementById('prix-livraison').textContent  = fraisLivraison.toFixed(2) + ' €';
    document.getElementById('prix-total').textContent      = prixTotal.toFixed(2) + ' €';
    document.getElementById('frais-livraison-input').value = fraisLivraison.toFixed(2);
}

document.getElementById('nb_personnes').addEventListener('input', calculerPrix);
document.getElementById('adresse_livraison').addEventListener('input', calculerPrix);
document.getElementById('km_livraison').addEventListener('input', calculerPrix);
</script>

<?php require_once 'views/layouts/footer.php'; ?>