<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

$method = $_SERVER["REQUEST_METHOD"];
$parsed = parse_url($_SERVER["REQUEST_URI"]);
$path = $parsed["path"];




$routes = [
  "GET" => [
    "/php-crash/12.%20Database/server/" => "countryListHandler",
    "/php-crash/12.%20Database/server/check-country" => "singleCountryHandler"
  ],
  "POST" => [],
];


$handlerFunction = $routes[$method][$path] ?? "notFoundHandler";

$handlerFunction();



function compileTemplate($filePath, $params = []): string
{
  ob_start();
  require __DIR__ . "/views/" . $filePath;
  return ob_get_clean();
}



function countryListHandler()
{
  $pdo = getConnection();

  $statement = $pdo->prepare('SELECT * FROM `countries`');
  $statement->execute();
  $countries = $statement->fetchAll(PDO::FETCH_ASSOC);

  echo compileTemplate('wrapper.phtml', [
    'content' => compileTemplate('countryList.phtml', [
      "countries" => $countries
    ])
  ]);
}


function singleCountryHandler()
{
  $countryId = $_GET['id'] ?? "";
  $pdo = getConnection();
  $statement = $pdo->prepare("SELECT * FROM `countries` WHERE id = ?");
  $statement->execute([$countryId]);
  $country = $statement->fetch(PDO::FETCH_ASSOC);

  $statement =  $statement = $pdo->prepare("SELECT * FROM `cities` WHERE countryId = ?");
  $statement->execute([$countryId]);
  $cities = $statement->fetchAll(PDO::FETCH_ASSOC);

  echo compileTemplate('wrapper.phtml', [
    'content' => compileTemplate('singleCountry.phtml', [
      "country" => $country,
      "cities" => $cities
    ])
  ]);
}


function getConnection()
{
  $serverName = $_SERVER['DB_HOST'];
  $userName = $_SERVER['DB_USER'];
  $password =  $_SERVER['DB_PASSWORD'];
  $dbName = $_SERVER['DB_NAME'];

  return new PDO(
    'mysql:host=' . $serverName . ';dbname=' . $dbName,
    $userName,
    $password
  );
}

function notFoundHandler()
{
  echo "Route not found!";
}
