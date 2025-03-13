<?php
include './config.php';

function ThemThuoc($conn) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_them'])) {
        // Tạo mã thuốc tự động
        $query = "SELECT MAX(CAST(SUBSTRING(MaThuoc, 3) AS UNSIGNED)) as max_id FROM Thuoc WHERE MaThuoc LIKE 'T%'"; // Tìm giá trị lớn nhất
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result); // Lấy đc max_id
        $next_id = ($row['max_id']) ? $row['max_id'] + 1 : 1;
        $MaThuoc = 'T' . str_pad($next_id, 3, '0', STR_PAD_LEFT);
        
        $MaLoai = $_POST['ma_loai'];         
        $MaHangSX = $_POST['ma_hangsx'];     
        $MaNCC = $_POST['ma_ncc'];           
        $TenThuoc = $_POST['ten_thuoc'];     
        $CongDung = $_POST['cong_dung'];     
        $DonGia = $_POST['don_gia'];         
        $SoLuong = $_POST['so_luong'];       
        $HanSuDung = $_POST['han_su_dung'];
        
        $sql = "CALL ThemThuoc(?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);  // Dùng Prepare Statement để tránh lỗi thêm SQL Injection
        $stmt->bind_param("ssssssdss", $MaThuoc, $MaLoai, $MaHangSX, $MaNCC, $TenThuoc, $CongDung, $DonGia, $SoLuong, $HanSuDung);  // Gán giá trị cho các tham số 

        if ($stmt->execute()) {
            $stmt->close();  // Tránh rò rỉ bộ nhớ 
            return "Thêm thuốc thành công!";
        } else {
            $stmt->close();
            return "Lỗi: không thể thêm thuốc! " . mysqli_error($conn);
        }
    }
    return null;
}

?>
