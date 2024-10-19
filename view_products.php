<?php
session_start();
include 'components/connect.php';

if(!isset($_SESSION['User_Login'])){
   $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
   header("Location:index.php");}

   $user_id = $_SESSION['User_Login'];

if(isset($_POST['add_to_cart'])){

   $product_id = $_POST['product_id'];
   $product_id = filter_var($product_id, FILTER_SANITIZE_STRING);
   
   $verify_cart = $conn->prepare("SELECT * FROM tbcart WHERE cUserid = ? AND cProductid = ?");   
   $verify_cart->execute([$user_id, $product_id]);

   $max_cart_items = $conn->prepare("SELECT * FROM tbcart WHERE cUserid = ?");
   $max_cart_items->execute([$user_id]);

   if($verify_cart->rowCount() > 0){
      $warning_msg[] = 'Already added to cart!';
   }elseif($max_cart_items->rowCount() == 10){
      $warning_msg[] = 'Cart is full!';
   }else{

      $select_price = $conn->prepare("SELECT * FROM tbproduct WHERE cProductid = ? ");
      $select_price->execute([$product_id]);
      $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);

      $insert_cart = $conn->prepare("INSERT INTO tbcart(cUserid, cProductid, price) VALUES(?,?,?)");
      $insert_cart->execute([$user_id, $product_id, $fetch_price['cPrice']]);
      $success_msg[] = 'Added to cart!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>View Products</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <link rel="stylesheet" href="css/bootstrap.css">
   <link rel="stylesheet" href="css/stylecopy.css">

</head>
<body>
   
<?php include 'header.php'; ?>


<section class="products" >


   <div class="box-container">

   <?php 
      $select_products = $conn->prepare("SELECT * FROM tbproduct");
      $select_products->execute();

      if($select_products->rowCount() > 0){
         while($fetch_prodcut = $select_products->fetch(PDO::FETCH_ASSOC)){
            $imguser = $fetch_prodcut['cUserid'];
            $select_user = $conn->prepare("SELECT * FROM tbuser WHERE cUserid = ? ");
            $select_user->execute([$imguser]);
            $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

            $category=$fetch_prodcut['cCategoryid'];

            $select_category = $conn->prepare("SELECT * FROM tbcategory WHERE cCategoryid = $category");
            $select_category->execute();
            $fetch_category = $select_category->fetch(PDO::FETCH_ASSOC)
   ?>
   <form action="" method="POST" class="box with-heart">
      <img src="<?php echo $fetch_prodcut['file_path']; ?>" alt="<?php echo $fetch_prodcut['file_name']; ?>" class="image">
      <a href="viewprofile.php" onclick="window.location.href='viewprofile.php?profile_id=<?php echo $fetch_user['cUserid']; ?>'; return false;">
   <img id="imageproduct" class="img-account-profile rounded-circle mb-2" src="<?php echo isset($fetch_user['cImguser']) ? 'data:image/jpeg;base64,' . base64_encode($fetch_user['cImguser']) : 'http://bootdey.com/img/Content/avatar/avatar1.png'; ?>" alt="" style="width: 5rem; height: 5rem;">
</a>

      <i class="fas fa-heart" onclick = "changeHeartColor(this)"></i>  
      <a href="<?php echo $fetch_prodcut['file_path']; ?>" target="_blank">
         <i class="fa-solid fa-up-right-and-down-left-from-center"></i>
      </a>
      <h1 class="name" style="font-size:25px"><?= $fetch_prodcut['cProductName'] ?></h1>
      <input type="hidden" name="product_id" value="<?= $fetch_prodcut['cProductid']; ?>"><br>
      <div class="flexbox">
         <p class="price" style="color:black;"><span style="font-size:22px">Detail :</span><?= $fetch_prodcut['cDetails'] ?></p>
         <p class="price" style="color:black;"><span style="font-size:22px">Category : </span><?= $fetch_category['cCategoryName'] ?></p>
         <p class="price" style="font-size:22px"><i class="fas fa-indian-rupee-sign"></i><?= $fetch_prodcut['cPrice'] ?></p>
      </div>
      <input type="submit" name="add_to_cart" value="add to cart" class="btnn" style="float:right;font-size: 20px;">
   </form>




   <?php
      }
   }else{
      echo '<p class="empty">no products found!</p>';
   }
   ?>

   </div>

</section>








<script src="js/script.js"></script>
<script>
function changeHeartColor(icon) {
    icon.classList.toggle('heart-active');
}
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
   <script src="js/script.js"></script>
   <?php include 'components/alert.php'; ?>

</body>
</html>