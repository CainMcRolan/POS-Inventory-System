<?php 
   require('../../helper/connect.php');

   //Handles Product Submission (Insert Query)
   if (isset($_POST['product_submit'])) {
      $product_code = mysqli_real_escape_string($connection, $_POST['product_code']);
      $product_name = mysqli_real_escape_string($connection, $_POST['product_name']);
      $product_price = mysqli_real_escape_string($connection, $_POST['product_price']);
      $product_category = mysqli_real_escape_string($connection, $_POST['product_category']);
      $product_description = mysqli_real_escape_string($connection, $_POST['product_description']);
      $product_count = mysqli_real_escape_string($connection, $_POST['product_count']);
      $product_delivery = mysqli_real_escape_string($connection, $_POST['product_delivery']);
      $product_transfer = mysqli_real_escape_string($connection, $_POST['product_transfer']);
      $product_wasteges = mysqli_real_escape_string($connection, $_POST['product_wasteges']);
      $product_pullout = mysqli_real_escape_string($connection, $_POST['product_pullout']);
      $product_return = mysqli_real_escape_string($connection, $_POST['product_return']);
      $product_stock = (int) $product_delivery + (int) $product_transfer + (int) $product_return - (int) $product_wasteges - (int) $product_pullout;
      $variance = (int) $product_stock - (int) $product_count;

      //Handles Image Query
      $target_dir = "../../assets/products/";
      $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
      $uploadOk = 1;
      $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

      if (!empty($_FILES["product_image"]["tmp_name"])) {
         $check = getimagesize($_FILES["product_image"]["tmp_name"]);
         if ($check !== false) {
             $uploadOk = 1;
         } else {
             echo "File is not an image.";
             $uploadOk = 0;
         }
     
         if ($uploadOk == 0) {
             echo "Sorry, your file was not uploaded.";
         } else {
             if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
                 $product_image = basename($_FILES["product_image"]["name"]);
             } else {
                 echo "Sorry, there was an error uploading your file.";
             }
         }
      }
     
      //Handes Update Query
      $updateFields = "
         name = '{$product_name}',
         category = '{$product_category}',
         price = '{$product_price}',
         current_stock = '{$product_stock}',
         physical_count = '{$product_count}',
         delivery = '{$product_delivery}',
         transfer = '{$product_transfer}',
         wasteges = '{$product_wasteges}',
         pull_out = '{$product_pullout}',
         returns = '{$product_return}',
         variance = '{$variance}',
         description = '{$product_description}'";

      if (!empty($product_image)) {
         $updateFields .= ", image = '{$product_image}'";
      }

      $query = "UPDATE product SET $updateFields WHERE code = '{$product_code}'";

      $result = mysqli_query($connection, $query);

      if ($result) {  
         echo "<script>window.parent.location.reload();</script>";
         exit();
      } else {
         echo "Error: " . mysqli_error($connection);
      }
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Document</title>
   <link rel="stylesheet" href="../../styles/home-admin.css">
</head>
<body>
   <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" class="myForm new-product-form" enctype="multipart/form-data">
      <input type="hidden" name="product_code" value="<?= $_GET['code'] ?>">
      <label>Product Name:</label>
      <?php
         $product_code = mysqli_real_escape_string($connection, $_GET['code']);
         $query = "SELECT * FROM product WHERE code = '{$product_code}'";
         $result = mysqli_query($connection, $query);
         if ($result && mysqli_num_rows($result) > 0) {
            $product = mysqli_fetch_assoc($result);
         }
      ?>

      <input type="text" name="product_name" value="<?= isset($product['name']) ? htmlspecialchars($product['name']) : '' ?>">
      <label>Price:</label>
      <input type="text" name="product_price" value="<?= isset($product['price']) ? htmlspecialchars($product['price']) : '' ?>">
      <label>Category:</label>
      <input type="text" name="product_category" value="<?= isset($product['category']) ? htmlspecialchars($product['category']) : '' ?>">
      <label>Description:</label>
      <input type="text" name="product_description" value="<?= isset($product['description']) ? htmlspecialchars($product['description']) : '' ?>">
      <label>Physical Count:</label>
      <input type="number" name="product_count" value="<?= isset($product['physical_count']) ? htmlspecialchars($product['physical_count']) : '' ?>">
      <label>Delivery:</label>
      <input type="number" name="product_delivery" value="<?= isset($product['delivery']) ? htmlspecialchars($product['delivery']) : '' ?>">
      <label>Transfer:</label>
      <input type="number" name="product_transfer" value="<?= isset($product['transfer']) ? htmlspecialchars($product['transfer']) : '' ?>">
      <label>Wasteges:</label>
      <input type="number" name="product_wasteges" value="<?= isset($product['wasteges']) ? htmlspecialchars($product['wasteges']) : '' ?>">
      <label>Pull Out:</label>
      <input type="number" name="product_pullout" value="<?= isset($product['pull_out']) ? htmlspecialchars($product['pull_out']) : '' ?>">
      <label>Returns:</label>
      <input type="number" name="product_return" value="<?= isset($product['returns']) ? htmlspecialchars($product['returns']) : '' ?>">
      <label>Select Image:</label>
      <input type="file" name="product_image" accept="image/png, image/jpeg">
      <div class="actions">
         <input type="button" value="Cancel" class="formButtons app-content-headerButton cancel">
         <input type="submit" value="Submit" name="product_submit" class="formButtons app-content-headerButton submit">
      </div>
   </form>
   <script>
      document.querySelector('.cancel').addEventListener('click', () => {
         window.parent.location.reload();
      })
   </script>
</body>
</html>