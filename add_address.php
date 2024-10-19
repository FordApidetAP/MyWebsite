<?php
session_start();
include 'components/connect.php';

if(!isset($_SESSION['User_Login'])){
   $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
   header("Location:index.php");}

   $user_id = $_SESSION['User_Login'];

if(isset($_POST['add'])){

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phonenumber = $_POST['phonenumber'];
    $address = $_POST['address'];
    $zip_code = $_POST['zip_code'];
    $districts = $_POST['Ref_subdist_id'];
    $amphures = $_POST['Ref_dist_id'];
    $provinces = $_POST['Ref_prov_id'];


            $insert_tbaddress = $conn->prepare("INSERT INTO tbaddress(cAUserid,
                                                                      cAFirstName, 
                                                                      cALastName, 
                                                                      cAPhoneNumber, 
                                                                      cAEmail, 
                                                                      cAAddress, 
                                                                      cDistrictsid) 
                                                        VALUES(:user_id, :firstname, :lastname, :phonenumber, :email, :address, :districts)");
            $insert_tbaddress->bindParam(':user_id', $user_id);
            $insert_tbaddress->bindParam(':firstname', $firstname);
            $insert_tbaddress->bindParam(':lastname', $lastname);
            $insert_tbaddress->bindParam(':phonenumber', $phonenumber);
            $insert_tbaddress->bindParam(':email', $email);
            $insert_tbaddress->bindParam(':address', $address);
            $insert_tbaddress->bindParam(':districts', $districts);       

            if ($insert_tbaddress->execute()) {
                $success_msg[] = 'Added to address!';
                header("Location:addressuser.php");
                exit();
            }
            
    
}
?>