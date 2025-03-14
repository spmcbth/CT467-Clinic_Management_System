<?php
include './config.php';

// Hàm tạo mã ID tự động
function generateID($prefix, $table, $column, $conn) {
    $prefixLength = strlen($prefix);
    $stmt = $conn->prepare("SELECT MAX(CAST(SUBSTRING($column, $prefixLength + 1) AS UNSIGNED)) AS max_id FROM $table");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $maxId = $row['max_id'] ? $row['max_id'] : 0;
    return $prefix . str_pad($maxId + 1, 3, '0', STR_PAD_LEFT);
}

// Các hàm lấy danh sách 
function LayDanhSachThuoc(){
    global $conn;
    if ($conn->more_results()) {
        $conn->next_result();
    }
    return $conn->query("CALL LayDanhSachThuoc()");
}

function LayDanhSachLoaiThuoc(){
    global $conn;
    return $conn->query("CALL LayDanhSachLoaiThuoc()");
}

function LayDanhSachKhachhang(){
    global $conn;
    return $conn->query("CALL LayDanhSachKhachHang()");
}

function LayDanhSachHoaDon(){
    global $conn;
    return $conn->query("CALL LayDanhSachHoaDon()");
}

function LayDanhSachThuocHetHan(){
    global $conn;
    return $conn->query("CALL LayDanhSachThuocHetHan()");
}

function LayDanhSachHangSX() {
    global $conn;
    return $conn->query("CALL LayDanhSachHangSX()");
}

function LayDanhSachNhaCungCap() {
    global $conn;
    return $conn->query("CALL LayDanhSachNhaCungCap()");
}

function LayChiTietHoaDon($conn, $maHD) {
    $query = "CALL LayChiTietHoaDon(?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $maHD);
    $stmt->execute();
    
    $result = $stmt->get_result();
    return $result;
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

// HÓA ĐƠN 
function ThemHoaDon($conn, $maKH, $ngayLap, $tongTien) {
    $query = "INSERT INTO HoaDon (MaKH, NgayLap, TongTien) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssd", $maKH, $ngayLap, $tongTien);

    if ($stmt->execute()) {
        return $conn->insert_id; // Trả về MaHD vừa tạo
    } else {
        return false;
    }
}

function ThemChiTietHoaDon($conn, $maHD, $maThuoc, $soLuongBan, $giaBan) {
    $query = "INSERT INTO ChiTietHoaDon (MaHD, MaThuoc, SoLuongBan, GiaBan) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssid", $maHD, $maThuoc, $soLuongBan, $giaBan);

    return $stmt->execute();
}

function CapNhatTongTienHoaDon($maHD, $tongTien) {
    global $conn;
    $query = "UPDATE HoaDon SET TongTien = ? WHERE MaHD = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $tongTien, $maHD);
    return $stmt->execute();
}


?>