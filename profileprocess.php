<!-- imgprofile -->
<?php

    session_start();
    include 'components/connect.php';
    if(!isset($_SESSION['User_Login'])){
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
        header("Location:index.php");
    }
    $loginid = $_SESSION['User_Login'];

    if(isset($_POST['changeimg'])){

        $image = $_FILES['fileInput']['tmp_name'];
        $imagedata = file_get_contents($image);


        if(empty($image)) {
            $_SESSION['error'] = 'กรุณาอัพโหลดรูป';
            header("Location:profile.php");
        }else{
            try{

                $upimg = $conn->prepare("UPDATE tbuser SET cImguser = :imagedata WHERE cUserid = :loginid");
                
                $upimg->bindParam(":imagedata", $imagedata);
                $upimg->bindParam(':loginid', $loginid);


                $upimg->execute();
                

                header("Location: profile.php");

            } catch (PDOException $e) {
                $_SESSION['error'] = 'มีข้อผิดพลาดในการอัปเดตข้อมูล: ' . $e->getMessage();
                header("Location: profile.php");
            }
        }
    }
    ?>
                