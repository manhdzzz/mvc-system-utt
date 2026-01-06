<?php
$u = $_SESSION["user"] ?? ["hoten" => "Khách"];
$page = $data["page"] ?? "";
?>
<!doctype html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Hệ thống thi trắc nghiệm UTT</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/style.css?v=3">
</head>

<body>

  <!-- HEADER -->
  <header class="site-header">
    <div class="header-top">
      <div class="header-logo">UTT EXAM SYSTEM</div>
      <div class="header-user">
        <span class="header-user-name"><?= htmlspecialchars($u["hoten"]) ?></span>
        <a href="<?= BASE_URL ?>/index.php?url=AuthController/logout" class="header-logout">Đăng xuất</a>
      </div>
    </div>

    <nav class="header-nav">
      <?php
      $act = $_GET["url"] ?? "DashboardController";
      $isV = function ($k) use ($act) {
        return (stripos($act, $k) !== false) ? "active" : ""; };
      $role = $u["role"] ?? "admin";
      ?>
      <a href="<?= BASE_URL ?>/index.php?url=DashboardController/index"
        class="nav-link <?= $isV('Dashboard') ?>">Dashboard</a>
      <a href="<?= BASE_URL ?>/index.php?url=PhongthiController/index" class="nav-link <?= $isV('Phongthi') ?>">Phòng
        Thi</a>

      <?php if ($role === 'admin'): ?>
        <a href="<?= BASE_URL ?>/index.php?url=DethiController/index" class="nav-link <?= $isV('Dethi') ?>">Đề Thi</a>
        <a href="<?= BASE_URL ?>/index.php?url=CauhoiController/index" class="nav-link <?= $isV('Cauhoi') ?>">Câu Hỏi</a>
        <a href="<?= BASE_URL ?>/index.php?url=MonthiController/index" class="nav-link <?= $isV('Monthi') ?>">Môn Thi</a>
        <a href="<?= BASE_URL ?>/index.php?url=HocvienAdminController/index"
          class="nav-link <?= $isV('HocvienAdmin') ?>">Học Viên</a>
        <a href="<?= BASE_URL ?>/index.php?url=LophocController/index" class="nav-link <?= $isV('Lophoc') ?>">Lớp Học</a>
        <a href="<?= BASE_URL ?>/index.php?url=TaikhoanController/index" class="nav-link <?= $isV('Taikhoan') ?>">Tài
          Khoản</a>
      <?php endif; ?>
    </nav>
  </header>

  <!-- MAIN CONTENT -->
  <main class="page-wrap">
    <?php require_once __DIR__ . "/" . $page . ".php"; ?>
  </main>

  <!-- FOOTER -->
  <footer class="site-footer">
    © 2026 UTT All Rights Reserved
  </footer>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Auto show notifications
      <?php if (!empty($data["msg"])): ?>
        Swal.fire({
          icon: 'success',
          title: 'Thành công!',
          text: <?= json_encode($data["msg"]) ?>,
          timer: 2500,
          showConfirmButton: false,
          background: '#1e293b',
          color: '#f1f5f9'
        });
      <?php endif; ?>

      <?php if (!empty($data["err"])): ?>
        Swal.fire({
          icon: 'error',
          title: 'Lỗi!',
          text: <?= json_encode($data["err"]) ?>,
          background: '#1e293b',
          color: '#f1f5f9'
        });
      <?php endif; ?>
    });

    // Confirm delete helper
    function confirmDelete(url, itemName) {
      Swal.fire({
        title: 'CẢNH BÁO MẤT DỮ LIỆU!',
        text: 'Hành động này sẽ xóa vĩnh viễn mục này và TOÀN BỘ dữ liệu liên quan (Bài làm, Câu hỏi, Lớp học...). Bạn có chắc chắn muốn tiếp tục?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#3b82f6',
        confirmButtonText: 'Xóa vĩnh viễn',
        cancelButtonText: 'Hủy',
        background: '#1e293b',
        color: '#f1f5f9'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = url;
        }
      });
      return false;
    }
  </script>
</body>

</html>