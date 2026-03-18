<?php
require_once 'config/config.php';
require_once 'config/database.php';

$url = $_GET['url'] ?? 'accueil';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

if (empty($url[0])) {
    $url[0] = 'accueil';
}

$controllerName = ucfirst($url[0]) . 'Controller';
$method = $url[1] ?? 'index';

$controllerFile = 'controllers/' . $controllerName . '.php';
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerName();
    $controller->$method();
} else {
    http_response_code(404);
    echo "Page introuvable";
}