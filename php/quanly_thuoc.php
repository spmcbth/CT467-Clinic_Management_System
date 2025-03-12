<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
include 'config.php';
require_once 'function/functions.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Thuốc - Quản Lý Nhà Thuốc</title>
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
                        <h2>Quản Lý Thuốc</h2>
                    </div>
                    
                    <div class="card p-4 shadow">
                        <a href="them_thuoc.php" class="btn btn-primary mb-3">
                            <i class="fas fa-plus"></i> Thêm Thuốc
                        </a>
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>Mã Thuốc</th>
                                    <th>Tên Thuốc</th>
                                    <th>Loại Thuốc</th>
                                    <th>Công dụng</th>
                                    <th>Đơn Giá</th>
                                    <th>Số Lượng</th>
                                    <th>Đơn Vị Tính</th>
                                    <th>Tổng Tiền</th>
                                    <th>Hạn Sử Dụng</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php
                                $result = GetDanhSachThuoc();
                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>
                                                <td>" . htmlspecialchars($row['MaThuoc']) . "</td>
                                                <td>" . htmlspecialchars($row['CongDung']) . "</td>
                                                <td>" . htmlspecialchars($row['TenThuoc']) . "</td>
                                                <td>" . htmlspecialchars($row['TenLoai']) . "</td>
                                                <td>" . number_format($row['DonGia'], 0, ',', '.') . " đ</td>
                                                <td>" . htmlspecialchars($row['SoLuongTonKho']) . "</td>
                                                <td>" . htmlspecialchars($row['DonViTinh']) . "</td>
                                                <td>" . number_format($row['TongTien'], 0, ',', '.') . " đ</td>
                                                <td>" . htmlspecialchars($row['HanSuDung']) . "</td>                                                
                                                <td>
                                                    <a href='sua_thuoc.php?id=" . htmlspecialchars($row['ma_thuoc']) . "' class='btn btn-warning btn-sm'>
                                                        <i class='fas fa-edit'></i> Sửa
                                                    </a>
                                                    <a href='xoa_thuoc.php?id=" . htmlspecialchars($row['ma_thuoc']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Bạn có chắc muốn xóa thuốc này không?\");'>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

</body>
</html>