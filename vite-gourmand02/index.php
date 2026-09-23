<?php
require_once "config/config.php";
require_once "config/database_sqlite.php";

// Parse REQUEST_URI
$request_uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$request_uri = str_replace("/index.php", "", $request_uri);
$request_uri = rtrim($request_uri, "/");

// Route par défaut
if (empty($request_uri) || $request_uri === "") {
    $url = ["accueil"];
} else {
    $url = explode("/", trim($request_uri, "/"));
}

if (empty($url[0])) {
    $url[0] = "accueil";
}

$controllerName = ucfirst($url[0]) . "Controller";

// Détecte si cest un ID numérique
$method = "index";
if (isset($url[1])) {
    if (is_numeric($url[1])) {
        // /menus/1 -> appelle detail() avec id=1
        $method = "detail";
        $_GET["id"] = $url[1];
    } else {
        // /menus/filtrer -> appelle filtrer()
        $method = $url[1];
    }
}

$controllerFile = __DIR__ . "/controllers/" . $controllerName . ".php";

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerName();
    
    if (method_exists($controller, $method)) {
        $controller->$method();
    } else {
        http_response_code(404);
        echo "Erreur 404: Méthode $method non trouvée dans $controllerName";
    }
} else {
    http_response_code(404);
    echo "Erreur 404: Controller $controllerName non trouvé";
}
?>
