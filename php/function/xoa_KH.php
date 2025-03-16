<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config.php';
require_once 'functions.php';

if (!isset($_GET['id'])) {
    $_SESSION['thongbao'] = "<div class='alert alert-danger'>Lỗi: Mã khách hàng không hợp lệ!</div>";
    header("Location: ../quanly_khachhang.php");
    exit();
}

$MaKH = $_GET['id'];

// Gọi stored procedure để xóa khách hàng
$stmt = $conn->prepare("CALL XoaKhachHang(?)");
$stmt->bind_param("s", $MaKH);

if ($stmt->execute()) {
    $_SESSION['thongbao'] = "<div class='alert alert-success'>Xóa khách hàng thành công!</div>";
} else {
    $_SESSION['thongbao'] = "<div class='alert alert-danger'>Lỗi: Không thể xóa khách hàng! Hãy kiểm tra xem khách hàng có hóa đơn hay không.</div>";
}

$stmt->close();
$conn->close();

header("Location: ../quanly_khachhang.php");
exit();
?>