<?php

session_start();
include 'components/connect.php';

if (!isset($_SESSION['User_Login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("Location:index.php");
    exit(); // เพิ่มบรรทัดนี้เพื่อหยุดการทำงานของสคริปต์หลังจาก redirect
}

$user_id = $_SESSION['User_Login'];
$grand_total = $_SESSION['TotalAmount'];

if (isset($_POST['place_order'])) {

    $addresstbb = $_POST['addresstbb'];
    $payment = $_POST['payment'];

    $verify_cart = $conn->prepare("SELECT * FROM tbcart WHERE cUserid = ?");
    $verify_cart->execute([$user_id]);

    if ($verify_cart->rowCount() > 0) {

        // สร้างออเดอเพียงครั้งเดียวสำหรับทั้งหมด
        $insert_order = $conn->prepare("INSERT INTO tborder(cUserid, cPaymentid, cTotalAmount, cAAddressid) VALUES(:user_id,:payment,:grand_total, :addresstbb)");
        $insert_order->bindParam(':user_id', $user_id);
        $insert_order->bindParam(':payment', $payment);
        $insert_order->bindParam(':grand_total', $grand_total);
        $insert_order->bindParam(':addresstbb', $addresstbb);
        $insert_order->execute();
        $order_ID = $conn->lastInsertId();

        while ($f_cart = $verify_cart->fetch(PDO::FETCH_ASSOC)) {
            // เพิ่มรายละเอียดสินค้าลงในตาราง tborderdetail
            $insert_detail = $conn->prepare("INSERT INTO tborderdetail(cOrderid, cProductid) VALUES(:order_ID, :product_id)");
            $insert_detail->bindParam(':order_ID', $order_ID);
            $insert_detail->bindParam(':product_id', $f_cart['cProductid']);
            $insert_detail->execute();
        }

        // ลบรายการในตะกร้าหลังจากสร้างออเดอเรียบร้อย
        $delete_cart_id = $conn->prepare("DELETE FROM tbcart WHERE cUserid = ?");
        $delete_cart_id->execute([$user_id]);

        header('location:orders.php');
        exit(); // เพิ่มบรรทัดนี้เพื่อหยุดการทำงานของสคริปต์หลังจาก redirect
    } else {
        $_SESSION['error'] = 'Your cart is empty!';
        header('location:index.php');
        exit(); // เพิ่มบรรทัดนี้เพื่อหยุดการทำงานของสคริปต์หลังจาก redirect
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/bootstrap.css">
</head>
<body>
   
<?php include 'header.php'; ?>
<section class="checkout">

   <h1 class="heading">checkout summary<?php  ?></h1>

   <div class="row">
   <form action="" method="POST">
         <h3>billing details</h3>
         <div class="flex">
            <div class="box">
               <?php
                         $payment ="SELECT * FROM tbpayment";
      
                         $tbpayment = $conn->prepare($payment);
                         $tbpayment->execute();
                         $rowtbpayment = $tbpayment->fetchAll(PDO::FETCH_ASSOC);   
                         ?>
            <p>payment method <span>*</span>
                  <select class="form-control box input" name="payment" style="font-size:18px; margin-top: 2rem;">
                     <option value="" >Select payment</option>
                        <?php foreach ($rowtbpayment as $index => $rowpayment) { ?>
                           <option value="<?php echo $index + 1; ?>"><?php echo ($index + 1) . " - " . $rowpayment['cPaymentName']; ?></option>
                        <?php } ?>
                  </select>
                  <?php
                         $address ="SELECT * FROM tbaddress WHERE cAUserid = $user_id";   
                         $tbaddress = $conn->prepare($address);
                         $tbaddress->execute();
                         $rowtbaddress = $tbaddress->fetchAll(PDO::FETCH_ASSOC);   
                         $rowtbaddresstb = $tbaddress->fetch(PDO::FETCH_ASSOC);   
                         ?>
            <p>Address<span>*</span></p>
                  <select class="form-control box input" name="addresstbb" style="font-size:18px; margin-top: 2rem;">
                     <option value="">Select Address</option>
                        <?php foreach ($rowtbaddress as $indexx => $rowaddress) { ?>
                           <?php $districts = $rowaddress['cDistrictsid'];
                           
                           $sqldistricts = "          SELECT * FROM tbdistricts 
                                                      LEFT JOIN tbamphures ON tbdistricts.cAmphuresid = tbamphures.cAmphuresid 
                                                      LEFT JOIN tbprovince ON tbamphures.cProvinceid = tbprovince.cProvinceid 
                                                      WHERE tbdistricts.cDistrictsid = :districts";
                                   $stmtdistricts = $conn->prepare($sqldistricts);
                                   $stmtdistricts->execute([':districts' => $districts]);
                                   $rowdistricts = $stmtdistricts->fetch(PDO::FETCH_ASSOC);

                                   if ($rowdistricts) {
        
                                    $amphures = $rowdistricts['cAmphuresid'];
                                    $sqlamphures ="SELECT * FROM tbamphures WHERE cAmphuresid =:amphures";
                                    $stmtamphures = $conn->prepare($sqlamphures);
                                    $stmtamphures->execute([':amphures' => $amphures]);
                                    $rowamphures = $stmtamphures->fetch(PDO::FETCH_ASSOC);
                                
                                    $provinces = $rowamphures['cProvinceid'];
                                    $sqlprovinces ="SELECT * FROM tbprovince WHERE cProvinceid =:provinces";
                                    $stmtprovinces = $conn->prepare($sqlprovinces);
                                    $stmtprovinces->execute([':provinces' => $provinces]);
                                    $rowprovinces = $stmtprovinces->fetch(PDO::FETCH_ASSOC);
                                    
                                } else {
                                    
                                }
                                   
                                   ?>
                           <option value="<?php echo $indexx + 1; ?>"><?php echo ($indexx + 1) . '. ' . $rowaddress['cAFirstName'] . ' ' . $rowaddress['cALastName'] . ' ' . $rowaddress['cAAddress'] . ' ตำบล' . $rowdistricts['namethDistricts'] . ' อำเภอ' . $rowamphures['namethAmphures'] . ' จังหวัด' . $rowprovinces['namethProvince'] . ' ' . $rowdistricts['zip_code']; ?></option>
                        <?php } ?>
                  </select>

            </div>

            <?php 
               $select_cart = $conn->prepare("SELECT * FROM tbcart WHERE cUserid = ?");
               $select_cart->execute([$user_id]); 
               if($select_cart->rowCount() > 0){
                  while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                     $select_products = $conn->prepare("SELECT * FROM tbproduct WHERE cProductid = ?");
                     $select_products->execute([$fetch_cart['cProductid']]);
                     $fetch_product = $select_products->fetch(PDO::FETCH_ASSOC);
                  }}
               ?>
         </div>
         <input type="submit" value="place order" name="place_order" class="btnn">
      </form>
      <div class="summary">
         <h3 class="title">cart items</h3>
         <?php
            $grand_total = 0;
            if(isset($_GET['get_id'])){
               $select_get = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
               $select_get->execute([$_GET['get_id']]);
               while($fetch_get = $select_get->fetch(PDO::FETCH_ASSOC)){
         ?>
         <div class="flex">
            <img src="uploaded_files/<?= $fetch_get['image']; ?>" class="image" alt="">
            <div>
               <h3 class="name"><?= $fetch_get['name']; ?></h3>
               <p class="price"><i class="fas fa-indian-rupee-sign"></i> <?= $fetch_get['price']; ?> x 1</p>
            </div>
         </div>
         <?php
               }
            }else{
               $select_cart = $conn->prepare("SELECT * FROM tbcart WHERE cUserid = ?");
               $select_cart->execute([$user_id]);
               if($select_cart->rowCount() > 0){
                  while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                     $select_products = $conn->prepare("SELECT * FROM tbproduct WHERE cProductid = ?");
                     $select_products->execute([$fetch_cart['cProductid']]);
                     $fetch_product = $select_products->fetch(PDO::FETCH_ASSOC);
                     $sub_total = $fetch_product['cPrice'];

                     $grand_total += $sub_total;

            
         ?>
         <div class="flex">
            <img src="<?php echo $fetch_product['file_path']; ?>" alt="<?php echo $fetch_product['file_name']; ?>" class="image">
            <div>
               <h3 class="name"><?= $fetch_product['cProductName']; ?></h3>
               <p class="price"><i class="fas fa-indian-rupee-sign"></i> <?= $fetch_product['cPrice']; ?></p>
            </div>
         </div>

         <?php
                  }
               }else{
                  echo '<p class="empty">your cart is empty</p>';
               }
            }
            
         ?>
         <div class="grand-total"><span>grand total :</span><p><i class="fas fa-indian-rupee-sign"></i> <?= $grand_total; ?></p></div>
           <?php $_SESSION['TotalAmount']= $grand_total; ?>
   </div>



</section>





<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
   <script src="js/script.js"></script>
   <?php include 'components/alert.php'; ?>

</body>
</html>