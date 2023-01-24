<?php

$method = $_SERVER["REQUEST_METHOD"];
$parsed = parse_url($_SERVER['REQUEST_URI']);
$path = $parsed['path'];

$routes = [
    "GET" => [
        "/php-crash/9.Templates_and_compositions/" => "homeHandler",
        "/php-crash/9.Templates_and_compositions/products" => "productListHandler"
    ],
    "POST" => [
        "/php-crash/9.Templates_and_compositions/products" => "createProductHandler"
    ]
];

$handlerFunction = $routes[$method][$path] ?? "notFoundHandler";

$safeHandlerFunction = function_exists($handlerFunction) ? $handlerFunction : "notFounctHandler";

$safeHandlerFunction();





function compileTemplate($filePath, $params = []): string
{
    ob_start(); // ob = output buffering => kiirt adatok pl echo v require nem pumpálódik bele a kimenetbe, hanem memoriába mentődik
    require $filePath;
    return ob_get_clean(); // Addig amig az ob_get_clean()-el ki nem szeded a felgyülemlett tartalmat , és ürited a memoriát
}



function homeHandler()
{
    $homeTemplate = compileTemplate('./views/home.php');
    echo compileTemplate('./views/wrapper.php', [
        "innerTemplate" => $homeTemplate,
        "activeLink" => "/php-crash/9.Templates_and_compositions/"
    ]);
}

function productListHandler()
{
    $contents = file_get_contents("./products.json");
    $products = json_decode($contents, true);

    
    $productListTemplate = compileTemplate("./views/product-list.php", [
        "products" => $products,
        "activeLink" => "/php-crash/9.Templates_and_compositions/"
    ]);

    echo compileTemplate('./views/wrapper.php', [
        "innerTemplate" => $productListTemplate,
        "activeLink" => "/php-crash/9.Templates_and_compositions/products"
    ]);
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

    header("Location: /php-crash/9.Templates_and_compositions/products");
}

function notFoundHandler()
{
    echo "Oldal nem található";
}
