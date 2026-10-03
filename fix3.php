<?php
require_once 'config/config.php';
require_once 'config/database.php';

$db = new Database();
$pdo = $db->connect();

$pdo->exec("ALTER TABLE commandes 
    ADD COLUMN motif_annulation TEXT NULL,
    ADD COLUMN mode_contact VARCHAR(50) NULL");

echo "OK";
?>
