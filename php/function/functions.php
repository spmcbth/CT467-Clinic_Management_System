<?php
include '../config.php';

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

function GetDanhSachThuoc(){
    global $conn;
    return $conn->query("CALL GetDanhSachThuoc()");
}

function GetDanhSachLoaiThuoc(){
    global $conn;
    return $conn->query("CALL GetDanhSachLoaiThuoc()");
}

function GetDanhSachKhachhang(){
    global $conn;
    return $conn->query("CALL GetDanhSachKhachHang()");
}

function GetDanhSachHoaDon(){
    global $conn;
    return $conn->query("CALL GetDanhSachHoaDon()");
}

function GetDanhSachThuocHetHan(){
    global $conn;
    return $conn->query("CALL GetDanhSachThuocHetHan()");
}

?>