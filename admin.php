<?php
include("database.php");
if (isset($_POST["psubmit"])) {
  $product_name = $_POST["pname"];
  $product_price = $_POST["pprice"];
  $product_image = $_FILES["pimage"]["name"];
  $product_image_temp = $_FILES['pimage']['tmp_name'];
  $product_image_folder = 'images/' . $product_image;

  if (empty($product_name) || empty($product_price) || empty($product_image)) {
    $message[] = "All Fields Must Be Filled!";
  } else {
    $insert = "INSERT INTO products(name, price, image) VALUES('$product_name', '$product_price', '$product_image')";
    $upload = mysqli_query($conn, $insert);
    if ($upload) {
      move_uploaded_file($product_image_temp, $product_image_folder);
      $message[] = 'New Product Added Successfully!';
      header("Location: " . $_SERVER['PHP_SELF']);
      exit();
    } else {
      $message[] = 'Failed to add new product!';
    }
  }
}

if (isset($_GET['delete'])) {
  $delete_id = $_GET['delete'];
  mysqli_query($conn, "DELETE FROM products WHERE id = $delete_id");
  header("Location: " . $_SERVER['PHP_SELF']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="admin.css">
  <title>Document</title>
</head>

<body>
  <?php
  if (isset($message)) {
    foreach ($message as $message) {
      echo '<span class="message">' . $message . '</span>';
    }
  }
  ?>

  <div class="container">
    <div class="inner_container">
      <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
        <h1>Add New Product</h1>
        <div class="box">
          <label for="pname">Product Name</label>
          <input type="text" placeholder="Enter product name" name="pname">
        </div>
        <div class="box">
          <label for="pprice">Price</label>
          <input type="number" placeholder="Enter product price" name="pprice">
        </div>
        <div class="box">
          <label for="pimage">Image</label>
          <input type="file" accept="image/png, image/jpeg, image/jpg" name="pimage">
        </div>
        <input type="submit" class="sub" value="submit" name="psubmit">
      </form>
    </div>

    <?php
    $select = mysqli_query($conn, "SELECT * FROM products");
    ?>

    <div class="product-display">
      <table class="product-display-table">
        <thead>
          <tr>
            <th>Product Image</th>
            <th>Product Name</th>
            <th>Product Price</th>
            <th>Action</th>
          </tr>
        </thead>

        <?php
        while ($row = mysqli_fetch_assoc($select)) {

        ?>

          <tr>
            <td><img src="images/<?= $row['image']; ?>" height="100"></td>
            <td><?= $row['name']; ?></td>
            <td><?= $row['price']; ?></td>
            <td>
              <a href="admin_update.php?edit=<?= $row['id']; ?>" class="btn">
                <i class="fas fa-edit">
                  edit
                </i>
              </a>
              <a href="admin.php?delete=<?= $row['id']; ?>" class="btn">
                <i class="fas fa-trash">
                  delete
                </i>
              </a>
            </td>
          </tr>

        <?php
        };
        ?>

      </table>
    </div>
  </div>
</body>

</html>