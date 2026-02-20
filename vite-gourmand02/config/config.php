<?php
// Chargement des variables d'environnement
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'vite_gourmand');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');

// Configuration application
define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost:8080');
define('APP_ENV', $_ENV['APP_ENV'] ?? 'development');

// Règles de tarification
define('LIVRAISON_BASE', 5.00);
define('LIVRAISON_KM', 0.59);
define('REDUCTION_PERSONNES', 5);
define('REDUCTION_TAUX', 0.10);