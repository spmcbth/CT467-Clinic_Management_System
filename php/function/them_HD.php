<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config.php';
require_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_them'])) {
    // Tạo mã hóa đơn tự động
    $query = "SELECT MAX(CAST(SUBSTRING(MaHD, 3) AS UNSIGNED)) AS max_id FROM HoaDon WHERE MaHD LIKE 'HD%'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $next_id = ($row['max_id']) ? $row['max_id'] + 1 : 1;
    $MaHD = 'HD' . str_pad($next_id, 3, '0', STR_PAD_LEFT);

    // Lấy dữ liệu từ form
    $MaKH = isset($_POST['MaKH']) ? $_POST['MaKH'] : '';
    $NgayLap = date("Y-m-d"); // Lấy ngày hiện tại
    $TongTien = 0; // Ban đầu = 0, cập nhật sau khi thêm chi tiết hóa đơn

    // Debug - Kiểm tra dữ liệu đầu vào
    error_log("MaHD: $MaHD, MaKH: $MaKH, NgayLap: $NgayLap");

    // Kiểm tra MaKH
    if (empty($MaKH)) {
        $_SESSION['thongbao'] = "<div class='alert alert-danger'>Vui lòng chọn khách hàng!</div>";
        header("Location: ../quanly_hoadon.php");
        exit();
    }

    // Bắt đầu transaction
    if (function_exists('mysqli_begin_transaction')) {
        mysqli_begin_transaction($conn);
    } else {
        mysqli_query($conn, "START TRANSACTION");
    }

    try {
        // Thêm hóa đơn vào bảng `HoaDon`
        if (!ThemHoaDon($MaHD, $MaKH, $NgayLap, $TongTien)) {
            throw new Exception("Lỗi khi thêm hóa đơn: " . mysqli_error($conn));
        }

        // Kiểm tra danh sách thuốc có tồn tại không
        if (!empty($_POST['thuoc'])) {
            foreach ($_POST['thuoc'] as $index => $thuoc) {
                if (!isset($thuoc['MaThuoc'], $thuoc['SoLuong'], $thuoc['GiaBan'])) {
                    throw new Exception("Dữ liệu thuốc không hợp lệ tại hàng " . ($index + 1) . "!");
                }

                $MaThuoc = $thuoc['MaThuoc'];
                $SoLuong = intval($thuoc['SoLuong']);
                $GiaBan = floatval(str_replace(',', '.', $thuoc['GiaBan']));
                $ThanhTien = $SoLuong * $GiaBan;
                $TongTien += $ThanhTien;

                // Debug - Kiểm tra dữ liệu thuốc
                error_log("Thuốc $index: MaThuoc=$MaThuoc, SoLuong=$SoLuong, GiaBan=$GiaBan, ThanhTien=$ThanhTien");

                // Tạo mã chi tiết hóa đơn tự động
                $MaCTHD = 'CT' . $MaHD . str_pad($index + 1, 2, '0', STR_PAD_LEFT);

                // Thêm chi tiết hóa đơn
                if (!ThemChiTietHoaDon($MaCTHD, $MaHD, $MaThuoc, $SoLuong, $GiaBan)) {
                    throw new Exception("Lỗi khi thêm chi tiết hóa đơn: " . mysqli_error($conn));
                }

                // Cập nhật số lượng tồn kho
                if (!CapNhatSoLuongThuoc($MaThuoc, $SoLuong)) {
                    throw new Exception("Không đủ số lượng thuốc trong kho hoặc lỗi khi cập nhật tồn kho!");
                }
            }
        } else {
            throw new Exception("Vui lòng thêm ít nhất một loại thuốc vào hóa đơn!");
        }

        // Cập nhật tổng tiền vào bảng `HoaDon`
        if (!CapNhatTongTienHoaDon($MaHD, $TongTien)) {
            throw new Exception("Lỗi khi cập nhật tổng tiền hóa đơn: " . mysqli_error($conn));
        }

        // Nếu mọi thứ thành công, commit transaction
        mysqli_commit($conn);

        $_SESSION['thongbao'] = "<div class='alert alert-success'>Thêm hóa đơn thành công!</div>";
        } catch (Exception $e) {
            mysqli_rollback($conn); // Rollback nếu có lỗi
            $_SESSION['thongbao'] = "<div class='alert alert-danger'>{$e->getMessage()}</div>";
            error_log("Lỗi thêm hóa đơn: " . $e->getMessage());
        }

    header("Location: ../quanly_hoadon.php");
    exit();
}

// Lấy danh sách khách hàng và thuốc
$dsKhachHang = LayDanhSach('LayDanhSachKhachHang');
$dsThuoc = LayDanhSach('LayDanhSachThuoc');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm hóa đơn - Quản Lý Nhà Thuốc</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/image/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/them_HD.css">
</head>
<body>
<div class="wrapper">
    <div class="content-wrapper">
        <?php include '../includes/sidebar.php'; ?>
        <div class="main-content">
            <div class="content">
                <div class="container">
                    <h2>Thêm Hóa Đơn</h2>
                    <?php if (isset($_SESSION['thongbao'])) {
                        echo $_SESSION['thongbao'];
                        unset($_SESSION['thongbao']);
                    } ?>
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label for="MaKH">Khách Hàng</label>
                            <select name="MaKH" id="MaKH" required>
                                <option value="">-- Chọn Khách Hàng --</option>
                                <?php foreach ($dsKhachHang as $kh) : ?>
                                    <option value="<?= $kh['MaKH'] ?>"><?= $kh['TenKH'] ?> (<?= $kh['SoDienThoai'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="NgayLap">Ngày Lập</label>
                            <input type="text" id="NgayLap" value="<?= date("d/m/Y") ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Danh Sách Thuốc</label>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Thuốc</th>
                                        <th>Số Lượng</th>
                                        <th>Giá Bán</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="ds-thuoc">
                                    <tr>
                                        <td>
                                            <select name="thuoc[0][MaThuoc]" class="select-thuoc" required required onchange="capNhatGia(this)">
                                                <option value="">-- Chọn Thuốc --</option>
                                                <?php foreach ($dsThuoc as $thuoc) : ?>
                                                    <option value="<?= $thuoc['MaThuoc'] ?>" data-giaban="<?= $thuoc['DonGia'] ?>">
                                                        <?= $thuoc['TenThuoc'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td><input type="number" name="thuoc[0][SoLuong]" min="1" required></td>
                                        <td><input type="text" name="thuoc[0][GiaBan]" class="gia-ban" readonly></td>
                                        <td><button type="button" onclick="xoaThuoc(this)" class="btn btn-danger">Xóa</button></td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" onclick="themThuoc()" class="btn btn-primary">+ Thêm Thuốc</button>
                        </div>
                        <div class="form-group">
                            <button type="submit" name="btn_them" value="1" class="btn btn-primary">Lưu Hóa Đơn</button>
                            <a href="../quanly_hoadon.php" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Áp dụng cho select-thuoc ban đầu
    document.querySelectorAll(".select-thuoc").forEach(select => {
        select.addEventListener("change", function() {
            capNhatGia(this);
        });
    });
});

function capNhatGia(select) {
    let selectedOption = select.options[select.selectedIndex];
    let giaBanInput = select.closest("tr").querySelector(".gia-ban");
    if (selectedOption.getAttribute("data-giaban")) {
        giaBanInput.value = selectedOption.getAttribute("data-giaban");
    } else {
        giaBanInput.value = "";
    }
}

function themThuoc() {
    let index = document.querySelectorAll("#ds-thuoc tr").length;
    let row = `<tr>
        <td>
            <select name="thuoc[${index}][MaThuoc]" class="select-thuoc" required onchange="capNhatGia(this)">
                <option value="">-- Chọn Thuốc --</option>
                <?php foreach ($dsThuoc as $thuoc) : ?>
                    <option value="<?= $thuoc['MaThuoc'] ?>" data-giaban="<?= $thuoc['DonGia'] ?>">
                        <?= $thuoc['TenThuoc'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </td>
        <td><input type="number" name="thuoc[${index}][SoLuong]" min="1" required></td>
        <td><input type="text" name="thuoc[${index}][GiaBan]" class="gia-ban" readonly></td>
        <td><button type="button" onclick="xoaThuoc(this)">Xóa</button></td>
    </tr>`;
    document.querySelector("#ds-thuoc").insertAdjacentHTML("beforeend", row);
}

function xoaThuoc(btn) {
    btn.closest("tr").remove();
}
</script>
</body>
</html>