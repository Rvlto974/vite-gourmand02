<?php
require_once 'config/database.php';
$db = (new Database())->connect();

$db->exec("CREATE TABLE IF NOT EXISTS menu_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    menu_id INT NOT NULL,
    url VARCHAR(500) NOT NULL,
    ordre INT DEFAULT 0,
    FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE
)");

echo "✅ Table menu_images créée !";