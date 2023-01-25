<div class="card container p-3 m-3">
  <form action="/php-crash/10.CRUD/products" method="POST">
    <input type="text" name="name" placeholder="Név" />
    <input type="number" name="price" placeholder="Ár" />
    <button type="submit" class="btn btn-success">Küldés</button>
  </form>

  <?php foreach ($params["products"] as $product) : ?>
    <h3>Név: <?php echo $product["name"] ?></h3>
    <p>Ár: <?php echo $product["price"] ?> ft</p>


    <?php if ($params["editedProductId"] === $product["id"]) : ?>

      <form class="form-inline form-group" action="/php-crash/10.CRUD/update-product?id=<?php echo $product["id"] ?>" method="POST">
        <input class="form-control mr-2" type="text" name="name" placeholder="Név" value="<?php echo $product["name"] ?>" />
        <input class="form-control mr-2" type="number" name="price" placeholder="Ár" value="<?php echo $product["price"] ?>" />
        <button type="submit" class="btn btn-success">Küldés</button>
        <a href="/php-crash/10.CRUD/products">
          <button class="btn btn-outline-primary mr-2">Vissza</button>
        </a>
      </form>

    <?php else : ?>

      <div class="btn-group">
        <form action="/php-crash/10.CRUD/delete-product?id=<?php echo $product["id"] ?>" method="POST">
          <button type="submit" class="btn btn-danger">Törlés</button>
        </form>
        <a href="/php-crash/10.CRUD/products?edit=<?php echo $product["id"] ?>">
          <button type="button" class="btn btn-warning mr-2">Szerkesztés</button>
        </a>
      </div>



    <?php endif; ?>



    <hr>
  <?php endforeach; ?>
</div>