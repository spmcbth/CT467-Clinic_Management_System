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
        VALUES (NEW.MaThuoc, CONCAT('Cảnh báo: Thuốc "', NEW.TenThuoc, '" sắp hết hạn sử dụng trong ', ngayConLai, ' ngày!'));
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
