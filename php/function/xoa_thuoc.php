<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../config.php';
require_once 'functions.php';

$MaThuoc = $_GET['id'];

// Gọi stored procedure để xóa thuốc
$stmt = $conn->prepare("CALL XoaThuoc(?)");
$stmt->bind_param("s", $MaThuoc);

if ($stmt->execute()) {
    $_SESSION['thongbao'] = "<div class='alert alert-success'>Xóa thuốc thành công!</div>";
} else {
    $_SESSION['thongbao'] = "<div class='alert alert-danger'>Lỗi: Không thể xóa thuốc!</div>";
}

$stmt->close();
header("Location: ../quanly_thuoc.php");
exit();
?>