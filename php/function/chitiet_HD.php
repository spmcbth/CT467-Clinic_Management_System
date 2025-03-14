<?php
require_once '../config.php';
require_once '../function/functions.php';

$maHD = $_GET['id'];
$hoaDon = LayHoaDonTheoMa($conn, $maHD);
$chiTietHD = LayChiTietHoaDon($conn, $maHD);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Chi Tiết Hóa Đơn</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h2>Chi Tiết Hóa Đơn</h2>
    <p>Mã Hóa Đơn: <?php echo $hoaDon['MaHD']; ?></p>
    <p>Khách Hàng: <?php echo $hoaDon['TenKH']; ?></p>
    <p>Ngày Lập: <?php echo $hoaDon['NgayLap']; ?></p>
    <p>Tổng Tiền: <?php echo number_format($hoaDon['TongTien'], 0, ',', '.'); ?> đ</p>

    <h3>Danh Sách Thuốc</h3>
    <table border="1">
        <tr>
            <th>Tên Thuốc</th>
            <th>Số Lượng</th>
            <th>Giá Bán</th>
            <th>Thành Tiền</th>
        </tr>
        <?php while ($row = $chiTietHD->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['TenThuoc']; ?></td>
                <td><?php echo $row['SoLuongBan']; ?></td>
                <td><?php echo number_format($row['GiaBan'], 0, ',', '.'); ?> đ</td>
                <td><?php echo number_format($row['SoLuongBan'] * $row['GiaBan'], 0, ',', '.'); ?> đ</td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
