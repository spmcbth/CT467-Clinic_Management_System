<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../config.php';
require_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_them'])) {
    // Tạo mã khách hàng tự động
    $query = "SELECT MAX(CAST(SUBSTRING(MaKH, 3) AS UNSIGNED)) as max_id FROM KhachHang WHERE MaKH LIKE 'KH%'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $next_id = ($row['max_id']) ? $row['max_id'] + 1 : 1;
    $MaKH = 'KH' . str_pad($next_id, 3, '0', STR_PAD_LEFT);

    // Lấy dữ liệu từ form
    $TenKH = $_POST['ten_kh'];
    $SoDienThoai = $_POST['so_dien_thoai'];
    $DiaChi = $_POST['dia_chi'];

    // Gọi function để thêm khách hàng
    if (ThemKhachHang($MaKH, $TenKH, $SoDienThoai, $DiaChi)) {
        $_SESSION['thongbao'] = "<div class='alert alert-success'>Thêm khách hàng thành công!</div>";
    } else {
        $_SESSION['thongbao'] = "<div class='alert alert-danger'>Lỗi thêm khách hàng: " . mysqli_error($conn) . "</div>";
    }

    header("Location: ../quanly_khachhang.php"); // Quay lại trang quản lý khách hàng
    exit();
}
?>