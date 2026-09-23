<?php
require_once '../config/database_sqlite.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    $db = (new Database())->connect();
    $method = $_SERVER['REQUEST_METHOD'];
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = str_replace('/api/', '', $path);
    $parts = explode('/', trim($path, '/'));
    
    $resource = $parts[0] ?? '';
    $id = $parts[1] ?? null;
    $subresource = $parts[2] ?? null;
    
    // Route: /api/menus/{id}/plats
    if ($resource === 'menus' && $id && $subresource === 'plats' && $method === 'GET') {
        $stmt = $db->prepare("SELECT id, nom, type, description, allergenes FROM plats WHERE menu_id = ? ORDER BY type");
        $stmt->execute([$id]);
        $plats = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Trier par ordre: entree, plat, dessert
        $order = ['entree' => 0, 'plat' => 1, 'dessert' => 2];
        usort($plats, function($a, $b) use ($order) {
            $orderA = $order[$a['type']] ?? 999;
            $orderB = $order[$b['type']] ?? 999;
            return $orderA - $orderB;
        });
        
        echo json_encode(['success' => true, 'data' => $plats]);
        exit;
    }
    
    switch ($resource) {
        case 'plats':
            if ($method === 'GET') {
                if ($id) {
                    // GET /api/plats/{id}
                    $stmt = $db->prepare("SELECT id, nom, type, description, allergenes FROM plats WHERE id = ?");
                    $stmt->execute([$id]);
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo json_encode(['success' => true, 'data' => $data ?: null]);
                } else {
                    // GET /api/plats
                    $stmt = $db->query("SELECT id, nom, type, description, allergenes FROM plats");
                    echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
                }
            }
            break;
            
        case 'menus':
            if ($method === 'GET') {
                if ($id) {
                    // GET /api/menus/{id}
                    $stmt = $db->prepare("SELECT id, titre, description FROM menus WHERE id = ?");
                    $stmt->execute([$id]);
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo json_encode(['success' => true, 'data' => $data ?: null]);
                } else {
                    // GET /api/menus
                    $stmt = $db->query("SELECT id, titre, description FROM menus");
                    echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
                }
            }
            break;
            
        case 'avis':
            if ($method === 'GET') {
                if ($id) {
                    // GET /api/avis/{id}
                    $stmt = $db->prepare("SELECT id, commande_id, note, commentaire FROM avis WHERE id = ?");
                    $stmt->execute([$id]);
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo json_encode(['success' => true, 'data' => $data ?: null]);
                } else {
                    // GET /api/avis
                    $stmt = $db->query("SELECT id, commande_id, note, commentaire FROM avis");
                    echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
                }
            }
            break;
            
        default:
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Resource not found']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
