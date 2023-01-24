<?php

$method = $_SERVER["REQUEST_METHOD"];
$parsed = parse_url($_SERVER['REQUEST_URI']);
$path = $parsed['path'];

$routes = [
    "GET" => [
        "/php-crash/10.CRUD/" => "homeHandler",
        "/php-crash/10.CRUD/products" => "productListHandler"
    ],
    "POST" => [
        "/php-crash/10.CRUD/products" => "createProductHandler",
        "/php-crash/10.CRUD/delete-product" => "deleteProductHandler"
    ]
];

$handlerFunction = $routes[$method][$path] ?? "notFoundHandler";

$safeHandlerFunction = function_exists($handlerFunction) ? $handlerFunction : "notFounctHandler";

$safeHandlerFunction();



function deleteProductHandler()
{
    $deletedProductId = $_GET["id"] ?? "";
    $products = json_decode(file_get_contents("./products.json"), true);

    $foundProductIndex = -1; // 0 index valid index , ha sikertelen a keresés ezt akarjuk a memoriába tudni nem a validot

    foreach ($products as $index => $product) {

        if ($product["id"] === $deletedProductId) {
            $foundProductIndex = $index;
            break; // Iteráció azonnal befejeződik
        }
    }

    echo $foundProductIndex;

    if ($foundProductIndex === -1) {
        header("Location: /php-crash/10.CRUD/products");
        return;
    }

    array_splice($products, $foundProductIndex, 1);

    file_put_contents("./products.json", json_encode($products));
    header("Location: /php-crash/10.CRUD/products");
}


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
        "activeLink" => "/php-crash/10.CRUD/"
    ]);
}

function productListHandler()
{
    $contents = file_get_contents("./products.json");
    $products = json_decode($contents, true);


    $productListTemplate = compileTemplate("./views/product-list.php", [
        "products" => $products,
        "activeLink" => "/php-crash/10.CRUD/"
    ]);

    echo compileTemplate('./views/wrapper.php', [
        "innerTemplate" => $productListTemplate,
        "activeLink" => "/php-crash/10.CRUD/products"
    ]);
}

function createProductHandler()
{
    $name = $_POST["name"];
    $price = $_POST["price"];

    $newProduct = [
        "id" => uniqid(),
        "name" => $name,
        "price" => (int)$price
    ];

    $content = file_get_contents("./products.json");
    $products = json_decode($content, true);

    array_push($products, $newProduct);

    $json = json_encode($products);
    file_put_contents('./products.json', $json);

    header("Location: /php-crash/10.CRUD/products");
}

function notFoundHandler()
{
    echo "Oldal nem található";
}
