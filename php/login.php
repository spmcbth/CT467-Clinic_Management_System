<?php
session_start();
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM Users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            $_SESSION["username"] = $username;
            $_SESSION["HoTen"] = $row["HoTen"];
            header("Location: quanly_thongbao.php");
            exit();
        } else {
            $error = "Sai mật khẩu!";
        }
    } else {
        $error = "Tài khoản không tồn tại!";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="../assets/css/resigter_login.css">
</head>
<body>
    <div class="container">
        <span class="close-btn">X</span>
        <div class="icon">
            <img src="..\assets\image\ban-thuoc-online.webp" alt="Icon">
        </div>
        <h2>Đăng Nhập</h2>
        <?php if (isset($error)) echo "<p style='color: red; text-align: center;'>$error</p>"; ?>
        <form method="POST" action="">
            <div class="input-group">
                <label>Tài khoản</label>
                <input type="text" name="username" required>
            </div>
            <div class="input-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-login">Đăng nhập</button>
        </form>
        <button class="btn-register" onclick="window.location.href='register.php'">Đăng ký</button>
    </div>
</body>
</html>
