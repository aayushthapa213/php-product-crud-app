<?php
include("database.php");

$id = $_GET['edit'];

if (isset($_POST["update_product"])) {
  $product_name = $_POST["pname"];
  $product_price = $_POST["pprice"];
  $product_image = $_FILES["pimage"]["name"];
  $product_image_temp = $_FILES['pimage']['tmp_name'];
  $product_image_folder = 'images/' . $product_image;

  if (empty($product_name) || empty($product_price) || empty($product_image)) {
    $message[] = "All Fields Must Be Filled!";
  } else {
    $update = "UPDATE products SET name = '$product_name', price = '$product_price', image = '$product_image' WHERE id = $id ";
    $upload = mysqli_query($conn, $update);
    if ($upload) {
      move_uploaded_file($product_image_temp, $product_image_folder);
      $message[] = 'Product Updated Successfully!';
      header("Location: admin.php");
    } else {
      $message[] = 'Failed to add new product!';
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>admin update</title>
  <link rel="stylesheet" href="admin.css">
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

      <?php
      $select = mysqli_query($conn, "SELECT * FROM products WHERE id = $id ");
      while ($row = mysqli_fetch_assoc($select)) {

      ?>

        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
          <h1>Add New Product</h1>
          <div class="box">
            <label for="pname">Product Name</label>
            <input type="text" placeholder="Enter product name" name="pname" value="<?= $row['name']; ?>">
          </div>
          <div class="box">
            <label for="pprice">Price</label>
            <input type="number" placeholder="Enter product price" name="pprice" value="<?= $row['price']; ?>">
          </div>
          <div class="box">
            <label for="pimage">Image</label>
            <input type="file" accept="image/png, image/jpeg, image/jpg" name="pimage">
          </div>
          <input type="submit" class="sub" value="Update Product" name="update_product">
          <a href="admin.php">Go Back</a>
        </form>
      <?php
      };
      ?>
    </div>

</body>

</html>