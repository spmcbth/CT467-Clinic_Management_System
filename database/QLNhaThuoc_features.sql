USE QLNhaThuoc;

# TRIGGER: THÔNG BÁO THUỐC HẾT HẠN 
-- Tự động thông báo thuốc sắp hết hạn mỗi ngày 
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
SHOW EVENTS; -- Kiểm tra event có đc gọi hay chưa 

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

#		===  LẤY DANH SÁCH THUỐC  === 
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
-- CALL LayDanhSachThuoc()

#    === CÁC HÀM LẤY DANH SÁCH  ===
-- danh sách Khách Hàng 
DELIMITER $$
CREATE PROCEDURE LayDanhSachKhachHang()
BEGIN
	SELECT * FROM KhachHang;
END $$
-- CALL LayDanhSachKhachHang()

-- danh sách Hóa Đơn 
DELIMITER $$
CREATE PROCEDURE LayDanhSachHoaDon()
BEGIN
	SELECT * FROM HoaDon;
END $$
-- CALL LayDanhSachHoaDon()

-- danh sách thông báo thuốc sắp hết hạn
DELIMITER $$
CREATE PROCEDURE LayDanhSachThuocHetHan()
BEGIN
	SELECT * FROM ThongBao;
END $$
-- CALL LayDanhSachThuocHetHan()

-- danh sách loại thuốc
DELIMITER $$
CREATE PROCEDURE LayDanhSachLoaiThuoc()
BEGIN
	SELECT * FROM LoaiThuoc;
END $$
-- CALL LayDanhSachLoaiThuoc()

-- danh sách hãng sản xuất 
DELIMITER $$
CREATE PROCEDURE LayDanhSachHangSX()
BEGIN
	SELECT * FROM HangSanXuat;
END $$
-- CALL LayDanhSachHangSanXuat()

-- danh sách nhà cung cấp 
DELIMITER $$
CREATE PROCEDURE LayDanhSachNhaCungCap()
BEGIN
	SELECT * FROM NhaCungCap;
END $$
-- CALL LayDanhSachNhaCungCap()

-- chi tiết hóa đơn
DELIMITER $$
DROP PROCEDURE IF EXISTS LayChiTietHoaDon $$
CREATE PROCEDURE LayChiTietHoaDon(IN p_MaHD VARCHAR(10))
BEGIN
	SELECT Thuoc.TenThuoc, ChiTietHoaDon.SoLuongBan, ChiTietHoaDon.GiaBan
	FROM ChiTietHoaDon
	JOIN Thuoc ON ChiTietHoaDon.MaThuoc = Thuoc.MaThuoc
	WHERE ChiTietHoaDon.MaHD = p_MaHD;
END $$
-- CALL LayChiTietHoaDon('HD001')

--  lấy hóa đơn theo mã 
DELIMITER $$
DROP PROCEDURE IF EXISTS LayHoaDon $$
CREATE PROCEDURE LayHoaDon(IN p_MaHD VARCHAR(10))
BEGIN
	SELECT HoaDon.MaHD, KhachHang.TenKH, HoaDon.NgayLap, HoaDon.TongTien
	FROM HoaDon
	JOIN KhachHang ON HoaDon.MaKH = KhachHang.MaKH
	WHERE HoaDon.MaHD = p_MaHD;
END $$
-- CALL LayHoaDon('HD001')

#		=== THÊM THUỐC ===
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

#		=== HÓA ĐƠN  ===
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

#		=== SỬA THUỐC  ===
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
    SET MaThuoc = p_MaThuoc,
        MaLoai = p_MaLoai,
        MaHangSX = p_MaHangSX,
        MaNCC = p_MaNCC,
        TenThuoc = p_TenThuoc,
        CongDung = p_CongDung,
        DonGia = p_DonGia,
        SoLuongTonKho = p_SoLuongTonKho,
        HanSuDung = P_HanSuDung
    WHERE MaThuoc = p_MaThuoc;
END $$

#		===  XÓA THUỐC  ===
DELIMITER $$
DROP PROCEDURE IF EXISTS XoaThuoc $$
CREATE PROCEDURE XoaThuoc(IN p_MaThuoc VARCHAR(10))
BEGIN
    -- Xóa dữ liệu liên quan trong bảng ChiTietHoaDon trước (nếu có)
    DELETE FROM ChiTietHoaDon WHERE MaThuoc = p_MaThuoc;
    -- Sau đó xóa thuốc trong bảng Thuoc
    DELETE FROM Thuoc WHERE MaThuoc = p_MaThuoc;
END $$ 



