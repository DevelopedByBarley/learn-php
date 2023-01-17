<?php
// String concat / String . String /

var_dump("Hello " . "World");


// Aritmetikai / integer|float, integer|float -> integer|float

3 + 2;
6 - 1.2;
3 * 2;
3 / 2;
3 % 2;
echo 3 * 2 + 1;
echo 3 * (2 + 1);

// Implicit tipus átalakitás 
"3" + 5; //-> int8

// Logikai operátorok / bool -> bool /

//Negálás 

!true; // -> false

// && , || operátor /bool, bool -> bool /

// &&
true && false; // false
false && true; // false
false && false; //false 
true && true; //true

// ||

true || false; // -> true
false || true; // -> true
false || false; // -> false
true || true; // -> true

// Összehasonlitó operátorok / >, <, >=, <=, === , !==, == , != /

// int|float, int|float -> bool

2 < 3; //true

// any, any -> bool

"Alma" === "Kókusz"; //false
"Alma" === "Alma"; //true
"Alma" !== "Alma"; //false

"15" == 15; // true
"15" === 15; // false


// Conditional op (_ ? _ : _)

// bool, any, any -> any

true ? 12 : 17; // 12
true ? "Alma" : "Kókusz"; // Alma
false ? "Alma" : "Kókusz"; // Kókusz

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Operátorok</title>
</head>

<body>

</body>

</html>