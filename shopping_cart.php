<?php
session_start();
include 'components/connect.php';

if(!isset($_SESSION['User_Login'])){
   $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
   header("Location:index.php");}

   $user_id = $_SESSION['User_Login'];


if(isset($_POST['delete_item'])){

   $cart_id = $_POST['cart_id'];
   $cart_id = filter_var($cart_id, FILTER_SANITIZE_STRING);
   
   $verify_delete_item = $conn->prepare("SELECT * FROM tbcart WHERE cCartid = ?");
   $verify_delete_item->execute([$cart_id]);

   if($verify_delete_item->rowCount() > 0){
      $delete_cart_id = $conn->prepare("DELETE FROM tbcart WHERE cCartid = ?");
      $delete_cart_id->execute([$cart_id]);
      $success_msg[] = 'Cart item deleted!';
   }else{
      $warning_msg[] = 'Cart item already deleted!';
   } 

}

if(isset($_POST['empty_cart'])){
   
   $verify_empty_cart = $conn->prepare("SELECT * FROM tbcart WHERE cUserid = ?");
   $verify_empty_cart->execute([$user_id]);

   if($verify_empty_cart->rowCount() > 0){
      $delete_cart_id = $conn->prepare("DELETE FROM tbcart WHERE cUserid = ?");
      $delete_cart_id->execute([$user_id]);
      $success_msg[] = 'Cart emptied!';
   }else{
      $warning_msg[] = 'Cart already emptied!';
   } 

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shopping Cart</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <link rel="stylesheet" href="css/bootstrap.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
  .container {
    display: flex;
    flex-direction: row;
    align-items: center;
   border: 2px solid black;
    margin-bottom:2rem;
    background: white;
    border-radius: 1.5rem;
    height: auto;
   padding:1.5rem;
   justify-content: space-between;
  }

  .image {
    width: 15rem; /* ปรับขนาดตามต้องการ */
    height: auto;
    border: 2px solid gray;

  }

  .name {
    margin: 0 10px; /* ระยะห่างระหว่างชื่อสินค้ากับราคา */
    font-size: 20px;
    /* padding-left: 150px; */
  }

  .price {
    margin-right: 10px; /* ระยะห่างขอบขวา */
    font-size: 20px;
    /* padding-left: 150px; */
  }
  .delete-btn{
   margin-left: 10px; /* ระยะห่างจากขอบซ้าย */


  }
</style>

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="products">



   <?php
      $grand_total = 0;
      $select_cart = $conn->prepare("SELECT * FROM tbcart WHERE cUserid = ?");
      $select_cart->execute([$user_id]);
      if($select_cart->rowCount() > 0){
         while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){

         $select_products = $conn->prepare("SELECT * FROM tbproduct WHERE cProductid = ?");
         $select_products->execute([$fetch_cart['cProductid']]);
         if($select_products->rowCount() > 0){
            $fetch_product = $select_products->fetch(PDO::FETCH_ASSOC);
      
   ?>
   <form action="" method="POST" class="container">
  <input type="hidden" name="cart_id" value="<?= $fetch_cart['cCartid']; ?>">
  <img src="<?php echo $fetch_product['file_path']; ?>" alt="<?php echo $fetch_product['file_name']; ?>" class="image">
  <h3 class="name"><?= $fetch_product['cProductName']; ?></h3>
  <p class="price"><i class="fas fa-indian-rupee-sign"></i> <?= $fetch_cart['price']; ?></p>
  <input type="submit" value="delete" name="delete_item" class="btn btn-danger btn-lg m-5" style="font-size: 20px; border-radius: .5rem;" onclick="return confirm('delete this item?');">
   </form>

   <?php
      $sub_total = $fetch_cart['price'];
      $grand_total += $sub_total;
      }else{
         echo '<p class="empty">product was not found!</p>';
      }
      }
   }else{
            echo '<p class="empty">your cart is empty!</p>';
   }
   ?>

   </div>

   <?php if($grand_total != 0){ ?>
      <div class="container" style="padding:1.5rem;">
         <p style="font-size: 20px;" >grand total : <span><i class="fas fa-indian-rupee-sign" ></i> <?= $grand_total; ?></span></p>
         <form action="" method="POST">
          <input type="submit" value="empty cart" name="empty_cart" class="btn btn-danger" style="font-size: 20px; padding:1rem; border-radius: 1.25rem;" onclick="return confirm('empty your cart?');">
         </form>
         <a href="checkout.php" class="btn btn-primary" style="font-size: 20px; padding:1rem; border-radius: 1.25rem;">proceed to checkout</a>
      </div>
   <?php } ?>

</section>



<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
   <script src="js/script.js"></script>
   <?php include 'components/alert.php'; ?>


</body>
</html>