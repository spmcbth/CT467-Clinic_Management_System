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

// Hàm thêm thuốc
if (!function_exists('ThemThuoc')) {
    function ThemThuoc($MaThuoc, $MaLoai, $MaHangSX, $MaNCC, $TenThuoc, $CongDung, $DonGia, $SoLuong, $HanSuDung) {
        global $conn;
        $stmt = $conn->prepare("CALL ThemThuoc(?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssdss", $MaThuoc, $MaLoai, $MaHangSX, $MaNCC, $TenThuoc, $CongDung, $DonGia, $SoLuong, $HanSuDung);
        return $stmt->execute();
    }
}


?>