<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';
require_once 'function/functions.php';
require_once 'function/them_loai_thuoc.php';

// Lấy danh sách loại thuốc
$loaiThuoc = LayDanhSach('LayDanhSachLoaiThuoc');

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
    <title>Quản Lý Loại Thuốc - Quản Lý Nhà Thuốc</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/image/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/table.css">
</head>
<body>
<div class="wrapper">
    <div class="content-wrapper">
        <?php include 'includes/sidebar.php'; ?>

        <div class="main-content">
            <div class="content">
                <div class="container">
                    <div class="page-header">
                        <h2>Quản Lý Loại Thuốc</h2>
                    </div>
                    <div class="card p-4 shadow">
                        
                        <!-- Button thêm loại thuốc -->
                        <div class="btn-container">
                            <button class="btn btn-primary mb-3" onclick="toggleForm()">
                                <i class="fas fa-plus"></i> Thêm Loại Thuốc
                            </button>
                        </div>

                        <!-- Thông báo -->
                        <?php if (!empty($thongBao)) echo $thongBao; ?>

                        <!-- Form thêm loại thuốc -->
                        <div id="formThemLoaiThuoc" class="form-container" style="display: none;">
                            <form method="POST" action="./function/them_loai_thuoc.php" class="form-thuoc">
                                <div class="form-group">
                                    <label>Tên Loại Thuốc:</label>
                                    <input type="text" name="ten_loai" placeholder="Nhập tên loại thuốc" required>
                                </div>
                                <div class="form-group">
                                    <label>Đơn Vị Tính:</label>
                                    <input type="text" name="don_vi_tinh" placeholder="Nhập đơn vị tính" required>
                                </div>
                                <input type="submit" name="btn_them" value="Lưu Loại Thuốc" class="btn-submit">
                            </form>
                        </div>

                        <!-- Bảng loại thuốc -->
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>Mã Loại</th>
                                    <th>Tên Loại</th>
                                    <th>Đơn Vị Tính</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php
                                if ($loaiThuoc && $loaiThuoc->num_rows > 0) {
                                    while ($row = $loaiThuoc->fetch_assoc()) {
                                        echo "<tr>
                                                <td class='ma'>" . htmlspecialchars($row['MaLoai']) . "</td>
                                                <td class='ten-loai'>" . htmlspecialchars($row['TenLoai']) . "</td>
                                                <td class='don-vi-tinh'>" . htmlspecialchars($row['DonViTinh']) . "</td>
                                                <td class='button-center'>
                                                    <a href='function/sua_loai_thuoc.php?id=" . htmlspecialchars($row['MaLoai']) . "' class='btn btn-warning btn-sm'>
                                                        <i class='fas fa-edit'></i> Sửa
                                                    </a>
                                                    <a href='function/xoa_loai_thuoc.php?id=" . htmlspecialchars($row['MaLoai']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Bạn có chắc muốn xóa loại thuốc này không?\");'>
                                                        <i class='fas fa-trash'></i> Xóa
                                                    </a>
                                                </td>
                                            </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-center'>Không có dữ liệu</td></tr>";
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
        const form = document.getElementById('formThemLoaiThuoc');
        form.style.display = (form.style.display === 'none') ? 'block' : 'none';
    }
</script>
</body>
</html>
