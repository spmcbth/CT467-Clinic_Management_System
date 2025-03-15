<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
require_once ROOT_PATH . "/config.php"; 
require_once ROOT_PATH . '/function/functions.php';
require_once ROOT_PATH . '/function/them_HD.php';  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $maHD = $_POST["MaHD"];
    $maKH = $_POST["MaKH"];
    $ngayLap = $_POST["NgayLap"];
    $tongTien = 0;

    // Thêm hóa đơn vào bảng HoaDon
    if (ThemHoaDon($con, $maHD, $maKH, $ngayLap)) {
        foreach ($_POST["Thuoc"] as $index => $maThuoc) {
            $soLuong = $_POST["SoLuong"][$index];
            $giaBan = $_POST["GiaBan"][$index];
            $tongTien += $soLuong * $giaBan;

            $maCTHD = "CT" . str_pad($index + 1, 3, "0", STR_PAD_LEFT); // Tạo Mã Chi Tiết Hóa Đơn tự động

            ThemChiTietHoaDon($maCTHD, $maHD, $maThuoc, $soLuong, $giaBan); // Thêm vào bảng ChiTietHoaDon
        }
        CapNhatTongTienHoaDon($maHD, $tongTien);
        header("Location: ../quanly_hoadon.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Thuốc - Quản Lý Nhà Thuốc</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/image/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/table.css">
    <link rel="stylesheet" href="../assets/css/them_thuoc.css">
</head>
<body>
<div class="wrapper">
    <div class="content-wrapper">
        <?php include 'includes/sidebar.php'; ?>
        
        <div class="main-content">
            <div class="content">
                <div class="container">
                    <div class="page-header">
                        <h2>Thêm Chi Tiết Hóa Đơn</h2>
                    </div>

                    <div class="card p-4 shadow">
                        <form method="post">
                            <label>Mã Hóa Đơn:</label>
                            <input type="text" name="MaHD" required>

                            <label>Khách Hàng:</label>
                            <select name="MaKH">
                                <?php
                                $khachHangList = LayDanhSachKhachHang();
                                while ($kh = $khachHangList->fetch_assoc()) {
                                    echo "<option value='{$kh['MaKH']}'>{$kh['TenKH']}</option>";
                                }
                                ?>
                            </select>

                            <label>Ngày Lập:</label>
                            <input type="date" name="NgayLap" required>

                            <h3>Chi Tiết Hóa Đơn</h3>
                            <div id="chiTietHoaDon">
                                <div class="row">
                                    <select name="Thuoc[]">
                                        <?php
                                        $thuocList = LayDanhSachThuoc();
                                        while ($thuoc = $thuocList->fetch_assoc()) {
                                            echo "<option value='{$thuoc['MaThuoc']}'>{$thuoc['TenThuoc']} - {$thuoc['DonGia']} đ</option>";
                                        }
                                        ?>
                                    </select>
                                    <input type="number" name="SoLuong[]" min="1" required>
                                    <input type="text" name="GiaBan[]" required>
                                    <button type="button" onclick="xoaDong(this)">X</button>
                                </div>
                            </div>

                            <button type="button" onclick="themDong()">+ Thêm Thuốc</button>
                            <button type="submit">Lưu Hóa Đơn</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</div>

    <script>
        function themDong() {
            let div = document.createElement("div");
            div.classList.add("row");
            div.innerHTML = document.querySelector(".row").innerHTML;
            document.getElementById("chiTietHoaDon").appendChild(div);
        }
        function xoaDong(btn) {
            btn.parentElement.remove();
        }
    </script>
</body>
</html>
