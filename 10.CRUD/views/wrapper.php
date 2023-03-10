<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css" />
  <title>Document</title>
</head>
<nav class="navbar navbar-expand navbar-light">
  <div class="navbar-nav">
    <a class="nav-item nav-link <?php echo $params["activeLink"] === "/php-crash/10.CRUD/" ? "active" : ""?>" href="/php-crash/10.CRUD/">
      Címlap
    </a>
    <a class="nav-item nav-link <?php echo $params["activeLink"] === "/php-crash/10.CRUD/products" ? "active" : ""?>" href="/php-crash/10.CRUD/products">
      Termékek
    </a>
  </div>
</nav>

<h1><?php echo $params["innerTemplate"]?></h1>

<footer class=" text-center fixed-bottom text-lg-start">
  <div class="text-center p-3">
    Footer tartalom
  </div>
</footer>
</body>

</html>