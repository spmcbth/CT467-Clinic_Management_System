<?php
include __DIR__ . '/../config.php';

            /* === LẤY DANH SÁCH === */
if (!function_exists('LayDanhSach')) {
    function LayDanhSach($procedureName) {
        global $conn;
        while ($conn->more_results()) {
            $conn->next_result();
        }
        return $conn->query("CALL $procedureName()");  // Gọi Procedure có trong MySQL
    }
}

            /* === GỌI STORED PROCEDURE === */
function CallStoredProcedure($procedure, $params) {
    global $conn;
    
    // Kiểm tra và giải phóng bộ nhớ cho các truy vấn trước đó 
    while ($conn->more_results() && $conn->next_result()) {
        if ($res = $conn->store_result()) {
            $res->free();
        }
    }
    
    // Xây dựng chuỗi tham số để xử lý 
    $placeholders = implode(", ", array_map(fn($p) => "'" . $conn->real_escape_string($p) . "'", $params));
    
    $sql = "CALL $procedure($placeholders)"; // Gọi Stored Procedure tương ứng 

    if (!$conn->multi_query($sql)) {
        die("Lỗi MySQL: " . $conn->error);
    }
    
    $data = []; // Tạo mảng chứa kết quả trả về 
    do {
        if ($res = $conn->store_result()) {
            while ($row = $res->fetch_assoc()) {
                $data[] = $row;
            }
            $res->free();
        }
    } while ($conn->more_results() && $conn->next_result());    // Chuyển sang result kế tiếp nếu có 
    
    return $data ?: true;   // Trả về true hoặc dữ liệu của Stored Procedure
}


            /* === THÊM, SỬA, XÓA THUỐC === */
function ThemThuoc($MaThuoc, $MaLoai, $MaHangSX, $MaNCC, $TenThuoc, $CongDung, $DonGia, $SoLuong, $HanSuDung) {
    return CallStoredProcedure("ThemThuoc", func_get_args());
}

function SuaThuoc($MaThuoc, $MaLoai, $MaHangSX, $MaNCC, $TenThuoc, $CongDung, $DonGia, $SoLuong, $HanSuDung) {
    return CallStoredProcedure("SuaThuoc", func_get_args());
}

function XoaThuoc($MaThuoc) {
    return CallStoredProcedure("XoaThuoc", [$MaThuoc]);
}


            /* === THÊM, SỬA, XÓA LOẠI THUỐC === */
function ThemLoaiThuoc($MaLoai, $TenLoai, $DonViTinh) {
    return CallStoredProcedure("ThemLoaiThuoc", func_get_args());
}

function SuaLoaiThuoc($MaLoai, $TenLoai, $DonViTinh) {
    return CallStoredProcedure("SuaLoaiThuoc", func_get_args());
}

function XoaLoaiThuoc($MaLoai) {
    return CallStoredProcedure("XoaLoaiThuoc", [$MaLoai]);
}


            /* === THÊM, SỬA, XÓA KHÁCH HÀNG === */
function ThemKhachHang($MaKH, $TenKH, $SoDienThoai, $DiaChi) {
    return CallStoredProcedure("ThemKhachHang", func_get_args());
}

function SuaKhachHang($MaKH, $TenKH, $SoDienThoai, $DiaChi) {
    return CallStoredProcedure("SuaKhachHang", func_get_args());
}

function XoaKhachHang($MaKH) {
    return CallStoredProcedure("XoaKhachHang", [$MaKH]);
}


            /* === KIỂM TRA DỮ LIỆU === */
// Hàm kiểm tra dữ liệu trước khi sửa thuốc 
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

// Hàm kiểm tra dữ liệu trước khi sửa loại thuốc 
if (!function_exists('kiemTraDuLieuLoaiThuoc')) {
    function kiemTraDuLieuLoaiThuoc($TenLoai, $DonViTinh) {
        if (empty($TenLoai) || empty($DonViTinh)) {
            return "Thông tin thuốc không được để trống!";
        }
        return ""; // Trả về rỗng nếu dữ liệu hợp lệ
    }
}


            /* === QUẢN LÝ HÓA ĐƠN === */
// Thêm hóa đơn
if (!function_exists('ThemHoaDon')) {
    function ThemHoaDon($MaHD, $MaKH, $NgayLap, $TongTien) {
        global $conn;
        $query = "INSERT INTO HoaDon (MaHD, MaKH, NgayLap, TongTien) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssd", $MaHD, $MaKH, $NgayLap, $TongTien);
        return mysqli_stmt_execute($stmt);
    }
}

// Thêm chi tiết hóa đơn
if (!function_exists('ThemChiTietHoaDon')) {
    function ThemChiTietHoaDon($MaCTHD, $MaHD, $MaThuoc, $SoLuongBan, $GiaBan) {
        global $conn;
        $query = "INSERT INTO ChiTietHoaDon (MaCTHD, MaHD, MaThuoc, SoLuongBan, GiaBan) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssid", $MaCTHD, $MaHD, $MaThuoc, $SoLuongBan, $GiaBan);
        return mysqli_stmt_execute($stmt);
    }
}

// Cập nhật tổng tiền hóa đơn
if (!function_exists('CapNhatTongTienHoaDon')) {
    function CapNhatTongTienHoaDon($MaHD, $TongTien) {
        global $conn;
        $query = "UPDATE HoaDon SET TongTien = ? WHERE MaHD = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ds", $TongTien, $MaHD);
        return mysqli_stmt_execute($stmt);
    }
}

// Lấy số lượng thuốc theo mã 
if (!function_exists('LaySoLuongThuoc')) {
    function LaySoLuongThuoc($maLoai) {
        global $conn; 
    
        $query = "SELECT LaySoLuongThuoc(?) AS SoLuongTon";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $maLoai);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return $row['SoLuongTon'];
        }
        
        return 0; // Trả về 0 nếu không có dữ liệu
    }
}

            /* === XEM CHI TIẾT HÓA ĐƠN === */
// Lấy hóa đơn theo mã 
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

// Lấy chi tiết hóa đơn theo mã 
if (!function_exists('LayChiTietHoaDon')) {
    function LayChiTietHoaDon($conn, $maHD) {
        $query = "CALL LayChiTietHoaDon(?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $maHD);
        $stmt->execute();
    
        $result = $stmt->get_result();
        $chiTietHoaDon = [];
    
        while ($row = $result->fetch_assoc()) {
            $chiTietHoaDon[] = $row;
        }
    
        $stmt->close();
        return $chiTietHoaDon;
    }
}
?>