<?php
session_start();
include 'components/connect.php';

if(!isset($_SESSION['User_Login'])){
   $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
   header("Location:index.php");}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.css">
</head>
<body>
<body>
      
      <?php include 'header.php'; ?>
   
      <section class="product-form">
   
   <form action="add_address.php" method="post">
      <h3>Add Address</h3>
      <div class="row gx-3 mb-3 text-start  fs-3">
           <div class="col-md-6">
               <p>Firstname<span>*</span></p>
               <input type="text" name="firstname" placeholder="enter firstname" class="box">
           </div>
           <div class="col-md-6">
               <p>Lastname<span>*</span></p>
               <input type="text" name="lastname" placeholder="enter lastname" class="box">
           </div>
       </div>
       <div class="row gx-3 mb-3 text-start  fs-3">
           <div class="col-md-6">
               <p>Email<span>*</span></p>
               <input type="text" name="email" placeholder="enter email" class="box">
           </div>
           <div class="col-md-6">
               <p>Phonenumber<span>*</span></p>
               <input type="text" name="phonenumber" placeholder="enter phonenumber" class="box">
           </div>
       </div>
       <p>Address<span>*</span></p>
       <input type="text" name="address" placeholder="enter address" class="box">
      <div class="row gx-3 mb-3 text-start  fs-3">
           <?php
               $sql_provinces = $conn->prepare("SELECT * FROM tbprovince");
               $sql_provinces->execute();
           ?>
           <div class="col-md-6">
               <p>Changwat<span>*</span></p>
                       <select class="form-control box" name="Ref_prov_id" id="provinces">
                           <option value="" selected disabled>-กรุณาเลือกจังหวัด-</option>
                               <?php foreach ($sql_provinces as $value) { ?>
                           <option value="<?=$value['cProvinceid']?>"><?=$value['namethProvince']?></option>
                               <?php } ?>
                       </select>
           </div>
           <div class="col-md-6">
               <p>Amphoe<span>*</span></p>
                   <select class="form-control box" name="Ref_dist_id" id="amphures"></select>
           </div>
       </div>
       <div class="row gx-3 mb-3 text-start  fs-3">
           <div class="col-md-6">
               <p>Tambon<span>*</span></p>
               <select class="form-control box" name="Ref_subdist_id" id="districts"></select>
           </div>
           <div class="col-md-6">
               <p>Postal code<span>*</span></p>
               <input type="text" name="zip_code" id="zip_code" class="form-control box">
           </div>
       </div>
      <input type="submit" class="btnn" name="add" value="add address">
   </form>
   
   </section>
   
   
   
   
   
   
   
   <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
      <script src="js/script.js"></script>
      <?php include('script.php');?>
      <?php include 'components/alert.php'; ?>
   
   
   
      </body>
</body>
</html>