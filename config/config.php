<?php

// Charge le fichier .env uniquement pour le développement local.
$envFile = __DIR__ . '/../.env';

if (is_file($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        // Ne remplace jamais une variable déjà fournie par Fly.io.
        if ($key !== '' && getenv($key) === false) {
            $_ENV[$key] = $value;
        }
    }
}

// Récupère les variables de Fly.io ou du fichier .env local.
function envValue(string $key, string $default): string
{
    $value = getenv($key);

    if ($value !== false) {
        return $value;
    }

    return $_ENV[$key] ?? $default;
}

// Environnement de l'application.
define('APP_ENV', envValue('APP_ENV', 'production'));
define('APP_URL', envValue('APP_URL', 'http://localhost:8080'));

// Affichage des erreurs uniquement en développement.
$isDevelopment = APP_ENV === 'development';

ini_set('display_errors', $isDevelopment ? '1' : '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

// Configuration MySQL.
define('DB_HOST', envValue('DB_HOST', 'mysql'));
define('DB_NAME', envValue('DB_NAME', 'vite_gourmand'));
define('DB_USER', envValue('DB_USER', 'root'));
define('DB_PASS', envValue('DB_PASS', 'root'));
define('DB_PORT', envValue('DB_PORT', '3306'));

// Livraison et réductions.
define('LIVRAISON_BASE', 5.00);
define('LIVRAISON_KM', 0.59);
define('REDUCTION_PERSONNES', 5);
define('REDUCTION_TAUX', 0.10);
