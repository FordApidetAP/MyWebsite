<?php
   session_start();
   include 'components/connect.php';

   if(!isset($_SESSION['User_Login'])){
      $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
      header("Location:index.php");}

      $user = $_SESSION['User_Login'];

   if(isset($_POST['add'])){

      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);
      $detail = $_POST['detail'];
      $detail = filter_var($detail, FILTER_SANITIZE_STRING);
      $category = $_POST['category'];

      $file_name = $_FILES['image']['name'];
      $file_tmp = $_FILES['image']['tmp_name'];

      move_uploaded_file($file_tmp, 'upload/' . $file_name);

      $product = $conn->prepare("INSERT INTO tbproduct (file_name, file_path,	cProductName, cDetails, cPrice, cCategoryid, cUserid) VALUES (:file_name, :file_path, :name, :detail, :price, :category, :user)");               
      $product->bindParam(':file_name', $file_name);
      $product->bindParam(':file_path', $file_path);
      $product->bindParam(":name", $name);
      $product->bindParam(":detail", $detail);
      $product->bindParam(":price", $price);
      $product->bindParam(":category", $category);
      $product->bindParam(":user", $user);

      $file_path = 'upload/' . $file_name;

      $product->execute();
         $success_msg[] = 'Product added!';


   }

   ?>

   <!DOCTYPE html>
   <html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Add Product</title>

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <link rel="stylesheet" href="css/bootstrap.css">
   <link rel="stylesheet" href="css/style.css">

   </head>
   <body>
      
   <?php include 'header.php'; ?>

   <section class="product-form">

<form action="" method="POST" enctype="multipart/form-data">
   <h3>product info</h3>
   <p>product name <span>*</span></p>
   <input type="text" name="name" placeholder="enter product name" required maxlength="50" class="box">
   <p>product detail <span>*</span></p>
   <input type="text" name="detail" placeholder="enter product detail" required maxlength="50" class="box">
   <?php
          $userid = $_SESSION['User_Login'];
          $sql1 ="SELECT * FROM tbcategory";
      
          $stmtt = $conn->prepare($sql1);
          $stmtt->execute();
          $roww = $stmtt->fetchAll(PDO::FETCH_ASSOC);   
   ?>
   <p>product category <span>*</span></p>
   <select class="box " id="category" name="category">
      <option value="">Select Category</option>
         <?php foreach ($roww as $index => $category) { ?>
            <option value="<?php echo $index + 1; ?>"><?php echo ($index + 1) . " - " . $category['cCategoryName']; ?></option>
         <?php } ?>
   </select>
   <p>product price <span>*</span></p>
   <input type="number" name="price" placeholder="enter product price" required min="0" max="9999999999" maxlength="10" class="box">
   <p>product image <span>*</span></p>
   <input type="file" name="image" required accept="image/*" class="box">
   <input type="submit" class="btnn" name="add" value="add product">
</form>

</section>







<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
   <script src="js/script.js"></script>
   <?php include 'components/alert.php'; ?>



   </body>
   </html>