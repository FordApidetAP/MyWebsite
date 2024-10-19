<?php
session_start(); // เริ่มเซสชันหรือใช้เซสชันที่มีอยู่แล้ว

// ทำลบข้อมูลเซสชันทั้งหมด
session_unset();
session_destroy();

// ไปยังหน้าล็อกอินหลังจากล็อกเอาท์
header("Location: index.php");
exit();
?>
