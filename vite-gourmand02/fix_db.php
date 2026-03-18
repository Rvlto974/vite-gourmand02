<?php
require_once 'config/database.php';
$db = (new Database())->connect();

// Vérifie structure table plats
$stmt = $db->query("DESCRIBE plats");
echo "=== TABLE PLATS ===\n";
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
    echo $col['Field'] . ' - ' . $col['Type'] . "\n";
}

// Vérifie si table menu_images existe
$stmt = $db->query("SHOW TABLES LIKE 'menu_images'");
echo "\n=== TABLE MENU_IMAGES ===\n";
echo $stmt->rowCount() > 0 ? "Existe\n" : "N'existe pas\n";

// Vérifie données plats
$stmt = $db->query("SELECT COUNT(*) as nb FROM plats");
echo "\n=== NB PLATS ===\n";
echo $stmt->fetch()['nb'] . " plats\n";