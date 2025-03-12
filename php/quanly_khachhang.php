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
                        <h2>Quản Lý Khách Hàng</h2>
                    </div>
                    
                    <div class="card p-4 shadow">
                        <a href="them_KH.php" class="btn btn-primary mb-3">
                            <i class="fas fa-plus"></i> Thêm Khách Hàng
                        </a>
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>Mã Khách Hàng</th>
                                    <th>Họ và Tên</th>
                                    <th>Số Điện Thoại</th>
                                    <th>Địa Chỉ</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php
                                $result = GetDanhSachKhachHang();
                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>
                                                <td>" . htmlspecialchars($row['MaKH']) . "</td>
                                                <td>" . htmlspecialchars($row['TenKH']) . "</td>
                                                <td>" . htmlspecialchars($row['SoDienThoai']) . "</td>
                                                <td>" . htmlspecialchars($row['DiaChi']) . "</td>
                                                <td>
                                                    <a href='sua_KH.php?id=" . htmlspecialchars($row['MaKH']) . "' class='btn btn-warning btn-sm'>
                                                        <i class='fas fa-edit'></i> Sửa
                                                    </a>
                                                    <a href='xoa_KH.php?id=" . htmlspecialchars($row['MaKH']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Bạn có chắc muốn xóa khách hàng này không?\");'>
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