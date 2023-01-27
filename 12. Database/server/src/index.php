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
    "/php-crash/12.%20Database/server/check-country" => "singleCountryHandler",
    "/php-crash/12.%20Database/server/single-city" => "singleCityHandler"
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

  // ------ Összes ország lekérdezése érkező ID alapján
  $statement = $pdo->prepare("SELECT * FROM `countries` WHERE id = ?");
  $statement->execute([$countryId]);
  $country = $statement->fetch(PDO::FETCH_ASSOC);




  // ------ Összes város lekérdezése érkező countryID alapján

  $statement =  $statement = $pdo->prepare("SELECT * FROM `cities` WHERE countryId = ?");
  $statement->execute([$countryId]);
  $cities = $statement->fetchAll(PDO::FETCH_ASSOC);



  // ---- Összes ország és  nyelv lekérdezése OrszágId alapján Languanges tábla összekapcsolásával országok nyelvid és  nyelvek id segitségével


  $statement =  $statement = $pdo->prepare("SELECT * FROM `countrylanguages`
  JOIN languages ON languageId = languages.id
  WHERE countryId = ?");
  $statement->execute([$countryId]);
  $languanges = $statement->fetchAll(PDO::FETCH_ASSOC);



  echo compileTemplate('wrapper.phtml', [
    'content' => compileTemplate('singleCountry.phtml', [
      "country" => $country,
      "cities" => $cities,
      "languages" => $languanges
    ])
  ]);
}


function singleCityHandler()
{
  $singleCityId = $_GET["id"] ?? "";

  $pdo = getConnection();
  $statement =  $statement = $pdo->prepare("SELECT * FROM `cities` WHERE id = ?");
  $statement->execute([$singleCityId]);
  $city = $statement->fetch(PDO::FETCH_ASSOC);

 

  echo compileTemplate("wrapper.phtml", [
    "content" => compileTemplate("singleCity.phtml", [
      "city" => $city
    ]),
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
