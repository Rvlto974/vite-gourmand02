<?php
require_once 'config/config.php';
require_once 'config/database.php';
$db = (new Database())->connect();
$stmt = $db->query('DESCRIBE utilisateurs');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));