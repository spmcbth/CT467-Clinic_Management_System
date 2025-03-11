<?php
$servername = "localhost";  
$username = "root"; // Tài khoản MySQL của bạn 
$password = "123456"; // Thay đổi mậtmật       
$database = "QLNhaThuoc";  

// Kết nối đến MySQL
$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>
