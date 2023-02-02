<?php



require './users.php';
require './helpers/compileTemplate.php';
require './helpers/getPathWithId.php';
require './countries.php';
require '../../db/getConnection.php';

$method = $_SERVER["REQUEST_METHOD"];
$parsed = parse_url($_SERVER['REQUEST_URI']);
$path = $parsed['path'];

$routes = [
    'GET' => [
        '/learn-php/13Authentication/server/' => 'countryListHandler',
        '/learn-php/13Authentication/server/check-country' => 'singleCountryHandler',
        '/learn-php/13Authentication/server/check-city' => 'singleCityHandler'
    ],
    'POST' => [
        '/learn-php/13Authentication/server/register' => 'registerHandler',
        '/learn-php/13Authentication/server/login' => 'loginHandler',
        '/learn-php/13Authentication/server/logout' => 'logoutHandler'
    ],
];

$handlerFunction = $routes[$method][$path] ?? "notFoundHandler";

$handlerFunction();





function notFoundHandler()
{
    echo "Oldal nem található";
}
