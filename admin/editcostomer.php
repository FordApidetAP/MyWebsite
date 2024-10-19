<?php 

session_start();
include '../components/connect.php';
       
if(!isset($_SESSION['Admin_Login'])){
  $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
  header("Location:../index.php");}

$adminid = $_SESSION['Admin_Login'];


    if (isset($_REQUEST['update_id'])) {
        try {
            $id = $_REQUEST['update_id'];
            $select_tbuser = $conn->prepare("SELECT * FROM tbuser WHERE cUserid = :id");
            $select_tbuser->bindParam(':id', $id);
            $select_tbuser->execute();
            $rowtbuser = $select_tbuser->fetch(PDO::FETCH_ASSOC);
            extract($rowtbuser);

            $select_tblogin = $conn->prepare("SELECT * FROM tblogin WHERE cLoginid = :id");
            $select_tblogin->bindParam(':id', $id);
            $select_tblogin->execute();
            $rowtblogin = $select_tblogin->fetch(PDO::FETCH_ASSOC);
            extract($rowtblogin);
        } catch(PDOException $e) {
            $e->getMessage();
        }
    }

    if (isset($_REQUEST['btn_update'])) {
        $username = $_REQUEST['txt_username'];
        $email = $_REQUEST['txt_email'];
        $type = $_REQUEST['txt_type'];

        if (empty($username)) {
            $errorMsg = "Please enter Firstname";
        } else if (empty($email)) {
            $errorMsg = "please Enter Lastname";
        } else if (empty($type)) {
            $errorMsg = "select your type";
        } else {
            try {
                if (!isset($errorMsg)) {
                    $insert_tbuser = $conn->prepare("UPDATE tbuser SET cUsername = :username WHERE cUserid = :id");
                    $insert_tbuser->bindParam(':username', $username);
                    $insert_tbuser->bindParam(':id', $id);

                    $insert_tblogin = $conn->prepare("UPDATE tblogin SET cEmail = :email, cTypeid = :type WHERE cLoginid = :id");
                    $insert_tblogin->bindParam(':email', $email);
                    $insert_tblogin->bindParam(':type', $type);
                    $insert_tblogin->bindParam(':id', $id);

                    if ($insert_tbuser->execute() && $insert_tblogin->execute()) {
                        $insertMsg = "Insert Successfully...";
                        header("refresh:2;costomer.php");
                    }
                }
            } catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
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
<?php include 'Sidebar.php'; ?>

<div class="col py-3">
<div class="container">
    <div class="display-3 text-center">Edit Page</div>

    <?php 
         if (isset($errorMsg)) {
    ?>
        <div class="alert alert-danger">
            <strong>Wrong! <?php echo $errorMsg; ?></strong>
        </div>
    <?php } ?>
    

    <?php 
         if (isset($updateMsg)) {
    ?>
        <div class="alert alert-success">
            <strong>Success! <?php echo $updateMsg; ?></strong>
        </div>
    <?php } ?>

    <form method="post" class="form-horizontal mt-5">
                    
                    <div class="form-group text-center mb-3">
                        <div class="row">
                            <label for="firstname" class="col-sm-3 control-label">Username</label>
                            <div class="col-sm-9">
                                <input type="text" name="txt_username" class="form-control" value="<?php echo $cUsername; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-center mb-3">
                        <div class="row">
                            <label for="Email" class="col-sm-3 control-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" name="txt_email" class="form-control"  value="<?php echo $cEmail; ?>">
                            </div>
                        </div>
                    </div>
                            <?php
                                $sql ="SELECT * FROM tbtype";
                            
                                $stmt = $conn->prepare($sql);
                                $stmt->execute();
                                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);   
                            ?>
                    <div class="form-group text-center mb-3">
                        <div class="row">
                            <label for="password" class="col-sm-3 control-label">Type</label>
                                <div class="col-sm-9">
                                <select id="txt_type" name="txt_type" class="form-control">
                                    <option  value="<?php echo $cTypeid; ?>">Select Type</option>
                                    <?php foreach ($row as $index => $Type) { ?>
                                        <option value="<?php echo $index + 1; ?>"><?php echo ($index + 1) . " - " . $Type['cTypename']; ?></option>
                                    <?php } ?>
                                </select>
                                </div>
                        </div>
                    </div>


                    <div class="form-group text-center mb-3">
                        <div class="col-md-12 mt-3">
                            <input type="submit" name="btn_update" class="btn btn-success" value="Insert">
                            <a href="costomer.php" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>


            </form>

</div>
</div>
</div>
</body>
</html>