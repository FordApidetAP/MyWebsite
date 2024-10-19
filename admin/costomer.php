<?php 

session_start();
include '../components/connect.php';
error_reporting(0);
       
if(!isset($_SESSION['Admin_Login'])){
  $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
  header("Location:../index.php");}

$adminid = $_SESSION['Admin_Login'];


    if (isset($_REQUEST['delete_id'])) {
        $id = $_REQUEST['delete_id'];

        $select_stmt1 = $conn->prepare("SELECT * FROM tbuser WHERE cUserid = :id");
        $select_stmt1->bindParam(':id', $id);
        $select_stmt1->execute();
        $row = $select_stmt1->fetch(PDO::FETCH_ASSOC);

        $select_stmt2 = $conn->prepare("SELECT * FROM tblogin WHERE cLoginid = :id");
        $select_stmt2->bindParam(':id', $id);
        $select_stmt2->execute();
        $roww = $select_stmt2->fetch(PDO::FETCH_ASSOC);

        // Delete an original record from conn
        $delete_stmt1 = $conn->prepare('DELETE FROM tbuser WHERE cUserid = :id');
        $delete_stmt1->bindParam(':id', $id);
        $delete_stmt1->execute();

        // Delete an original record from conn
        $delete_stmt2 = $conn->prepare('DELETE FROM tblogin WHERE cLoginid = :id');
        $delete_stmt2->bindParam(':id', $id);
        $delete_stmt2->execute();

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
    <h1 class="text-center">Customer</h1>
    <a href="add.php" class="btn btn-success mb-3" style="float: right; margin-right: 3rem;">Add+</a>
    <table class="table table-bordered table-striped table-hover text-center">
        <thead>
            <th>id</th>            
            <th>Username</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone number</th>
            <th>Address</th>
            <th>Type</th>
            <th>Edit</th>
            <th>Delete</th>
        </thead>

        <tbody>
        <?php 
    $select_tbuser = $conn->prepare("SELECT * FROM tbuser");
    $select_tbuser->execute();

    while($rowtbuser = $select_tbuser->fetch(PDO::FETCH_ASSOC)) {

        $select_tblogin = $conn->prepare("SELECT * FROM tblogin WHERE cLoginid = :userid");
        $select_tblogin->bindParam(':userid', $rowtbuser["cUserid"]);
        $select_tblogin->execute();
        $rowtblogin = $select_tblogin->fetch(PDO::FETCH_ASSOC);
        
        $select_tbtype = $conn->prepare("SELECT * FROM tbtype WHERE cTypeid = :cTypeid");
        $select_tbtype->bindParam(':cTypeid', $rowtblogin["cTypeid"]);
        $select_tbtype->execute();
        $rowtbtype = $select_tbtype->fetch(PDO::FETCH_ASSOC);

        $districts = $rowtbuser['cDistrictsid'];
        $sqldistricts = "
            SELECT * FROM tbdistricts 
            LEFT JOIN tbamphures ON tbdistricts.cAmphuresid = tbamphures.cAmphuresid 
            LEFT JOIN tbprovince ON tbamphures.cProvinceid = tbprovince.cProvinceid 
            WHERE tbdistricts.cDistrictsid = :districts";
        
        $stmtdistricts = $conn->prepare($sqldistricts);
        $stmtdistricts->execute([':districts' => $districts]);
        $rowdistricts = $stmtdistricts->fetch(PDO::FETCH_ASSOC);
        

        
            $amphures = $rowdistricts['cAmphuresid'];
            $sqlamphures ="SELECT * FROM tbamphures WHERE cAmphuresid =:amphures";
            $stmtamphures = $conn->prepare($sqlamphures);
            $stmtamphures->execute([':amphures' => $amphures]);
            $rowamphures = $stmtamphures->fetch(PDO::FETCH_ASSOC);
        
            $provinces = $rowamphures['cProvinceid'];
            $sqlprovinces ="SELECT * FROM tbprovince WHERE cProvinceid =:provinces";
            $stmtprovinces = $conn->prepare($sqlprovinces);
            $stmtprovinces->execute([':provinces' => $provinces]);
            $rowprovinces = $stmtprovinces->fetch(PDO::FETCH_ASSOC);
            

        
?>
<tr>
    <td><?php echo $rowtbuser["cUserid"]; ?></td>
    <td><?php echo $rowtbuser["cUsername"]; ?></td>
    <td><?php echo $rowtbuser["cFirstName"] . ' ' . $rowtbuser["cLastName"]; ?></td>
    <td><?php echo $rowtblogin["cEmail"]; ?></td>
    <td><?php echo $rowtbuser["cPhoneNumber"]; ?></td>
    <td>
        <?php echo $rowtbuser["cAddress"]; ?>
        <?php if(isset($rowdistricts['namethDistricts'])): ?>
            ตำบล <?php echo $rowdistricts['namethDistricts']; ?>
        <?php endif; ?>
        <?php if(isset($rowamphures['namethAmphures'])): ?>
            อำเภอ <?php echo $rowamphures['namethAmphures']; ?>
        <?php endif; ?>
        <?php if(isset($rowprovinces['namethProvince'])): ?>
            จังหวัด <?php echo $rowprovinces['namethProvince']; ?>
        <?php endif; ?>
        <?php echo isset($rowdistricts['zip_code']) ? $rowdistricts['zip_code'] : ''; ?>
    </td>
    <td><?php echo $rowtbtype["cTypename"]; ?></td>
    <td><a href="editcostomer.php?update_id=<?php echo $rowtbuser["cUserid"]; ?>" class="btn btn-warning">Edit</a></td>
    <td><a href="?delete_id=<?php echo $rowtbuser["cUserid"]; ?>" class="btn btn-danger">Delete</a></td>
</tr>

<?php } ?>


        </tbody>
    </table>
    </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
</body>
</html>