
  <div class="card container p-3 m-3">
    <form action="/php-crash/9.Templates_and_compositions/products" method="POST">
      <input type="text" name="name" placeholder="Név" />
      <input type="number" name="price" placeholder="Ár" />
      <button type="submit" class="btn btn-success">Küldés</button>
    </form>

    <?php foreach ($params["products"] as $product) : ?>
      <h3>Név: <?php echo $product["name"] ?></h3>
      <p>Ár: <?php echo $product["price"] ?> ft</p>
      <hr>
    <?php endforeach; ?>
  </div>
