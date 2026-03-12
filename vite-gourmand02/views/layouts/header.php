<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite & Gourmand</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="icon" type="image/x-icon" href="/assets/favicon.ico">
</head>
<body>

<?php
// Flash message
if (isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}
?>

<?php if (isset($flash)) : ?>
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div class="toast show align-items-center text-white border-0
        <?= $flash['type'] === 'success' ? 'bg-success' : 'bg-danger' ?>"
        role="alert" id="flashToast">
        <div class="d-flex">
            <div class="toast-body"><?= htmlspecialchars($flash['message']) ?></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
<script>
    setTimeout(() => {
        const toast = document.getElementById('flashToast');
        if (toast) toast.style.display = 'none';
    }, 3000);
</script>
<?php endif; ?>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/" style="color:#2E6B5E;">Vite & Gourmand</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="/menus">Nos Menus</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/contact">Contact</a>
                </li>
                <?php if (isset($_SESSION['user_id'])) : ?>
                    <?php if ($_SESSION['user_role'] === 'employe') : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/employe/dashboard">Espace employé</a>
                        </li>
                    <?php elseif ($_SESSION['user_role'] === 'admin') : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/dashboard">Espace admin</a>
                        </li>
                    <?php else : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/commandes/historique">Mes commandes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/utilisateurs/profil">Mon profil</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="btn btn-outline-secondary btn-sm ms-2" href="/auth/deconnexion">Déconnexion</a>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="btn btn-outline-secondary btn-sm ms-2" href="/auth/connexion">Connexion</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

