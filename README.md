# Hướng Dẫn Cài Đặt Dự Án

## 1. Cài Đặt Composer trên Windows
1. Truy cập [https://getcomposer.org/](https://getcomposer.org/) và tải Composer Setup.
2. Chạy file `.exe` và làm theo hướng dẫn để cài đặt.
3. Kiểm tra cài đặt bằng lệnh:

    ```bash
    composer --version
    ```

## 2. Clone mPDF bằng Git
1. Mở Command Prompt và chuyển đến thư mục dự án:
    ```bash
    cd [thư_mục_dự_án]
    ```
2. Clone mPDF vào thư mục `vendor`:
    ```bash
    git clone https://github.com/mpdf/mpdf.git vendor/mpdf
    ```

## 3. Cài Đặt Các Phụ Thuộc Dự Án
1. Cài đặt các thư viện PHP bằng Composer:
    ```bash
    composer install
    ```

## 4. Chạy Dự Án
1. Thay đổi cấu hình trong `php/config.php`
```php
$servername = "localhost";  
$username = "root"; // Tài khoản MySQL của bạn 
$password = ""; // Nhập mật khẩu nếu có
$database = "QLNhaThuoc";  
```
2. Chạy dự án:
****Lựa chọn 1 (PHP Built-in Server)**: Sử dụng PHP server tích hợp sẵn để chạy ứng dụng
    ```bash
    php -S localhost:8080
    ```
    Mở trình duyệt và truy cập [http://localhost:8080](http://localhost:8000) để xem kết quả.
**Lựa chọn 2 (XAMPP)**: Sử dụng phần mềm XAMPP để chạy Apache server
