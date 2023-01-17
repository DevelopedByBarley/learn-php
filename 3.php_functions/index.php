<?php

//Pure functions -> 

  function evenOrNot($input) {
    return $input % 2 === 0 ? "Szám páros" : "Szám páratlan";
  };

  function evenOrNot_2(int $input) : string { // Függvény input és output explicit meghatározása!
    return $input % 2 === 0 ? "Szám páros" : "Szám páratlan";
  };

  echo evenOrNot_2(20);
  //echo evenOrNot_2([]) => Fatal error!
   

//----------------------------------------------------------------------------------------------------------------------------------


// Inpure functions -> 
function evenOrNot_3( $input)  { 
  echo "Teszt inpure functions!"; // -> mellékhatás, side effect
  return $input % 2 === 0 ? "Szám páros" : "Szám páratlan";
};


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Függvények php-ban</title>
</head>
<body>
</body>
</html>