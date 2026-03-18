<?php
require_once 'config/database.php';
$db = (new Database())->connect();

$db->exec("CREATE TABLE IF NOT EXISTS horaires (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jour VARCHAR(20) NOT NULL,
    heure_ouverture VARCHAR(10) NULL,
    heure_fermeture VARCHAR(10) NULL,
    ferme TINYINT(1) DEFAULT 0
)");

$jours = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'];
$defaults = [
    'Lundi'    => ['09:00','18:00',0],
    'Mardi'    => ['09:00','18:00',0],
    'Mercredi' => ['09:00','18:00',0],
    'Jeudi'    => ['09:00','18:00',0],
    'Vendredi' => ['09:00','18:00',0],
    'Samedi'   => ['09:00','12:00',0],
    'Dimanche' => [null,null,1],
];

foreach ($defaults as $jour => $data) {
    $stmt = $db->prepare("INSERT IGNORE INTO horaires (jour, heure_ouverture, heure_fermeture, ferme) VALUES (?,?,?,?)");
    $stmt->execute([$jour, $data[0], $data[1], $data[2]]);
}

echo "✅ Table horaires créée et remplie !";