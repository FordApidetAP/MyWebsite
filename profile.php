<?php

session_start();
include 'components/connect.php';

if(!isset($_SESSION['User_Login'])){
   $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
   header("Location:index.php");}

    $userid = $_SESSION['User_Login'];


    $sql = "SELECT * FROM tbuser WHERE cUserid = :userid";
    $sql1 = "SELECT * FROM tblogin WHERE cLoginid = :userid";
   
    $stmt = $conn->prepare($sql);
    $stmt1 = $conn->prepare($sql1);
    $stmt->execute([':userid' => $userid]);
    $stmt1->execute([':userid' => $userid]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $roww = $stmt1->fetch(PDO::FETCH_ASSOC);


    $districts = $row['cDistrictsid'];
    $sqldistricts = "
        SELECT * FROM tbdistricts 
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

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>PhotoFolio Bootstrap Template - Index</title>
    
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
  <link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/style.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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
            <div class="card mb-4 mb-xl-0">
                <div class="card-body text-center">
                <form action="profileprocess.php" method="post" enctype="multipart/form-data">
                <?php if(isset($_SESSION['error'])) { ?>
                <?php
                    echo "<script>
                    Swal.fire({
                        title: '".$_SESSION['error']."',
                        icon: 'error',
                        confirmButtonText: 'ตกลง'
                    });
                </script>";
                unset($_SESSION['error']);
                ?>	
            <?php } ?>

            <?php if(isset($_SESSION['success'])) { ?>
                <?php
                    echo "<script>
                    Swal.fire({
                        title: '".$_SESSION['success']."',
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    });
                </script>";
                unset($_SESSION['success']);    
                ?>
            <?php } ?>
            <img id="previewImage" class="img-account-profile rounded-circle mb-2" src="<?php echo isset($row['cImguser']) ? 'data:image/jpeg;base64,' . base64_encode($row['cImguser']) : 'http://bootdey.com/img/Content/avatar/avatar1.png'; ?>" alt="">
                            <div class="small font-italic text-muted mb-4" style="font-size:20px">JPG or PNG no larger than 5 MB</div>
                            <input type="file" id="fileInput"  name="fileInput" style="display: none;" accept="image/*">
                            <label for="fileInput" class="btn btn-outline-danger" style="font-size:20px">Upload image</label>
                            &nbsp;&nbsp;<button class="btn btn-primary btn-lg" name="changeimg" type="submit" style="font-size: 20px">Save Image</button>
                            <script>
                                // JavaScript
                            document.getElementById('fileInput').addEventListener('change', function() {
                                const file = this.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        document.getElementById('previewImage').src = e.target.result;
                                    };
                                    reader.readAsDataURL(file);
                                }
                            });

                            </script>
                            </form>
                    <p></p>

                                    <div class="mb-3 text-start fs-3">
                                <label class="small mb-1 fs-3" for="inputUsername">Username</label>
                                <span class="form-control fs-4 text-danger <?php echo isset($row["cUsername"]) ? '' : 'text-danger'; ?>" id="inputFirstName"> <?php echo isset($row["cUsername"]) ? $row["cUsername"] : 'Null'; ?></span>

                            </div>
                            <!-- Form Row-->
                            <div class="row gx-3 mb-3">
                                <!-- Form Group (first name)-->
                                <div class="col-md-6  text-start">
                                    <label class="small mb-1 fs-3" for="inputFirstName">First name</label>
                                    <span class="form-control fs-4 text-danger <?php echo isset($row["cFirstName"]) ? '' : 'text-danger'; ?>" id="inputFirstName"> <?php echo isset($row["cFirstName"]) ? $row["cFirstName"] : 'Null'; ?></span>
                                </div>
                                <!-- Form Group (last name)-->
                                <div class="col-md-6  text-start">
                                    <label class="small mb-1 fs-3" for="inputLastName">Last name</label>
                                    <span class="form-control fs-4 text-danger <?php echo isset($row["cLastName"]) ? '' : 'text-danger'; ?>" id="inputFirstName"> <?php echo isset($row["cLastName"]) ? $row["cLastName"] : 'Null'; ?></span>
                                </div>
                            </div>
                            <!-- Form Row        -->
                            <div class="row gx-3 mb-3  text-start">
                                <!-- Form Group (organization name)-->
                                <div class="col-md-6">
                                    <label class="small mb-1 fs-3" for="inputEmail">Email address</label>
                                    <span class="form-control fs-4 text-danger <?php echo isset($roww["cEmail"]) ? '' : 'text-danger'; ?>" id="inputFirstName"> <?php echo isset($roww["cEmail"]) ? $roww["cEmail"] : 'Null'; ?></span>
                                </div>
                                <!-- Form Group (location)-->
                                <div class="col-md-6  text-start">
                                <label class="small mb-1 fs-3" for="inputPhone">Phone number</label>
                                <span class="form-control fs-4 text-danger <?php echo isset($row["cPhoneNumber"]) ? '' : 'text-danger'; ?>" id="inputFirstName"> <?php echo isset($row["cPhoneNumber"]) ? $row["cPhoneNumber"] : 'Null'; ?></span>
                                </div>
                            </div>
                            <div class="mb-3 text-start">
                                <label class="small mb-1 fs-3 " for="inputLocation">location</label>
                                <span class="form-control fs-4 text-danger <?php echo (isset($row["cAddress"]) && isset($rowdistricts['namethDistricts'])) ? '' : 'text-danger'; ?>" id="inputFirstName">
                                <?php echo isset($row["cAddress"]) ? $row["cAddress"] : 'Null'; ?>
                                <?php if(isset($rowdistricts['namethDistricts'])): ?>
                                    ตำบล <?php echo $rowdistricts['namethDistricts']; ?>
                                <?php endif; ?>
                                <?php if(isset($rowamphures['namethAmphures'])): ?>
                                    อำเภอ <?php echo $rowamphures['namethAmphures'] ; ?>
                                <?php endif; ?>
                                <?php if(isset($rowprovinces['namethProvince'])): ?>
                                    จังหวัด <?php echo $rowprovinces['namethProvince']; ?>
                                <?php endif; ?>
                                &nbsp;<?php echo isset($rowdistricts['zip_code']) ? $rowdistricts['zip_code'] : ''; ?>
                                </span>
                            </div>
                            <div class="mb-3 text-start">
                            <p>&nbsp;</p>
                            </div>
                                    <button type="button" class="btn btn-outline-danger btn-lg" style="font-size: 20px" onclick="window.location.href='myaccount.php'">Edit Profile</button>
                                <p></p>
            
                    </div>
                </div>
            </div>
        </div>
    </div>


</section>
<script src="js/script.js"></script>

<?php include 'components/alert.php'; ?>


    
</body>
</html>