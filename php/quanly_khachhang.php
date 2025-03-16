<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';
require_once 'function/functions.php';
require_once 'function/them_KH.php';  

// Lấy danh sách khách hàng
$result = LayDanhSach('LayDanhSachKhachHang');

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
    <title>Quản Lý Khách Hàng - Quản Lý Nhà Thuốc</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/image/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/table.css">
</head>
<body>

<div class="wrapper">
    <div class="content-wrapper">
        <?php include 'includes/sidebar.php'; ?>
        
        <div class="main-content">
            <div class="content">
                <div class="container">
                    <div class="page-header">
                        <h2>Quản Lý Khách Hàng</h2>
                    </div>
                    <div class="card p-4 shadow">
                        
                        <!-- Button thêm khách hàng -->
                        <div class="btn-container">
                            <button class="btn btn-primary mb-3" onclick="toggleForm()">
                                <i class="fas fa-plus"></i> Thêm Khách Hàng
                            </button>
                        </div>

                        <!-- Thông báo -->
                        <?php if (!empty($thongBao)) echo $thongBao; ?>

                        <!-- Form Thêm Khách Hàng -->
                        <div id="formThemKhachHang" class="form-container" style="display: none;">
                            <form method="POST" action="function/them_KH.php" class="form-khachhang">
                                <div class="form-group">
                                    <label>Họ và Tên:</label>
                                    <input type="text" name="ten_kh" placeholder="Nhập họ và tên khách hàng" required>
                                </div>

                                <div class="form-group">
                                    <label>Số Điện Thoại:</label>
                                    <input type="tel" name="so_dien_thoai" pattern="0[0-9]{9}" placeholder="Nhập số điện thoại (10 chữ số)" required>
                                </div>

                                <div class="form-group full-width">
                                    <label>Địa Chỉ:</label>
                                    <textarea name="dia_chi" placeholder="Nhập địa chỉ khách hàng" required></textarea>
                                </div>

                                <input type="submit" name="btn_them" value="Lưu Khách Hàng" class="btn-submit">
                            </form>
                        </div>

                        <!-- Bảng Danh Sách Khách Hàng -->
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>Mã Khách Hàng</th>
                                    <th>Họ và Tên</th>
                                    <th>Số Điện Thoại</th>
                                    <th>Địa Chỉ</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php if ($result && $result->num_rows > 0): ?>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['MaKH']) ?></td>
                                            <td><?= htmlspecialchars($row['TenKH']) ?></td>
                                            <td><?= htmlspecialchars($row['SoDienThoai']) ?></td>
                                            <td><?= htmlspecialchars($row['DiaChi']) ?></td>
                                            <td>
                                                <a href="function/sua_KH.php?id=<?= htmlspecialchars($row['MaKH']) ?>" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i> Sửa
                                                </a>
                                                <a href="function/xoa_KH.php?id=<?= htmlspecialchars($row['MaKH']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa khách hàng này không?');">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="text-center">Không có dữ liệu</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    function toggleForm() {
        var form = document.getElementById("formThemKhachHang");
        form.style.display = form.style.display === "none" ? "block" : "none";
    }
</script>
</body>
</html>