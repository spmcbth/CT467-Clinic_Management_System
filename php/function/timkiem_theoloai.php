<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../config.php';
require_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_tim_kiem'])) {
    $maLoai = $_POST['loai_thuoc'];

    if (!empty($maLoai)) {
        global $conn;

        // Lấy tên loại thuốc từ CSDL
        $sql = "SELECT TenLoai, DonViTinh FROM LoaiThuoc WHERE MaLoai = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $maLoai);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $tenLoai = $row['TenLoai']; 
        $DonViTinh = $row['DonViTinh'];

        // Lấy số lượng tồn kho từ function
        $soLuong = LaySoLuongThuoc($maLoai);

        // Thông báo kết quả
        $_SESSION['thongbao'] = "<div class='alert alert-info'>Số lượng tồn kho của loại thuốc <b>$tenLoai</b>: <b>$soLuong</b> <b>$DonViTinh</b>.</div>";
    } else {
        $_SESSION['thongbao'] = "<div class='alert alert-danger'>Vui lòng chọn loại thuốc!</div>";
    }

    header("Location: ../quanly_loai_thuoc.php");
    exit();
}
?>
