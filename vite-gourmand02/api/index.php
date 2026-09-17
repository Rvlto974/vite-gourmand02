<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once '../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/api/', '', $path);
$parts = explode('/', trim($path, '/'));

$resource = $parts[0] ?? '';
$id = $parts[1] ?? null;

try {
    $db = (new Database())->connect();

    switch ($resource) {
        case 'plats':
            if ($method === 'GET') {
                $stmt = $db->query("SELECT id, nom, type, description, allergenes FROM plats");
                echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
            }
            break;

        case 'menus':
            if ($method === 'GET') {
                $query = "SELECT id, titre, description, theme, regime, nb_personnes_min, prix_base, stock, actif, image FROM menus ORDER BY id DESC";
                $stmt = $db->query($query);
                echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
            }
            break;

        case 'commandes':
            if ($method === 'GET') {
                $query = "
                    SELECT c.id, c.utilisateur_id, c.menu_id, c.nb_personnes, c.prix_total, c.statut, 
                           c.adresse_livraison, c.date_prestation, c.created_at, c.heure_livraison,
                           u.prenom, u.nom, u.email, m.titre as menu_titre
                    FROM commandes c
                    LEFT JOIN utilisateurs u ON c.utilisateur_id = u.id
                    LEFT JOIN menus m ON c.menu_id = m.id
                    ORDER BY c.created_at DESC
                    LIMIT 50
                ";
                $stmt = $db->query($query);
                echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
            }
            break;

        case 'utilisateurs':
            if ($method === 'GET' && $id) {
                $stmt = $db->prepare("SELECT id, prenom, nom, email, role FROM utilisateurs WHERE id = ?");
                $stmt->execute([$id]);
                echo json_encode(['success' => true, 'data' => $stmt->fetch(PDO::FETCH_ASSOC)]);
            }
            break;

        case 'avis':
            if ($method === 'GET') {
                // Les avis sont liés aux commandes, pas aux menus
                $query = "
                    SELECT a.id, a.note, a.commentaire, a.created_at as date_avis, a.valide,
                           u.prenom, u.nom, 
                           c.id as commande_id, m.titre as menu_titre
                    FROM avis a
                    LEFT JOIN utilisateurs u ON a.utilisateur_id = u.id
                    LEFT JOIN commandes c ON a.commande_id = c.id
                    LEFT JOIN menus m ON c.menu_id = m.id
                    ORDER BY a.created_at DESC
                    LIMIT 50
                ";
                $stmt = $db->query($query);
                echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
            }
            break;

        default:
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Ressource non trouvée']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
