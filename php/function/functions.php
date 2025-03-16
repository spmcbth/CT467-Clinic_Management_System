<?php
include __DIR__ . '/../config.php';

// Các hàm lấy danh sách 
if (!function_exists('LayDanhSachThuoc')) {
    function LayDanhSachThuoc(){
        global $conn;
        if ($conn->more_results()) {
            $conn->next_result();
        }
        return $conn->query("CALL LayDanhSachThuoc()");
    }
}

if (!function_exists('LayDanhSachLoaiThuoc')) {
    function LayDanhSachLoaiThuoc(){
        global $conn;
        return $conn->query("CALL LayDanhSachLoaiThuoc()");
    }
}

if (!function_exists('LayDanhSachKhachhang')) {
    function LayDanhSachKhachhang(){
        global $conn;
        return $conn->query("CALL LayDanhSachKhachHang()");
    }
}

if (!function_exists('LayDanhSachHoaDon')) {
    function LayDanhSachHoaDon(){
        global $conn;
        return $conn->query("CALL LayDanhSachHoaDon()");
    }
}

if (!function_exists('LayDanhSachThuocHetHan')) {
    function LayDanhSachThuocHetHan(){
        global $conn;
        return $conn->query("CALL LayDanhSachThuocHetHan()");
    }
}

if (!function_exists('LayDanhSachHangSX')) {
    function LayDanhSachHangSX() {
        global $conn;
        return $conn->query("CALL LayDanhSachHangSX()");
    }
}

if (!function_exists('LayDanhSachNhaCungCap')) {
    function LayDanhSachNhaCungCap() {
        global $conn;
        return $conn->query("CALL LayDanhSachNhaCungCap()");
    }    
}

// Hàm thêm thuốc
if (!function_exists('ThemThuoc')) {
    function ThemThuoc($MaThuoc, $MaLoai, $MaHangSX, $MaNCC, $TenThuoc, $CongDung, $DonGia, $SoLuong, $HanSuDung) {
        global $conn;
        $stmt = $conn->prepare("CALL ThemThuoc(?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssdss", $MaThuoc, $MaLoai, $MaHangSX, $MaNCC, $TenThuoc, $CongDung, $DonGia, $SoLuong, $HanSuDung);
        return $stmt->execute();
    }
}

// Hàm sửa thuốc
if (!function_exists('kiemTraDuLieuThuoc')) {
    function kiemTraDuLieuThuoc($TenThuoc, $MaLoai, $CongDung, $DonGia, $SoLuong) {
        if (empty($TenThuoc) || empty($MaLoai) || empty($CongDung)) {
            return "Thông tin thuốc không được để trống!";
        }
        if ($DonGia < 0) {
            return "Đơn giá không thể nhỏ hơn 0!";
        }
        if ($SoLuong < 0) {
            return "Số lượng không thể nhỏ hơn 0!";
        }
        return ""; // Trả về rỗng nếu dữ liệu hợp lệ
    }
}

if (!function_exists('SuaThuoc')) {
    function SuaThuoc($MaThuoc, $MaLoai, $MaHangSX, $MaNCC, $TenThuoc, $CongDung, $DonGia, $SoLuong, $HanSuDung) {
        global $conn;
        $stmt = $conn->prepare("CALL SuaThuoc(?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssdss", $MaThuoc, $MaLoai, $MaHangSX, $MaNCC, $TenThuoc, $CongDung, $DonGia, $SoLuong, $HanSuDung);
        return $stmt->execute();
    }
}

// Hàm xóa thuốc
if (!function_exists('XoaThuoc')) {
    function XoaThuoc($MaThuoc) {
        global $conn;
        $stmt = $conn->prepare("CALL XoaThuoc(?)");
        $stmt->bind_param("s", $MaThuoc);
        return $stmt->execute();
    }
}

#      ===  HÓA ĐƠN  ===
// Thêm hóa đơn
function ThemHoaDon($MaHD, $MaKH, $NgayLap, $TongTien) {
    global $conn;
    $query = "INSERT INTO HoaDon (MaHD, MaKH, NgayLap, TongTien) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssd", $MaHD, $MaKH, $NgayLap, $TongTien);
    return mysqli_stmt_execute($stmt);
}
// Thêm chi tiết hóa đơn
function ThemChiTietHoaDon($MaCTHD, $MaHD, $MaThuoc, $SoLuongBan, $GiaBan) {
    global $conn;
    $query = "INSERT INTO ChiTietHoaDon (MaCTHD, MaHD, MaThuoc, SoLuongBan, GiaBan) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssid", $MaCTHD, $MaHD, $MaThuoc, $SoLuongBan, $GiaBan);
    return mysqli_stmt_execute($stmt);
}
// Cập nhật tổng tiền
function CapNhatTongTienHoaDon($MaHD, $TongTien) {
    global $conn;
    $query = "UPDATE HoaDon SET TongTien = ? WHERE MaHD = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ds", $TongTien, $MaHD);
    return mysqli_stmt_execute($stmt);
}
// Cập nhật số lượng thuốc
function CapNhatSoLuongThuoc($MaThuoc, $SoLuong) {
    global $conn;
    
    // Kiểm tra số lượng tồn kho
    $query = "SELECT SoLuongTonKho FROM Thuoc WHERE MaThuoc = '$MaThuoc'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    
    if ($row['SoLuongTonKho'] < $SoLuong) {
        return false; // Không đủ số lượng
    }
    
    // Cập nhật số lượng
    $query = "UPDATE Thuoc SET SoLuongTonKho = SoLuongTonKho - $SoLuong WHERE MaThuoc = '$MaThuoc'";
    return mysqli_query($conn, $query);
}


if (!function_exists('LayHoaDon')) {
    function LayHoaDon($conn, $maHD) {
        $query = "CALL LayHoaDon(?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $maHD);
        $stmt->execute();
        
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}

if (!function_exists('LayChiTietHoaDon')) {
    function LayChiTietHoaDon($conn, $maHD) {
        $query = "CALL LayChiTietHoaDon(?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $maHD);
        $stmt->execute();
    
        $result = $stmt->get_result();
        $chiTietHoaDon = [];
    
        while ($row = $result->fetch_assoc()) {
            $chiTietHoaDon[] = $row; // Lưu nhiều dòng vào mảng
        }
    
        $stmt->close();
        return $chiTietHoaDon; // Trả về danh sách chi tiết hóa đơn
    }
}






?>