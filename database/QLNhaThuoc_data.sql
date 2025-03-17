USE QLNhaThuoc;

INSERT INTO HangSanXuat (MaHangSX, TenHang, QuocGia) VALUES
('HSX001', 'Dược Hậu Giang', 'Việt Nam'),
('HSX002', 'Traphaco', 'Việt Nam'),
('HSX003', 'Sanofi', 'Pháp'),
('HSX004', 'Imexpharm', 'Việt Nam'),
('HSX005', 'GSK (GlaxoSmithKline)', 'Anh');

INSERT INTO LoaiThuoc (MaLoai, TenLoai, DonViTinh) VALUES 
('LT001', 'Thuốc giảm đau', 'Viên'),
('LT002', 'Thuốc kháng sinh', 'Viên'),
('LT003', 'Thuốc đau dạ dày', 'Gói'),
('LT004', 'Vitamin tổng hợp', 'Viên'),
('LT005', 'Thuốc ho', 'Chai'),
('LT006', 'Thuốc tim mạch', 'Viên'),
('LT007', 'Thuốc hạ sốt', 'Viên'),
('LT008', 'Thuốc nhỏ mắt', 'Ống');

INSERT INTO NhaCungCap (MaNCC, TenNCC, SoDienThoai) VALUES
('NCC001', 'Dược phẩm Trung Sơn', '0901234567'),
('NCC002', 'Công ty Dược An Khang', '0912345678'),
('NCC003', 'Dược phẩm Nam Hà', '0987654321'),
('NCC004', 'Nhà thuốc Phano', '0935123456'),
('NCC005', 'Công ty CP Dược Việt Nam', '0945678901');

INSERT INTO Thuoc (MaThuoc, MaLoai, MaHangSX, MaNCC, TenThuoc, CongDung, DonGia, SoLuongTonKho, HanSuDung) VALUES
('T001', 'LT001', 'HSX001', 'NCC001', 'Paracetamol', 'Giảm đau, hạ sốt', 5000, 200, '2025-03-31'),
('T002', 'LT001', 'HSX002', 'NCC002', 'Ibuprofen', 'Giảm đau, chống viêm', 8000, 150, '2026-10-15'),
('T003', 'LT002', 'HSX003', 'NCC003', 'Amoxicillin', 'Kháng sinh điều trị nhiễm khuẩn', 12000, 100, '2026-07-30'),
('T004', 'LT002', 'HSX004', 'NCC002', 'Erythromycin', 'Kháng sinh nhóm macrolid', 15000, 180, '2026-05-01'),
('T005', 'LT004', 'HSX003', 'NCC003', 'Vitamin C', 'Bổ sung vitamin C', 10000, 250, '2026-08-20'),
('T006', 'LT004', 'HSX005', 'NCC004', 'Calcium-D', 'Bổ sung canxi, tốt cho xương', 18000, 120, '2025-03-10'),
('T007', 'LT003', 'HSX002', 'NCC002', 'Omeprazole', 'Điều trị viêm loét dạ dày', 22000, 80, '2026-11-22'),
('T008', 'LT003', 'HSX005', 'NCC005', 'Ranitidine', 'Giảm tiết axit dạ dày', 16000, 90, '2026-09-05'),
('T009', 'LT005', 'HSX001', 'NCC001', 'Bromhexine', 'Tiêu đờm, giảm ho', 9000, 130, '2026-10-15'),
('T010', 'LT005', 'HSX002', 'NCC002', 'Dextromethorphan', 'Giảm ho do kích ứng', 7500, 150, '2025-11-20');

INSERT INTO KhachHang (MaKH, TenKH, SoDienThoai, DiaChi) VALUES
('KH001', 'Mạch Gia Hân', '0905123456', 'Cần Thơ'),
('KH002', 'Mai Trần Ngọc Trân', '0916234567', 'Cần Thơ'),
('KH003', 'Lê Văn A', '0927345678', 'Hà Nội'),
('KH004', 'Nguyễn Thị B', '0927345678', 'Đà Nẵng'),
('KH005', 'Phạm Thị C', '0938456789', 'Hồ Chí Minh');

INSERT INTO HoaDon (MaHD, MaKH, NgayLap, TongTien) VALUES
('HD001', 'KH001', '2025-03-01', 50000),
('HD002', 'KH002', '2025-03-02', 70000),
('HD003', 'KH003', '2025-03-03', 60000),
('HD004', 'KH004', '2025-03-04', 80000),
('HD005', 'KH005', '2025-03-05', 75000);


INSERT INTO ChiTietHoaDon (MaCTHD, MaHD, MaThuoc, SoLuongBan, GiaBan) VALUES
('CTHD001', 'HD001', 'T001', 2, 5000),
('CTHD002', 'HD001', 'T002', 1, 7000),
('CTHD003', 'HD002', 'T003', 3, 6000),
('CTHD004', 'HD002', 'T004', 2, 8000),
('CTHD005', 'HD003', 'T005', 1, 7500),
('CTHD006', 'HD003', 'T006', 2, 12000),
('CTHD007', 'HD004', 'T007', 2, 15000),
('CTHD008', 'HD004', 'T008', 1, 13000),
('CTHD009', 'HD005', 'T009', 3, 25000),
('CTHD010', 'HD005', 'T010', 1, 28000);
