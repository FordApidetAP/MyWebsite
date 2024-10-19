<?php
session_start();
include 'components/connect.php';
error_reporting(0);

if(!isset($_SESSION['User_Login'])){
   $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
   header("Location:index.php");}

   $user_id = $_SESSION['User_Login'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>My Orders</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
   <link rel="stylesheet" href="css/bootstrap.css">
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="orders">

   <h1 class="heading">my orders</h1>

   <div class="box-container">

   <?php
      $select_orders = $conn->prepare("SELECT * FROM tborder WHERE cUserid = ?");
      $select_orders->execute([$user_id]);
      if($select_orders->rowCount() > 0){
         while($fetch_order = $select_orders->fetch(PDO::FETCH_ASSOC)){
            $select_orderdetail = $conn->prepare("SELECT * FROM tborderdetail WHERE cOrderid = ?");
            $select_orderdetail->execute([$fetch_order['cOrderid']]);
            $fetch_orderdetail = $select_orderdetail->fetch(PDO::FETCH_ASSOC);

            $select_product = $conn->prepare("SELECT * FROM tbproduct WHERE cProductid = ?");
            $select_product->execute([$fetch_orderdetail['cProductid']]);
            if($select_product->rowCount() > 0){
               while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <a href="view_orders.php?get_id=<?= $fetch_order['cOrderid']; ?>">
         <p class="date"><i class="fa fa-calendar"></i><span><?= $fetch_order['cOrderDate']; ?></span></p>
         <img src="<?php echo $fetch_product['file_path']; ?>" alt="<?php echo $fetch_product['file_name']; ?>" class="image">
         <h3 class="name"><?= $fetch_product['cProductName']; ?></h3>
         <p class="price"><i class="fas fa-indian-rupee-sign"></i> <?= $fetch_product['cPrice']; ?></p>
      </a>
   </div>
   <?php
            }
         }
      }
   }else{
      echo '<p class="empty">no orders found!</p>';
   }
   ?>

   </div>

</section>














<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
   <script src="js/script.js"></script>
   <?php include 'components/alert.php'; ?>

</body>
</html>