<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../config.php';
require_once 'functions.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_them'])) {
    // Tạo mã thuốc tự động
    $query = "SELECT MAX(CAST(SUBSTRING(MaThuoc, 3) AS UNSIGNED)) as max_id FROM Thuoc WHERE MaThuoc LIKE 'T%'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $next_id = ($row['max_id']) ? $row['max_id'] + 1 : 1;
    $MaThuoc = 'T' . str_pad($next_id, 3, '0', STR_PAD_LEFT);
    
    $MaLoai = $_POST['ma_loai'];         
    $MaHangSX = $_POST['ma_hangsx'];     
    $MaNCC = $_POST['ma_ncc'];           
    $TenThuoc = $_POST['ten_thuoc'];     
    $CongDung = $_POST['cong_dung'];     
    $DonGia = $_POST['don_gia'];         
    $SoLuong = $_POST['so_luong'];       
    $HanSuDung = $_POST['han_su_dung'];

    // Gọi function để thêm thuốc
    if (ThemThuoc($MaThuoc, $MaLoai, $MaHangSX, $MaNCC, $TenThuoc, $CongDung, $DonGia, $SoLuong, $HanSuDung)) {
        $_SESSION['thongbao'] = "<div class='alert alert-success'>Thêm thuốc thành công!</div>";
    } else {
        $_SESSION['thongbao'] = "<div class='alert alert-danger'>Lỗi thêm thuốc: " . $stmt->error . "</div>";
    }
    
    $stmt->close();
    header("Location: ../quanly_thuoc.php"); // Quay lại trang quản lý thuốc
    exit();
}

?>
