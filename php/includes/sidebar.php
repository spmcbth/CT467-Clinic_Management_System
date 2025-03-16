<?php
// sidebar.php - File sidebar được include vào trang chính
?>
<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        <img src="../../assets/image/icon.png" alt="Logo">
        <h3>Quản Lý Nhà Thuốc</h3>
    </div>
    
    <div class="sidebar-menu">
        <ul>
            <li>
                <a href="/php/quanly_thuoc.php">
                    <i class="fas fa-pills"></i>
                    <span>Quản lý Thuốc</span>
                </a>
            </li>
            <li>
                <a href="/php/quanly_loai_thuoc.php">
                    <i class="fas fa-list"></i>
                    <span>Quản lý Loại thuốc</span>
                </a>
            </li>
            <li>
                <a href="/php/quanly_khachhang.php">
                    <i class="fas fa-users"></i>
                    <span>Quản lý Khách hàng</span>
                </a>
            </li>
            <li>
                <a href="/php/quanly_hoadon.php">
                    <i class="fas fa-file-invoice"></i>
                    <span>Quản lý Hóa đơn</span>
                </a>
            </li>
            <li>
                <a href="/php/quanly_thongbao.php">
                    <i class="fas fa-bell"></i>
                    <span>Thông báo</span>
                </a>
            </li>
            <li>
                <a href="/php/logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Đăng xuất</span>
                </a>
            </li>
        </ul>
    </div>
</div>

<script>
    // Menu active hiện tại
    const currentLocation = location.href;
    const menuItems = document.querySelectorAll('.sidebar-menu a');
    menuItems.forEach(item => {
        if(item.href === currentLocation) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
    });
</script>