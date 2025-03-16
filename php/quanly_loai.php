<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
require_once 'config.php';
require_once 'function/functions.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Loại Thuốc - Quản Lý Nhà Thuốc</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/image/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/table.css">
    <link rel="stylesheet" href="../assets/css/them_thuoc.css">
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

                        <!-- Form thêm thuốc -->
                        <div id="form-them-loai-thuoc" class="hidden">
                            <form action="function/them_loai_thuoc.php" method="POST">
                                <div class="mb-3">
                                    <label for="maLoai" class="form-label">Mã Loại</label>
                                    <input type="text" class="form-control" id="maLoai" name="maLoai" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tenLoai" class="form-label">Tên Loại</label>
                                    <input type="text" class="form-control" id="tenLoai" name="tenLoai" required>
                                </div>
                                <div class="mb-3">
                                    <label for="donViTinh" class="form-label">Đơn Vị Tính</label>
                                    <input type="text" class="form-control" id="donViTinh" name="donViTinh" required>
                                </div>
                                <button type="submit" class="btn btn-success">Thêm Loại Thuốc</button>
                                <button type="button" class="btn btn-danger" onclick="toggleForm()">Hủy</button>
                            </form>
                        </div>
                        <!-- Bảng loại thuốc  -->
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>Mã Loại</th>
                                    <th>Tên Loại</th>
                                    <th>Đơn Vị Tính</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php
                                $result = LayDanhSachLoaiThuoc();
                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>
                                                <td class='ma'>" . htmlspecialchars($row['MaLoai']) . "</td>
                                                <td class='ten-loai'>" . htmlspecialchars($row['TenLoai']) . "</td>
                                                <td class='don-vi-tinh'>" . htmlspecialchars($row['DonViTinh']) . "</td>                                               
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
<script>
    function toggleForm() {
        var form = document.getElementById("form-them-loai-thuoc");
        form.classList.toggle("hidden");
    }
</script>
</body>
</html>