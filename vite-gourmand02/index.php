<?php
// Chargement de la configuration
require_once 'config/config.php';
require_once 'config/database.php';

// Récupération de la route
$url = $_GET['url'] ?? 'accueil';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Définition du contrôleur et de la méthode
$controllerName = ucfirst($url[0]) . 'Controller';
$method = $url[1] ?? 'index';

// Chargement du contrôleur
$controllerFile = 'controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerName();
    $controller->$method();
} else {
    http_response_code(404);
    require_once 'views/404.php';
}
