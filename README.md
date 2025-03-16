# Hướng Dẫn Cài Đặt Dự Án

## 1. Tải Mã Nguồn

### 1.1. Tải Mã Nguồn bằng Git
- Truy cập trang repository GitHub của dự án.
- Nhấn vào nút **Code** màu xanh.
- Chọn **HTTPS** hoặc **SSH** và sao chép đường dẫn URL của repository.
- Mở Terminal hoặc Command Prompt và chạy lệnh sau để clone mã nguồn về máy của bạn:
    ```bash
    git clone [URL của repository]
    ```
- Di chuyển vào thư mục dự án:
    ```bash
    cd [tên_thư_mục_dự_án]
    ```

### 1.2. Tải Mã Nguồn bằng ZIP
- Nhấn vào nút **Code** màu xanh ở phía trên bên phải của danh sách file trong repository.
- Chọn **Download ZIP** từ menu dropdown.
- Tải về và giải nén file ZIP vào thư mục lưu trữ dự án.

---

## 2. Cài đặt Cơ Sở Dữ Liệu (MySQL)
1. Truy cập **phpMyAdmin** tại [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
2. Nhấn **New**, nhập tên CSDL: **QLNhaThuoc**, nhấn **Create**.
3. Import file SQL:
   - Chọn database **QLNhaThuoc**.
   - Nhấn tab **Import**, chọn file:
     ```bash
     QLNhaThuoc_db.sql
     QLNhaThuoc_data.sql
     QLNhaThuoc_features.sql
     ```
   - Nhấn **Go** để import CSDL.

---

## 3. Cài Đặt Composer trên Windows (Nếu chưa có)
1. Truy cập [https://getcomposer.org/](https://getcomposer.org/) và tải **Composer Setup**.
2. Chạy file `.exe` và làm theo hướng dẫn để cài đặt.
3. Kiểm tra cài đặt bằng lệnh:
    ```bash
    composer --version
    ```

---

## 4. Cài Đặt Các Phụ Thuộc Của Dự Án
1. Mở **Command Prompt** hoặc **Terminal** và di chuyển vào thư mục dự án:
    ```bash
    cd [tên_thư_mục_dự_án]
    ```
2. Cài đặt tất cả thư viện PHP bằng Composer:
    ```bash
    composer install
    ```
3. Cài đặt thêm thư viện **PhpSpreadsheet** và **TCPDF** nếu chưa có:
    ```bash
    composer require phpoffice/phpspreadsheet
    composer require tecnickcom/tcpdf
    ```

---

## 5. Chạy Dự Án

### 5.1. Cấu Hình Kết Nối MySQL
Mở file `php/config.php` và thay đổi thông tin kết nối MySQL của bạn:

```php
$servername = "localhost";  
$username = "root"; // Tài khoản MySQL của bạn 
$password = ""; // Nhập mật khẩu nếu có
$database = "QLNhaThuoc";
