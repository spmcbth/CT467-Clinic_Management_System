if ($stmt->execute()) {
    $_SESSION['thongbao'] = "<div class='alert alert-success'>Xóa thuốc thành công!</div>";
} else {
    $_SESSION['thongbao'] = "<div class='alert alert-danger'>Lỗi xóa thuốc: " . $stmt->error . "</div>";
}
header("Location: quanly_thuoc.php");
exit();
