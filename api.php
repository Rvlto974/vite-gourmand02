<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Sécurité basique
if ($_GET['key'] !== 'vite_gourmand_secret') {
    http_response_code(403);
    die(json_encode(['error' => 'Accès refusé']));
}

// Lire directement les variables d'environnement fly.io
$db_host = getenv('DB_HOST') ?: 'mysql';
$db_port = getenv('DB_PORT') ?: '3306';
$db_name = getenv('DB_NAME') ?: 'vite_gourmand';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASS') ?: 'root';

try {
    $pdo = new PDO(
        'mysql:host=' . $db_host . ';port=' . $db_port . ';dbname=' . $db_name . ';charset=utf8',
        $db_user,
        $db_pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $menus = $pdo->query('SELECT * FROM menus')->fetchAll(PDO::FETCH_ASSOC);
    $users = $pdo->query('SELECT id, nom, prenom, email, role FROM utilisateurs')->fetchAll(PDO::FETCH_ASSOC);
    $commandes = $pdo->query('SELECT * FROM commandes')->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'menus'     => $menus,
        'users'     => $users,
        'commandes' => $commandes,
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}