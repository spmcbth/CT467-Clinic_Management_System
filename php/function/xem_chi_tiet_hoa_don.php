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
$hoaDon = LayHoaDon($conn, $maHD);
$chiTietHoaDon = LayChiTietHoaDon($conn, $maHD);

if (!$hoaDon) {
    echo "Hóa đơn không tồn tại!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết hóa đơn - Quản Lý Nhà Thuốc</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/image/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/chitiethoadon.css">
</head>
<body>

<div class="wrapper">
    <div class="content-wrapper">
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
                                <?php foreach ($chiTietHoaDon as $row) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['TenThuoc']) ?></td>
                                        <td><?= htmlspecialchars($row['SoLuongBan']) ?></td>
                                        <td><?= number_format($row['GiaBan'], 0, ',', '.') ?> VNĐ</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <a href="../quanly_hoadon.php" class="btn btn-secondary mt-3">Quay Lại</a>
                        <a href="export_pdf.php?id=<?= htmlspecialchars($hoaDon['MaHD']) ?>" class="btn btn-primary mt-3">Xuất Hóa Đơn PDF</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
