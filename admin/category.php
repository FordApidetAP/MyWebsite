<?php 
session_start();
include '../components/connect.php';
       
if(!isset($_SESSION['Admin_Login'])){
  $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
  header("Location:../index.php");}

$adminid = $_SESSION['Admin_Login'];


    if (isset($_REQUEST['delete_id'])) {
        $id = $_REQUEST['delete_id'];

        $select_stmt1 = $conn->prepare("SELECT * FROM tbcategory WHERE cCategoryid = :id");
        $select_stmt1->bindParam(':id', $id);
        $select_stmt1->execute();
        $row = $select_stmt1->fetch(PDO::FETCH_ASSOC);

        // Delete an original record from conn
        $delete_stmt1 = $conn->prepare('DELETE FROM tbcategory WHERE cCategoryid = :id');
        $delete_stmt1->bindParam(':id', $id);
        $delete_stmt1->execute();


        header('Location:costomer.php');
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

    <h1 class="text-center">Category</h1>
    <a href="addcategory.php" class="btn btn-success mb-3" style="float: right; margin-right: 3rem;">Add+</a>
    <table class="table table-bordered table-striped table-hover text-center">
        <thead>
            <th>id</th>            
            <th>Username</th>
            <th>Delete</th>
        </thead>

        <tbody>
        <?php 
    $select_tbcategory = $conn->prepare("SELECT * FROM tbcategory");
    $select_tbcategory->execute();

    while($rowtbcategory = $select_tbcategory->fetch(PDO::FETCH_ASSOC)) {
        
?>
<tr>
    <td><?php echo $rowtbcategory["cCategoryid"]; ?></td>
    <td><?php echo $rowtbcategory["cCategoryName"]; ?></td>
    <td><a href="?delete_id=<?php echo $rowtbcategory["cCategoryid"]; ?>" class="btn btn-danger">Delete</a></td>
</tr>

<?php } ?>


        </tbody>
    </table>
    </div>
    </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
</body>
</html>