<?php
$servername = "localhost";  
$username = "root";         
$password = "123456";             
$database = "QLNhaThuoc";  
$port = 3306; 

// Kết nối đến MySQL
$conn = new mysqli($servername, $username, $password, $database, $port);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>
