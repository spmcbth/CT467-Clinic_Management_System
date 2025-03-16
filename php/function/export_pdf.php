<?php
ob_start(); // Xóa buffer để tránh lỗi file bị hỏng

require_once '../../vendor/tecnickcom/tcpdf/tcpdf.php';
require_once '../config.php';
require_once 'functions.php';

$maHD = $_GET['id'];
$hoaDon = LayHoaDon($conn, $maHD);
$chiTietHoaDon = LayChiTietHoaDon($conn, $maHD);

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator('Hệ Thống Quản Lý Hiệu Thuốc');
$pdf->SetTitle('Hóa Đơn Bán Thuốc - ' . $maHD);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->AddPage();
$pdf->SetFont('dejavusans', '', 11);

$html = '
<style>
    body { font-family: DejaVu Sans; }
    .header { text-align: center; font-size: 20px; font-weight: bold; margin-bottom: 10px; }
    .info p { margin: 5px 0; }
    .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .table th, .table td { border: 1px solid #000; padding: 8px; text-align: center; }
    .table th { background-color: #f2f2f2; font-weight: bold; }
    .total { text-align: right; font-size: 14px; font-weight: bold; margin-top: 10px; }
    .footer { text-align: center; margin-top: 40px; }
</style>

<div class="header">HÓA ĐƠN BÁN THUỐC</div>

<div class="info">
    <p><strong>Mã Hóa Đơn:</strong> ' . $hoaDon['MaHD'] . '</p>
    <p><strong>Khách Hàng:</strong> ' . $hoaDon['TenKH'] . '</p>
    <p><strong>Ngày Lập:</strong> ' . $hoaDon['NgayLap'] . '</p>
</div>

<h3>Chi Tiết Hóa Đơn</h3>
<table class="table">
    <tr>
        <th width="10%">STT</th>
        <th width="40%">Tên Thuốc</th>
        <th width="15%">Số Lượng</th>
        <th width="15%">Đơn Giá</th>
        <th width="20%">Thành Tiền</th>
    </tr>';

$stt = 1;
$tongTien = 0;
foreach ($chiTietHoaDon as $row) {
    $thanhTien = $row['SoLuongBan'] * $row['GiaBan'];
    $tongTien += $thanhTien;
    $html .= '
    <tr>
        <td>' . $stt++ . '</td>
        <td>' . htmlspecialchars($row['TenThuoc']) . '</td>
        <td>' . $row['SoLuongBan'] . '</td>
        <td>' . number_format($row['GiaBan'], 0, ',', '.') . ' VNĐ</td>
        <td>' . number_format($thanhTien, 0, ',', '.') . ' VNĐ</td>
    </tr>';
}

$html .= '
</table>

<div class="total">
    <p><strong>Tổng Tiền: ' . number_format($hoaDon['TongTien'], 0, ',', '.') . ' VNĐ</strong></p>
</div>

<div class="footer">
    <p>Ngày ' . date('d') . ' tháng ' . date('m') . ' năm ' . date('Y') . '</p>
    <p style="margin-top: 50px; font-weight: bold;">Người lập hóa đơn</p>
</div>';

$pdf->writeHTML($html, true, false, true, false, '');
ob_end_clean();
$pdf->Output('HoaDon_' . $maHD . '.pdf', 'I');
?>
