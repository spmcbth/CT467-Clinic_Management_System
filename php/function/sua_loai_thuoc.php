<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config.php';
require_once 'functions.php';

if (!isset($_GET['id'])) {
    $_SESSION['thongbao'] = "Mã loại thuốc không hợp lệ!";
    header("Location: ../quanly_loai_thuoc.php");
    exit();
}

$MaLoai = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM LoaiThuoc WHERE MaLoai = ?");
$stmt->bind_param("s", $MaLoai);
$stmt->execute();
$loaiThuoc = $stmt->get_result()->fetch_assoc();

if (!$loaiThuoc) {
    $_SESSION['thongbao'] = "Không tìm thấy loại thuốc!";
    header("Location: ../quanly_loai_thuoc.php");
    exit();
}

$thongBao = isset($_SESSION['thongbao']) ? $_SESSION['thongbao'] : "";
if (isset($_SESSION['thongbao'])) {
    unset($_SESSION['thongbao']);
}

// Xử lý cập nhật loại thuốc khi nhấn nút "Cập nhật"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $TenLoai = $_POST['ten_loai'];
    $DonViTinh = $_POST['don_vi_tinh'];

    $loi = kiemTraDuLieuLoaiThuoc($TenLoai, $DonViTinh);

    if (!empty($loi)) {
        $_SESSION['thongbao'] = $loi;
    } else {
        if (SuaLoaiThuoc($MaLoai, $TenLoai, $DonViTinh)) {
            $_SESSION['thongbao'] = "<div class='alert alert-success'>Cập nhật loại thuốc thành công!</div>";
        } else {
            $_SESSION['thongbao'] = "<div class='alert alert-danger'>Lỗi cập nhật loại thuốc!</div>";
        }
        header("Location: ../quanly_loai_thuoc.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa loại thuốc - Quản Lý Nhà Thuốc</title>
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
                        <h2>Sửa loại thuốc</h2>
                    </div>

                    <div class="card p-4 shadow">
                        <?php if (!empty($thongBao)): ?>
                            <div class="alert alert-success"><?php echo htmlspecialchars($thongBao); ?></div>
                        <?php endif; ?>

                        <form method="POST" class="edit-form">
                            <div class="form-group">
                                <label for="ten_loai">Tên loại thuốc:</label>
                                <input type="text" name="ten_loai" id="ten_loai" value="<?= htmlspecialchars($loaiThuoc['TenLoai']) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="don_vi_tinh">Đơn vị tính:</label>
                                <input type="text" name="don_vi_tinh" id="don_vi_tinh" value="<?= htmlspecialchars($loaiThuoc['DonViTinh']) ?>" required>
                            </div>

                            <div class="form-buttons">
                                <button type="submit" class="btn-primary">Cập nhật</button>
                                <a href="../quanly_loai_thuoc.php" class="btn-secondary">Quay lại</a>
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
