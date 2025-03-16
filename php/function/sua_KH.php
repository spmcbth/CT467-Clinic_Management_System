<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config.php';
require_once 'functions.php';

if (!isset($_GET['id'])) {
    $_SESSION['thongbao'] = "Mã khách hàng không hợp lệ!";
    header("Location: ../quanly_khachhang.php");
    exit();
}

$MaKH = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM KhachHang WHERE MaKH = ?");
$stmt->bind_param("s", $MaKH);
$stmt->execute();
$khachhang = $stmt->get_result()->fetch_assoc();

if (!$khachhang) {
    $_SESSION['thongbao'] = "Không tìm thấy khách hàng!";
    header("Location: ../quanly_khachhang.php");
    exit();
}

$thongBao = isset($_SESSION['thongbao']) ? $_SESSION['thongbao'] : "";
if (isset($_SESSION['thongbao'])) {
    unset($_SESSION['thongbao']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $TenKH = $_POST['ten_kh'];
    $SoDienThoai = $_POST['so_dien_thoai'];
    $DiaChi = $_POST['dia_chi'];

    if (empty($TenKH) || empty($SoDienThoai) || empty($DiaChi)) {
        $_SESSION['thongbao'] = "Vui lòng điền đầy đủ thông tin!";
    } else {
        if (SuaKhachHang($MaKH, $TenKH, $SoDienThoai, $DiaChi)) {
            $_SESSION['thongbao'] = "<div class='alert alert-success'>Cập nhật khách hàng thành công!</div>";
        } else {
            $_SESSION['thongbao'] = "<div class='alert alert-danger'>Lỗi cập nhật khách hàng.</div>";
        }
        header("Location: ../quanly_khachhang.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa thông tin khách hàng - Quản Lý Nhà Thuốc</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/image/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/form_sua.css">
</head>
<body>
<div class="wrapper">
    <div class="content-wrapper">
        <?php include '../includes/sidebar.php'; ?>

        <div class="main-content">
            <div class="content">
                <div class="container">
                    <div class="page-header">
                        <h2>Sửa thông tin khách hàng</h2>
                    </div>

                    <div class="card p-4 shadow">
                        <?php if (!empty($thongBao)): ?>
                            <div class="alert alert-success"><?php echo htmlspecialchars($thongBao); ?></div>
                        <?php endif; ?>

                        <form method="POST" class="edit-form">
                            <div class="form-group">
                                <label for="ten_kh">Tên khách hàng:</label>
                                <input type="text" name="ten_kh" id="ten_kh" value="<?= htmlspecialchars($khachhang['TenKH']) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="so_dien_thoai">Số điện thoại:</label>
                                <input type="text" name="so_dien_thoai" id="so_dien_thoai" value="<?= htmlspecialchars($khachhang['SoDienThoai']) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="dia_chi">Địa chỉ:</label>
                                <textarea name="dia_chi" id="dia_chi" required><?= htmlspecialchars($khachhang['DiaChi']) ?></textarea>
                            </div>

                            <div class="form-buttons">
                                <button type="submit" class="btn-primary">Cập nhật</button>
                                <a href="../quanly_khachhang.php" class="btn-secondary">Quay lại</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</div>
</body>
</html>
