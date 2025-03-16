<?php
ob_start(); // Xóa buffer để tránh lỗi file bị hỏng

require_once '../../vendor/autoload.php';
require_once '../config.php'; 
require_once 'functions.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

try {
    // Tạo đối tượng Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Thiết lập tiêu đề file Excel
    $sheet->setCellValue('A1', 'CHI TIẾT HÓA ĐƠN');
    $sheet->mergeCells('A1:F1'); // Gộp ô
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Tiêu đề cột
    $sheet->setCellValue('A3', 'Mã Hóa Đơn');
    $sheet->setCellValue('B3', 'Khách Hàng');
    $sheet->setCellValue('C3', 'Ngày Lập');
    $sheet->setCellValue('D3', 'Tên Thuốc');
    $sheet->setCellValue('E3', 'Số Lượng');
    $sheet->setCellValue('F3', 'Giá Bán (VNĐ)');
    $sheet->setCellValue('G3', 'Thành Tiền (VNĐ)');

    // Bôi đậm tiêu đề cột
    $sheet->getStyle('A3:G3')->getFont()->setBold(true);
    $sheet->getStyle('A3:G3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Truy vấn lấy dữ liệu chi tiết hóa đơn
    $sql = "SELECT HoaDon.MaHD, KhachHang.TenKH, HoaDon.NgayLap, 
                   Thuoc.TenThuoc, ChiTietHoaDon.SoLuongBan, ChiTietHoaDon.GiaBan, 
                   (ChiTietHoaDon.SoLuongBan * ChiTietHoaDon.GiaBan) AS ThanhTien
            FROM HoaDon 
            JOIN KhachHang ON HoaDon.MaKH = KhachHang.MaKH
            JOIN ChiTietHoaDon ON HoaDon.MaHD = ChiTietHoaDon.MaHD
            JOIN Thuoc ON ChiTietHoaDon.MaThuoc = Thuoc.MaThuoc
            ORDER BY HoaDon.MaHD";

    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $rowNum = 4; // Dòng bắt đầu nhập dữ liệu vào excel 
        $currentMaHD = null;

        while ($row = $result->fetch_assoc()) {
            if ($row['MaHD'] !== $currentMaHD) {
                $currentMaHD = $row['MaHD'];  // Im thêm dòng cho hóa đơn mới 

                // In thông tin hóa đơn
                $sheet->setCellValue('A' . $rowNum, $row['MaHD']);
                $sheet->setCellValue('B' . $rowNum, $row['TenKH']);
                $sheet->setCellValue('C' . $rowNum, date('d/m/Y', strtotime($row['NgayLap'])));
                
                // Bôi đậm dòng tiêu đề hóa đơn
                $sheet->getStyle("A$rowNum:G$rowNum")->getFont()->setBold(true);
                $sheet->getStyle("A$rowNum:G$rowNum")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $rowNum++; // Xuống dòng để in chi tiết sản phẩm
            }

            // In chi tiết từng sản phẩm của hóa đơn
            $sheet->setCellValue('D' . $rowNum, $row['TenThuoc']);
            $sheet->setCellValue('E' . $rowNum, $row['SoLuongBan']);
            $sheet->setCellValueExplicit('F' . $rowNum, $row['GiaBan'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $sheet->setCellValueExplicit('G' . $rowNum, $row['ThanhTien'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            
            // Định dạng cột tiền tệ theo kiểu 10,000
            $sheet->getStyle('F' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('G' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');
            

            $rowNum++; // Tăng dòng tiếp theo
        }
    } else {
        $sheet->setCellValue('A4', 'Không có dữ liệu hóa đơn.');
    }

    // Tự động căn chỉnh cột
    foreach (range('A', 'G') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $conn->close();

    ob_end_clean(); // Xóa buffer

    // Thiết lập header để tải file Excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="ChiTietHoaDon.xlsx"');
    header('Cache-Control: max-age=0');

    // Xuất file Excel
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

} catch (Exception $e) {
    echo "Đã xảy ra lỗi: " . $e->getMessage();
    exit;
}
?>
