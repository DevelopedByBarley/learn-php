<?php

/**
    Apache szerver konfigurációja
    Publikus és nem publikus fájlok
    Az útvonalválasztó működése
 */

$parsed = parse_url($_SERVER['REQUEST_URI']);
$path = $parsed["path"];
echo $path;
switch ($path) {
  case "/php-crash/7.Build_Simple_Website/penzvalto":

    $value = (int)($_GET['mennyit'] ?? 1);
    $sourceCurrency = $_GET['mirol'] ?? 'USD';
    $targetCurrency = $_GET['mire'] ?? 'HUF';




    $content = file_get_contents("https://kodbazis.hu/api/exchangerates?base={$sourceCurrency}");
    $decodedContent = json_decode($content, true);

    $result = $decodedContent["rates"][$targetCurrency] * $value;
    echo "<br/><hr/>";

    $currencies = json_decode(file_get_contents('./currencies.json'), true);
    require "./views/converter.php";
    break;
  case "/php-crash/7.Build_Simple_Website/":
    require "./views/home.html";
    break;
  default:
    echo "Oldal nem található <a href='/php-crash/7.Build_Simple_Website/'>Vissza a címlapra...</a>";
}
