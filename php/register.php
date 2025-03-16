<?php
include "config.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["HoTen"]) || empty($_POST["DiaChi"]) || empty($_POST["SoDienThoai"])) {
        $error = "Vui lòng nhập đầy đủ thông tin.";
        return;
    }

    $username = $_POST["username"]; 
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $HoTen = $_POST["HoTen"];
    $DiaChi = $_POST["DiaChi"];
    $SoDienThoai = $_POST["SoDienThoai"];

    $check_sql = "SELECT * FROM Users WHERE username = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $error = "Tài khoản đã tồn tại!";
    } else {
        $sql = "INSERT INTO Users (username, password, HoTen, DiaChi, SoDienThoai) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $username, $password, $HoTen, $DiaChi, $SoDienThoai);

        if ($stmt->execute()) {
            $success = "Đăng ký thành công!";
            echo "<script>
                    alert('$success');
                    window.location.href = 'login.php';
                  </script>";
        } else {
            $error = "Lỗi đăng ký: " . $stmt->error;
            echo "<script>
                    alert('$error');
                  </script>";
        }
        $stmt->close();        
    }

    $stmt_check->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/image/icon.png">
    <link rel="stylesheet" href="../assets/css/resigter_login.css">
</head>
<body>
    <div class="container">
        <h2>Đăng Ký</h2>
        <?php
        if (isset($error)) echo "<p class='error'>$error</p>";
        if (isset($success)) echo "<p class='success'>$success</p>";
        ?>
        <form method="POST" action="">
            <div class="input-group">
                <label for="username">Tài khoản</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="input-group">
                <label for="HoTen">Họ tên</label>
                <input type="text" id="HoTen" name="HoTen" required>
            </div>
            <div class="input-group">
                <label for="DiaChi">Địa chỉ</label>
                <input type="text" id="DiaChi" name="DiaChi" required>
            </div>
            <div class="input-group">
                <label for="SoDienThoai">Số điện thoại</label>
                <input type="text" id="SoDienThoai" name="SoDienThoai" required>
            </div>
            <button type="submit" class="btn-submit">Đăng ký</button>
        </form>
        <div class="link">
            Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a>
        </div>
    </div>
</body>
</html>

