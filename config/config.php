<?php
// Charge les variables du fichier .env s’il existe.
if (file_exists(__DIR__ . '/../.env')) {
    // Lit le fichier ligne par ligne, en ignorant les lignes vides.
    $lines = file(
        __DIR__ . '/../.env',
        FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
    );

    foreach ($lines as $line) {
        // Ignore les lignes sans signe égal et les commentaires.
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            // Sépare le nom de la variable de sa valeur.
            [$key, $value] = explode('=', $line, 2);

            // Ajoute la variable à l’environnement après avoir retiré les espaces.
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// Active l’affichage des erreurs et des avertissements PHP.
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Définit la configuration de connexion à la base de données.
define('DB_HOST', $_ENV['DB_HOST'] ?? 'mysql');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'vite_gourmand');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? 'root');

// Définit l’URL et l’environnement de l’application.
define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost:8080');
define('APP_ENV', $_ENV['APP_ENV'] ?? 'development');

// Définit les paramètres de livraison et de réduction.
define('LIVRAISON_BASE', 5.00);
define('LIVRAISON_KM', 0.59);
define('REDUCTION_PERSONNES', 5);
define('REDUCTION_TAUX', 0.10);