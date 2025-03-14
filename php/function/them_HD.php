<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../config.php';
require_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $maKH = $_POST['maKH'];
    $ngayLap = date('Y-m-d');
    $tongTien = $_POST['tongTien'];

    // Thêm hóa đơn trước
    $maHD = ThemHoaDon($conn, $maKH, $ngayLap, $tongTien);

    if ($maHD) {
        // Thêm từng chi tiết hóa đơn
        foreach ($_POST['chiTietHoaDon'] as $chiTiet) {
            $maThuoc = $chiTiet['maThuoc'];
            $soLuongBan = $chiTiet['soLuongBan'];
            $giaBan = $chiTiet['giaBan'];

            ThemChiTietHoaDon($conn, $maHD, $maThuoc, $soLuongBan, $giaBan);
        }
        echo "Thêm hóa đơn thành công!";
    } else {
        echo "Lỗi khi thêm hóa đơn!";
    }

    header("Location: ../quanly_hoadon.php"); // Quay lại trang quản lý thuốc
    exit();
}

?>