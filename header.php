<?php
       include 'components/connect.php';
       
       if(!isset($_SESSION['User_Login'])){
         $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
         header("Location:index.php");}
 
     $userid = $_SESSION['User_Login'];

     
    $sql = "SELECT * FROM tbuser WHERE cUserid = :userid";   
    $stmt = $conn->prepare($sql);
    $stmt->execute([':userid' => $userid]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<header class="header">
   <section class="flex">
      <a href="view_products.php" class="logo" style="text-decoration: none;">Logo</a>

      <div class="spacer"></div>

      <div id="menu-btnn" class="fas fa-bars"></div>

      <nav class="navbar" >
         <a href="add_product.php" style="text-decoration: none;">add product</a>
         <a href="view_products.php" style="text-decoration: none;">view products</a>
         <?php
            $count_cart_items = $conn->prepare("SELECT * FROM tbcart WHERE cUserid = :userid");
            $count_cart_items->execute([':userid' => $userid]);
            $total_cart_items = $count_cart_items->rowCount();
         ?>
         <a href="shopping_cart.php" class="cart-btnn" style="text-decoration: none;">cart<span><?= $total_cart_items; ?></span></a>
      </nav>

      <div class="profilee-dropdown">
         <div onclick="toggle()" class="profilee-dropdown-btnn">
            <div class="profilee-img">
      <img id="image" class="img-account-profile rounded-circle mb-2" src="<?php echo isset($row['cImguser']) ? 'data:image/jpeg;base64,' . base64_encode($row['cImguser']) : 'http://bootdey.com/img/Content/avatar/avatar1.png'; ?>" alt=""style="width: 3rem;
      height: 3rem;">

               <i class="fa-solid fa-circle"></i>
            </div>
            &nbsp;&nbsp;
            <span><?php echo $row['cUsername']?>&nbsp;&nbsp;<i class="fa-solid fa-angle-down"></i></span>
         </div>

         <ul class="profilee-dropdown-list">
            <li class="profilee-dropdown-list-item"><a href="profile.php"><i class="fa-regular fa-user"></i>Profile</a></li>
            <li class="profilee-dropdown-list-item"><a href="orders.php"><i class="fa-regular fa-envelope"></i>Myorders</a></li>
            <li class="profilee-dropdown-list-item"><a href="addressuser.php"><i class="fa-regular fa-envelope"></i>AddAddress</a></li>
            <hr />
            <li class="profilee-dropdown-list-item"><a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i>Log out</a></li>
         </ul>
      </div>
   </section>
</header>
