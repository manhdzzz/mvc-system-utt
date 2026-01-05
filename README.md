# BÀI TẬP LỚN: HỆ THỐNG THI TRẮC NGHIỆM TRỰC TUYẾN - UTT

Lưu ý quan trọng: Đây chỉ là hệ thống tham khảo theo mô hình mvc, không phải Hệ thống thi trắc nghiệm trực tuyến được phát triển cho **Trường Đại học Công nghệ GTVT (UTT)**.

## Mục lục

- [Giới thiệu](#giới-thiệu)
- [Công nghệ sử dụng](#công-nghệ-sử-dụng)
- [Cấu trúc dự án](#cấu-trúc-dự-án)
- [Cài đặt](#cài-đặt)
- [Database Schema](#database-schema)
- [Controllers](#controllers)
- [Models](#models)
- [Views](#views)
- [Tính năng](#tính-năng)
- [Hướng dẫn sử dụng](#hướng-dẫn-sử-dụng)

---

## Giới thiệu

Hệ thống cho phép:
- **Admin**: Quản lý môn thi, đề thi, câu hỏi, học viên, lớp học, phòng thi, tài khoản
- **Học viên**: Đăng nhập, làm bài thi trắc nghiệm, xem kết quả

### Đặc điểm nổi bật:
- Giao diện Dark Blue hiện đại
- Responsive trên mọi thiết bị
- Anti-cheat protection (chặn copy, F12)
- Auto role detection (tự động phát hiện Admin/Học viên)
- Import/Export CSV
- Real-time answer saving

---

## Công nghệ sử dụng

| Công nghệ | Phiên bản | Mô tả |
|-----------|-----------|-------|
| PHP | 7.4+ | Backend |
| MySQL | 5.7+ / MariaDB | Database |
| PDO | - | Database Driver |
| CSS3 | - | Styling (Dark Blue Theme) |
| JavaScript | ES6+ | Frontend interactions |
| SweetAlert2 | 11.x | Notifications |
| XAMPP | - | Local development |

---

## Cấu trúc dự án

```
thitracnghiem/
├── Core/                       # Core framework
│   ├── App.php                 # Router & dispatcher
│   ├── Auth.php                # Authentication helper
│   ├── config.php              # Database configuration
│   ├── Controller.php          # Base controller class
│   └── Database.php            # PDO connection
│
├── MVC/
│   ├── Controllers/            # 10 Controllers
│   │   ├── AuthController.php          # Login/Logout
│   │   ├── DashboardController.php     # Dashboard thống kê
│   │   ├── MonthiController.php        # Quản lý môn thi
│   │   ├── DethiController.php         # Quản lý đề thi
│   │   ├── CauhoiController.php        # Quản lý câu hỏi
│   │   ├── PhongthiController.php      # Quản lý phòng thi
│   │   ├── LophocController.php        # Quản lý lớp học
│   │   ├── HocvienAdminController.php  # Admin quản lý học viên
│   │   ├── HocvienController.php       # Học viên làm bài thi
│   │   └── TaikhoanController.php      # Quản lý tài khoản
│   │
│   ├── Models/                 # 8 Models
│   │   ├── User_m.php          # users table
│   │   ├── Hocvien_m.php       # hoc_vien table
│   │   ├── Monthi_m.php        # mon_thi table
│   │   ├── Dethi_m.php         # de_thi table
│   │   ├── Cauhoi_m.php        # cau_hoi table
│   │   ├── Phongthi_m.php      # phong_thi table
│   │   ├── Lophoc_m.php        # lop_hoc table
│   │   └── LamBai_m.php        # bai_lam, bai_lam_ct tables
│   │
│   └── Views/                  # Templates
│       ├── layout_admin.php    # Admin layout
│       ├── layout_login.php    # Login layout
│       ├── layout_hv.php       # Học viên layout
│       ├── layout_hv_blank.php # Trang làm bài
│       └── Pages/              # View pages
│
└── public/
    ├── index.php               # Entry point
    └── assets/
        └── style.css           # Dark Blue CSS
```

---

## Cài đặt

### Yêu cầu
- XAMPP / WAMP / LAMP với PHP 7.4+
- MySQL 5.7+ hoặc MariaDB 10+

### Bước 1: Clone project

```bash
cd C:\xampp\htdocs
git clone <repo-url> thitracnghiem
```

### Bước 2: Cấu hình database

Mở file `Core/config.php`:

```php
<?php
define("BASE_URL", "/thitracnghiem/public");

define("DB_HOST", "localhost");
define("DB_NAME", "thitracnghiem");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_PORT", 3306);
```

### Bước 3: Tạo database

Mở phpMyAdmin hoặc MySQL CLI và chạy:

```sql
CREATE DATABASE thitracnghiem CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE thitracnghiem;
```

### Bước 4: Tạo các bảng

```sql
-- ========================================
-- BẢNG USERS (Admin)
-- ========================================
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  hoten VARCHAR(255) NOT NULL,
  username VARCHAR(100) NOT NULL UNIQUE,
  email VARCHAR(255),
  password_hash VARCHAR(255) NOT NULL,
  trangthai TINYINT DEFAULT 1 COMMENT '1=Active, 0=Locked',
  role VARCHAR(50) DEFAULT 'admin',
  lop_id INT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tạo tài khoản admin mặc định (password: admin123)
INSERT INTO users (hoten, username, email, password_hash, trangthai, role) 
VALUES ('Administrator', 'admin', 'admin@utt.edu.vn', 'admin123', 1, 'admin');

-- ========================================
-- BẢNG LỚP HỌC
-- ========================================
CREATE TABLE lop_hoc (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ma_lop VARCHAR(50) NOT NULL UNIQUE,
  ten_lop VARCHAR(255) NOT NULL,
  trangthai TINYINT DEFAULT 1,
  nguoi_tao VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ========================================
-- BẢNG HỌC VIÊN
-- ========================================
CREATE TABLE hoc_vien (
  id INT AUTO_INCREMENT PRIMARY KEY,
  hoten VARCHAR(255) NOT NULL,
  ma_hv VARCHAR(50) NOT NULL UNIQUE COMMENT 'Mã học viên dùng để đăng nhập',
  matkhau VARCHAR(255) NOT NULL COMMENT 'Mật khẩu (plain hoặc bcrypt)',
  lop_id INT NOT NULL,
  trangthai TINYINT DEFAULT 1,
  created_by INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (lop_id) REFERENCES lop_hoc(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ========================================
-- BẢNG MÔN THI
-- ========================================
CREATE TABLE mon_thi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ma_mon VARCHAR(50) NOT NULL UNIQUE,
  ten_mon VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

-- ========================================
-- BẢNG CÂU HỎI
-- ========================================
CREATE TABLE cau_hoi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  mon_id INT NOT NULL,
  noi_dung TEXT NOT NULL COMMENT 'Nội dung câu hỏi',
  dap_an_a TEXT,
  dap_an_b TEXT,
  dap_an_c TEXT,
  dap_an_d TEXT,
  dap_an_dung CHAR(1) NOT NULL COMMENT 'A, B, C hoặc D',
  diem INT DEFAULT 1 COMMENT 'Điểm cho câu đúng',
  giai_thich TEXT COMMENT 'Giải thích đáp án',
  loai VARCHAR(10) DEFAULT 'TB' COMMENT 'D=Dễ, TB=Trung bình, K=Khó',
  kich_hoat TINYINT DEFAULT 1,
  FOREIGN KEY (mon_id) REFERENCES mon_thi(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ========================================
-- BẢNG ĐỀ THI
-- ========================================
CREATE TABLE de_thi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ma_de VARCHAR(50) NOT NULL UNIQUE,
  ten_de VARCHAR(255) NOT NULL,
  thoi_gian INT DEFAULT 30 COMMENT 'Thời gian làm bài (phút)',
  mon_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (mon_id) REFERENCES mon_thi(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ========================================
-- BẢNG LIÊN KẾT ĐỀ THI - CÂU HỎI
-- ========================================
CREATE TABLE de_thi_cau_hoi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  de_id INT NOT NULL,
  cauhoi_id INT NOT NULL,
  UNIQUE KEY (de_id, cauhoi_id),
  FOREIGN KEY (de_id) REFERENCES de_thi(id) ON DELETE CASCADE,
  FOREIGN KEY (cauhoi_id) REFERENCES cau_hoi(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ========================================
-- BẢNG PHÒNG THI
-- ========================================
CREATE TABLE phong_thi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ma_phong VARCHAR(50) NOT NULL UNIQUE,
  ten_phong VARCHAR(255) NOT NULL,
  mon_id INT NOT NULL,
  de_id INT NOT NULL,
  lop_id INT NOT NULL,
  bat_dau DATETIME COMMENT 'Thời gian bắt đầu thi',
  nguoi_tao VARCHAR(100),
  trangthai TINYINT DEFAULT 1 COMMENT '1=Mở, 0=Đóng',
  FOREIGN KEY (mon_id) REFERENCES mon_thi(id),
  FOREIGN KEY (de_id) REFERENCES de_thi(id),
  FOREIGN KEY (lop_id) REFERENCES lop_hoc(id)
) ENGINE=InnoDB;

-- ========================================
-- BẢNG PHÒNG THI - HỌC VIÊN
-- ========================================
CREATE TABLE phong_thi_hoc_vien (
  id INT AUTO_INCREMENT PRIMARY KEY,
  phong_id INT NOT NULL,
  hocvien_id INT NOT NULL,
  kich_hoat TINYINT DEFAULT 0 COMMENT '1=Được phép thi',
  diem DECIMAL(5,2) DEFAULT 0,
  cau_dung INT DEFAULT 0,
  trang_thai VARCHAR(50) DEFAULT 'Chưa thi' COMMENT 'Chưa thi, Đang thi, Đã nộp, Hủy',
  lam_lai INT DEFAULT 0 COMMENT 'Số lần thi lại',
  tru DECIMAL(5,2) DEFAULT 0,
  con_lai INT DEFAULT 0,
  ghi_chu TEXT,
  thoi_gian_vao DATETIME,
  UNIQUE KEY (phong_id, hocvien_id),
  FOREIGN KEY (phong_id) REFERENCES phong_thi(id) ON DELETE CASCADE,
  FOREIGN KEY (hocvien_id) REFERENCES hoc_vien(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ========================================
-- BẢNG BÀI LÀM
-- ========================================
CREATE TABLE bai_lam (
  id INT AUTO_INCREMENT PRIMARY KEY,
  phong_id INT NOT NULL,
  hocvien_id INT NOT NULL,
  de_id INT NOT NULL,
  start_at DATETIME NOT NULL,
  end_at DATETIME,
  total_cnt INT DEFAULT 0,
  correct_cnt INT DEFAULT 0,
  score DECIMAL(5,2) DEFAULT 0,
  status VARCHAR(20) DEFAULT 'Doing' COMMENT 'Doing, Done',
  FOREIGN KEY (phong_id) REFERENCES phong_thi(id),
  FOREIGN KEY (hocvien_id) REFERENCES hoc_vien(id),
  FOREIGN KEY (de_id) REFERENCES de_thi(id)
) ENGINE=InnoDB;

-- ========================================
-- BẢNG BÀI LÀM CHI TIẾT
-- ========================================
CREATE TABLE bai_lam_ct (
  id INT AUTO_INCREMENT PRIMARY KEY,
  bailam_id INT NOT NULL,
  cauhoi_id INT NOT NULL,
  chon CHAR(1) COMMENT 'A, B, C, D hoặc NULL',
  dung TINYINT DEFAULT 0,
  diem DECIMAL(5,2) DEFAULT 0,
  FOREIGN KEY (bailam_id) REFERENCES bai_lam(id) ON DELETE CASCADE,
  FOREIGN KEY (cauhoi_id) REFERENCES cau_hoi(id)
) ENGINE=InnoDB;
```

### Bước 5: Truy cập hệ thống

Mở trình duyệt:
```
http://localhost/thitracnghiem/public/
```

Đăng nhập Admin:
- Username: `admin`
- Password: `admin123`

---

## Database Schema

### Sơ đồ quan hệ

```
┌─────────────┐      ┌─────────────┐      ┌─────────────┐
│   users     │      │  lop_hoc    │◄─────│  hoc_vien   │
│  (Admin)    │      │  (Classes)  │      │ (Students)  │
└─────────────┘      └──────┬──────┘      └──────┬──────┘
                            │                     │
                            ▼                     │
                     ┌─────────────┐              │
                     │ phong_thi   │              │
                     │(Exam Rooms) │◄─────────────┘
                     └──────┬──────┘
                            │
              ┌─────────────┼─────────────┐
              ▼             ▼             ▼
       ┌───────────┐ ┌───────────┐ ┌─────────────────┐
       │ mon_thi   │ │  de_thi   │ │phong_thi_hoc_vien│
       │(Subjects) │ │  (Exams)  │ │(Student-Room Map)│
       └─────┬─────┘ └─────┬─────┘ └─────────────────┘
             │             │
             ▼             │
       ┌───────────┐       │
       │  cau_hoi  │◄──────┘
       │(Questions)│
       └───────────┘
             │
             ▼
     ┌───────────────┐
     │de_thi_cau_hoi │
     │(Exam-Question)│
     └───────────────┘
             │
             ▼
      ┌─────────────┐      ┌─────────────┐
      │  bai_lam    │──────│ bai_lam_ct  │
      │(Submissions)│      │  (Answers)  │
      └─────────────┘      └─────────────┘
```

---

## Controllers

### 1. AuthController
- `login()` - Hiển thị form đăng nhập
- `doLogin()` - Xử lý đăng nhập (auto detect Admin/Học viên)
- `logout()` - Đăng xuất

### 2. DashboardController
- `index()` - Hiển thị thống kê tổng quan

### 3. MonthiController
- `index()` - Danh sách môn thi
- `store()` - Thêm môn thi
- `edit($id)` - Sửa môn thi
- `update($id)` - Cập nhật môn thi
- `delete($id)` - Xóa môn thi
- `template()` - Tải file CSV mẫu
- `import()` - Import từ CSV

### 4. DethiController
- `index()` - Danh sách đề thi
- `store()` - Tạo đề thi (với random câu hỏi)
- `delete($id)` - Xóa đề thi
- `template()` - Tải file CSV mẫu
- `import()` - Import từ CSV

### 5. CauhoiController
- `index()` - Danh sách câu hỏi
- `create()` - Form thêm câu hỏi
- `store()` - Lưu câu hỏi mới
- `edit($id)` - Form sửa câu hỏi
- `update($id)` - Cập nhật câu hỏi
- `delete($id)` - Xóa câu hỏi
- `template()` - Tải file CSV mẫu
- `import()` - Import từ CSV

### 6. PhongthiController
- `index()` - Danh sách phòng thi
- `store()` - Tạo phòng thi
- `edit($id)` - Sửa phòng thi
- `update($id)` - Cập nhật
- `delete($id)` - Xóa
- `vaoPhong($id)` - Quản lý học viên trong phòng
- `toggle()` - Bật/tắt kích hoạt học viên
- `reset()` - Reset bài làm học viên
- `huy()` - Hủy bài thi
- `export($id)` - Xuất kết quả Excel
- `template()` - Tải file CSV mẫu
- `import()` - Import từ CSV

### 7. LophocController
- `index()` - Danh sách lớp học
- `create()` - Form thêm lớp
- `store()` - Lưu lớp mới
- `edit($id)` - Form sửa
- `update($id)` - Cập nhật
- `delete($id)` - Xóa
- `template()` - Tải file CSV mẫu
- `import()` - Import từ CSV

### 8. HocvienAdminController
- `index()` - Danh sách học viên
- `store()` - Thêm học viên
- `update($id)` - Cập nhật
- `delete($id)` - Xóa
- `template()` - Tải file CSV mẫu
- `import()` - Import từ CSV

### 9. HocvienController (Dành cho học viên)
- `phongthi()` - Danh sách phòng thi
- `confirm($phong_id)` - Xác nhận vào thi
- `start()` - Bắt đầu làm bài
- `do($bailam_id)` - Trang làm bài
- `saveAnswer()` - AJAX lưu đáp án
- `submit()` - Nộp bài
- `history()` - Lịch sử thi
- `result($bailam_id)` - Xem kết quả
- `logout()` - Đăng xuất

### 10. TaikhoanController
- `index()` - Danh sách tài khoản
- `create()` - Form thêm
- `store()` - Lưu tài khoản
- `edit($id)` - Form sửa
- `update($id)` - Cập nhật
- `delete($id)` - Xóa
- `template()` - Tải file CSV mẫu
- `import()` - Import từ CSV

---

## Models

### User_m.php
```php
findByUsername($u)     // Tìm admin theo username
getAll()               // Lấy tất cả tài khoản
find($id)              // Tìm theo ID
insert(...)            // Thêm tài khoản
updateBasic(...)       // Cập nhật
delete($id)            // Xóa
search($q)             // Tìm kiếm
```

### Hocvien_m.php
```php
findByMaHV($ma_hv)     // Tìm học viên theo mã (login)
getLops()              // Lấy danh sách lớp
search($lop_id, $q)    // Tìm kiếm học viên
insert(...)            // Thêm học viên
update(...)            // Cập nhật
delete($id)            // Xóa
```

### Monthi_m.php
```php
getAll()               // Lấy tất cả môn thi
list($q)               // Danh sách + thống kê
find($id)              // Tìm theo ID
insert($ma, $ten)      // Thêm môn
update($id, $ma, $ten) // Cập nhật
delete($id)            // Xóa
```

### Cauhoi_m.php
```php
list($mon_id, $q)      // Danh sách câu hỏi
find($id)              // Tìm câu hỏi
insert(...)            // Thêm câu hỏi
updateBasic(...)       // Cập nhật
delete($id)            // Xóa
```

### Dethi_m.php
```php
list($mon_id, $q)      // Danh sách đề thi
insertDe(...)          // Tạo đề thi
pickQuestions(...)     // Random câu hỏi theo loại
addQuestionsToDe(...)  // Gán câu hỏi vào đề
delete($id)            // Xóa đề
```

### LamBai_m.php
```php
listPhongThiByHocVien($hv_id)  // Phòng thi của học viên
phongInfo($phong_id)           // Thông tin phòng
pickQuestions($de)             // Lấy câu hỏi theo đề
createBaiLam(...)              // Tạo bài làm
insertBaiLamCT(...)            // Thêm câu hỏi vào bài
getQuestionsOfBaiLam(...)      // Lấy câu hỏi của bài
saveAnswer(...)                // Lưu đáp án (AJAX)
submit(...)                    // Nộp bài + chấm điểm
getRemainingTime(...)          // Thời gian còn lại
getAnsweredStatus(...)         // Trạng thái đã trả lời
listHistory($hv_id)            // Lịch sử thi
```

---

## Views

### Layouts
| File | Mô tả |
|------|-------|
| `layout_admin.php` | Layout admin với header, nav, footer |
| `layout_login.php` | Layout trang đăng nhập |
| `layout_hv.php` | Layout học viên với header, nav |
| `layout_hv_blank.php` | Layout làm bài (không nav) |

### Pages
| File | Controller | Mô tả |
|------|------------|-------|
| `login.php` | Auth | Trang đăng nhập |
| `dashboard.php` | Dashboard | Thống kê tổng quan |
| `mt_list.php` | Monthi | Danh sách môn thi |
| `mt_edit.php` | Monthi | Form sửa môn |
| `dt_list.php` | Dethi | Danh sách đề thi |
| `ch_list.php` | Cauhoi | Danh sách câu hỏi |
| `ch_form.php` | Cauhoi | Form thêm/sửa câu hỏi |
| `pt_list.php` | Phongthi | Danh sách phòng thi |
| `pt_edit.php` | Phongthi | Form sửa phòng |
| `pt_inside.php` | Phongthi | Quản lý HV trong phòng |
| `lh_list.php` | Lophoc | Danh sách lớp học |
| `lh_form.php` | Lophoc | Form thêm/sửa lớp |
| `hv_list.php` | HocvienAdmin | Danh sách học viên |
| `tk_list.php` | Taikhoan | Danh sách tài khoản |
| `tk_form.php` | Taikhoan | Form thêm/sửa TK |
| `hv_phong_list.php` | Hocvien | DS phòng thi (student) |
| `hv_confirm.php` | Hocvien | Xác nhận vào thi |
| `hv_do.php` | Hocvien | Trang làm bài |
| `hv_history.php` | Hocvien | Lịch sử thi |
| `hv_result.php` | Hocvien | Kết quả bài thi |

---

## Tính năng

### Admin Features
- Dashboard thống kê (học viên, lớp, môn, đề, câu hỏi, phòng thi, bài làm)
- CRUD đầy đủ cho tất cả entities
- Import/Export CSV
- Quản lý phòng thi real-time
- Kích hoạt/khóa học viên
- Reset bài làm, hủy bài
- Xuất kết quả Excel

### Student Features
- Đăng nhập bằng mã học viên
- Xem danh sách phòng thi
- Làm bài thi trắc nghiệm
- Auto-save đáp án (AJAX)
- Countdown timer
- Xem kết quả & giải thích
- Lịch sử thi

### Security Features
- Anti-cheat protection:
  - Chặn right-click
  - Chặn Ctrl+C, F12, Ctrl+U, Ctrl+Shift+I
  - Chặn text selection
  - SweetAlert cảnh báo vi phạm
- Session-based authentication
- Input validation & sanitization
- PDO prepared statements

---

## Hướng dẫn sử dụng

### Quy trình tạo bài thi

1. **Tạo Môn thi** (Quản lý Môn thi → Thêm mới)
2. **Tạo Câu hỏi** (Ngân hàng Câu hỏi → Thêm mới)
   - Chọn loại: D (Dễ), TB (Trung bình), K (Khó)
3. **Tạo Đề thi** (Quản lý Đề thi → Thêm mới)
   - Chọn môn, thời gian, số câu mỗi loại
4. **Tạo Lớp học** (Quản lý Lớp học → Thêm mới)
5. **Thêm Học viên** (Quản lý Học viên → Thêm mới/Import)
6. **Tạo Phòng thi** (Quản lý Phòng thi → Thêm mới)
   - Chọn lớp, môn, đề, thời gian
7. **Kích hoạt học viên** (Vào phòng → Bật kích hoạt)
8. **Học viên làm bài** (Đăng nhập → Làm bài → Nộp)
9. **Xem kết quả** (Vào phòng → Xem điểm)

### CSV Import Format

**Môn thi:**
```csv
ma_mon,ten_mon
TOAN,Toán học
LY,Vật lý
```

**Câu hỏi:**
```csv
noi_dung,dap_an_a,dap_an_b,dap_an_c,dap_an_d,dap_an_dung,diem,giai_thich,loai,kich_hoat
1+1=?,1,2,3,4,B,1,Vì 1+1=2,D,1
```

**Học viên:**
```csv
hoten,ma_hv,matkhau,trangthai
Nguyễn Văn A,SV001,123456,1
```

**Lớp học:**
```csv
ma_lop,ten_lop,trangthai
CNTT01,Công nghệ thông tin K01,1
```

---

## Bản quyền

© 2026 MENJMOI All Rights Reserved
