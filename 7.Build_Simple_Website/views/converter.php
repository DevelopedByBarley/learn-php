<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <title>Currency App</title>
</head>

<body>
  <div class="card w-25 m-auto p-3">
    <form>
      <input class="form-control mb-2" type="number" name="mennyit" value="<?php echo $value ?>">
      <select name="mirol" class="form-control mb-2">
        <?php foreach ($currencies as $currency) : ?>
          <option value="<?php echo $currency['label'] ?>" <?php echo $sourceCurrency === $currency['label'] ? "selected" : "" ?>>
            <?php echo $currency['name'];
            echo $currency['symbol'] ?>
          </option>
        <?php endforeach ?>
      </select>
      <h1 class="text-center">
        <?php echo $result ?>
      </h1>
      <select name="mire" class="form-control mb-2">
        <?php foreach ($currencies as $currency) : ?>
          <option value="<?php echo $currency['label'] ?>" <?php echo $targetCurrency === $currency['label'] ? "selected" : "" ?>>
            <?php echo $currency['name'];
            echo $currency['symbol'] ?>
          </option>
        <?php endforeach ?>
      </select>
      <input type="submit" class="btn btn-primary form-control">
    </form>
  </div>
</body>

</html>