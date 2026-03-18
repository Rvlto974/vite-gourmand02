<?php
require_once 'config/database.php';
$db = (new Database())->connect();

$stmt = $db->query("SELECT id, titre FROM menus");
echo "=== MENUS ===\n";
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
    echo "id: {$r['id']} | titre: {$r['titre']}\n";
}

$stmt = $db->query("SELECT * FROM menu_images");
echo "\n=== MENU IMAGES ===\n";
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
    echo "menu_id: {$r['menu_id']} | url: {$r['url']}\n";
}