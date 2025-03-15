<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once  '../config.php';
require_once 'functions.php';

if (!isset($_GET['id'])) {
    $_SESSION['thongbao'] = "Mã thuốc không hợp lệ!";
    header("Location: ../quanly_thuoc.php");
    exit();
}

$MaThuoc = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM Thuoc WHERE MaThuoc = ?");
$stmt->bind_param("s", $MaThuoc);
$stmt->execute();
$thuoc = $stmt->get_result()->fetch_assoc();

if (!$thuoc) {
    $_SESSION['thongbao'] = "Không tìm thấy thuốc!";
    header("Location: /php/quanly_thuoc.php");
    exit();
}

// Lấy danh sách dropdown
$loaiThuoc = $conn->query("SELECT * FROM LoaiThuoc ORDER BY TenLoai");
$hangSX = $conn->query("SELECT * FROM HangSanXuat ORDER BY TenHang");
$nhaCungCap = $conn->query("SELECT * FROM NhaCungCap ORDER BY TenNCC");
$thongBao = isset($_SESSION['thongbao']) ? $_SESSION['thongbao'] : "";

if (isset($_SESSION['thongbao'])) {
    unset($_SESSION['thongbao']);
}

// Xử lý cập nhật thuốc khi nhấn nút "Cập nhật"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $TenThuoc = $_POST['ten_thuoc'];
    $MaLoai = $_POST['ma_loai'];
    $CongDung = $_POST['cong_dung'];
    $DonGia = $_POST['don_gia'];
    $SoLuong = $_POST['so_luong'];
    $HanSuDung = $_POST['han_su_dung'];
    $MaHangSX = $_POST['ma_hangsx'];
    $MaNCC = $_POST['ma_ncc'];  

    // Kiểm tra dữ liệu đầu vào
    $loi = kiemTraDuLieuThuoc($TenThuoc, $MaLoai, $CongDung, $DonGia, $SoLuong);

    if (!empty($loi)) {
        $_SESSION['thongbao'] = $loi;
    } else {
        // Gọi function để cập nhật thuốc
        if (SuaThuoc($MaThuoc, $MaLoai, $MaHangSX, $MaNCC, $TenThuoc, $CongDung, $DonGia, $SoLuong, $HanSuDung)) {
            $_SESSION['thongbao'] = "<div class='alert alert-success'>Cập nhật thuốc thành công!</div>";
        } else {
            $_SESSION['thongbao'] = "<div class='alert alert-danger'>Lỗi cập nhật thuốc: " . $stmt->error . "</div>";
        }

        $stmt->close();
        header("Location: ../quanly_thuoc.php");
        exit();           
    }    
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa thông tin thuốc - Quản Lý Nhà Thuốc</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/image/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/sua_thuoc.css">
</head>
<body>
<div class="wrapper">
    <div class="content-wrapper">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="main-content">
            <div class="content">
                <div class="container">
                    <div class="page-header"> 
                        <h2>Sửa thông tin thuốc</h2>
                    </div>

                    <div class="card p-4 shadow">
                        <?php if (!empty($thongBao)): ?> 
                        <div class="alert alert-success"><?php echo htmlspecialchars($thongBao); ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" class="edit-form">
                            <div class="form-group">
                                <label for="ten_thuoc">Tên thuốc:</label>
                                <input type="text" name="ten_thuoc" id="ten_thuoc" value="<?= htmlspecialchars($thuoc['TenThuoc']) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="ma_loai">Loại thuốc:</label>
                                <select name="ma_loai" id="ma_loai" required>
                                    <option value="">-- Chọn loại thuốc --</option>
                                    <?php while ($row = $loaiThuoc->fetch_assoc()): ?>
                                        <option value="<?= htmlspecialchars($row['MaLoai']) ?>" <?= ($row['MaLoai'] == $thuoc['MaLoai']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($row['TenLoai']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="cong_dung">Công dụng:</label>
                                <textarea name="cong_dung" id="cong_dung" required><?= htmlspecialchars($thuoc['CongDung']) ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="don_gia">Đơn giá:</label>
                                <input type="number" step="0.01" name="don_gia" id="don_gia" value="<?= htmlspecialchars($thuoc['DonGia']) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="so_luong">Số lượng:</label>
                                <input type="number" name="so_luong" id="so_luong" value="<?= htmlspecialchars($thuoc['SoLuongTonKho']) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="han_su_dung">Hạn sử dụng:</label>
                                <input type="date" name="han_su_dung" id="han_su_dung" value="<?= htmlspecialchars($thuoc['HanSuDung']) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="ma_hangsx">Hãng sản xuất:</label>
                                <select name="ma_hangsx" id="ma_hangsx" required>
                                    <option value="">-- Chọn hãng sản xuất --</option>
                                    <?php while ($row = $hangSX->fetch_assoc()): ?>
                                        <option value="<?= htmlspecialchars($row['MaHangSX']) ?>" <?= ($row['MaHangSX'] == $thuoc['MaHangSX']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($row['TenHang']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="ma_ncc">Nhà cung cấp:</label>
                                <select name="ma_ncc" id="ma_ncc" required>
                                    <option value="">-- Chọn nhà cung cấp --</option>
                                    <?php while ($row = $nhaCungCap->fetch_assoc()): ?>
                                        <option value="<?= htmlspecialchars($row['MaNCC']) ?>" <?= ($row['MaNCC'] == $thuoc['MaNCC']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($row['TenNCC']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="form-buttons">
                                <button type="submit" class="btn-primary">Cập nhật</button>
                                <a href="../quanly_thuoc.php" class="btn-secondary">Quay lại</a>
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

    