<?php

include 'components/connect.php';

if (isset($_POST['function']) && $_POST['function'] == 'provinces') {
    $id = $_POST['id'];
    $sql = "SELECT * FROM tbamphures WHERE cProvinceid=:id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo '<option value="" selected disabled>-กรุณาเลือกอำเภอ-</option>';
    foreach ($result as $row) {
        echo '<option value="'.$row['cAmphuresid'].'">'.$row['namethAmphures'].'</option>';
        $_SESSION['amphures']= $row['cAmphuresid'];
        $_SESSION['amphuresname']= $row['namethAmphures'];
    }
}

if (isset($_POST['function']) && $_POST['function'] == 'amphures') {
    $id = $_POST['id'];
    $sql = "SELECT * FROM tbdistricts WHERE cAmphuresid=:id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo '<option value="" selected disabled>-กรุณาเลือกตำบล-</option>';
    foreach ($result as $row) {
        echo '<option value="'.$row['cDistrictsid'].'">'.$row['namethDistricts'].'</option>';
        $_SESSION['districts']= $row['cDistrictsid'];
        $_SESSION['districtsname']= $row['namethDistricts'];
    }
}

if (isset($_POST['function']) && $_POST['function'] == 'districts') {
    $id = $_POST['id'];
    $sql = "SELECT * FROM tbdistricts WHERE cDistrictsid=:id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $result['zip_code'];
    $_SESSION['zip_code']= $result['zip_code'];
    exit();
}
?>
