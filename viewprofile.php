<?php

session_start();
include 'components/connect.php';

if(!isset($_SESSION['User_Login'])){
   $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
   header("Location:index.php");}

    $userid = $_SESSION['User_Login'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>PhotoFolio Bootstrap Template - Index</title>
    
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <link rel="stylesheet" href="css/bootstrap.css">
   <link rel="stylesheet" href="css/style.css">


</head>
<style>
body{
background-color:#f2f6fc;
color:#69707a;
font-size: 15;
}
.img-account-profile {
    height: 25rem;
}
.rounded-circle {
    border-radius: 50% !important;
}
.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgb(33 40 50 / 15%);
    height: 775;
}
.card .card-header {
    font-weight: 500;
}
.card-header:first-child {
    border-radius: 0.35rem 0.35rem 0 0;
}
.card-header {
    padding: 1rem 1.35rem;
    margin-bottom: 0;
    background-color: rgba(33, 40, 50, 0.03);
    border-bottom: 1px solid rgba(33, 40, 50, 0.125);
}
.form-control, .dataTable-input {
    display: block;
    width: 100%;
    padding: 0.875rem 1.125rem;
    font-size: 0.875rem;
    font-weight: 400;
    line-height: 1;
    color: #69707a;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #c5ccd6;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border-radius: 0.35rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}



</style>


<body>
<?php include 'header.php'; ?>
<section class="profile">


<div class="container-xl px-4 mt-4">
        <div class="row">
                <div class="card-body text-center">
                    <form action="" method="post" enctype="multipart/form-data">
                        <?php
                            if(isset($_REQUEST['profile_id'])) {
                                $profile_id = $_REQUEST['profile_id'];
                              
                                $check_data = $conn->prepare("SELECT * FROM tbuser WHERE cUserid = :profile_id");
                                $check_data->bindParam(":profile_id", $profile_id);
                                $check_data->execute();
                                $row = $check_data->fetch(PDO::FETCH_ASSOC);
                            }
                                    ?>
                        <img id="previewImage" class="img-account-profile rounded-circle mb-2" src="<?php echo isset($row['cImguser']) ? 'data:image/jpeg;base64,' . base64_encode($row['cImguser']) : 'http://bootdey.com/img/Content/avatar/avatar1.png'; ?>" alt="">
                                <p></p><div class="small font-italic text-muted mb-4" style="font-size:30px"><?php echo $row['cUsername']?></div>
                    </form>
                </div>
            </div>

            <section class="products" >


   <div class="box-container">

   <?php 
      $select_products = $conn->prepare("SELECT * FROM tbproduct WHERE cUserid = :profile_id");
      $select_products->bindParam(":profile_id", $profile_id);
      $select_products->execute();
      

      if($select_products->rowCount() > 0){
         while($fetch_prodcut = $select_products->fetch(PDO::FETCH_ASSOC)){
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
            
        </div>
</div>


</section>
<script src="js/script.js"></script>

<?php include 'components/alert.php'; ?>


    
</body>
</html>