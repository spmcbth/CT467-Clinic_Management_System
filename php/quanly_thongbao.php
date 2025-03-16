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
    <title>Thông Báo Thuốc Hết HSD - Quản Lý Nhà Thuốc</title>
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
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>STT</th>
                                    <th>Mã Thuốc</th>
                                    <th>Nội Dung</th>
                                    <th>Ngày Thông Báo</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php
                                $result = LayDanhSach('LayDanhSachThuocHetHan');
                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>
                                                <td class='ma'>" . htmlspecialchars($row['MaThongBao']) . "</td>
                                                <td class='ma'>" . htmlspecialchars($row['MaThuoc']) . "</td>
                                                <td class='noi-dung'>" . htmlspecialchars($row['NoiDung']) . "</td>
                                                <td class='ngay'>" . htmlspecialchars($row['NgayThongBao']) . "</td>                                                
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