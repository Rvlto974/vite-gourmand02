<?php
require_once 'config/config.php';
require_once __DIR__ . '/../../config/database_sqlite.php'';
$db = (new Database())->connect();
$stmt = $db->query('DESCRIBE utilisateurs');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));