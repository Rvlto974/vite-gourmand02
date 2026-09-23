<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . "/../config/database_sqlite.php";
require_once __DIR__ . "/controllers/ApiController.php";

$controller = new ApiController();
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim(str_replace('/api', '', $uri), '/');
$query = $_GET;
$data = json_decode(file_get_contents('php://input'), true);

$response = ['success' => false, 'error' => 'Ressource non trouvée'];

try {
    switch (true) {
        // ====== MENUS ======
        case $path === 'menus' && $method === 'GET':
            $response = isset($query['id']) 
                ? $controller->getMenuById($query['id'])
                : $controller->getMenus();
            break;

        // ====== PLATS ======
        case $path === 'plats' && $method === 'GET':
            $response = isset($query['id'])
                ? $controller->getPlatById($query['id'])
                : $controller->getPlats();
            break;

        // ====== AVIS GET ======
        case $path === 'avis' && $method === 'GET':
            $response = isset($query['id'])
                ? $controller->getAvisById($query['id'])
                : $controller->getAvis();
            break;

        // ====== AVIS POST ======
        case $path === 'avis' && $method === 'POST':
            $response = $controller->createAvis($data);
            break;

        // ====== AVIS /:id PUT/DELETE ======
        case preg_match('#^avis/(\d+)$#', $path, $m_avis):
            $avis_id = $m_avis[1];
            if ($method === 'GET') {
                $response = $controller->getAvisById($avis_id);
            } elseif ($method === 'PUT') {
                $response = $controller->updateAvis($avis_id, $data);
            } elseif ($method === 'DELETE') {
                $response = $controller->deleteAvis($avis_id);
            }
            break;

        // ====== COMMANDES GET ======
        case $path === 'commandes' && $method === 'GET':
            $response = isset($query['id'])
                ? $controller->getCommandeById($query['id'])
                : $controller->getCommandes();
            break;

        // ====== COMMANDES POST ======
        case $path === 'commandes' && $method === 'POST':
            $response = $controller->createCommande($data);
            break;

        // ====== COMMANDES/:id GET/PUT/DELETE ======
        case preg_match('#^commandes/(\d+)$#', $path, $m1):
            if ($method === 'GET') {
                $response = $controller->getCommandeById($m1[1]);
            } elseif ($method === 'PUT') {
                $response = $controller->updateCommande($m1[1], $data);
            } elseif ($method === 'DELETE') {
                $response = $controller->deleteCommande($m1[1]);
            }
            break;

        // ====== COMMANDES/:id/statuts GET ======
        case preg_match('#^commandes/(\d+)/statuts$#', $path, $m2) && $method === 'GET':
            $response = $controller->getCommandeStatuts($m2[1]);
            break;

        // ====== UTILISATEURS ======
        case $path === 'utilisateurs' && $method === 'GET':
            $response = isset($query['id'])
                ? $controller->getUtilisateurById($query['id'])
                : $controller->getUtilisateurs();
            break;

        case $path === 'utilisateurs' && $method === 'POST':
            $response = $controller->createUtilisateur($data);
            break;

        case preg_match('#^utilisateurs/(\d+)$#', $path, $m3):
            if ($method === 'GET') {
                $response = $controller->getUtilisateurById($m3[1]);
            } elseif ($method === 'PUT') {
                $response = $controller->updateUtilisateur($m3[1], $data);
            } elseif ($method === 'DELETE') {
                $response = $controller->deleteUtilisateur($m3[1]);
            }
            break;
    }
} catch (Exception $e) {
    $response = ['success' => false, 'error' => $e->getMessage()];
}

http_response_code($response['success'] ? 200 : 400);
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
