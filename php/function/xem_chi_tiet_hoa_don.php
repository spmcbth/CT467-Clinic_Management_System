<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

require_once '../config.php';
require_once 'functions.php';

if (!isset($_GET['id'])) {
    echo "Không tìm thấy hóa đơn!";
    exit();
}

$maHD = $_GET['id'];

// Lấy thông tin hóa đơn
$sqlHoaDon = "SELECT HoaDon.MaHD, KhachHang.TenKH, HoaDon.NgayLap, HoaDon.TongTien
              FROM HoaDon
              JOIN KhachHang ON HoaDon.MaKH = KhachHang.MaKH
              WHERE HoaDon.MaHD = ?";
$stmt = $conn->prepare($sqlHoaDon);
$stmt->bind_param("s", $maHD);
$stmt->execute();
$resultHoaDon = $stmt->get_result();
$hoaDon = $resultHoaDon->fetch_assoc();

if (!$hoaDon) {
    echo "Hóa đơn không tồn tại!";
    exit();
}

// Lấy danh sách thuốc trong hóa đơn
$sqlChiTiet = "SELECT Thuoc.TenThuoc, ChiTietHoaDon.SoLuongBan, ChiTietHoaDon.GiaBan
               FROM ChiTietHoaDon
               JOIN Thuoc ON ChiTietHoaDon.MaThuoc = Thuoc.MaThuoc
               WHERE ChiTietHoaDon.MaHD = ?";
$stmt = $conn->prepare($sqlChiTiet);
$stmt->bind_param("s", $maHD);
$stmt->execute();
$resultChiTiet = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Hóa Đơn</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/table.css">
</head>
<body>

<div class="wrapper">
    <div class="content-wrapper">
        <?php include 'includes/sidebar.php'; ?>

        <div class="main-content">
            <div class="content">
                <div class="container">
                    <div class="page-header">
                        <h2>Chi Tiết Hóa Đơn</h2>
                    </div>

                    <div class="card p-4 shadow">
                        <h4><strong>Mã Hóa Đơn:</strong> <?= htmlspecialchars($hoaDon['MaHD']) ?></h4>
                        <h4><strong>Khách Hàng:</strong> <?= htmlspecialchars($hoaDon['TenKH']) ?></h4>
                        <h4><strong>Ngày Lập:</strong> <?= htmlspecialchars($hoaDon['NgayLap']) ?></h4>
                        <h4><strong>Tổng Tiền:</strong> <?= number_format($hoaDon['TongTien'], 0, ',', '.') ?> VNĐ</h4>

                        <h3 class="mt-4">Danh Sách Thuốc</h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tên Thuốc</th>
                                    <th>Số Lượng</th>
                                    <th>Giá Bán</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $resultChiTiet->fetch_assoc()) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['TenThuoc']) ?></td>
                                        <td><?= htmlspecialchars($row['SoLuongBan']) ?></td>
                                        <td><?= number_format($row['GiaBan'], 0, ',', '.') ?> VNĐ</td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>

                        <a href="hoa_don.php" class="btn btn-secondary mt-3">Quay Lại</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</div>

</body>
</html>
