<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../config.php';
require_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_them'])) {
    // Tạo mã loại thuốc tự động
    $query = "SELECT MAX(CAST(SUBSTRING(MaLoai, 3) AS UNSIGNED)) as max_id FROM LoaiThuoc WHERE MaLoai LIKE 'L%'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $next_id = ($row['max_id']) ? $row['max_id'] + 1 : 1;
    $MaLoai = 'LT' . str_pad($next_id, 3, '0', STR_PAD_LEFT);

    $TenLoai = $_POST['ten_loai'];
    $DonViTinh = $_POST['don_vi_tinh'];

    // Gọi function để thêm loại thuốc
    if (ThemLoaiThuoc($MaLoai, $TenLoai, $DonViTinh)) {
        $_SESSION['thongbao'] = "<div class='alert alert-success'>Thêm loại thuốc thành công!</div>";
    } else {
        $_SESSION['thongbao'] = "<div class='alert alert-danger'>Lỗi thêm loại thuốc: " . $conn->error . "</div>";
    }

    header("Location: ../quanly_loai_thuoc.php"); // Quay lại trang quản lý loại thuốc
    exit();
}
?>
