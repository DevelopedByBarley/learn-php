<?php
/**
  Funkcionális és procedurális szemléletmód
  Memóriával kapcsolatos operátorok
  Változók használata
*/

//Procedurális () -> void // Függvény nem tér vissza értékkel =>

  function writeThatText(): void {
    echo "<h1>First</h1>";
    echo "<h1>Second</h1>";
    echo "<h1>Third</h1><hr>";
  };

  writeThatText();
  writeThatText();
  writeThatText();

// () -> * // Nem várnak bemenetet, de szolgáltatnak értéket =>

function getInstruments() {
  return json_decode(file_get_contents("https://kodbazis.hu/api/instruments"), true);
}

echo "<pre>";
var_dump(getInstruments());




// (*) -> void // Vár bemenetet, de nem szolgáltatnak értéket /Pl adatbázisba tárolás/ 


//OPERÁTOROK

// Assigment operátor (=)

function getAgeText(int $age): string {
  return $age >= 18 ? "<h1>A felhasználó nagykorú</h1>" : "<h1>A felhasználó kiskorú</h1>";
}

$age = 25;
$age -= 10;
$age++;
$age++;
$age++;
$age--;

echo $age;


$subtitle = getAgeText($age);

// .= -> Meglévő string értékhez további string értéket fűzhetsz hozzá!
$subtitle .=  "!!!";

echo mb_strtoupper($subtitle);





?>