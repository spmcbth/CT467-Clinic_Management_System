<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config.php';
require_once 'functions.php';

if (!isset($_GET['id'])) {
    $_SESSION['thongbao'] = "<div class='alert alert-danger'>Lỗi: Mã thuốc không hợp lệ!</div>";
    header("Location: ../quanly_thuoc.php");
    exit();
}

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
$conn->close();

header("Location: ../quanly_thuoc.php");
echo "<script>window.location.href='../quanly_thuoc.php';</script>";
exit();

?>