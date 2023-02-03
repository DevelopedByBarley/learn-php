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
    ['GET', '/learn-php/14.Restaurant_Website/server/admin', 'adminDashboardHandler'],
    ['GET', '/learn-php/14.Restaurant_Website/server/admin/dish-types', 'dishTypeListHandler'],
    ['GET', '/learn-php/14.Restaurant_Website/server/admin/edit-dish/{slug}', 'dishEditHandler'],
    ['GET', '/learn-php/14.Restaurant_Website/server/admin/create-new-dish', 'createNewDishHandler'],
    ['POST', '/learn-php/14.Restaurant_Website/server/admin/delete-dish/{dishId}', 'deleteDishHandler'],
    ['POST', '/learn-php/14.Restaurant_Website/server/admin/create-dish', 'createDishHandler'],
    ['POST', '/learn-php/14.Restaurant_Website/server/login', 'loginHandler'],
    ['POST', '/learn-php/14.Restaurant_Website/server/admin/update-dish/{dishId}', 'updateDishHandler'],
    ['POST', '/learn-php/14.Restaurant_Website/server/admin/create-dish-type', 'createDishTypeHandler'],
    ['POST', '/learn-php/14.Restaurant_Website/server/admin/logout', 'logoutAdminHandler'],
];

// Útvonalválasztó inicializálása
$dispatch = registerRoutes($routes);
$matchedRoute = $dispatch($method, $path);
$handlerFunction = $matchedRoute['handler'];
$handlerFunction($matchedRoute['vars']);

// Handler függvények deklarálása
function homeHandler()
{
    $pdo = getConnection();
    $stmt = $pdo->prepare('SELECT * FROM `dishtypes`');
    $stmt->execute();
    $dishTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($dishTypes as $index => $dishType) {
        $stmt = $pdo->prepare('SELECT * FROM `dishes` WHERE isActive = 1 AND dishTypeId = ?');
        $stmt->execute([$dishType["id"]]);
        $dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);


        $dishTypes[$index]['dishes'] = $dishes;
    }



    echo render("wrapper.phtml", [
        "content" => render("public-menu.phtml", [
            "dishTypesWithDishes" => $dishTypes
        ])
    ]);
}
function createNewDishHandler()
{
    redirectIfTheUserNotLoggedIn();
    $pdo = getConnection();
    $stmt = $pdo->prepare('SELECT * FROM `dishtypes`');
    $stmt->execute();
    $dishTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo render("admin-wrapper.phtml", [
        "content" => render("create-dish.phtml", [
            "dishTypes" => $dishTypes
        ])
    ]);
}


function logoutAdminHandler() {
    session_start();
    session_destroy();
    $cookieParams = session_get_cookie_params();
    var_dump($cookieParams);

    setcookie(session_name(), "", 0, $cookieParams["path"], $cookieParams["domain"], $cookieParams["secure"], isset($cookieParams["httponly"]));
    
    header('Location: /learn-php/14.Restaurant_Website/server/');
}


function createDishHandler()
{
    $pdo = getConnection();
    $stmt = $pdo->prepare(
        "INSERT INTO `dishes` 
        (`name`, `slug`, `description`, `price`, `isActive`, `dishTypeId`) 
        VALUES 
        (:name, :slug, :description, :price, :isActive, :dishTypeId);"
    );
    $stmt->execute([
        "name" => $_POST["name"],
        "slug" => slugify($_POST["slug"]),
        "description" => $_POST["description"],
        "price" => $_POST["price"],
        "isActive" => (int)isset($_POST["isActive"]),
        "dishTypeId" => $_POST["dishTypeId"],
    ]);

    header("Location: /learn-php/14.Restaurant_Website/server/admin");
}

function deleteDishHandler($vars) {
    redirectIfTheUserNotLoggedIn();
    $pdo = getConnection();
    $stmt = $pdo->prepare('DELETE FROM `dishes` WHERE id = ?');
    $stmt->execute([$vars["dishId"]]);
  
    header("Location: /learn-php/14.Restaurant_Website/server/admin");
    
}

function dishEditHandler($vars)
{
    redirectIfTheUserNotLoggedIn();

    $pdo = getConnection();
    $stmt = $pdo->prepare('SELECT * FROM `dishes` WHERE slug = ?');
    $stmt->execute([$vars["slug"]]);
    $dish = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare('SELECT * FROM `dishtypes`');
    $stmt->execute();
    $dishTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);


    echo render("wrapper.phtml", [
        "content" => render("edit-dish.phtml", [
            "dish" => $dish,
            "dishTypes" => $dishTypes
        ])
    ]);
}



function dishTypeListHandler() {
    redirectIfTheUserNotLoggedIn();
    $pdo = getConnection();
    $stmt = $pdo->prepare('SELECT * FROM `dishtypes`');
    $stmt->execute();
    $dishTypes= $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo render("admin-wrapper.phtml", [
        "content" => render("dish-type-list.phtml", [
            "dishTypes" => $dishTypes
        ]) 
    ]);

}


function createDishTypeHandler() {
    redirectIfTheUserNotLoggedIn();
    $pdo = getConnection();
    $stmt = $pdo->prepare("INSERT INTO `dishtypes` 
    (`id`, `name`, `slug`, `description`) 
    VALUES 
    (NULL, ?, ?, ?)");
    $stmt->execute([
        $_POST["name"],
        slugify($_POST["name"]),
        $_POST["description"]
    ]);
   
    header("Location: /learn-php/14.Restaurant_Website/server/admin/dish-types");

}


function adminDashboardHandler()
{
    if (!isLoggedIn()) {
        echo render("wrapper.phtml", [
            "content" => render("login.phtml")
        ]);
        return;
    }

    $pdo = getConnection();
    $stmt = $pdo->prepare('SELECT * FROM `dishes` ORDER BY id DESC');
    $stmt->execute();
    $dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo render("admin-wrapper.phtml", [
        'content' => render("dish-list.phtml", [
            "dishes" => $dishes
        ])
    ]);
}

function loginHandler()
{

    $pdo = getConnection();
    $stmt = $pdo->prepare('SELECT * FROM `users` WHERE email = ?');
    $stmt->execute([$_POST["email"]]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);


    if (!$user) {
        header('Location: /learn-php/14.Restaurant_Website/server/admin?info=invalidCredentials');
        return;
    }
    if (!password_verify($_POST["password"], $user["password"])) {
        header('Location: /learn-php/14.Restaurant_Website/server/admin?info=invalidCredentials');
        return;
    }

    session_start();
    $_SESSION["userId"] = $user["id"];
    header("Location: /learn-php/14.Restaurant_Website/server/admin");
}


function redirectIfTheUserNotLoggedIn()
{
    if (isLoggedIn()) {
        return;
    }

    header("Location: /learn-php/14.Restaurant_Website/server/admin");
    exit;
}

function updateDishHandler($urlParams)
{
    $pdo = getConnection();
    $stmt = $pdo->prepare(
        "UPDATE `dishes` SET
            name = ?,
            slug = ?,
            description = ?,
            price = ?,
            dishTypeId = ?,
            isActive = ?
        WHERE id = ?"
    );
    $stmt->execute([
        $_POST['name'],
        $_POST['slug'],
        $_POST['description'],
        $_POST['price'],
        $_POST['dishTypeId'],
        (int)isset($_POST['isActive']),
        $urlParams["dishId"]
    ]);

    header("Location: /learn-php/14.Restaurant_Website/server/admin");
}


function isLoggedIn()
{
    if (!isset($_COOKIE[session_name()])) return false;
    if (!isset($_SESSION)) session_start();
    if (!isset($_SESSION["userId"])) return false;

    return true;
}



function notFoundHandler()
{
    http_response_code(404);
    echo render("wrapper.phtml", [
        "content" => render("404.phtml")
    ]);
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
