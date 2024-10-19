<?php 

session_start();
include '../components/connect.php';
       
if(!isset($_SESSION['Admin_Login'])){
  $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
  header("Location:../index.php");}

$adminid = $_SESSION['Admin_Login'];

    if (isset($_REQUEST['delete_id'])) {
        $id = $_REQUEST['delete_id'];

        $select_stmt1 = $conn->prepare("SELECT * FROM tbpayment WHERE cPaymentid = :id");
        $select_stmt1->bindParam(':id', $id);
        $select_stmt1->execute();
        $row = $select_stmt1->fetch(PDO::FETCH_ASSOC);

        $delete_stmt1 = $conn->prepare('DELETE FROM tbpayment WHERE cPaymentid = :id');
        $delete_stmt1->bindParam(':id', $id);
        $delete_stmt1->execute();


        header('Location:payment.php');
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
        
            <h1 class="text-center">Payment</h1>
            <a href="addpayment.php" class="btn btn-success mb-3" style="float: right; margin-right: 3rem;">Add+</a>
            <table class="table table-bordered table-striped table-hover text-center">
                <thead>
                    <th>id</th>            
                    <th>Paymentname</th>
                    <th>Delete</th>
                </thead>
    
                <tbody>
                <?php 
            $select_tbpayment = $conn->prepare("SELECT * FROM tbpayment");
            $select_tbpayment->execute();
        
            while($rowtbpayment = $select_tbpayment->fetch(PDO::FETCH_ASSOC)) {
                ?>
<tr>
    <td><?php echo $rowtbpayment["cPaymentid"]; ?></td>
    <td><?php echo $rowtbpayment["cPaymentName"]; ?></td>
    <td><a href="?delete_id=<?php echo $rowtbpayment["cPaymentid"]; ?>" class="btn btn-danger">Delete</a></td>
</tr>

<?php } ?>
</tbody>
    </table>
    </div>
    </div>
        </div>
    </div>
</body>
</html>