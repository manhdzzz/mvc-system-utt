-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th1 06, 2026 lúc 06:52 AM
-- Phiên bản máy phục vụ: 10.4.27-MariaDB
-- Phiên bản PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `thitracnghiem`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bai_lam`
--

CREATE TABLE `bai_lam` (
  `id` int(11) NOT NULL,
  `phong_id` int(11) NOT NULL,
  `hocvien_id` int(11) NOT NULL,
  `de_id` int(11) NOT NULL,
  `start_at` datetime NOT NULL,
  `end_at` datetime DEFAULT NULL,
  `score` float DEFAULT 0,
  `correct_cnt` int(11) DEFAULT 0,
  `total_cnt` int(11) DEFAULT 0,
  `status` varchar(30) DEFAULT 'Doing'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bai_lam`
--

INSERT INTO `bai_lam` (`id`, `phong_id`, `hocvien_id`, `de_id`, `start_at`, `end_at`, `score`, `correct_cnt`, `total_cnt`, `status`) VALUES
(12, 3, 5, 5, '2026-01-05 20:57:12', '2026-01-05 20:57:34', 0, 0, 12, 'Done'),
(13, 4, 4, 6, '2026-01-06 12:29:09', NULL, 0, 0, 3, 'Doing');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bai_lam_ct`
--

CREATE TABLE `bai_lam_ct` (
  `id` int(11) NOT NULL,
  `bailam_id` int(11) NOT NULL,
  `cauhoi_id` int(11) NOT NULL,
  `chon` char(1) DEFAULT NULL,
  `dung` tinyint(4) DEFAULT 0,
  `diem` float DEFAULT 0,
  `phan_van` tinyint(4) DEFAULT 0 COMMENT 'Danh dau phan van'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bai_lam_ct`
--

INSERT INTO `bai_lam_ct` (`id`, `bailam_id`, `cauhoi_id`, `chon`, `dung`, `diem`, `phan_van`) VALUES
(23, 12, 37, NULL, 0, 0, 0),
(24, 12, 38, NULL, 0, 0, 0),
(25, 12, 60, NULL, 0, 0, 0),
(26, 12, 45, NULL, 0, 0, 0),
(27, 12, 47, NULL, 0, 0, 0),
(28, 12, 56, NULL, 0, 0, 0),
(29, 12, 66, NULL, 0, 0, 0),
(30, 12, 63, NULL, 0, 0, 0),
(31, 12, 51, NULL, 0, 0, 0),
(32, 12, 65, NULL, 0, 0, 0),
(33, 12, 46, NULL, 0, 0, 0),
(34, 12, 39, NULL, 0, 0, 0),
(35, 13, 102, 'B', 0, 0, 0),
(36, 13, 103, NULL, 0, 0, 0),
(37, 13, 101, NULL, 0, 0, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cau_hoi`
--

CREATE TABLE `cau_hoi` (
  `id` int(11) NOT NULL,
  `mon_id` int(11) NOT NULL,
  `noi_dung` varchar(255) NOT NULL,
  `dap_an_a` varchar(255) NOT NULL,
  `dap_an_b` varchar(255) NOT NULL,
  `dap_an_c` varchar(255) NOT NULL,
  `dap_an_d` varchar(255) NOT NULL,
  `dap_an_dung` char(1) NOT NULL,
  `diem` int(11) DEFAULT 1,
  `giai_thich` text DEFAULT NULL,
  `loai` varchar(20) DEFAULT 'D',
  `kich_hoat` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `cau_hoi`
--

INSERT INTO `cau_hoi` (`id`, `mon_id`, `noi_dung`, `dap_an_a`, `dap_an_b`, `dap_an_c`, `dap_an_d`, `dap_an_dung`, `diem`, `giai_thich`, `loai`, `kich_hoat`, `created_at`) VALUES
(4, 4, '5 + 7 = ?', '10', '11', '12', '13', 'C', 1, '5 cộng 7 bằng 12', 'D', 1, '2026-01-05 19:46:35'),
(5, 4, '15 - 6 = ?', '8', '9', '10', '7', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(6, 4, '3 x 8 = ?', '21', '24', '26', '28', 'B', 1, 'Bảng cửu chương 3', 'D', 1, '2026-01-05 19:46:35'),
(7, 4, '45 : 5 = ?', '9', '8', '7', '6', 'A', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(8, 4, 'Tìm x biết: x + 10 = 25', '10', '15', '20', '5', 'B', 2, 'x = 25 - 10', 'TB', 1, '2026-01-05 19:46:35'),
(9, 4, 'Diện tích hình vuông cạnh 5cm là?', '20cm2', '25cm2', '10cm2', '15cm2', 'B', 2, '5 x 5 = 25', 'TB', 1, '2026-01-05 19:46:35'),
(10, 4, 'Số chẵn liền sau 18 là?', '19', '20', '22', '16', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(11, 4, '2 mũ 3 bằng bao nhiêu?', '6', '8', '9', '12', 'B', 2, '2 x 2 x 2 = 8', 'TB', 1, '2026-01-05 19:46:35'),
(12, 4, 'Căn bậc hai của 64 là?', '6', '7', '8', '9', 'C', 2, '8 x 8 = 64', 'TB', 1, '2026-01-05 19:46:35'),
(13, 4, 'Hình tam giác có tổng ba góc là?', '90 độ', '180 độ', '360 độ', '100 độ', 'B', 2, 'Định lý tổng 3 góc', 'TB', 1, '2026-01-05 19:46:35'),
(14, 4, '50% của 200 là?', '50', '150', '100', '20', 'C', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(15, 4, 'Phân số 1/2 bằng số thập phân nào?', '0.5', '0.2', '0.1', '0.25', 'A', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(16, 4, 'Số nguyên tố nhỏ nhất là?', '0', '1', '2', '3', 'C', 2, '', 'K', 1, '2026-01-05 19:46:35'),
(17, 4, 'Ước chung lớn nhất của 12 và 18?', '3', '6', '9', '2', 'B', 2, '', 'K', 1, '2026-01-05 19:46:35'),
(18, 4, 'Chu vi hình chữ nhật dài 4m rộng 3m?', '7m', '12m', '14m', '20m', 'C', 2, '(4+3)x2', 'TB', 1, '2026-01-05 19:46:35'),
(19, 4, 'Kết quả của 100 x 0 = ?', '100', '10', '1', '0', 'D', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(20, 4, 'Số Pi xấp xỉ bằng?', '3.12', '3.14', '3.15', '3.16', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(21, 4, '1 giờ có bao nhiêu phút?', '60', '30', '100', '24', 'A', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(22, 4, 'Hình bình hành có mấy cặp cạnh song song?', '1', '2', '3', '4', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(23, 4, '1kg bằng bao nhiêu gam?', '10', '100', '1000', '10000', 'C', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(24, 4, 'Góc vuông có số đo là?', '60 độ', '90 độ', '120 độ', '180 độ', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(25, 4, 'Tính: 2 + 2 x 2 = ?', '8', '6', '10', '4', 'B', 2, 'Nhân chia trước cộng trừ sau', 'TB', 1, '2026-01-05 19:46:35'),
(26, 4, 'Tìm x biết: 2x = 10', '2', '5', '8', '20', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(27, 4, 'Số tiếp theo trong dãy: 2; 4; 6; 8; ...?', '9', '10', '11', '12', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(28, 4, 'Thể tích hình lập phương cạnh 2cm?', '4cm3', '6cm3', '8cm3', '12cm3', 'C', 2, '2 x 2 x 2', 'K', 1, '2026-01-05 19:46:35'),
(29, 4, 'Định lý Pitago áp dụng cho tam giác nào?', 'Tam giác đều', 'Tam giác cân', 'Tam giác vuông', 'Tam giác nhọn', 'C', 2, '', 'TB', 1, '2026-01-05 19:46:35'),
(30, 4, 'Số nào chia hết cho 5?', '12', '23', '35', '41', 'C', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(31, 4, 'Nghịch đảo của 2/3 là?', '3/2', '1/3', '-2/3', '3/4', 'A', 2, '', 'TB', 1, '2026-01-05 19:46:35'),
(32, 4, '1 thế kỷ bằng bao nhiêu năm?', '10', '50', '100', '1000', 'C', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(33, 4, 'Giải phương trình: x - 5 = 0', '5', '-5', '0', '1', 'A', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(34, 4, 'Số âm nhân số âm ra kết quả gì?', 'Số âm', 'Số dương', 'Số 0', 'Không xác định', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(35, 4, 'Đường kính bằng mấy lần bán kính?', '1', '2', '3', '4', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(36, 4, 'Hình thoi có hai đường chéo như thế nào?', 'Song song', 'Vuông góc', 'Bằng nhau', 'Trùng nhau', 'B', 2, '', 'TB', 1, '2026-01-05 19:46:35'),
(37, 5, 'Tác giả của Truyện Kiều là ai?', 'Nguyễn Trãi', 'Nguyễn Du', 'Hồ Xuân Hương', 'Nguyễn Khuyến', 'B', 1, 'Đại thi hào dân tộc', 'D', 1, '2026-01-05 19:46:35'),
(38, 5, 'Chí Phèo là nhân vật của nhà văn nào?', 'Ngô Tất Tố', 'Nam Cao', 'Vũ Trọng Phụng', 'Kim Lân', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(39, 5, 'Bài thơ \'Sông núi nước Nam\' được coi là gì?', 'Bản tuyên ngôn độc lập đầu tiên', 'Bài thơ tình hay nhất', 'Bản cáo trạng', 'Bài hịch tướng sĩ', 'A', 2, '', 'K', 1, '2026-01-05 19:46:35'),
(40, 5, 'Trong câu \'Tre giữ làng; giữ nước; giữ mái nhà tranh\'', 'biện pháp tu từ là?', 'So sánh', 'Ẩn dụ', 'Nhân hóa', 'A', 0, '2', '', 0, '2026-01-05 19:46:35'),
(41, 5, 'Ai là tác giả của \'Dế Mèn phiêu lưu ký\'?', 'Tô Hoài', 'Nguyễn Huy Tưởng', 'Phạm Hổ', 'Đoàn Giỏi', 'A', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(42, 5, 'Câu \'Học đi đôi với hành\' thuộc loại câu gì?', 'Tục ngữ', 'Thành ngữ', 'Ca dao', 'Hò vè', 'A', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(43, 5, 'Tác phẩm \'Tắt đèn\' nói về nhân vật nào?', 'Chị Dậu', 'Lão Hạc', 'Thị Nở', 'Cô Tấm', 'A', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(44, 5, 'Nhà thơ nào được gọi là \'Bà Chúa Thơ Nôm\'?', 'Huyện Thanh Quan', 'Hồ Xuân Hương', 'Đoàn Thị Điểm', 'Lê Ngọc Hân', 'B', 2, '', 'TB', 1, '2026-01-05 19:46:35'),
(45, 5, 'Truyện cổ tích \'Tấm Cám\' thuộc thể loại gì?', 'Thần thoại', 'Truyền thuyết', 'Cổ tích thần kỳ', 'Ngụ ngôn', 'C', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(46, 5, 'Từ nào sau đây viết sai chính tả?', 'Xán lạn', 'Sáng lạng', 'Sắp xếp', 'Suôn sẻ', 'B', 2, 'Đúng là Xán lạn', 'K', 1, '2026-01-05 19:46:35'),
(47, 5, 'Ai là người sáng tác bài \'Quốc ca\' Việt Nam?', 'Phạm Tuyên', 'Văn Cao', 'Trịnh Công Sơn', 'Hoàng Việt', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(48, 5, 'Câu thơ \'Thân em vừa trắng lại vừa tròn\' nói về bánh gì?', 'Bánh chưng', 'Bánh giầy', 'Bánh trôi', 'Bánh gai', 'C', 1, 'Thơ Hồ Xuân Hương', 'D', 1, '2026-01-05 19:46:35'),
(49, 5, 'Sơn Tinh - Thủy Tinh giải thích hiện tượng gì?', 'Động đất', 'Lũ lụt', 'Hạn hán', 'Núi lửa', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(50, 5, 'Chủ tịch Hồ Chí Minh đọc Tuyên ngôn độc lập năm nào?', '1930', '1945', '1954', '1975', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(51, 5, 'Nhân vật Mị xuất hiện trong tác phẩm nào?', 'Vợ nhặt', 'Vợ chồng A Phủ', 'Chí Phèo', 'Lão Hạc', 'B', 2, '', 'TB', 1, '2026-01-05 19:46:35'),
(52, 5, 'Từ trái nghĩa với \'nhanh\' là?', 'Lẹ', 'Mau', 'Chậm', 'Gấp', 'C', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(53, 5, 'Thành ngữ \'Có công mài sắt; có ngày nên ...\'?', 'Vàng', 'Kim', 'Dao', 'Kéo', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(54, 5, 'Tác giả của \'Lão Hạc\' là?', 'Nam Cao', 'Ngô Tất Tố', 'Nguyên Hồng', 'Thạch Lam', 'A', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(55, 5, '\'Đất nước\' là đoạn trích trong trường ca nào?', 'Mặt đường khát vọng', 'Đất nước hình tia chớp', 'Những người đi tới biển', 'Trường ca biển', 'A', 2, 'Của Nguyễn Khoa Điềm', 'K', 1, '2026-01-05 19:46:35'),
(56, 5, 'Nhà văn Kim Lân nổi tiếng với đề tài gì?', 'Người lính', 'Nông thôn và người nông dân', 'Tiểu tư sản', 'Thành thị', 'B', 2, '', 'TB', 1, '2026-01-05 19:46:35'),
(57, 5, 'Từ nào đồng nghĩa với \'Hòa bình\'?', 'Chiến tranh', 'Xung đột', 'Thái bình', 'Hỗn loạn', 'C', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(58, 5, 'Hai câu thơ \'Cày đồng đang buổi ban trưa / Mồ hôi thánh thót như mưa ruộng cày\' là?', 'Thơ Đường luật', 'Ca dao', 'Tục ngữ', 'Vè', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(59, 5, 'Ai là tác giả của \'Bình Ngô Đại Cáo\'?', 'Lý Thường Kiệt', 'Trần Hưng Đạo', 'Nguyễn Trãi', 'Quang Trung', 'C', 2, '', 'K', 1, '2026-01-05 19:46:35'),
(60, 5, 'Truyện \'Thánh Gióng\' ca ngợi truyền thống gì?', 'Hiếu học', 'Yêu nước chống giặc', 'Lao động cần cù', 'Tương thân tương ái', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(61, 5, '\'Sóng\' là bài thơ của ai?', 'Anh Thơ', 'Xuân Quỳnh', 'Phan Thị Thanh Nhàn', 'Lâm Thị Mỹ Dạ', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(62, 5, 'Câu nào sau đây là câu cảm thán?', 'Trời hôm nay đẹp quá!', 'Bạn đi đâu đấy?', 'Mời bạn ngồi xuống.', 'Tôi đang học bài.', 'A', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(63, 5, 'Văn bản \'Hịch tướng sĩ\' được viết vào thời nào?', 'Thời Lý', 'Thời Trần', 'Thời Lê', 'Thời Nguyễn', 'B', 2, '', 'TB', 1, '2026-01-05 19:46:35'),
(64, 5, 'Trong câu \'Mặt trời của bắp thì nằm trên đồi\'', 'từ Mặt trời thứ 2 là?', 'Ẩn dụ', 'Hoán dụ', 'So sánh', 'A', 0, '2', '', 0, '2026-01-05 19:46:35'),
(65, 5, 'Nhân vật chính trong \'Chiếc lược ngà\' là ai?', 'Bé Thu', 'Ông Hai', 'Anh thanh niên', 'Chị Dậu', 'A', 1, '', 'TB', 1, '2026-01-05 19:46:35'),
(66, 5, 'Nhà thơ Tố Hữu được mệnh danh là?', 'Nhà thơ tình', 'Nhà thơ mới', 'Lá cờ đầu của thơ ca cách mạng', 'Ông hoàng thơ tình', 'C', 2, '', 'TB', 1, '2026-01-05 19:46:35'),
(67, 5, 'Câu \'Lá lành đùm lá rách\' khuyên chúng ta điều gì?', 'Tiết kiệm', 'Đoàn kết yêu thương', 'Dũng cảm', 'Trung thực', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(68, 6, 'Meaning of \'Hello\'?', 'Tạm biệt', 'Xin chào', 'Cảm ơn', 'Xin lỗi', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(69, 6, 'Translate \'Con mèo\' to English?', 'Dog', 'Cat', 'Bird', 'Fish', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(70, 6, 'What is the past tense of \'Go\'?', 'Goed', 'Goes', 'Went', 'Gone', 'C', 1, 'Bất quy tắc', 'D', 1, '2026-01-05 19:46:35'),
(71, 6, 'She _____ a beautiful girl.', 'am', 'is', 'are', 'be', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(72, 6, 'Which word means \'Trường học\'?', 'Hospital', 'School', 'Bank', 'Park', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(73, 6, 'He _____ football every day.', 'play', 'plays', 'playing', 'played', 'B', 2, 'Hiện tại đơn với He', 'TB', 1, '2026-01-05 19:46:35'),
(74, 6, 'Find the odd one out?', 'Red', 'Blue', 'Apple', 'Green', 'C', 1, 'Apple là quả', 'còn lại là màu', 0, '2026-01-05 19:46:35'),
(75, 6, 'Meaning of \'Teacher\'?', 'Bác sĩ', 'Giáo viên', 'Kỹ sư', 'Công nhân', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(76, 6, 'I _____ watching TV now.', 'am', 'is', 'are', 'do', 'A', 1, 'Hiện tại tiếp diễn', 'TB', 1, '2026-01-05 19:46:35'),
(77, 6, 'What _____ your name?', 'am', 'is', 'are', 'do', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(78, 6, 'Yesterday; I _____ a new car.', 'buy', 'buys', 'bought', 'buying', 'C', 2, 'Quá khứ đơn', 'TB', 1, '2026-01-05 19:46:35'),
(79, 6, 'Plural form of \'Child\'?', 'Childs', 'Children', 'Childrens', 'Childes', 'B', 2, 'Bất quy tắc', 'TB', 1, '2026-01-05 19:46:35'),
(80, 6, 'Meaning of \'Thank you\'?', 'Xin chào', 'Cảm ơn', 'Xin lỗi', 'Làm ơn', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(81, 6, '_____ do you live? - I live in Hanoi.', 'What', 'Where', 'When', 'Who', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(82, 6, 'Opposite of \'Hot\'?', 'Cold', 'Warm', 'Cool', 'Dry', 'A', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(83, 6, 'How _____ are you? - I am 10 years old.', 'old', 'many', 'much', 'long', 'A', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(84, 6, 'Translate \'Quyển sách\' to English?', 'Pen', 'Ruler', 'Book', 'Bag', 'C', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(85, 6, 'We _____ to the cinema last night.', 'go', 'went', 'gone', 'going', 'B', 2, '', 'TB', 1, '2026-01-05 19:46:35'),
(86, 6, 'He is good _____ Math.', 'in', 'on', 'at', 'of', 'C', 2, 'Cấu trúc good at', 'K', 1, '2026-01-05 19:46:35'),
(87, 6, 'Meaning of \'Family\'?', 'Bạn bè', 'Gia đình', 'Hàng xóm', 'Đồng nghiệp', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(88, 6, 'There _____ four people in my family.', 'is', 'are', 'am', 'be', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(89, 6, 'Can you _____ English?', 'speak', 'say', 'tell', 'talk', 'A', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(90, 6, 'It is raining _____.', 'heavy', 'heavily', 'heavier', 'heaviest', 'B', 2, 'Trạng từ bổ nghĩa động từ', 'K', 1, '2026-01-05 19:46:35'),
(91, 6, 'Meaning of \'Always\'?', 'Không bao giờ', 'Thỉnh thoảng', 'Luôn luôn', 'Thường xuyên', 'C', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(92, 6, 'My mother is a _____.', 'teach', 'teacher', 'teaching', 'teaches', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(93, 6, 'Look! The bus _____.', 'come', 'comes', 'is coming', 'came', 'C', 1, 'Dấu hiệu Look!', 'TB', 1, '2026-01-05 19:46:35'),
(94, 6, 'Meaning of \'Beautiful\'?', 'Xấu', 'Đẹp', 'Cao', 'Thấp', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(95, 6, '_____ is this? - It\'s a pen.', 'Who', 'What', 'Where', 'Why', 'B', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(96, 6, 'They _____ happy yesterday.', 'was', 'were', 'are', 'is', 'B', 1, 'Quá khứ với They', 'TB', 1, '2026-01-05 19:46:35'),
(97, 6, 'Do you like _____ music?', 'listen', 'listening', 'listens', 'listened', 'B', 2, 'Like + V-ing', 'TB', 1, '2026-01-05 19:46:35'),
(98, 6, 'Meaning of \'Sunday\'?', 'Thứ hai', 'Thứ bảy', 'Chủ nhật', 'Thứ sáu', 'C', 1, '', 'D', 1, '2026-01-05 19:46:35'),
(99, 6, 'I have _____ apple.', 'a', 'an', 'the', 'some', 'B', 1, 'Trước nguyên âm', 'D', 1, '2026-01-05 19:46:35'),
(100, 6, 'The sun _____ in the East.', 'rise', 'rises', 'rising', 'rose', 'B', 2, 'Sự thật hiển nhiên', 'TB', 1, '2026-01-05 19:46:35'),
(101, 1, '1 + 1', 'II', '2', 'Hi', '( 1 + 1 ) x ( 1 + 1 )', 'B', 1, 'OK', 'K', 1, '2026-01-05 20:07:39'),
(102, 1, 'A + B = ?', 'C', 'D', 'A', 'B', 'A', 3, '', 'K', 1, '2026-01-06 12:21:35'),
(103, 1, 'X + Y = ?', 'Z', 'Y', 'A', 'B', 'A', 3, '', 'K', 1, '2026-01-06 12:22:09');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `de_thi`
--

CREATE TABLE `de_thi` (
  `id` int(11) NOT NULL,
  `ma_de` varchar(30) NOT NULL,
  `ten_de` varchar(255) NOT NULL,
  `thoi_gian` int(11) NOT NULL DEFAULT 30,
  `mon_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `de_thi`
--

INSERT INTO `de_thi` (`id`, `ma_de`, `ten_de`, `thoi_gian`, `mon_id`, `created_at`) VALUES
(5, 'CKKT', 'ktthicuoiki', 60, 5, '2026-01-05 20:55:39'),
(6, 'GKCNTT', 'thigiuaki', 30, 1, '2026-01-06 12:23:38');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `de_thi_cau_hoi`
--

CREATE TABLE `de_thi_cau_hoi` (
  `id` int(11) NOT NULL,
  `de_id` int(11) NOT NULL,
  `cauhoi_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `de_thi_cau_hoi`
--

INSERT INTO `de_thi_cau_hoi` (`id`, `de_id`, `cauhoi_id`) VALUES
(14, 5, 39),
(7, 5, 43),
(11, 5, 44),
(3, 5, 45),
(9, 5, 51),
(6, 5, 52),
(13, 5, 55),
(8, 5, 56),
(4, 5, 60),
(5, 5, 61),
(12, 5, 63),
(10, 5, 65),
(17, 6, 101),
(16, 6, 102),
(15, 6, 103);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoc_vien`
--

CREATE TABLE `hoc_vien` (
  `id` int(11) NOT NULL,
  `hoten` varchar(100) NOT NULL,
  `ma_hv` varchar(50) NOT NULL,
  `matkhau` varchar(100) NOT NULL,
  `trangthai` tinyint(4) DEFAULT 1,
  `lop_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hoc_vien`
--

INSERT INTO `hoc_vien` (`id`, `hoten`, `ma_hv`, `matkhau`, `trangthai`, `lop_id`, `created_by`, `created_at`) VALUES
(1, 'Đỗ Thị Ánh', '69577', 'anh1234', 1, 2, NULL, '2025-12-29 14:59:48'),
(2, 'Nguyễn Thị Tú Quyên', '79778', 'quyen223', 1, 1, NULL, '2025-12-29 14:59:48'),
(3, 'Nguyễn Thị Ngọc Linh', '23567', 'linh1234', 1, 4, NULL, '2025-12-29 21:29:34'),
(4, 'admin', '888', '123', 1, 4, 1, '2026-01-03 22:32:56'),
(5, 'ABCD', '999', '123', 1, 1, 1, '2026-01-03 23:53:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lop_hoc`
--

CREATE TABLE `lop_hoc` (
  `id` int(11) NOT NULL,
  `ma_lop` varchar(20) NOT NULL,
  `ten_lop` varchar(100) NOT NULL,
  `trangthai` tinyint(4) DEFAULT 1,
  `nguoi_tao` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lop_hoc`
--

INSERT INTO `lop_hoc` (`id`, `ma_lop`, `ten_lop`, `trangthai`, `nguoi_tao`, `created_at`) VALUES
(1, 'KT', 'Kế toán', 1, 'Nguyễn Văn Quyền', '2021-08-07 16:33:21'),
(2, 'TMĐT', 'Thương mại điện tử', 1, 'Nguyễn Văn Quyền', '2021-08-07 16:33:21'),
(3, 'HTTT', 'Hệ thống thông tin', 1, 'Nguyễn Văn Quyền', '2021-08-07 16:33:21'),
(4, 'DCTT', 'Công nghệ thông tin', 1, 'Nguyễn Văn Quyền', '2021-08-07 16:33:21');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `mon_thi`
--

CREATE TABLE `mon_thi` (
  `id` int(11) NOT NULL,
  `ma_mon` varchar(20) NOT NULL,
  `ten_mon` varchar(100) NOT NULL,
  `trangthai` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `mon_thi`
--

INSERT INTO `mon_thi` (`id`, `ma_mon`, `ten_mon`, `trangthai`, `created_at`) VALUES
(1, 'TOAN2', 'TOÁN 2', 1, '2025-12-29 16:07:10'),
(4, 'TOAN', 'TOÁN', 1, '2026-01-05 19:46:35'),
(5, 'VAN', 'VĂN', 1, '2026-01-05 19:46:35'),
(6, 'ANH', 'ANH', 1, '2026-01-05 19:46:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phong_thi`
--

CREATE TABLE `phong_thi` (
  `id` int(11) NOT NULL,
  `ma_phong` varchar(30) NOT NULL,
  `ten_phong` varchar(255) NOT NULL,
  `mon_id` int(11) NOT NULL,
  `de_id` int(11) NOT NULL,
  `lop_id` int(11) NOT NULL,
  `trangthai` tinyint(4) DEFAULT 1,
  `bat_dau` datetime DEFAULT NULL,
  `nguoi_tao` varchar(100) DEFAULT '',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phong_thi`
--

INSERT INTO `phong_thi` (`id`, `ma_phong`, `ten_phong`, `mon_id`, `de_id`, `lop_id`, `trangthai`, `bat_dau`, `nguoi_tao`, `created_at`) VALUES
(3, '22265', 'THI CUỐI KÌ LỚP KINH TẾ', 5, 5, 1, 1, '2026-01-31 01:56:00', 'Ngô Thanh Nhã', '2026-01-05 20:56:57'),
(4, '22264', 'GIỮA KÌ CNTT', 1, 6, 4, 1, '2026-01-06 12:27:00', 'Ngô Thanh Nhã', '2026-01-06 12:24:20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phong_thi_hoc_vien`
--

CREATE TABLE `phong_thi_hoc_vien` (
  `id` int(11) NOT NULL,
  `phong_id` int(11) NOT NULL,
  `hocvien_id` int(11) NOT NULL,
  `thoi_gian_vao` datetime DEFAULT NULL,
  `kich_hoat` tinyint(4) DEFAULT 1,
  `diem` int(11) DEFAULT 0,
  `cau_dung` int(11) DEFAULT 0,
  `trang_thai` varchar(20) DEFAULT 'Chưa thi',
  `lam_lai` tinyint(4) DEFAULT 0,
  `so_lan_vi_pham` int(11) DEFAULT 0,
  `tru` int(11) DEFAULT 0,
  `con_lai` int(11) DEFAULT 0,
  `ghi_chu` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phong_thi_hoc_vien`
--

INSERT INTO `phong_thi_hoc_vien` (`id`, `phong_id`, `hocvien_id`, `thoi_gian_vao`, `kich_hoat`, `diem`, `cau_dung`, `trang_thai`, `lam_lai`, `so_lan_vi_pham`, `tru`, `con_lai`, `ghi_chu`) VALUES
(337, 3, 2, NULL, 1, 0, 0, 'Chưa thi', 0, 0, 0, 0, ''),
(338, 3, 5, '2026-01-05 20:57:12', 1, 0, 0, 'Chưa thi', 2, 0, 0, 0, ''),
(371, 4, 3, NULL, 1, 0, 0, 'Chưa thi', 0, 0, 0, 0, ''),
(372, 4, 4, '2026-01-06 12:29:09', 0, 0, 0, 'Đã nộp', 0, 4, 1, 0, 'Vi phạm quy chế thi - Bị cấm thi');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `hoten` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `trangthai` tinyint(4) DEFAULT 1,
  `role` varchar(20) DEFAULT 'admin',
  `lop_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `hoten`, `username`, `email`, `password_hash`, `trangthai`, `role`, `lop_id`, `created_at`) VALUES
(1, 'Ngô Thanh Nhã', 'thanhnha', 'nha21@gmail.com', '123', 1, 'admin', 1, '2025-08-07 16:33:21'),
(2, 'Nguyễn Thị Lan Anh', 'lananh', 'anh223@gmail.com', '123', 1, 'admin', 2, '2025-06-04 17:23:11'),
(3, 'DEP VAN TRAI', 'admin', 'cc@cc.cc', '123', 0, 'admin', NULL, '2026-01-03 22:32:26'),
(4, 'GIẢNG VIÊN 1', 'gv1', 'gv1@cc.cc', '$2y$10$thHyvyaqcEEgvpYkT6mFwufYQtSeMi4pwpHxVsJX18DWAYnmGy0Fa', 1, 'gv', NULL, '2026-01-05 20:32:50'),
(5, 'GIẢNG VIÊN 2', 'gv2', 'gv2@cc.cc', '$2y$10$MjJ.gJuAycrKmi94rEqnXudfclKjEJibkuT1.9rx8BgIOlEBgWPf.', 1, 'gv', NULL, '2026-01-06 12:31:31');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bai_lam`
--
ALTER TABLE `bai_lam`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `bai_lam_ct`
--
ALTER TABLE `bai_lam_ct`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `cau_hoi`
--
ALTER TABLE `cau_hoi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ch_mon` (`mon_id`);

--
-- Chỉ mục cho bảng `de_thi`
--
ALTER TABLE `de_thi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ma_de` (`ma_de`),
  ADD KEY `mon_id` (`mon_id`);

--
-- Chỉ mục cho bảng `de_thi_cau_hoi`
--
ALTER TABLE `de_thi_cau_hoi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_de_cauhoi` (`de_id`,`cauhoi_id`),
  ADD KEY `cauhoi_id` (`cauhoi_id`);

--
-- Chỉ mục cho bảng `hoc_vien`
--
ALTER TABLE `hoc_vien`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ma_hv` (`ma_hv`),
  ADD KEY `idx_hv_lop` (`lop_id`);

--
-- Chỉ mục cho bảng `lop_hoc`
--
ALTER TABLE `lop_hoc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ma_lop` (`ma_lop`);

--
-- Chỉ mục cho bảng `mon_thi`
--
ALTER TABLE `mon_thi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ten_mon` (`ten_mon`);

--
-- Chỉ mục cho bảng `phong_thi`
--
ALTER TABLE `phong_thi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ma_phong` (`ma_phong`),
  ADD KEY `mon_id` (`mon_id`),
  ADD KEY `de_id` (`de_id`),
  ADD KEY `lop_id` (`lop_id`);

--
-- Chỉ mục cho bảng `phong_thi_hoc_vien`
--
ALTER TABLE `phong_thi_hoc_vien`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_phong_hv` (`phong_id`,`hocvien_id`),
  ADD KEY `hocvien_id` (`hocvien_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `lop_id` (`lop_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bai_lam`
--
ALTER TABLE `bai_lam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `bai_lam_ct`
--
ALTER TABLE `bai_lam_ct`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT cho bảng `cau_hoi`
--
ALTER TABLE `cau_hoi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT cho bảng `de_thi`
--
ALTER TABLE `de_thi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `de_thi_cau_hoi`
--
ALTER TABLE `de_thi_cau_hoi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `hoc_vien`
--
ALTER TABLE `hoc_vien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `lop_hoc`
--
ALTER TABLE `lop_hoc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `mon_thi`
--
ALTER TABLE `mon_thi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `phong_thi`
--
ALTER TABLE `phong_thi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `phong_thi_hoc_vien`
--
ALTER TABLE `phong_thi_hoc_vien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=393;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cau_hoi`
--
ALTER TABLE `cau_hoi`
  ADD CONSTRAINT `fk_ch_mon` FOREIGN KEY (`mon_id`) REFERENCES `mon_thi` (`id`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `de_thi`
--
ALTER TABLE `de_thi`
  ADD CONSTRAINT `de_thi_ibfk_1` FOREIGN KEY (`mon_id`) REFERENCES `mon_thi` (`id`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `de_thi_cau_hoi`
--
ALTER TABLE `de_thi_cau_hoi`
  ADD CONSTRAINT `de_thi_cau_hoi_ibfk_1` FOREIGN KEY (`de_id`) REFERENCES `de_thi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `de_thi_cau_hoi_ibfk_2` FOREIGN KEY (`cauhoi_id`) REFERENCES `cau_hoi` (`id`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `hoc_vien`
--
ALTER TABLE `hoc_vien`
  ADD CONSTRAINT `fk_hv_lop` FOREIGN KEY (`lop_id`) REFERENCES `lop_hoc` (`id`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `phong_thi`
--
ALTER TABLE `phong_thi`
  ADD CONSTRAINT `phong_thi_ibfk_1` FOREIGN KEY (`mon_id`) REFERENCES `mon_thi` (`id`),
  ADD CONSTRAINT `phong_thi_ibfk_2` FOREIGN KEY (`de_id`) REFERENCES `de_thi` (`id`),
  ADD CONSTRAINT `phong_thi_ibfk_3` FOREIGN KEY (`lop_id`) REFERENCES `lop_hoc` (`id`);

--
-- Các ràng buộc cho bảng `phong_thi_hoc_vien`
--
ALTER TABLE `phong_thi_hoc_vien`
  ADD CONSTRAINT `phong_thi_hoc_vien_ibfk_1` FOREIGN KEY (`phong_id`) REFERENCES `phong_thi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `phong_thi_hoc_vien_ibfk_2` FOREIGN KEY (`hocvien_id`) REFERENCES `hoc_vien` (`id`);

--
-- Các ràng buộc cho bảng `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`lop_id`) REFERENCES `lop_hoc` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
