<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php"); 
    exit();
}

require_once 'config.php';
require_once 'function/functions.php';
require_once 'function/them_thuoc.php';  

// Lấy danh sách cho drop dơwn 
$loaiThuoc = mysqli_query($conn, "SELECT * FROM LoaiThuoc ORDER BY TenLoai");
$hangSX = mysqli_query($conn, "SELECT * FROM HangSanXuat ORDER BY TenHang");
$nhaCungCap = mysqli_query($conn, "SELECT * FROM NhaCungCap ORDER BY TenNCC");

$thongBao = "";
if (isset($_SESSION['thongbao'])) {
    $thongBao = $_SESSION['thongbao'];
    unset($_SESSION['thongbao']);
}

?>

<!DOCTYPE html> 
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Thông báo - Quản Lý Nhà Thuốc</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/image/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/table.css">
    <link rel="stylesheet" href="/assets/css/them_thuoc.css">
</head>
<body>
<div class="wrapper">
    <div class="content-wrapper">
        <?php include 'includes/sidebar.php'; ?>
        
        <div class="main-content">
            <div class="content">
                <div class="container">
                    <div class="page-header"> 
                        <h2>Quản Lý Thuốc</h2>
                    </div>
                    <div class="card p-4 shadow">
                        <!-- Button thêm thuốc -->
                        <div class="btn-container"> 
                            <button class="btn btn-primary mb-3" onclick="toggleForm()">
                                <i class="fas fa-plus"></i> Thêm Thuốc
                            </button>
                        </div> 

                        <!-- Thông báo -->
                        <?php if (!empty($thongBao)) echo $thongBao; ?>
                        
                        <!-- Thêm thuốc -->
                        <div id="formThemThuoc" class="form-container" style="display: none;">
                            <form method="POST" action="./function/them_thuoc.php" class="form-thuoc">
                                <div class="form-group">
                                    <label>Loại Thuốc:</label>
                                    <select name="ma_loai" required>
                                        <option value="">-- Chọn loại thuốc --</option>
                                        <?php while ($row = mysqli_fetch_assoc($loaiThuoc)) : ?>
                                            <option value="<?= htmlspecialchars($row['MaLoai']) ?>"><?= htmlspecialchars($row['TenLoai']) ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Hãng Sản Xuất:</label>
                                    <select name="ma_hangsx" required>
                                        <option value="">-- Chọn hãng sản xuất --</option>
                                        <?php while ($row = mysqli_fetch_assoc($hangSX)) : ?>
                                            <option value="<?= htmlspecialchars($row['MaHangSX']) ?>"><?= htmlspecialchars($row['TenHang']) ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Nhà Cung Cấp:</label>
                                    <select name="ma_ncc" required>
                                        <option value="">-- Chọn nhà cung cấp --</option>
                                        <?php while ($row = mysqli_fetch_assoc($nhaCungCap)) : ?>
                                            <option value="<?= htmlspecialchars($row['MaNCC']) ?>"><?= htmlspecialchars($row['TenNCC']) ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Tên Thuốc:</label>
                                    <input type="text" name="ten_thuoc" placeholder="Nhập tên thuốc" required>
                                </div>

                                <div class="form-group">
                                    <label>Đơn Giá (VNĐ):</label>
                                    <input type="number" name="don_gia" min="0" step="1000" placeholder="Nhập giá tiền" required>
                                </div>

                                <div class="form-group">
                                    <label>Số Lượng:</label>
                                    <input type="number" name="so_luong" min="1" placeholder="Nhập số lượng" required>
                                </div>

                                <div class="form-group">
                                    <label>Hạn Sử Dụng:</label>
                                    <input type="date" name="han_su_dung" required>
                                </div>

                                <div class="form-group full-width">
                                    <label>Công Dụng:</label>
                                    <textarea name="cong_dung" placeholder="Mô tả công dụng của thuốc" required></textarea>
                                </div>

                                <input type="submit" name="btn_them" value="Lưu Thuốc" class="btn-submit">
                            </form>
                        </div>

                        <!-- Bảng thuốc  -->
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>Mã Thuốc</th>
                                    <th>Tên Thuốc</th>
                                    <th>Loại Thuốc</th>
                                    <th>Công dụng</th>
                                    <th>Đơn Giá</th>
                                    <th>Số Lượng</th>
                                    <th>Đơn Vị Tính</th>
                                    <th>Hạn Sử Dụng</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php
                                $result = LayDanhSachThuoc();
                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>
                                                <td class='ma'>" . htmlspecialchars($row['MaThuoc']) . "</td>
                                                <td class='ten-thuoc'>" . htmlspecialchars($row['TenThuoc']) . "</td>
                                                <td class='ten-loai'>" . htmlspecialchars($row['TenLoai']) . "</td>
                                                <td class='cong-dung'>" . htmlspecialchars($row['CongDung']) . "</td>
                                                <td class='don-gia'>" . number_format($row['DonGia'], 0, ',', '.') . " đ</td>
                                                <td class='so-luong'>" . htmlspecialchars($row['SoLuongTonKho']) . "</td>
                                                <td class='don-vi-tinh'>" . htmlspecialchars($row['DonViTinh']) . "</td>
                                                <td class='ngay'>" . htmlspecialchars($row['HanSuDung']) . "</td>                                                
                                                <td class='button-center'>
                                                    <a href='function/sua_thuoc.php?id=" . htmlspecialchars($row['MaThuoc']) . "' class='btn btn-warning btn-sm'>
                                                        <i class='fas fa-edit'></i> Sửa
                                                    </a>
                                                    <a href='function/xoa_thuoc.php?id=" . htmlspecialchars($row['MaThuoc']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Bạn có chắc muốn xóa thuốc này không?\");'>
                                                        <i class='fas fa-trash'></i> Xóa
                                                    </a>
                                                </td>
                                            </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='9' class='text-center'>Không có dữ liệu</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</div>



<script>
    function toggleForm() {
        const form = document.getElementById('formThemThuoc');
        if (form.style.display === 'none') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }
</script>
</body>
</html>