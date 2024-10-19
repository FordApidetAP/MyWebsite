<?php

session_start();
include 'components/connect.php';

if(!isset($_SESSION['User_Login'])){
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("Location:index.php");
}

if(isset($_POST['myaccount'])){

    $firstname = $_POST['inputFirstName'];
    $lastname = $_POST['inputLastName'];
    $loginid = $_SESSION['User_Login'];
    $phonenumber = $_POST['inputPhone'];
    $location = $_POST['inputLocation'];
    $zip_code = $_POST['zip_code'];
    $districts = $_POST['Ref_subdist_id'];
    $amphures = $_POST['Ref_dist_id'];
    $provinces = $_POST['Ref_prov_id'];


    if(empty($firstname)){
        $_SESSION['error'] = 'กรุณากรอกชื่อ';
        header("Location:myaccount.php");
    } elseif(empty($lastname)){
        $_SESSION['error'] = 'กรุณากรอกนามสกุล';
        header("Location:myaccount.php");
    } elseif(empty($phonenumber)){
        $_SESSION['error'] = 'กรุณากรอกเบอร์โทรศัพท์';
        header("Location:myaccount.php");
    } elseif(empty($location)){
        $_SESSION['error'] = 'กรุณากรอกที่ตั้ง';
        header("Location:myaccount.php");
    } elseif(empty($districts)){
        $_SESSION['error'] = 'กรุณากรอกที่อยู่';
        header("Location:myaccount.php");
    } else {
        try{

            $sql = "UPDATE tbuser 
                    SET 
                        cFirstName = :firstname, 
                        cLastName = :lastname,
                        cLoginid = :loginid,
                        cPhoneNumber = :phonenumber, 
                        cAddress = :location, 
                        cDistrictsid = :districts
                    WHERE cUserid = :loginid";

        $stmt = $conn->prepare($sql);
            
        // ผูกค่า parameter
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':phonenumber', $phonenumber);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':districts', $districts);
        $stmt->bindParam(':loginid', $loginid);


        $stmt->execute();

        $_SESSION['zipcode'] = $zip_code;
        $_SESSION['districts'] = $districts;
        $_SESSION['amphures'] = $amphures;
        $_SESSION['provinces'] = $provinces;
            
        header("Location: profile.php");

        } catch (PDOException $e) {
            $_SESSION['error'] = 'มีข้อผิดพลาดในการอัปเดตข้อมูล: ' . $e->getMessage();
            header("Location: myaccount.php");
        }
    }
}
?>