<?php

$method = $_SERVER["REQUEST_METHOD"];
$parsed = parse_url($_SERVER['REQUEST_URI']);
$path = $parsed['path'];

$routes = [
    "GET" => [
        "/php-crash/8.Product_List_Website/" => "homeHandler",
        "/php-crash/8.Product_List_Website/products" => "productListHandler"
    ],
    "POST" => [
        "/php-crash/8.Product_List_Website/products" => "createProductHandler"
    ]
];

$handlerFunction = $routes[$method][$path] ?? "notFoundHandler";

$safeHandlerFunction = function_exists($handlerFunction) ? $handlerFunction : "notFounctHandler";

$safeHandlerFunction();

function homeHandler()
{
    require './views/home.php';
}

function productListHandler()
{
    $contents = file_get_contents("./products.json");
    $products = json_decode($contents, true);
    require "./views/product-list.php";
}

function createProductHandler()
{
    $name = $_POST["name"];
    $price = $_POST["price"];

    $newProduct = [
        "name" => $name,
        "price" => (int)$price
    ];

    $content = file_get_contents("./products.json");
    $products = json_decode($content, true);

    array_push($products, $newProduct);

    $json = json_encode($products);
    file_put_contents('./products.json', $json);

    header("Location: /php-crash/8.Product_List_Website/products");

}

function notFoundHandler()
{
    echo "Oldal nem található";
}
