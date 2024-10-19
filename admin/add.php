<?php 

    include '../components/connect.php';

    
    if (isset($_REQUEST['btn_insert'])) {
        $username = $_REQUEST['txt_username'];
        $email = $_REQUEST['txt_email'];
        $password = $_REQUEST['txt_password'];
        $type = $_REQUEST['txt_type'];

        if (empty($username)) {
            $errorMsg = "Please enter Firstname";
        } else if (empty($email)) {
            $errorMsg = "please Enter Lastname";
        } else if (strlen($_REQUEST['txt_password']) > 20 || strlen($_REQUEST['txt_password']) < 5) {
            $errorMsg = "please Enter Password must be 5 to 20 characters long.";
        } else if (empty($type)) {
            $errorMsg = "select your type";
        } else {
            try {
                if (!isset($errorMsg)) {
                    $insert_tbuser = $conn->prepare("INSERT INTO tbuser(cUsername) VALUES (:username)");
                    $insert_tbuser->bindParam(':username', $username);

                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    $insert_tblogin = $conn->prepare("INSERT INTO tblogin(cEmail, cPassword, cTypeid) VALUES (:email, :password, :type)");
                    $insert_tblogin->bindParam(':email', $email);
                    $insert_tblogin->bindParam(':password', $passwordHash);
                    $insert_tblogin->bindParam(':type', $type);

                    if ($insert_tbuser->execute() && $insert_tblogin->execute()) {
                        $insertMsg = "Insert Successfully...";
                        header("refresh:2;costomer.php");
                    }
                }
            } catch (PDOException $e) {
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
        <?php 
                if (isset($errorMsg)) {
            ?>
                <div class="alert alert-danger">
                    <strong>Wrong! <?php echo $errorMsg; ?></strong>
                </div>
            <?php } ?>
            

            <?php 
                if (isset($insertMsg)) {
            ?>
                <div class="alert alert-success">
                    <strong>Success! <?php echo $insertMsg; ?></strong>
                </div>
            <?php } ?>

            <form method="post" class="form-horizontal mt-5">
                    
                    <div class="form-group text-center mb-3">
                        <div class="row">
                            <label for="firstname" class="col-sm-3 control-label">Username</label>
                            <div class="col-sm-9">
                                <input type="text" name="txt_username" class="form-control" placeholder="Enter Username...">
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-center mb-3">
                        <div class="row">
                            <label for="Email" class="col-sm-3 control-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" name="txt_email" class="form-control" placeholder="Enter Email...">
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-center mb-3">
                        <div class="row">
                            <label for="password" class="col-sm-3 control-label">Password</label>
                            <div class="col-sm-9">
                                <input type="password" name="txt_password" class="form-control" placeholder="Enter Password...">
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
                                    <option value="">Select Type</option>
                                    <?php foreach ($row as $index => $Type) { ?>
                                        <option value="<?php echo $index + 1; ?>"><?php echo ($index + 1) . " - " . $Type['cTypename']; ?></option>
                                    <?php } ?>
                                </select>
                                </div>
                        </div>
                    </div>


                    <div class="form-group text-center mb-3">
                        <div class="col-md-12 mt-3">
                            <input type="submit" name="btn_insert" class="btn btn-success" value="Insert">
                            <a href="costomer.php" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>


            </form>
    </div>
</div>
</body>
</html>