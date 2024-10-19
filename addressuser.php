<?php
session_start();
include 'components/connect.php';

if(!isset($_SESSION['User_Login'])){
   $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
   header("Location:index.php");}

   $user_id = $_SESSION['User_Login'];

   if (isset($_REQUEST['delete_id'])) {
    $id = $_REQUEST['delete_id'];

   $select_stmt1 = $conn->prepare("SELECT * FROM tbaddress WHERE cAUserid = ?");
   $select_stmt1->execute([$id]);

   $delete_stmt1 = $conn->prepare("DELETE FROM tbaddress WHERE cAUserid = ?");
   $delete_stmt1->execute([$id]);

   header('Location:addressuser.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
   <link rel="stylesheet" href="css/stylecopy.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="py-3 container">
    <h1 class="text-center p-2" style="font-size:50px;">Address</h1>
    <a href="addressuseradd.php" class="btn btn-success mb-3" style="float: right; margin-right: 3rem; font-size:20px;">Add+</a>
    <table class="table table-bordered table-striped table-hover text-center container-fulid">
        <thead style="font-size:20px;">
            <th>ไอดี</th>
            <th>ชื่อผู้รับ</th>
            <th>เบอร์โทรศัพท์</th>
            <th>อีเมล</th>
            <th>ข้อมูลที่อยู่</th>
            <th>Delete</th>
        </thead>

        <tbody>
        <?php 
    $select_tbaddress = $conn->prepare("SELECT * FROM tbaddress WHERE cAUserid = $user_id ");
    $select_tbaddress->execute();

    while($rowtbaddress = $select_tbaddress->fetch(PDO::FETCH_ASSOC)) {
        $districts = $rowtbaddress['cDistrictsid'];
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
<tr style="font-size:16px;">
    <td><?php echo $rowtbaddress["cAAddressid"]; ?></td>
    <td><?php echo $rowtbaddress["cAFirstName"] . ' ' . $rowtbaddress["cALastName"]; ?></td>
    <td><?php echo $rowtbaddress["cAPhoneNumber"]; ?></td>
    <td><?php echo $rowtbaddress["cAEmail"]; ?></td>
    <td>
        <?php echo $rowtbaddress["cAAddress"]; ?>
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
    <td><a href="?delete_id=<?php echo $rowtbaddress["cAAddressid"]; ?>" class="btn btn-danger">Delete</a></td>
</tr>

<?php } ?>


        </tbody>
    </table>
    </div>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
   <script src="js/script.js"></script>
   <?php include 'components/alert.php'; ?>

                
</body>
</html>