USE QLNhaThuoc;

            /* === TRIGGER === */
-- Tự động thông báo thuốc hết hạn mỗi ngày vào 6h sáng 
DELIMITER $$
DROP EVENT IF EXISTS AutoCheckThuocHetHan $$
CREATE EVENT AutoCheckThuocHetHan
ON SCHEDULE EVERY 1 DAY
STARTS TIMESTAMP(CURDATE(), '06:00:00') -- dùng CURRENT_TIMESTAMP để check ngay lập tức 
DO
BEGIN
    INSERT INTO ThongBao (MaThuoc, NoiDung, NgayThongBao)
    SELECT 
        t.MaThuoc, 
        CONCAT('Thuốc "', t.TenThuoc, '" sắp hết hạn vào ngày ', DATE_FORMAT(t.HanSuDung, '%d-%m-%Y')), 
        NOW()
    FROM Thuoc t
    WHERE DATEDIFF(t.HanSuDung, CURDATE()) <= 30
    AND NOT EXISTS (
        SELECT 1 FROM ThongBao tb 
        WHERE tb.MaThuoc = t.MaThuoc 
        AND tb.NoiDung LIKE '%sắp hết hạn%'
    );
END $$

-- Thông báo nếu thuốc thêm vào sắp hết hạn 
DELIMITER $$
CREATE TRIGGER CheckThuocHetHan_Insert
AFTER INSERT ON Thuoc
FOR EACH ROW
BEGIN
    IF DATEDIFF(NEW.HanSuDung, CURDATE()) <= 30 THEN
        INSERT INTO ThongBao (MaThuoc, NoiDung, NgayThongBao)
        VALUES (NEW.MaThuoc, 
                CONCAT('Thuốc "', NEW.TenThuoc, '" sắp hết hạn vào ngày ', DATE_FORMAT(NEW.HanSuDung, '%d-%m-%Y')), 
                NOW());
    END IF;
END $$

-- Giảm số lượng thuốc khi thêm vào hóa đơn 
DELIMITER $$
DROP TRIGGER IF EXISTS GiamSoLuongThuoc $$
CREATE TRIGGER GiamSoLuongThuoc
AFTER INSERT ON ChiTietHoaDon
FOR EACH ROW
BEGIN
    UPDATE Thuoc 
    SET SoLuongTonKho = SoLuongTonKho - NEW.SoLuongBan
    WHERE MaThuoc = NEW.MaThuoc;
END $$

            /* === CÁC HÀM LẤY DANH SÁCH === */
-- Danh sách thuốc
DELIMITER $$
DROP PROCEDURE IF EXISTS LayDanhSachThuoc; $$
CREATE PROCEDURE LayDanhSachThuoc()
BEGIN
	SELECT t.MaThuoc, t.TenThuoc, lt.TenLoai, t.CongDung, t.DonGia, t.SoLuongTonKho, lt.DonViTinh, t.HanSuDung
	FROM Thuoc t
	JOIN LoaiThuoc lt ON t.MaLoai = lt.MaLoai
	JOIN NhaCungCap ncc ON t.MaNCC = ncc.MaNCC
	JOIN HangSanXuat hsx ON t.MaHangSX = hsx.MaHangSX
    ORDER BY t.MaThuoc;
END $$

-- Danh sách Khách Hàng 
DELIMITER $$
CREATE PROCEDURE LayDanhSachKhachHang()
BEGIN
	SELECT * FROM KhachHang;
END $$

-- Danh sách Hóa Đơn 
DELIMITER $$
CREATE PROCEDURE LayDanhSachHoaDon()
BEGIN
	SELECT * FROM HoaDon;
END $$

-- Danh sách chi tiết hóa đơn
DELIMITER $$
DROP PROCEDURE IF EXISTS LayChiTietHoaDon $$
CREATE PROCEDURE LayChiTietHoaDon(IN p_MaHD VARCHAR(10))
BEGIN
	SELECT Thuoc.TenThuoc, ChiTietHoaDon.SoLuongBan, ChiTietHoaDon.GiaBan
	FROM ChiTietHoaDon
	JOIN Thuoc ON ChiTietHoaDon.MaThuoc = Thuoc.MaThuoc
	WHERE ChiTietHoaDon.MaHD = p_MaHD;
END $$

-- Danh sách thông báo thuốc sắp hết hạn
DELIMITER $$
CREATE PROCEDURE LayDanhSachThuocHetHan()
BEGIN
	SELECT * FROM ThongBao;
END $$

-- Danh sách loại thuốc
DELIMITER $$
DROP PROCEDURE IF EXISTS LayDanhSachLoaiThuoc $$
CREATE PROCEDURE LayDanhSachLoaiThuoc()
BEGIN
	SELECT * FROM LoaiThuoc;
END $$

-- Danh sách hãng sản xuất 
DELIMITER $$
CREATE PROCEDURE LayDanhSachHangSX()
BEGIN
	SELECT * FROM HangSanXuat;
END $$

-- Danh sách nhà cung cấp 
DELIMITER $$
CREATE PROCEDURE LayDanhSachNhaCungCap()
BEGIN
	SELECT * FROM NhaCungCap;
END $$

-- Lấy hóa đơn theo mã 
DELIMITER $$
DROP PROCEDURE IF EXISTS LayHoaDon $$
CREATE PROCEDURE LayHoaDon(IN p_MaHD VARCHAR(10))
BEGIN
	SELECT HoaDon.MaHD, KhachHang.TenKH, HoaDon.NgayLap, HoaDon.TongTien
	FROM HoaDon
	JOIN KhachHang ON HoaDon.MaKH = KhachHang.MaKH
	WHERE HoaDon.MaHD = p_MaHD;
END $$

            /* === QUẢN LÝ THUỐC === */
-- Thêm thuốc
DELIMITER $$
CREATE PROCEDURE ThemThuoc(
    IN p_MaThuoc VARCHAR(10),
    IN p_MaLoai VARCHAR(10),
    IN p_MaHangSX VARCHAR(10),
    IN p_MaNCC VARCHAR(10),
    IN p_TenThuoc VARCHAR(255),
    IN p_CongDung TEXT,
    IN p_DonGia DECIMAL(10,2),
    IN p_SoLuongTonKho INT,
    IN p_HanSuDung DATE
)
BEGIN
    INSERT INTO Thuoc (MaThuoc, MaLoai, MaHangSX, MaNCC, TenThuoc, CongDung, DonGia, SoLuongTonKho, HanSuDung)
    VALUES (p_MaThuoc, p_MaLoai, p_MaHangSX, p_MaNCC, p_TenThuoc, p_CongDung, p_DonGia, p_SoLuongTonKho, p_HanSuDung);
END $$

-- Sửa thuốc
DELIMITER $$
DROP PROCEDURE IF EXISTS SuaThuoc $$
CREATE PROCEDURE SuaThuoc(
    IN p_MaThuoc VARCHAR(10),
    IN p_MaLoai VARCHAR(10),
    IN p_MaHangSX VARCHAR(10),
    IN p_MaNCC VARCHAR(10),
    IN p_TenThuoc VARCHAR(255),
    IN p_CongDung TEXT,
    IN p_DonGia DECIMAL(10,2),
    IN p_SoLuongTonKho INT,
    IN p_HanSuDung DATE
)
BEGIN
    UPDATE Thuoc
    SET MaLoai = p_MaLoai,
        MaHangSX = p_MaHangSX,
        MaNCC = p_MaNCC,
        TenThuoc = p_TenThuoc,
        CongDung = p_CongDung,
        DonGia = p_DonGia,
        SoLuongTonKho = p_SoLuongTonKho,
        HanSuDung = p_HanSuDung
    WHERE MaThuoc = p_MaThuoc;
END $$

-- Xóa thuốc
DELIMITER $$
DROP PROCEDURE IF EXISTS XoaThuoc $$
CREATE PROCEDURE XoaThuoc(IN p_MaThuoc VARCHAR(10))
BEGIN
    DELETE FROM ChiTietHoaDon WHERE MaThuoc = p_MaThuoc;
    DELETE FROM Thuoc WHERE MaThuoc = p_MaThuoc;
END $$ 

            /* === QUẢN LÝ LOẠI THUỐC === */
-- Thêm loại thuốc
DELIMITER $$
CREATE PROCEDURE ThemLoaiThuoc (
    IN p_MaLoai VARCHAR(10),
    IN p_TenLoai VARCHAR(100),
    IN p_DonViTinh VARCHAR(20)
)
BEGIN
    INSERT INTO LoaiThuoc (MaLoai, TenLoai, DonViTinh)
    VALUES (p_MaLoai, p_TenLoai, p_DonViTinh);
END $$

-- Sửa loại thuốc
DELIMITER $$
CREATE PROCEDURE SuaLoaiThuoc(
    IN p_MaLoai VARCHAR(10),
    IN p_TenLoai VARCHAR(255),
    IN p_DonViTinh VARCHAR(50)
)
BEGIN
    UPDATE LoaiThuoc 
    SET TenLoai = p_TenLoai, 
        DonViTinh = p_DonViTinh
    WHERE MaLoai = p_MaLoai;
END $$

-- Xóa loại thuốc
DELIMITER $$
DROP PROCEDURE IF EXISTS XoaLoaiThuoc $$
CREATE PROCEDURE XoaLoaiThuoc(IN p_MaLoai VARCHAR(10))
BEGIN
    DELETE FROM LoaiThuoc WHERE MaLoai = p_MaLoai;
END $$ 

            /* === QUẢN LÝ KHÁCH HÀNG === */
-- Thêm khách hàng
DELIMITER $$
CREATE PROCEDURE ThemKhachHang (
    IN p_MaKH VARCHAR(10),
    IN p_TenKH VARCHAR(100),
    IN p_SoDienThoai VARCHAR(15),
    IN p_DiaChi VARCHAR(200)
)
BEGIN
    INSERT INTO KhachHang (MaKH, TenKH, SoDienThoai, DiaChi)
    VALUES (p_MaKH, p_TenKH, p_SoDienThoai, p_DiaChi);
END $$

-- Sửa khách hàng
DELIMITER $$
CREATE PROCEDURE SuaKhachHang (
    IN p_MaKH VARCHAR(10),
    IN p_TenKH VARCHAR(100),
    IN p_SoDienThoai VARCHAR(15),
    IN p_DiaChi VARCHAR(200)
)
BEGIN
    UPDATE KhachHang 
    SET TenKH = p_TenKH, SoDienThoai = p_SoDienThoai, DiaChi = p_DiaChi
    WHERE MaKH = p_MaKH;
END $$

-- Xóa khách hàng
DELIMITER $$
CREATE PROCEDURE XoaKhachHang (IN p_MaKH VARCHAR(10))
BEGIN
    DELETE FROM KhachHang WHERE MaKH = p_MaKH;
END $$
