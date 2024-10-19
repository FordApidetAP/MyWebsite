<?php

    session_start();
    include 'components/connect.php';

    if(isset($_POST['register'])){

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $type = '2';

        if(empty($username)){
            $_SESSION['error'] = 'กรุณากรอกชื่อ';
            header("Location:register.php");
        }elseif(empty($email)){
            $_SESSION['error'] = 'กรุณากรอกอีเมล';
            header("Location:register.php");
        }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $_SESSION['error'] = 'รูปแบบอีเมลไม่ถูกต้อง';
            header("Location:register.php");
        }elseif(strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5){
            $_SESSION['error'] = 'รหัสผ่านต้องมีความยาว 5 ถึง 20 ตัวอักษร';
            header("Location:register.php");
        }else{
            try{

                $check_email = $conn->prepare("SELECT cEmail FROM tblogin WHERE cEmail = :email");
                $check_email->bindParam(":email", $email);
                $check_email->execute();
                $row = $check_email->fetch(PDO::FETCH_ASSOC);

                if($row['cEmail'] == $email){
                    $_SESSION['warning'] = 'มีอีเมลนี้อยู่แล้ว';
                    header("Location:register.php");
                }elseif(!isset($_SESSION['error'])){

                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    $stmtlogin = $conn->prepare("INSERT INTO tblogin(cEmail,cPassword, cTypeid)
                                                VALUES(:email, :password, :type)");
                    $stmtlogin->bindParam(":email", $email);
                    $stmtlogin->bindParam(":password", $passwordHash);
                    $stmtlogin->bindParam(":type", $type);
                    $stmtlogin->execute();


                    $stmtuser = $conn->prepare("INSERT INTO tbuser(cUsername)
                                                VALUE(:username)");
                    $stmtuser->bindParam(":username", $username);
                    $stmtuser->execute();


                    $_SESSION['success'] = "สมัครสมาชิกสำเร็จแล้ว";
                    header("Location:index.php");
                }else{
                    $_SESSION['error'] = "มีบางอย่างผิดพลาด";
                    header("Location:register.php");
                }

            }catch(PDOException $e){
                echo $e->getMessage();
            }
        }
    }
?>