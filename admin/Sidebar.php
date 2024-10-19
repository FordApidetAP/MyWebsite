<?php

       include '../components/connect.php';
       
       if(!isset($_SESSION['Admin_Login'])){
         $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
         header("Location:../index.php");}
 
     $adminid = $_SESSION['Admin_Login'];

     
    $sql = "SELECT * FROM tbuser WHERE cUserid = :adminid";   
    $stmt = $conn->prepare($sql);
    $stmt->execute([':adminid' => $adminid]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<div class="container-fluid">
    <div class="row flex-nowrap">
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                <a href="/" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <span class="fs-5 d-none d-sm-inline">Menu</span>
                </a>
                <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                    <li class="nav-item">
                        <a href="Admin.php" class="nav-link align-middle px-0">
                            <i class="fs-4 bi-house" style="font-size:20px;"></i> <span class="ms-1 d-none d-sm-inline">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="payment.php" class="nav-link px-0 align-middle">
                        <i class="bi bi-wallet" style="font-size:20px;"></i> <span class="ms-1 d-none d-sm-inline">Payment</span></a>
                    </li>
                    <li>
                        <a href="category.php" class="nav-link px-0 align-middle">
                            <i class="bi bi-book"  style="font-size:20px;"></i> <span class="ms-1 d-none d-sm-inline">Category</span></a>
                    </li>
                    <li>
                        <a href="../product.php" class="nav-link px-0 align-middle">
                        <i class="bi bi-bag" style="font-size:20px;"></i> <span class="ms-1 d-none d-sm-inline">Products</span> </a>
                    </li>
                    <li>
                        <a href="costomer.php" class="nav-link px-0 align-middle">
                        <i class="bi bi-person" style="font-size:20px;"></i> <span class="ms-1 d-none d-sm-inline">Customers</span> </a>
                    </li>
                </ul>
                <hr>
                <div class="dropdown pb-4">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <img id="image" class="img-account-profile rounded-circle mb-2" src="<?php echo isset($row['cImguser']) ? 'data:image/jpeg;base64,' . base64_encode($row['cImguser']) : 'http://bootdey.com/img/Content/avatar/avatar1.png'; ?>" alt=""style="width: 2rem;height: 2rem;">                        
                    &nbsp;&nbsp;<span><?php echo $row['cUsername']?>&nbsp;&nbsp;<i class="fa-solid fa-angle-down"></i></span>                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                        <li><a class="dropdown-item" href="#">New project...</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
                    </ul>
                </div>
            </div>
        </div>
        