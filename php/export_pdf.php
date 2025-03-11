<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Nạp thư viện MPDF
require_once 'config.php'; // Kết nối database

use Mpdf\Mpdf;

if (isset($_GET['id'])) {
    $maHD = $_GET['id'];

    // Truy vấn thông tin hóa đơn
    $sql = "SELECT h.MaHD, h.NgayLap, h.TongTien, k.TenKH, k.SoDienThoai, k.DiaChi
            FROM HoaDon h
            JOIN KhachHang k ON h.MaKH = k.MaKH
            WHERE h.MaHD = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $maHD);
    $stmt->execute();
    $result = $stmt->get_result();
    $hoaDon = $result->fetch_assoc();

    if (!$hoaDon) {
        die("Không tìm thấy hóa đơn!");
    }

    // Truy vấn chi tiết hóa đơn
    $sql_chitiet = "SELECT ct.MaThuoc, t.TenThuoc, ct.SoLuongBan, ct.GiaBan 
                    FROM ChiTietHoaDon ct
                    JOIN Thuoc t ON ct.MaThuoc = t.MaThuoc
                    WHERE ct.MaHD = ?";
    
    $stmt = $conn->prepare($sql_chitiet);
    $stmt->bind_param("i", $maHD);
    $stmt->execute();
    $result_ct = $stmt->get_result();

    // Tạo nội dung HTML cho PDF
    $html = '<h2>HÓA ĐƠN BÁN THUỐC</h2>';
    $html .= '<p><strong>Mã hóa đơn:</strong> ' . $hoaDon['MaHD'] . '</p>';
    $html .= '<p><strong>Khách hàng:</strong> ' . $hoaDon['TenKH'] . '</p>';
    $html .= '<p><strong>Địa chỉ:</strong> ' . $hoaDon['DiaChi'] . '</p>';
    $html .= '<p><strong>Ngày lập:</strong> ' . $hoaDon['NgayLap'] . '</p>';
    $html .= '<p><strong>Số điện thoại:</strong> ' . $hoaDon['SoDienThoai'] . '</p>';

    $html .= '<table border="1" width="100%" cellpadding="5" cellspacing="0">
                <tr>
                    <th>Mã Thuốc</th>
                    <th>Tên Thuốc</th>
                    <th>Số Lượng</th>
                    <th>Giá Bán</th>
                </tr>';
    
    while ($row = $result_ct->fetch_assoc()) {
        $html .= '<tr>
                    <td>' . $row['MaThuoc'] . '</td>
                    <td>' . $row['TenThuoc'] . '</td>
                    <td>' . $row['SoLuongBan'] . '</td>
                    <td>' . $number_format($row['GiaBan'], 0, ',', '.') . ' VNĐ</td>
                  </tr>';
    }

    $html .= '</table>';
    $html .= '<h3>Tổng tiền: ' . $number_format($hoaDon['TongTien'], 0, ',', '.') . ' VNĐ</h3>';

    // Tạo đối tượng MPDF và xuất PDF
    $mpdf = new Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output("HoaDon_" . $maHD . ".pdf", "D"); // "D" để tải xuống file PDF
}
?> 
