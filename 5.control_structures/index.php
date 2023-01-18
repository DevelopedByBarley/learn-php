<?php

/** 
  Mire használhatók a vezérlési szerkezetek
  Ciklusok és elágazások működése
 */


$countOfIteration = $_GET['countOfIteration'] ?? 0;
$counter = 0;

// While

while ($counter < $countOfIteration) {
  echo '1. teszt while <hr>';
  echo '2. teszt while <hr>';
  echo '3. teszt while <hr>';
  $counter++;
};


// Do-While 

do { // -> Iteráció után vizsgálja meg a feltételt
  echo '1. teszt do-while <hr>';
  echo '2. teszt do-while <hr>';
  echo '3. teszt do-while <hr>';
  $counter++;
} while ($counter < $countOfIteration);


// For


for ($counter = 0; $counter < $countOfIteration; $counter++) {
  echo '1. teszt for <hr>';
  echo '2. teszt for <hr>';
  echo '3. teszt for <hr>';
}


// ForEach -> Tömb elemeinek iterálására 

$fruits = ["apple", "banana", "pear", "orange"];

foreach ($fruits as $fruit) {
  echo $fruit . "<br><hr>";
}

$maxPrice = $_GET['maxPrice'] ?? 0;
echo $maxPrice;
$instruments = json_decode(file_get_contents("https://kodbazis.hu/api/instruments"), true);


foreach ($instruments as $instrument) {
  if ($instrument['price'] < $maxPrice || $maxPrice === 0) {
    echo $instrument["name"] . "<br>";
    echo "<img src='{$instrument['imageURL']}' width='50px' />";
    echo "<p>{$instrument['price']} Ft</p>";
  }
}


// If else
$age = $_GET['age'] ?? 0;

/**
 

if($age < 0) {
  echo "Helytelen életkor!";
} elseif($age > 0 && $age < 18) {
  echo "Felhasználó kiskorú";
} else {
  echo "Felhasználó nagykorú";
};

echo "<br>....";
 * 
 */



//Switch
switch (true) {
  case $age < 0:
    echo "Helytelen adat";
    break;
  case $age > 0 && $age < 18:
    echo "Felhasználó kiskorú";
    break;
  default:
    echo "Felhasználó nagykorú {$age}";
    break;
}

//Ha == akarod megvizsgálni

switch ($age) {
  case  0:
    echo "Helytelen adat";
    break;
  case 1:
    echo "Felhasználó 1 éves";
    break;
  default:
    echo "Felhasználó Ismeretlen életkorú";
    break;
}
