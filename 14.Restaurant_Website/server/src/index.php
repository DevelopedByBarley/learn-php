<?php

require './router.php';
require './slugifier.php';

$method = $_SERVER["REQUEST_METHOD"];
$parsed = parse_url($_SERVER['REQUEST_URI']);
$path = $parsed['path'];

// Útvonalak regisztrálása
$routes = [
    // [method, útvonal, handlerFunction],
    ['GET', '/learn-php/14.Restaurant_Website/server/', 'homeHandler'],
    ['GET', '/learn-php/14.Restaurant_Website/server/admin/etel-szerkesztese/{keresoBaratNev}', 'dishEditHandler'],
];

// Útvonalválasztó inicializálása
$dispatch = registerRoutes($routes);
$matchedRoute = $dispatch($method, $path);
$handlerFunction = $matchedRoute['handler'];
$handlerFunction($matchedRoute['vars']);

// Handler függvények deklarálása
function homeHandler()
{
    echo render("wrapper.phtml");
}

function dishEditHandler($vars)
{
    echo "<pre>";
    var_dump($vars);
    echo 'Étel szerkesztése: ' . $vars['keresoBaratNev'];
}

function notFoundHandler()
{
    echo 'Oldal nem található';
}

function render($path, $params = [])
{
    ob_start();
    require __DIR__ . '/views/' . $path;
    return ob_get_clean();
}

function getConnection()
{
    return new PDO(
        'mysql:host=' . $_SERVER['DB_HOST'] . ';dbname=' . $_SERVER['DB_NAME'],
        $_SERVER['DB_USER'],
        $_SERVER['DB_PASSWORD']
    );
}
