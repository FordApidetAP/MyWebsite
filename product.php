<?php 

session_start();
include 'components/connect.php';
       
if(!isset($_SESSION['Admin_Login'])){
  $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
  header("Location:../index.php");}

$adminid = $_SESSION['Admin_Login'];

    if (isset($_REQUEST['delete_id'])) {
        $id = $_REQUEST['delete_id'];

        $select_stmt1 = $conn->prepare("SELECT * FROM tbproduct WHERE cProductid = :id");
        $select_stmt1->bindParam(':id', $id);
        $select_stmt1->execute();
        $row = $select_stmt1->fetch(PDO::FETCH_ASSOC);

        // Delete an original record from conn
        $delete_stmt1 = $conn->prepare('DELETE FROM tbproduct WHERE cProductid = :id');
        $delete_stmt1->bindParam(':id', $id);
        $delete_stmt1->execute();


        header('Location:product.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<?php


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
                        <a href="admin/Admin.php" class="nav-link align-middle px-0">
                            <i class="fs-4 bi-house" style="font-size:20px;"></i> <span class="ms-1 d-none d-sm-inline">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="admin/payment.php" class="nav-link px-0 align-middle">
                        <i class="bi bi-wallet" style="font-size:20px;"></i> <span class="ms-1 d-none d-sm-inline">Payment</span></a>
                    </li>
                    <li>
                        <a href="admin/category.php" class="nav-link px-0 align-middle">
                            <i class="bi bi-book"  style="font-size:20px;"></i> <span class="ms-1 d-none d-sm-inline">Category</span></a>
                    </li>
                    <li>
                        <a href="product.php" class="nav-link px-0 align-middle">
                        <i class="bi bi-bag" style="font-size:20px;"></i> <span class="ms-1 d-none d-sm-inline">Products</span> </a>
                    </li>
                    <li>
                        <a href="admin/costomer.php" class="nav-link px-0 align-middle">
                        <i class="bi bi-person" style="font-size:20px;"></i> <span class="ms-1 d-none d-sm-inline">Customers</span> </a>
                    </li>
                </ul>
                <hr>
                <div class="dropdown pb-4">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <img id="image" class="img-account-profile rounded-circle mb-2" src="<?php echo isset($row['cImguser']) ? 'data:image/jpeg;base64,' . base64_encode($row['cImguser']) : 'http://bootdey.com/img/Content/avatar/avatar1.png'; ?>" alt=""style="width: 2rem;height: 2rem;">                        
                    &nbsp;&nbsp;<span><?php echo $row['cUsername']?>&nbsp;&nbsp;<i class="fa-solid fa-angle-down"></i></span>     
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                        <li><a class="dropdown-item" href="#">New project...</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Sign out</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        
<div class="col py-3">
    <h1 class="text-center">Product</h1>
    <table class="table table-bordered table-striped table-hover text-center">
        <thead>
            <th>id</th>            
            <th>ImageProduct</th>
            <th>ImageName</th>
            <th>ProductName</th>
            <th>Detail</th>
            <th>Price</th>
            <th>Category</th>
            <th>Creator</th>
            <th>Delete</th>
        </thead>

        <tbody>
        <?php 
    $select_tbproduct = $conn->prepare("SELECT * FROM tbproduct");
    $select_tbproduct->execute();
    

    while($rowtbproduct = $select_tbproduct->fetch(PDO::FETCH_ASSOC)) {

        $select_tbcategory = $conn->prepare("SELECT * FROM tbcategory WHERE cCategoryid = :userid");
        $select_tbcategory->bindParam(':userid', $rowtbproduct["cCategoryid"]);
        $select_tbcategory->execute();
        $rowtbcategory = $select_tbcategory->fetch(PDO::FETCH_ASSOC);

        $select_tbuser = $conn->prepare("SELECT * FROM tbuser WHERE cUserid = :userid");
        $select_tbuser->bindParam(':userid', $rowtbproduct["cUserid"]);
        $select_tbuser->execute();
        $rowtbuser = $select_tbuser->fetch(PDO::FETCH_ASSOC);

?>
            <tr>
                <td><?php echo $rowtbproduct["cProductid"]; ?></td> 
                <td><img src="<?php echo $rowtbproduct["file_path"]; ?>" style="width: 7rem;  border: 2px solid black;"></td>
                <td><?php echo $rowtbproduct["file_name"]; ?></td>
                <td><?php echo $rowtbproduct["cProductName"]; ?></td>
                <td><?php echo $rowtbproduct["cDetails"]; ?></td>
                <td><?php echo $rowtbproduct["cPrice"]; ?></td>
                <td><?php echo $rowtbcategory["cCategoryName"]; ?></td>
                <td><?php echo $rowtbuser["cUsername"]; ?></td>
                <td><a href="?delete_id=<?php echo $rowtbproduct["cProductid"]; ?>" class="btn btn-danger">Delete</a></td>
            </tr>
<?php 
        }
?>


        </tbody>
    </table>
    </div>

        </div>
    </div>
</body>
</html>