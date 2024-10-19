<?php

    session_start();
    include 'components/connect.php';

    if(isset($_POST['login'])){

        $email = $_POST['email'];
        $password = $_POST['password'];

        if(empty($email)){
            $_SESSION['error'] = 'กรุณากรอกอีเมล';
            header("Location:index.php");
        }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $_SESSION['error'] = 'รูปแบบอีเมลไม่ถูกต้อง';
            header("Location:index.php");
        }elseif(strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5){
            $_SESSION['error'] = 'รหัสผ่านต้องมีความยาว 5 ถึง 20 ตัวอักษร';
            header("Location:index.php");
        }else{
            try{ 

                $check_data = $conn->prepare("SELECT * FROM tblogin WHERE cEmail = :email");
                $check_data->bindParam(":email", $email);
                $check_data->execute();
                $row = $check_data->fetch(PDO::FETCH_ASSOC);

                if($check_data->rowCount() > 0){

                    if($email == $row['cEmail']){
                        if(password_verify($password, $row['cPassword'])){
                            if($row['cTypeid'] == 1){
                                $_SESSION['Admin_Login'] = $row['cLoginid'];
                                header("Location: admin/Admin.php");
                            }else{
                                $_SESSION['User_Login'] = $row['cLoginid'];
                                header("Location: view_products.php");
                            }
                        }else{
                            $_SESSION['error'] = 'รหัสผ่านผิด';
                            header("Location:index.php"); 
                        }
                    }else{
                        $_SESSION['error'] = 'อีเมลผิด';
                        header("Location:index.php");
                    }
                }else{
                    $_SESSION['error'] = "ไม่มีข้อมูลในระบบ";
                    header("Location:index.php");
                }

            }catch(PDOException $e){
                echo $e->getMessage();
            }
        }
    }
?>
