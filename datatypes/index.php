<?php
// Primitiv tipusok

//string -> "Alma"
//int -> 1
//float -> 1.25
//bool -> true
//null -> null


// Kompozit tipus

// Assziciativ tömb ->

$asszociativ = [
  "id" => 3,
  'name' => "banan",
  'price' => 2.65,
  'quantity' => null,
  'isDiscounted' => true
];

// Numerikus tömb -> 


// Array<String>
$fruits = ["Alma", "Körte", "Banán", "Kókusz", "Málna"];


// Array<Product>

$products = [
  [
    "id" => 3,
    'name' => "Kókusz",
    'price' => 2.65,
    'quantity' => null,
    'isDiscounted' => true
  ],
  [
    "id" => 5,
    'name' => "Banán",
    'price' => 5.2,
    'quantity' => null,
    'isDiscounted' => false
  ]
  //...
];

var_dump($products);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Adattipusok</title>
</head>

<body>
  <h1>
    <?php echo "Hello world {$fruits[0]}" ?>
  </h1>
</body>

</html>