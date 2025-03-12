USE QLNhaThuoc;

-- FUNCTION: trả về số lượng thuốc còn lại trong kho của một loại thuốc 
DELIMITER $$
CREATE FUNCTION GetSoLuongThuoc(ma_thuoc VARCHAR(10))
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE soLuong INT;
    
    SELECT SoLuongTonKho INTO soLuong
    FROM Thuoc
    WHERE MaThuoc = ma_thuoc;
    
    RETURN IFNULL(soLuong, 0); -- Trả về 0 nếu không tìm thấy 
END $$
-- SELECT GetSoLuongThuoc('T001') AS SoLuongTon;

-- TRIGGER: Tự động thông báo khi thuốc sắp hết hạn ( trước 30 ngày )
DELIMITER $$
CREATE TRIGGER CheckHanSuDungThuoc
AFTER UPDATE ON Thuoc
FOR EACH ROW
BEGIN
    DECLARE ngayHienTai DATE;
    DECLARE ngayConLai INT;

    SET ngayHienTai = CURDATE();
    SET ngayConLai = DATEDIFF(NEW.HanSuDung, ngayHienTai);

    -- Nếu thuốc sắp hết hạn trong vòng 30 ngày
    IF ngayConLai <= 30 AND ngayConLai > 0 THEN
        INSERT INTO ThongBao (MaThuoc, NoiDung)
        VALUES (NEW.MaThuoc, CONCAT('Thuốc "', NEW.TenThuoc, '" sắp hết hạn sử dụng trong ', ngayConLai, ' ngày!'));
    END IF;
END $$

-- STORED PROCEDUCE: Lấy danh sách thuốc theo loại
DELIMITER $$
CREATE PROCEDURE GetThuocTheoLoai(IN ten_loai VARCHAR(100))
BEGIN
    SELECT t.MaThuoc, t.TenThuoc, t.CongDung, t.DonGia, t.SoLuongTonKho, t.HanSuDung, h.TenHang AS NhaSanXuat, n.TenNCC AS NhaCungCap
    FROM Thuoc t
    JOIN LoaiThuoc l ON t.MaLoai = l.MaLoai
    JOIN HangSanXuat h ON t.MaHangSX = h.MaHangSX
    JOIN NhaCungCap n ON t.MaNCC = n.MaNCC
    WHERE l.TenLoai LIKE CONCAT('%', ten_loai, '%');
END $$
-- CALL GetThuocTheoLoai('kháng sinh');

-- Lấy danh sách Thuốc 
DELIMITER $$
DROP PROCEDURE IF EXISTS GetDanhSachThuoc; $$
CREATE PROCEDURE GetDanhSachThuoc()
BEGIN
	SELECT t.MaThuoc, t.TenThuoc, lt.TenLoai, t.CongDung, t.DonGia, t.SoLuongTonKho, lt.DonViTinh, (t.SoLuongTonKho * t.DonGia) AS TongTien, t.HanSuDung
	FROM Thuoc t
	JOIN LoaiThuoc lt ON t.MaLoai = lt.MaLoai
	JOIN NhaCungCap ncc ON t.MaNCC = ncc.MaNCC
	JOIN HangSanXuat hsx ON t.MaHangSX = hsx.MaHangSX;
END $$
-- CALL GetDanhSachThuoc()

-- Lấy danh sách Khách Hàng 
DELIMITER $$
CREATE PROCEDURE GetDanhSachKhachHang()
BEGIN
	SELECT * FROM KhachHang;
END $$
-- CALL GetDanhSachKhachHang()

-- Lấy danh sách Hóa Đơn 
DELIMITER $$
CREATE PROCEDURE GetDanhSachHoaDon()
BEGIN
	SELECT * FROM HoaDon;
END $$
-- CALL GetDanhSachHoaDon()

-- Lấy danh sách thông báo thuốc sắp hết hạn
DELIMITER $$
CREATE PROCEDURE GetDanhSachThuocHetHan()
BEGIN
	SELECT * FROM ThongBao;
END $$
-- CALL GetDanhSachThuocHetHan()

-- Lấy danh sách loại thuốc
DELIMITER $$
CREATE PROCEDURE GetDanhSachLoaiThuoc()
BEGIN
	SELECT * FROM LoaiThuoc;
END $$
-- CALL GetDanhSachLoaiThuoc()