# Hệ Thống Thi Trắc Nghiệm UTT (UTT Exam System)

## Mục Lục
1. [Giới Thiệu](#giới-thiệu)
2. [Yêu Cầu Hệ Thống](#yêu-cầu-hệ-thống)
3. [Cài Đặt](#cài-đặt)
4. [Cấu Trúc Dự Án](#cấu-trúc-dự-án)
5. [Cấu Hình](#cấu-hình)
6. [Cơ Sở Dữ Liệu](#cơ-sở-dữ-liệu)
7. [Kiến Trúc MVC](#kiến-trúc-mvc)
8. [Các Chức Năng Chính](#các-chức-năng-chính)
9. [Hướng Dẫn Sử Dụng](#hướng-dẫn-sử-dụng)
10. [Logic Nghiệp Vụ Chi Tiết](#logic-nghiệp-vụ-chi-tiết)
11. [API Endpoints](#api-endpoints)

---

## Giới Thiệu


**Lưu ý quan trọng**: Đây chỉ là hệ thống tham khảo theo mô hình mvc, không phải Hệ thống thi trắc nghiệm trực tuyến được phát triển cho **Trường Đại học Công nghệ GTVT (UTT)**.

**Hệ thống Thi Trắc Nghiệm UTT** là một ứng dụng web được xây dựng bằng PHP thuần theo kiến trúc MVC (Model-View-Controller). Hệ thống hỗ trợ tổ chức kỳ thi trắc nghiệm trực tuyến với khả năng giám sát thời gian thực và chấm điểm tự động.

### Tính năng nổi bật:
- Hệ thống câu hỏi phân loại theo độ khó (Dễ, Trung bình, Khó)
- Tự động tạo đề thi ngẫu nhiên từ ngân hàng câu hỏi
- Giám sát thời gian thực trạng thái học viên đang thi
- Theo dõi vi phạm với hệ thống trừ điểm tự động
- Import/Export dữ liệu qua file CSV
- Giao diện responsive, hiện đại với dark theme

---

## Các Vai Trò Người Dùng

Hệ thống có 3 vai trò người dùng với các quyền hạn khác nhau:

### 1. Quản Trị Viên (Admin)

**Mô tả:** Admin có toàn quyền quản lý hệ thống, từ thiết lập dữ liệu ban đầu đến giám sát kỳ thi.

**Quy trình làm việc:**

```
┌─────────────────────────────────────────────────────────────────────────┐
│                        ADMIN WORKFLOW                                   │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  1. THIẾT LẬP BAN ĐẦU                                                   │
│     ├── Tạo tài khoản Admin/GV                                          │
│     ├── Tạo Lớp học                                                     │
│     └── Import Học viên vào lớp (CSV)                                   │
│                                                                         │
│  2. CHUẨN BỊ NGÂN HÀNG ĐỀ                                               │
│     ├── Tạo Môn thi                                                     │
│     ├── Nhập Câu hỏi (theo độ khó: D/TB/K)                              │
│     └── Tạo Đề thi (chọn số lượng câu từng loại)                        │
│                                                                         │
│  3. TỔ CHỨC KỲ THI                                                      │
│     ├── Tạo Phòng thi (gán Lớp + Đề + Thời gian)                        │
│     ├── Kích hoạt/Khóa học viên tham gia                                │
│     └── Giám sát phòng thi real-time                                    │
│                                                                         │
│  4. SAU KỲ THI                                                          │
│     ├── Xem kết quả chi tiết từng học viên                              │
│     ├── Xuất báo cáo CSV                                                │
│     └── Reset cho học viên làm lại (nếu cần)                            │
│                                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

**Các chức năng chi tiết:**

| Module | Chức năng | Mô tả |
|--------|-----------|-------|
| **Tài khoản** | CRUD + Import | Quản lý tài khoản Admin và Giảng viên |
| **Lớp học** | CRUD + Import | Tạo và quản lý các lớp học |
| **Học viên** | CRUD + Import | Thêm học viên vào lớp, import từ CSV |
| **Môn thi** | CRUD + Import | Quản lý danh mục các môn thi |
| **Câu hỏi** | CRUD + Import | Nhập câu hỏi với 4 đáp án, phân loại độ khó |
| **Đề thi** | Tạo tự động | Chọn số câu D/TB/K, hệ thống random từ ngân hàng |
| **Phòng thi** | CRUD + Giám sát | Tạo ca thi, gán lớp, theo dõi tiến trình |

**Phân quyền đặc biệt của Admin:**
- Xem Dashboard thống kê toàn hệ thống
- Xóa dữ liệu (cascade delete - xóa tất cả dữ liệu liên quan)
- Khóa/Mở khóa tài khoản người dùng
- Ghi nhận vi phạm và hủy bài thi

---

### 2. Giảng Viên (GV)

**Mô tả:** Giảng viên có quyền giám sát phòng thi nhưng không thể thay đổi cấu trúc hệ thống (không thể tạo môn, câu hỏi, đề thi).

**Quy trình làm việc:**

```
┌─────────────────────────────────────────────────────────────────────────┐
│                      GIẢNG VIÊN WORKFLOW                                │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  1. TRƯỚC GIỜ THI                                                       │
│     ├── Đăng nhập hệ thống                                              │
│     ├── Xem danh sách phòng thi được phân công                          │
│     └── Vào phòng thi để chuẩn bị                                       │
│                                                                         │
│  2. TRONG GIỜ THI                                                       │
│     ├── Kích hoạt học viên được phép thi                                │
│     ├── Theo dõi trạng thái: Chưa thi / Đang thi / Đã nộp               │
│     ├── Giám sát tiến độ làm bài (số câu đã trả lời)                    │
│     ├── Ghi nhận vi phạm (chuyển tab, thoát cửa sổ)                     │
│     └── Ghi chú cho từng học viên                                       │
│                                                                         │
│  3. SAU GIỜ THI                                                         │
│     ├── Hủy bài thi của học viên vi phạm                                │
│     ├── Reset cho học viên làm lại                                      │
│     └── Xuất kết quả phòng thi ra CSV                                   │
│                                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

**So sánh quyền Admin vs GV:**

| Chức năng | Admin | GV |
|-----------|:-----:|:--:|
| Dashboard thống kê | ✅ | ❌ |
| Quản lý Tài khoản | ✅ | ❌ |
| Quản lý Lớp học | ✅ | ❌ |
| Quản lý Học viên | ✅ | ❌ |
| Quản lý Môn thi | ✅ | ❌ |
| Quản lý Câu hỏi | ✅ | ❌ |
| Quản lý Đề thi | ✅ | ❌ |
| **Xem danh sách Phòng thi** | ✅ | ✅ |
| **Vào phòng giám sát** | ✅ | ✅ |
| **Kích hoạt/Khóa học viên** | ✅ | ✅ |
| **Ghi nhận vi phạm** | ✅ | ✅ |
| **Reset làm lại** | ✅ | ✅ |
| **Hủy bài thi** | ✅ | ✅ |
| **Xuất kết quả CSV** | ✅ | ✅ |
| Tạo/Xóa Phòng thi | ✅ | ❌ |

---

### 3. Học Viên (HV)

**Mô tả:** Học viên đăng nhập bằng mã học viên (`ma_hv`) để tham gia làm bài thi trắc nghiệm.

**Quy trình làm bài thi:**

```
┌─────────────────────────────────────────────────────────────────────────┐
│                       HỌC VIÊN WORKFLOW                                 │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  1. ĐĂNG NHẬP                                                           │
│     ├── Nhập Mã học viên + Mật khẩu                                     │
│     └── Hệ thống kiểm tra: tài khoản hợp lệ + không bị khóa             │
│                                                                         │
│  2. XEM PHÒNG THI                                                       │
│     ├── Hiển thị danh sách các phòng thi của lớp mình                   │
│     ├── Thông tin: Tên phòng, Môn, Thời gian bắt đầu                    │
│     └── Nút "Vào thi" (chỉ hiện khi đến giờ)                            │
│                                                                         │
│  3. XÁC NHẬN THÔNG TIN                                                  │
│     ├── Hiển thị thông tin ca thi: Số câu, Thời gian                    │
│     ├── Kiểm tra: Đã đến giờ? Được kích hoạt? Lớp không bị khóa?        │
│     └── Nhấn "Bắt đầu làm bài"                                          │
│                                                                         │
│  4. LÀM BÀI THI                                                         │
│     ├── Hiển thị câu hỏi từng câu một                                   │
│     ├── Chọn đáp án A/B/C/D (tự động lưu ngay khi chọn)                 │
│     ├── Đánh dấu "Phân vân" để review lại                               │
│     ├── Bảng điều hướng: Nhảy đến câu bất kỳ                            │
│     ├── Đồng hồ đếm ngược thời gian còn lại                             │
│     └── Cảnh báo vi phạm khi thực hiện các tổ hợp phím bị cấm           │
│         (Chuột phải, ctrl+c, f12)                                       │
│                                                                         │
│  5. NỘP BÀI                                                             │
│     ├── Nhấn nút "Nộp bài"                                              │
│     ├── Tự động nộp khi hết giờ                                         │
│     └── Tự động nộp khi vi phạm > 3 lần                                 │
│                                                                         │
│  6. XEM KẾT QUẢ                                                         │
│     ├── Hiển thị điểm số, số câu đúng/sai                               │
│     ├── Chi tiết từng câu: Đáp án chọn vs Đáp án đúng                   │
│     └── Giải thích (nếu có)                                             │
│                                                                         │
│  7. LỊCH SỬ THI                                                         │
│     └── Xem lại tất cả các bài thi đã làm                               │
│                                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

**Các tính năng làm bài:**

| Tính năng | Mô tả |
|-----------|-------|
| **Lưu tự động** | Đáp án được lưu ngay khi chọn (AJAX), không sợ mất dữ liệu |
| **Đánh dấu phân vân** | Đánh dấu câu hỏi để quay lại xem xét sau |
| **Điều hướng nhanh** | Nhảy đến bất kỳ câu nào qua bảng điều hướng |
| **Đếm ngược** | Hiển thị thời gian còn lại, cảnh báo khi sắp hết giờ |
| **Theo dõi vi phạm** | Phát hiện chuyển tab, thoát cửa sổ - tự động ghi nhận |
| **Tự động nộp** | Nộp bài khi hết giờ hoặc vi phạm quá 3 lần |

---

### Hệ Thống Xử Lý Vi Phạm

Hệ thống tự động phát hiện và xử lý các hành vi vi phạm trong quá trình thi:

**Các loại vi phạm được phát hiện:**
- Chuyển tab trình duyệt (visibility change)
- Thoát khỏi cửa sổ thi (blur event)
- Giám thị ghi nhận thủ công

**Quy tắc xử lý:**

| Số lần vi phạm | Hình thức xử lý |
|:--------------:|-----------------|
| 1-2 lần | Cảnh báo, ghi nhận |
| 3 lần | Trừ 1 điểm |
| > 3 lần | **Cấm thi**, tự động thu bài, ghi chú "Vi phạm quy chế thi" |

**Công thức tính điểm cuối cùng:**
```
Điểm cuối = Điểm gốc - floor(Số lần vi phạm / 3)
```

---

### Sơ Đồ Tương Tác Giữa Các Vai Trò

```
                    ┌─────────────────┐
                    │      ADMIN      │
                    │   (Full quyền)  │
                    └────────┬────────┘
                             │
           ┌─────────────────┼─────────────────┐
           │                 │                 │
           ▼                 ▼                 ▼
    ┌──────────────┐  ┌──────────────┐  ┌──────────────┐
    │  Lớp học     │  │  Môn thi     │  │  Tài khoản   │
    │  + Học viên  │  │  + Câu hỏi   │  │  (Admin/GV)  │
    └──────┬───────┘  │  + Đề thi    │  └──────────────┘
           │          └──────┬───────┘
           │                 │
           └────────┬────────┘
                    │
                    ▼
             ┌──────────────┐
             │  PHÒNG THI   │
             │  (Lớp + Đề)  │
             └──────┬───────┘
                    │
       ┌────────────┼────────────┐
       │            │            │
       ▼            ▼            ▼
 ┌──────────┐ ┌──────────┐ ┌──────────┐
 │ GIẢNG    │ │ GIẢNG    │ │  HỌC     │
 │ VIÊN     │ │ VIÊN     │ │  VIÊN    │
 │ (Giám    │ │ (Giám    │ │ (Làm bài)│
 │  sát)    │ │  sát)    │ └──────────┘
 └──────────┘ └──────────┘
```

---

## Yêu Cầu Hệ Thống

- **Web Server**: Apache (XAMPP, WAMP, LAMP)
- **PHP**: >= 7.4
- **MySQL**: >= 5.7
- **Trình duyệt**: Chrome, Firefox, Safari, Edge (phiên bản mới nhất)

---

## Cài Đặt

### Bước 1: Clone/Copy dự án
```bash
# Copy thư mục thitracnghiem vào thư mục htdocs của XAMPP
C:\xampp\htdocs\thitracnghiem
```

### Bước 2: Tạo cơ sở dữ liệu
Mở phpMyAdmin hoặc MySQL CLI và chạy các lệnh SQL ở phần [Cơ Sở Dữ Liệu](#cơ-sở-dữ-liệu).

### Bước 3: Cấu hình kết nối
Chỉnh sửa file `Core/config.php` theo môi trường của bạn.

### Bước 4: Truy cập hệ thống
```
http://localhost/thitracnghiem/public/
```

---

## Cấu Trúc Dự Án

```
thitracnghiem/
├── Core/                          # Các file core của framework
│   ├── config.php                 # Cấu hình database, base URL
│   ├── Database.php               # Class kết nối PDO
│   ├── Controller.php             # Base controller với auth methods
│   ├── Auth.php                   # Helper xác thực tĩnh
│   └── App.php                    # Router điều hướng request
│
├── MVC/
│   ├── Controllers/               # Các controller xử lý logic
│   │   ├── AuthController.php     # Đăng nhập/đăng xuất
│   │   ├── DashboardController.php # Dashboard thống kê
│   │   ├── TaikhoanController.php # Quản lý tài khoản admin/gv
│   │   ├── LophocController.php   # Quản lý lớp học
│   │   ├── MonthiController.php   # Quản lý môn thi
│   │   ├── CauhoiController.php   # Quản lý câu hỏi
│   │   ├── DethiController.php    # Quản lý đề thi
│   │   ├── PhongthiController.php # Quản lý phòng thi
│   │   ├── HocvienAdminController.php # Admin quản lý học viên
│   │   └── HocvienController.php  # Học viên làm bài thi
│   │
│   ├── Models/                    # Các model tương tác database
│   │   ├── User_m.php             # Model tài khoản admin/gv
│   │   ├── Hocvien_m.php          # Model học viên
│   │   ├── Lophoc_m.php           # Model lớp học
│   │   ├── Monthi_m.php           # Model môn thi
│   │   ├── Cauhoi_m.php           # Model câu hỏi
│   │   ├── Dethi_m.php            # Model đề thi
│   │   ├── Phongthi_m.php         # Model phòng thi
│   │   └── LamBai_m.php           # Model làm bài thi
│   │
│   └── Views/                     # Các file giao diện
│       ├── layout_admin.php       # Layout cho admin/gv
│       ├── layout_hv.php          # Layout cho học viên
│       ├── layout_hv_blank.php    # Layout trống cho trang làm bài
│       ├── layout_login.php       # Layout trang đăng nhập
│       └── Pages/                 # Các trang con
│           ├── login.php          # Trang đăng nhập
│           ├── dashboard.php      # Dashboard thống kê
│           ├── tk_*.php           # Trang quản lý tài khoản
│           ├── lh_*.php           # Trang quản lý lớp học
│           ├── mt_*.php           # Trang quản lý môn thi
│           ├── ch_*.php           # Trang quản lý câu hỏi
│           ├── dt_*.php           # Trang quản lý đề thi
│           ├── pt_*.php           # Trang quản lý phòng thi
│           └── hv_*.php           # Trang học viên
│
└── public/                        # Thư mục public (entry point)
    ├── index.php                  # Entry point của ứng dụng
    └── assets/
        └── style.css              # CSS styling
```

---

## Cấu Hình

### File `Core/config.php`

```php
<?php
// Thiết lập múi giờ Việt Nam
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Base URL của ứng dụng
define("BASE_URL", "/thitracnghiem/public");

// Cấu hình Database
define("DB_HOST", "localhost");
define("DB_NAME", "thitracnghiem");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_PORT", 3306);
```

### File `Core/Database.php`

```php
<?php
class Database
{
  protected $con;
  
  public function __construct()
  {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $this->con = new PDO($dsn, DB_USER, DB_PASS, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
  }

  public function getConnection()
  {
    return $this->con;
  }
}
```

---

## Cơ Sở Dữ Liệu

### Sơ đồ quan hệ (ERD)

```
users (Admin/Giảng viên)
  └── lop_hoc (Lớp học)
        └── hoc_vien (Học viên)
              └── phong_thi_hoc_vien (Tham gia phòng thi)
              └── bai_lam (Bài làm)
                    └── bai_lam_ct (Chi tiết bài làm)

mon_thi (Môn thi)
  └── cau_hoi (Câu hỏi)
  └── de_thi (Đề thi)
        └── de_thi_cau_hoi (Liên kết đề - câu hỏi)
        └── phong_thi (Phòng thi)
```

### Script khởi tạo CSDL

> **Chi tiết CSDL mẫu tại:** [here](https://github.com/manhdzzz/mvc-system-utt/blob/main/thitracnghiem.sql)

```sql
-- ===========================================
-- DATABASE: thitracnghiem
-- ===========================================

CREATE DATABASE IF NOT EXISTS thitracnghiem
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE thitracnghiem;

-- ===========================================
-- BẢNG 1: users (Tài khoản Admin/Giảng viên)
-- ===========================================
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  hoten VARCHAR(255) NOT NULL COMMENT 'Họ tên',
  username VARCHAR(100) NOT NULL UNIQUE COMMENT 'Tên đăng nhập',
  email VARCHAR(255) DEFAULT NULL COMMENT 'Email',
  password_hash VARCHAR(255) NOT NULL COMMENT 'Mật khẩu đã hash (bcrypt)',
  trangthai TINYINT DEFAULT 1 COMMENT 'Trạng thái: 1=Hoạt động, 0=Khóa',
  role ENUM('admin', 'gv') DEFAULT 'admin' COMMENT 'Vai trò: admin hoặc gv',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Dữ liệu mẫu: Admin mặc định (password: admin123)
INSERT INTO users (hoten, username, email, password_hash, trangthai, role)
VALUES ('Quản trị viên', 'admin', 'admin@utt.edu.vn', 
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 'admin');
-- Lưu ý: password_hash trên là bcrypt của "password" 
-- Để tạo hash mới: echo password_hash("admin123", PASSWORD_BCRYPT);

-- ===========================================
-- BẢNG 2: lop_hoc (Lớp học)
-- ===========================================
CREATE TABLE lop_hoc (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ma_lop VARCHAR(50) NOT NULL UNIQUE COMMENT 'Mã lớp',
  ten_lop VARCHAR(255) NOT NULL COMMENT 'Tên lớp',
  trangthai TINYINT DEFAULT 1 COMMENT 'Trạng thái: 1=Hoạt động, 0=Khóa',
  nguoi_tao VARCHAR(255) DEFAULT NULL COMMENT 'Người tạo',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ===========================================
-- BẢNG 3: hoc_vien (Học viên)
-- ===========================================
CREATE TABLE hoc_vien (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ma_hv VARCHAR(50) NOT NULL UNIQUE COMMENT 'Mã học viên (dùng để đăng nhập)',
  hoten VARCHAR(255) NOT NULL COMMENT 'Họ tên học viên',
  matkhau VARCHAR(255) DEFAULT NULL COMMENT 'Mật khẩu (plaintext hoặc hash)',
  password_hash VARCHAR(255) DEFAULT NULL COMMENT 'Mật khẩu đã hash',
  lop_id INT NOT NULL COMMENT 'ID lớp học',
  trangthai TINYINT DEFAULT 1 COMMENT 'Trạng thái: 1=Hoạt động, 0=Khóa',
  created_by INT DEFAULT NULL COMMENT 'ID người tạo',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (lop_id) REFERENCES lop_hoc(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ===========================================
-- BẢNG 4: mon_thi (Môn thi)
-- ===========================================
CREATE TABLE mon_thi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ma_mon VARCHAR(50) NOT NULL UNIQUE COMMENT 'Mã môn (VD: TOAN, LY, HOA)',
  ten_mon VARCHAR(255) NOT NULL COMMENT 'Tên môn thi',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ===========================================
-- BẢNG 5: cau_hoi (Câu hỏi)
-- ===========================================
CREATE TABLE cau_hoi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  mon_id INT NOT NULL COMMENT 'ID môn thi',
  noi_dung TEXT NOT NULL COMMENT 'Nội dung câu hỏi',
  dap_an_a TEXT NOT NULL COMMENT 'Đáp án A',
  dap_an_b TEXT NOT NULL COMMENT 'Đáp án B',
  dap_an_c TEXT NOT NULL COMMENT 'Đáp án C',
  dap_an_d TEXT NOT NULL COMMENT 'Đáp án D',
  dap_an_dung CHAR(1) NOT NULL COMMENT 'Đáp án đúng: A, B, C hoặc D',
  diem FLOAT DEFAULT 1 COMMENT 'Điểm của câu hỏi',
  giai_thich TEXT DEFAULT NULL COMMENT 'Giải thích đáp án',
  loai ENUM('D', 'TB', 'K') DEFAULT 'TB' COMMENT 'Độ khó: D=Dễ, TB=Trung bình, K=Khó',
  kich_hoat TINYINT DEFAULT 1 COMMENT 'Kích hoạt: 1=Có, 0=Không',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (mon_id) REFERENCES mon_thi(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ===========================================
-- BẢNG 6: de_thi (Đề thi)
-- ===========================================
CREATE TABLE de_thi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ma_de VARCHAR(50) NOT NULL UNIQUE COMMENT 'Mã đề thi',
  ten_de VARCHAR(255) NOT NULL COMMENT 'Tên đề thi',
  thoi_gian INT NOT NULL COMMENT 'Thời gian làm bài (phút)',
  mon_id INT NOT NULL COMMENT 'ID môn thi',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (mon_id) REFERENCES mon_thi(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ===========================================
-- BẢNG 7: de_thi_cau_hoi (Liên kết đề thi - câu hỏi)
-- ===========================================
CREATE TABLE de_thi_cau_hoi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  de_id INT NOT NULL COMMENT 'ID đề thi',
  cauhoi_id INT NOT NULL COMMENT 'ID câu hỏi',
  UNIQUE KEY unique_de_cauhoi (de_id, cauhoi_id),
  FOREIGN KEY (de_id) REFERENCES de_thi(id) ON DELETE CASCADE,
  FOREIGN KEY (cauhoi_id) REFERENCES cau_hoi(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ===========================================
-- BẢNG 8: phong_thi (Phòng thi)
-- ===========================================
CREATE TABLE phong_thi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ma_phong VARCHAR(50) NOT NULL UNIQUE COMMENT 'Mã phòng thi',
  ten_phong VARCHAR(255) NOT NULL COMMENT 'Tên phòng thi',
  mon_id INT NOT NULL COMMENT 'ID môn thi',
  de_id INT NOT NULL COMMENT 'ID đề thi',
  lop_id INT NOT NULL COMMENT 'ID lớp học tham gia',
  bat_dau DATETIME DEFAULT NULL COMMENT 'Thời gian bắt đầu thi',
  nguoi_tao VARCHAR(255) DEFAULT NULL COMMENT 'Người tạo phòng',
  trangthai TINYINT DEFAULT 1 COMMENT 'Trạng thái: 1=Hoạt động, 0=Khóa',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (mon_id) REFERENCES mon_thi(id) ON DELETE CASCADE,
  FOREIGN KEY (de_id) REFERENCES de_thi(id) ON DELETE CASCADE,
  FOREIGN KEY (lop_id) REFERENCES lop_hoc(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ===========================================
-- BẢNG 9: phong_thi_hoc_vien (Học viên trong phòng thi)
-- ===========================================
CREATE TABLE phong_thi_hoc_vien (
  id INT AUTO_INCREMENT PRIMARY KEY,
  phong_id INT NOT NULL COMMENT 'ID phòng thi',
  hocvien_id INT NOT NULL COMMENT 'ID học viên',
  kich_hoat TINYINT DEFAULT 1 COMMENT 'Kích hoạt: 1=Được thi, 0=Bị khóa',
  trang_thai ENUM('Chưa thi', 'Đang thi', 'Đã nộp', 'Hủy') DEFAULT 'Chưa thi',
  thoi_gian_vao DATETIME DEFAULT NULL COMMENT 'Thời gian vào phòng thi',
  diem FLOAT DEFAULT 0 COMMENT 'Điểm đạt được',
  cau_dung INT DEFAULT 0 COMMENT 'Số câu trả lời đúng',
  lam_lai INT DEFAULT 0 COMMENT 'Số lần làm lại',
  so_lan_vi_pham INT DEFAULT 0 COMMENT 'Số lần vi phạm',
  tru FLOAT DEFAULT 0 COMMENT 'Số điểm bị trừ do vi phạm',
  con_lai FLOAT DEFAULT 0 COMMENT 'Điểm còn lại sau khi trừ',
  ghi_chu TEXT DEFAULT NULL COMMENT 'Ghi chú của giám thị',
  UNIQUE KEY unique_phong_hv (phong_id, hocvien_id),
  FOREIGN KEY (phong_id) REFERENCES phong_thi(id) ON DELETE CASCADE,
  FOREIGN KEY (hocvien_id) REFERENCES hoc_vien(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ===========================================
-- BẢNG 10: bai_lam (Bài làm của học viên)
-- ===========================================
CREATE TABLE bai_lam (
  id INT AUTO_INCREMENT PRIMARY KEY,
  phong_id INT NOT NULL COMMENT 'ID phòng thi',
  hocvien_id INT NOT NULL COMMENT 'ID học viên',
  de_id INT NOT NULL COMMENT 'ID đề thi',
  start_at DATETIME DEFAULT NULL COMMENT 'Thời điểm bắt đầu làm',
  end_at DATETIME DEFAULT NULL COMMENT 'Thời điểm nộp bài',
  total_cnt INT DEFAULT 0 COMMENT 'Tổng số câu hỏi',
  correct_cnt INT DEFAULT 0 COMMENT 'Số câu trả lời đúng',
  score FLOAT DEFAULT 0 COMMENT 'Điểm đạt được',
  status ENUM('Doing', 'Done') DEFAULT 'Doing' COMMENT 'Trạng thái bài làm',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (phong_id) REFERENCES phong_thi(id) ON DELETE CASCADE,
  FOREIGN KEY (hocvien_id) REFERENCES hoc_vien(id) ON DELETE CASCADE,
  FOREIGN KEY (de_id) REFERENCES de_thi(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ===========================================
-- BẢNG 11: bai_lam_ct (Chi tiết bài làm - từng câu)
-- ===========================================
CREATE TABLE bai_lam_ct (
  id INT AUTO_INCREMENT PRIMARY KEY,
  bailam_id INT NOT NULL COMMENT 'ID bài làm',
  cauhoi_id INT NOT NULL COMMENT 'ID câu hỏi',
  chon CHAR(1) DEFAULT NULL COMMENT 'Đáp án học viên chọn: A, B, C, D hoặc NULL',
  dung TINYINT DEFAULT 0 COMMENT 'Đúng: 1=Đúng, 0=Sai',
  diem FLOAT DEFAULT 0 COMMENT 'Điểm của câu này',
  phan_van TINYINT DEFAULT 0 COMMENT 'Đánh dấu phân vân: 1=Có, 0=Không',
  FOREIGN KEY (bailam_id) REFERENCES bai_lam(id) ON DELETE CASCADE,
  FOREIGN KEY (cauhoi_id) REFERENCES cau_hoi(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ===========================================
-- INDEX để tối ưu query
-- ===========================================
CREATE INDEX idx_hocvien_lop ON hoc_vien(lop_id);
CREATE INDEX idx_cauhoi_mon ON cau_hoi(mon_id);
CREATE INDEX idx_cauhoi_loai ON cau_hoi(loai);
CREATE INDEX idx_dethi_mon ON de_thi(mon_id);
CREATE INDEX idx_phongthi_lop ON phong_thi(lop_id);
CREATE INDEX idx_bailam_phong ON bai_lam(phong_id);
CREATE INDEX idx_bailam_hv ON bai_lam(hocvien_id);
CREATE INDEX idx_bailam_status ON bai_lam(status);
```

---

## Kiến Trúc MVC

### Entry Point (`public/index.php`)

```php
<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__."/../Core/config.php";
require_once __DIR__."/../Core/Database.php";
require_once __DIR__."/../Core/Controller.php";
require_once __DIR__."/../Core/App.php";

new App();
```

### Router (`Core/App.php`)

```php
<?php
class App {
  public function __construct(){
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    // Parse URL: ?url=ControllerName/action/param1/param2
    $url = $_GET["url"] ?? "AuthController/login";
    $arr = explode("/", trim($url, "/"));

    $controller = $arr[0] ?: "AuthController";
    $action     = $arr[1] ?? "login";
    $params     = array_slice($arr, 2);

    // Load controller
    $file = __DIR__."/../MVC/Controllers/$controller.php";
    if(!file_exists($file)) die("Không tìm thấy Controller: $controller");

    require_once $file;
    $c = new $controller;
    if(!method_exists($c, $action)) die("Không tìm thấy Action: $action");

    // Call action with params
    call_user_func_array([$c, $action], $params);
  }
}
```

### Base Controller (`Core/Controller.php`)

```php
<?php
class Controller
{
  // Load model
  public function model($name)
  {
    require_once __DIR__ . "/../MVC/Models/$name.php";
    return new $name;
  }

  // Load view với layout
  public function view($layout, $data = [])
  {
    require_once __DIR__ . "/../MVC/Views/$layout.php";
  }

  // Flash message (hiển thị 1 lần)
  protected function flash($k, $v)
  {
    $_SESSION["_flash"][$k] = $v;
  }

  protected function getFlash($k)
  {
    $v = $_SESSION["_flash"][$k] ?? "";
    unset($_SESSION["_flash"][$k]);
    return $v;
  }

  // Middleware xác thực
  protected function needLogin()
  {
    if (empty($_SESSION["user"])) {
      header("Location: " . BASE_URL . "/index.php?url=AuthController/login");
      exit;
    }
  }

  protected function needAdmin()
  {
    $this->needLogin();
    $role = $_SESSION["user"]["role"] ?? "";
    if ($role !== "admin") {
      die("Bạn không có quyền truy cập (Admin only)!");
    }
  }

  protected function needGV()
  {
    $this->needLogin();
    $role = $_SESSION["user"]["role"] ?? "";
    if ($role !== "admin" && $role !== "gv") {
      die("Bạn không có quyền truy cập!");
    }
  }

  protected function needHV()
  {
    if (empty($_SESSION["hv"])) {
      header("Location: " . BASE_URL . "/index.php?url=AuthController/login");
      exit;
    }
  }
}
```

---

## Các Chức Năng Chính

### 1. Hệ thống đăng nhập (AuthController)

Hỗ trợ 3 loại người dùng:
- **Admin/GV**: Đăng nhập bằng username/password trong bảng `users`
- **Học viên**: Đăng nhập bằng mã học viên (`ma_hv`) và mật khẩu

```php
// Logic xác thực
public function doLogin()
{
  $u = trim($_POST["username"] ?? "");
  $p = $_POST["password"] ?? "";

  // 1. Kiểm tra trong bảng users (Admin/GV)
  $user = $this->model("User_m")->findByUsername($u);
  if ($user) {
    // Hỗ trợ cả bcrypt hash và plaintext
    if (strpos($dbPass, '$2y$') === 0) {
      $ok = password_verify($p, $dbPass);
    } else {
      $ok = ($p === $dbPass);
    }
    
    if ($ok && $user["trangthai"] === 1) {
      $_SESSION["user"] = [...];
      // Redirect theo role
    }
  }

  // 2. Kiểm tra trong bảng hoc_vien
  $hv = $this->model("Hocvien_m")->findByMaHV($u);
  // Tương tự...
}
```

### 2. Dashboard thống kê (DashboardController)

Hiển thị các số liệu thống kê:
- Tổng số học viên
- Tổng số lớp học
- Tổng số môn thi
- Tổng số đề thi
- Tổng số câu hỏi
- Tổng số phòng thi
- Số bài làm đã hoàn thành
- Số học viên đang thi

### 3. Quản lý câu hỏi (CauhoiController)

**Các chức năng:**
- Liệt kê câu hỏi theo môn, tìm kiếm, phân trang
- Thêm/sửa/xóa câu hỏi
- Import câu hỏi từ file CSV
- Tải file mẫu CSV

**Cấu trúc file CSV import:**
```
ma_mon,noi_dung,A,B,C,D,dap_an,diem,giai_thich,loai,kich_hoat
TOAN,2+4=?,6,7,8,9,A,1,,D,1
```

**Logic phân loại độ khó:**
- `D` = Dễ
- `TB` = Trung bình  
- `K` = Khó

### 4. Tạo đề thi tự động (DethiController)

Đề thi được tạo bằng cách chọn ngẫu nhiên câu hỏi theo loại:

```php
public function store()
{
  $soD = (int) ($_POST["so_de"] ?? 0);   // Số câu Dễ
  $soTB = (int) ($_POST["so_tb"] ?? 0);  // Số câu Trung bình
  $soK = (int) ($_POST["so_kho"] ?? 0);   // Số câu Khó

  // Chọn ngẫu nhiên từ ngân hàng câu hỏi
  $idsD = $m->pickQuestions($mon_id, "D", $soD);
  $idsTB = $m->pickQuestions($mon_id, "TB", $soTB);
  $idsK = $m->pickQuestions($mon_id, "K", $soK);

  // Tạo đề và liên kết câu hỏi
  $de_id = $m->insertDe($ma, $ten, $time, $mon_id);
  $m->addQuestionsToDe($de_id, $idsD);
  $m->addQuestionsToDe($de_id, $idsTB);
  $m->addQuestionsToDe($de_id, $idsK);
}
```

### 5. Quản lý phòng thi (PhongthiController)

**Luồng tạo phòng thi:**
1. Admin chọn lớp, môn thi, đề thi
2. Thiết lập thời gian bắt đầu
3. Hệ thống tự động thêm tất cả học viên của lớp vào phòng

**Các chức năng giám sát:**
- **Kích hoạt/Khóa học viên**: Cho phép hoặc cấm thi
- **Reset làm lại**: Cho học viên thi lại
- **Hủy bài**: Hủy bài làm của học viên
- **Ghi chú**: Thêm ghi chú cho học viên
- **Ghi nhận vi phạm**: Theo dõi và xử lý vi phạm

### 6. Hệ thống làm bài thi (HocvienController)

**Luồng làm bài:**

```php
// 1. Học viên xác nhận vào phòng thi
public function confirm($phong_id)
{
  // Kiểm tra giờ thi
  if (strtotime($phong["bat_dau"]) > time()) {
    $this->flash("err", "Chưa đến giờ thi");
    return;
  }
  
  // Kiểm tra lớp có bị khóa không
  // Kiểm tra học viên có được kích hoạt không
}

// 2. Bắt đầu làm bài
public function start()
{
  // Chọn câu hỏi ngẫu nhiên theo cấu hình đề
  $qs = $m->pickQuestions($phong);
  
  // Tạo bài làm
  $bailam_id = $m->createBaiLam($phong_id, $hv_id, $de_id, count($qs));
  
  // Tạo chi tiết bài làm cho từng câu
  foreach ($qs as $q) {
    $m->insertBaiLamCT($bailam_id, $q["id"]);
  }
  
  // Cập nhật trạng thái "Đang thi"
  $m->setVaoPhongTime($phong_id, $hv_id);
}

// 3. Lưu đáp án (AJAX)
public function saveAnswer()
{
  // Lưu đáp án tức thì vào database
  $m->saveAnswer($bailam_id, $cauhoi_id, $answer);
  
  // Trả về thời gian còn lại
  return ["remaining" => $remaining];
}

// 4. Nộp bài
public function submit()
{
  // Chấm điểm từng câu
  foreach ($qs as $q) {
    $dung = ($chon === $q["dap_an"]) ? 1 : 0;
    $diem = $dung ? $q["diem"] : 0;
    // Cập nhật bai_lam_ct
  }
  
  // Cập nhật tổng điểm
  $st->execute([$score, $correct, $bailam_id]);
  
  // Cập nhật trạng thái trong phong_thi_hoc_vien
  $m->updatePhongHVAfterSubmit($phong_id, $hv_id, $kq);
}
```

### 7. Hệ thống theo dõi vi phạm

**Logic xử lý vi phạm:**

```php
public function tangViPham($phong_id, $hocvien_id)
{
  $so_vp = $row["so_lan_vi_pham"] + 1;
  $diem = $row["diem"];

  // Trừ 1 điểm mỗi 3 lần vi phạm
  $tru = floor($so_vp / 3);
  $con_lai = max(0, $diem - $tru);

  // Nếu vi phạm > 3 lần: Cấm thi
  if ($so_vp > 3) {
    // Khóa tài khoản, tự động nộp bài
    // Trạng thái = "Đã nộp"
    // Ghi chú = "Vi phạm quy chế thi - Bị cấm thi"
  }

  return [
    "so_vp" => $so_vp,
    "tru" => $tru,
    "con_lai" => $con_lai,
    "cam_thi" => ($so_vp > 3)
  ];
}
```

**Phát hiện vi phạm từ client (JavaScript):**
- Chuyển tab (visibility change)
- Thoát khỏi cửa sổ thi

---

## Hướng Dẫn Sử Dụng

### Dành cho Admin

1. **Đăng nhập**: Truy cập `/public/` và đăng nhập với tài khoản admin
2. **Tạo lớp học**: Menu "Lớp Học" → Thêm mới
3. **Thêm học viên**: Menu "Học Viên" → Chọn lớp → Thêm mới hoặc Import CSV
4. **Tạo môn thi**: Menu "Môn Thi" → Thêm mới
5. **Nhập câu hỏi**: Menu "Câu Hỏi" → Import file CSV (có thể tải mẫu)
6. **Tạo đề thi**: Menu "Đề Thi" → Chọn số câu theo độ khó
7. **Tạo phòng thi**: Menu "Phòng Thi" → Chọn lớp, đề, thời gian bắt đầu
8. **Giám sát**: Vào phòng thi để theo dõi tiến trình

### Dành cho Học viên

1. **Đăng nhập**: Nhập mã học viên và mật khẩu
2. **Xem phòng thi**: Danh sách các phòng thi được phân công
3. **Vào thi**: Nhấn "Vào thi" khi đến giờ
4. **Làm bài**: Chọn đáp án, đánh dấu phân vân nếu cần
5. **Nộp bài**: Nhấn nút nộp bài hoặc hết giờ tự động nộp
6. **Xem kết quả**: Kiểm tra điểm và đáp án sau khi nộp

---

## Logic Nghiệp Vụ Chi Tiết

### Tính thời gian còn lại

```php
public function getRemainingTime($bailam_id)
{
  $st = $this->con->prepare("
    SELECT 
      d.thoi_gian * 60 as duration_seconds,
      TIMESTAMPDIFF(SECOND, bl.start_at, NOW()) as elapsed_seconds
    FROM bai_lam bl
    JOIN de_thi d ON d.id=bl.de_id
    WHERE bl.id=?
  ");
  $st->execute([$bailam_id]);
  $row = $st->fetch();

  $remaining = $row["duration_seconds"] - $row["elapsed_seconds"];
  return max(0, $remaining);
}
```

### Tự động nộp bài khi hết giờ

Khi học viên vào trang làm bài, hệ thống kiểm tra:

```php
if ($time_remaining <= 0) {
  // Thu thập các đáp án đã lưu
  $answers = [];
  foreach ($answered as $cid => $ans) {
    if ($ans) $answers[$cid] = $ans;
  }
  
  // Tự động nộp bài
  $kq = $m->submit($bailam_id, $answers);
  $m->updatePhongHVAfterSubmit($phong_id, $hv_id, $kq);
  
  $this->flash("err", "Hết giờ làm bài! Hệ thống đã tự động nộp bài.");
}
```

### Chấm điểm bài làm

```php
public function submit($bailam_id, $answers)
{
  $qs = $this->getQuestionsOfBaiLam($bailam_id);
  $correct = 0;
  $score = 0;

  foreach ($qs as $q) {
    $cid = $q["cauhoi_id"];
    $chon = strtoupper(trim($answers[$cid] ?? ""));
    
    // Kiểm tra đáp án hợp lệ
    if (!in_array($chon, ["A", "B", "C", "D"])) {
      $chon = null;
    }

    // So sánh với đáp án đúng
    $dung = ($chon && $chon === strtoupper($q["dap_an"])) ? 1 : 0;
    $diem = $dung ? (float) $q["diem"] : 0;

    if ($dung) {
      $correct++;
      $score += $diem;
    }

    // Cập nhật chi tiết bài làm
    $this->con->prepare("
      UPDATE bai_lam_ct SET chon=?, dung=?, diem=? 
      WHERE bailam_id=? AND cauhoi_id=?
    ")->execute([$chon, $dung, $diem, $bailam_id, $cid]);
  }

  // Cập nhật bài làm
  $this->con->prepare("
    UPDATE bai_lam 
    SET end_at=NOW(), score=?, correct_cnt=?, status='Done' 
    WHERE id=?
  ")->execute([$score, $correct, $bailam_id]);

  return ["score" => $score, "correct" => $correct, "total" => count($qs)];
}
```

### Cascade Delete

Khi xóa dữ liệu, hệ thống xóa tất cả dữ liệu liên quan:

**Xóa lớp học → Xóa:**
- Học viên thuộc lớp
- Bài làm của học viên
- Chi tiết bài làm
- Phòng thi của lớp
- Học viên trong phòng thi

**Xóa môn thi → Xóa:**
- Câu hỏi thuộc môn
- Đề thi thuộc môn
- Liên kết đề-câu hỏi
- Phòng thi thuộc môn
- Và tất cả dữ liệu liên quan

---

## API Endpoints

### Format URL
```
/public/index.php?url=ControllerName/action/param1/param2
```

### Danh sách endpoints chính

| Controller | Action | Mô tả |
|------------|--------|-------|
| AuthController | login | Trang đăng nhập |
| AuthController | doLogin | Xử lý đăng nhập (POST) |
| AuthController | logout | Đăng xuất |
| DashboardController | index | Dashboard thống kê |
| TaikhoanController | index | Danh sách tài khoản |
| TaikhoanController | create | Form tạo tài khoản |
| TaikhoanController | store | Lưu tài khoản (POST) |
| TaikhoanController | edit/{id} | Form sửa tài khoản |
| TaikhoanController | update | Cập nhật tài khoản (POST) |
| TaikhoanController | delete/{id} | Xóa tài khoản |
| TaikhoanController | template | Tải mẫu CSV |
| TaikhoanController | import | Import từ CSV (POST) |
| LophocController | * | CRUD lớp học |
| MonthiController | * | CRUD môn thi |
| CauhoiController | * | CRUD câu hỏi |
| DethiController | * | CRUD đề thi |
| PhongthiController | index | Danh sách phòng thi |
| PhongthiController | store | Tạo phòng thi (POST) |
| PhongthiController | vaoPhong/{id} | Quản lý học viên trong phòng |
| PhongthiController | monitor/{id} | Giám sát thời gian thực |
| PhongthiController | kichhoat | Kích hoạt học viên (POST) |
| PhongthiController | vipham | Ghi nhận vi phạm (POST) |
| PhongthiController | exportExcel/{id} | Xuất kết quả CSV |
| HocvienAdminController | * | Admin quản lý học viên |
| HocvienController | phongthi | Danh sách phòng thi (HV) |
| HocvienController | confirm/{id} | Xác nhận vào thi |
| HocvienController | start | Bắt đầu làm bài (POST) |
| HocvienController | do/{id} | Trang làm bài |
| HocvienController | saveAnswer | Lưu đáp án (AJAX POST) |
| HocvienController | saveViolation | Ghi vi phạm (AJAX POST) |
| HocvienController | markReview | Đánh dấu phân vân (AJAX POST) |
| HocvienController | submit | Nộp bài (POST) |
| HocvienController | result/{id} | Xem kết quả |
| HocvienController | history | Lịch sử thi |

---

## Ghi chú kỹ thuật

### Bảo mật
- Sử dụng PDO prepared statements để chống SQL Injection
- Session-based authentication
- Role-based access control (Admin, GV, HV)

### Hiệu năng
- Phân trang mặc định 50 items/trang
- Indexes trên các cột thường query
- Sử dụng `INSERT IGNORE` để tránh lỗi duplicate

### Khả năng mở rộng
- Kiến trúc MVC dễ bảo trì
- Models kế thừa từ Database class
- Views sử dụng layout pattern
- Hỗ trợ import/export CSV

---

## Tác giả

**MENJMOI EPU - DEV For UTT**

© 2026 MENJMOI All Rights Reserved
