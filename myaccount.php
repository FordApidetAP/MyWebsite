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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</head>
<style>
    body{margin-top:20px;
    background-color:#f2f6fc;
    color:#69707a;
    }
    .img-account-profile {
        height: 10rem;
    }
    .rounded-circle {
        border-radius: 50% !important;
    }
    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgb(33 40 50 / 15%);
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
<section class="profile-edit">
<div class="container-xl px-4 mt-4">
        <div class="row">
            <div class="card mb-4 mb-xl-0">
            <h1 style="padding: 25px 0px 15px 25px ">Edit Profile</h1>
            <hr class="mt-0 mb-4">
                <div class="card-body text-center">
                <form action="myaccountprocess.php" method="post">
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
                    <div class="mb-3 text-start  fs-3">
                                <label class="small mb-1" for="inputUsername">Username</label>
                                <input class="form-control  fs-4" id="inputUsername" name="inputUsername" type="text" placeholder="Enter your username" value="<?php echo $row["cUsername"]; ?>" readonly>
                            </div>
                            <!-- Form Row-->
                            <div class="row gx-3 mb-3 text-start  fs-3">
                                <!-- Form Group (first name)-->
                                <div class="col-md-6">
                                    <label class="small mb-1" for="inputFirstName">First name</label>
                                    <input class="form-control  fs-4" id="inputFirstName" name="inputFirstName" type="text" placeholder="Enter your first name" value="<?php echo isset($row["cFirstName"]) ? $row["cFirstName"] : ''; ?>" <?php echo isset($row["cFirstName"]) ? 'readonly' : ''; ?>>
                                </div>
                                <!-- Form Group (last name)-->
                                <div class="col-md-6">
                                    <label class="small mb-1" for="inputLastName">Last name</label>
                                    <input class="form-control  fs-4" id="inputLastName" name="inputLastName" type="text" placeholder="Enter your last name" value="<?php echo isset($row["cLastName"]) ? $row["cLastName"] : ''; ?>" <?php echo isset($row["cLastName"]) ? 'readonly' : ''; ?>>
                                </div>
                            </div>
                            <!-- Form Row        -->
                            <div class="row gx-3 mb-3 text-start  fs-3">
                                <!-- Form Group (organization name)-->
                                <div class="col-md-6">
                                    <label class="small mb-1" for="inputEmail">Email address</label>
                                    <input class="form-control  fs-4" id="inputEmail" name="inputEmail" type="email" placeholder="Enter your email" value="<?php echo $roww["cEmail"]; ?>" readonly>
                                </div>
                                <!-- Form Group (location)-->
                                <div class="col-md-6">
                                <label class="small mb-1" for="inputPhone">Phone number</label>
                                    <input class="form-control  fs-4" id="inputPhone" name="inputPhone" type="tel" placeholder="Enter your phone number" value="<?php echo isset($row["cPhoneNumber"]) ? $row["cPhoneNumber"] : ''; ?>" <?php echo isset($row["cPhoneNumber"]) ? 'readonly' : ''; ?>>
                                </div>
                            </div>
                            <!-- Form Group (email address)-->

                            <?php
                                $sql_provinces = $conn->prepare("SELECT * FROM tbprovince");
                                $sql_provinces->execute();
                
                            ?>

                            <div class="row gx-3 mb-3 text-start  fs-3">
                                <!-- Form Group (first name)-->
                                <div class="col-md-6">
                                    <label for="sel1">Changwat</label>
                                        <select class="form-control  fs-4" name="Ref_prov_id" id="provinces">
                                            <option value="" selected disabled>-กรุณาเลือกจังหวัด-</option>
                                                <?php foreach ($sql_provinces as $value) { ?>
                                            <option value="<?=$value['cProvinceid']?>"><?=$value['namethProvince']?></option>
                                            $_SESSION['province']= $row['cProvinceid'];
                                            $_SESSION['provincename']= $row['namethProvince'];
                                                <?php } ?>
                                        </select>
                                </div>
                                <!-- Form Group (last name)-->
                                <div class="col-md-6">
                                    <label for="sel1">Amphoe</label>
                                    <select class="form-control  fs-4" name="Ref_dist_id" id="amphures"></select>
                                </div>
                            </div>
                            <!-- Form Row        -->
                            <div class="row gx-3 mb-3 text-start  fs-3">
                                <!-- Form Group (organization name)-->
                                <div class="col-md-6">
                                    <label for="sel1">Tambon</label>
                                    <select class="form-control  fs-4" name="Ref_subdist_id" id="districts"></select>
                                </div>
                                <!-- Form Group (location)-->
                                <div class="col-md-6">
                                    <label for="sel1">Postal code</label>
                                    <input type="text" name="zip_code" id="zip_code" class="form-control  fs-4">
                                </div>
                            </div>


                            <div class="mb-3 text-start  fs-3">
                            
                                    <label class="small mb-1" for="inputLocation">Location</label>
                                    <input class="form-control  fs-4" id="inputLocation" name="inputLocation" type="text" placeholder="Enter your location" value="<?php echo isset($row["cAddress"]) ? $row["cAddress"] : ''; ?>" <?php echo isset($row["cAddress"]) ? 'readonly' : ''; ?>>
                            </div>
                            

                            <!-- Form Row-->
                            <br>
                            <!-- Save changes button-->
                            <button class="btn btn-primary" name="myaccount" type="submit" style="font-size: 20px">Save changes</button>
                        </form>
            
                    </div>
                </div>
            </div>
        </div>
    </div>
                <script src="js/script.js"></script>
                <?php include('script.php');?>
</body>
</html>