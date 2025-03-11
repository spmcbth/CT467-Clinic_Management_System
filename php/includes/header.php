<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Nhà Thuốc</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/image/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background-color: #28a745;
            color: white;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 100;
        }
        
        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .sidebar-header h3 {
            margin-bottom: 10px;
            font-size: 1.5rem;
        }
        
        .sidebar-header img {
            width: 60px;
            height: auto;
            margin-bottom: 10px;
        }
        
        .sidebar-menu {
            padding: 10px 0;
        }
        
        .sidebar-menu ul {
            list-style: none;
        }
        
        .sidebar-menu li {
            position: relative;
            margin: 5px 0;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid white;
        }
        
        .sidebar-menu a.active {
            background-color: rgba(255, 255, 255, 0.2);
            border-left: 4px solid white;
        }
        
        .sidebar-menu i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            background-color: #f8f9fa;
        }
        
        .content {
            flex: 1;
            padding: 30px;
        }
        
        .container {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .page-header {
            margin-bottom: 20px;
        }
        
        .page-header h2 {
            color: #28a745;
            font-size: 24px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../../assets/image/icon.png" alt="Logo">
            <h3>Quản Lý Nhà Thuốc</h3>
        </div>
        
        <div class="sidebar-menu">
            <ul>
                <li>
                    <a href="quanly_thuoc.php" class="active">
                        <i class="fas fa-pills"></i>
                        <span>Quản lý Thuốc</span>
                    </a>
                </li>
                <li>
                    <a href="quanly_loai.php">
                        <i class="fas fa-list"></i>
                        <span>Quản lý Loại thuốc</span>
                    </a>
                </li>
                <li>
                    <a href="quanly_khachhang.php">
                        <i class="fas fa-users"></i>
                        <span>Quản lý Khách hàng</span>
                    </a>
                </li>
                <li>
                    <a href="quanly_hoadon.php">
                        <i class="fas fa-file-invoice"></i>
                        <span>Quản lý Hóa đơn</span>
                    </a>
                </li>
                <li>
                    <a href="quanly_thongbao.php">
                        <i class="fas fa-bell"></i>
                        <span>Thông báo Thuốc</span>
                    </a>
                </li>
                <li>
                    <a href="logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Đăng xuất</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Nội dung chính -->
    <div class="main-content">
        <div class="content">
            <div class="page-header">
                <h2>Hệ thống Quản lý Nhà thuốc</h2>
            </div>
            <div class="container">
                <h3>Nội dung trang</h3>
                <p>Đây là nơi hiển thị nội dung của từng trang cụ thể.</p>
            </div>
        </div>
    </div>
</div>

<script>
    // active check
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

</body>
</html>
