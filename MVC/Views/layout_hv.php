<?php $hv = $_SESSION["hv"] ?? ["hoten" => "Học viên"]; ?>
<!doctype html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Học viên - UTT Exam System</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/style.css?v=3">
  <style>
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      background: var(--bg-primary);
      color: var(--text-primary);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .hv-header {
      background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
      padding: 0;
      box-shadow: var(--shadow-lg);
    }

    .hv-header-top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 16px 24px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .hv-logo {
      font-size: 20px;
      font-weight: 800;
      color: #fff;
      letter-spacing: -0.5px;
    }

    .hv-nav {
      display: flex;
      gap: 8px;
      padding: 12px 24px;
      background: rgba(0, 0, 0, 0.1);
    }

    .hv-nav a {
      color: rgba(255, 255, 255, 0.85);
      text-decoration: none;
      padding: 10px 18px;
      border-radius: var(--radius-md);
      font-weight: 500;
      font-size: 14px;
      transition: var(--transition);
    }

    .hv-nav a:hover {
      background: rgba(255, 255, 255, 0.15);
      color: #fff;
    }

    .hv-nav a.active {
      background: rgba(255, 255, 255, 0.2);
      color: #fff;
    }

    .hv-user {
      display: flex;
      align-items: center;
      gap: 16px;
      color: rgba(255, 255, 255, 0.9);
    }

    .hv-user-name {
      font-weight: 600;
      font-size: 14px;
    }

    .hv-logout {
      background: rgba(255, 255, 255, 0.15);
      color: #fff;
      padding: 8px 16px;
      border-radius: var(--radius-md);
      font-weight: 600;
      text-decoration: none;
      transition: var(--transition);
    }

    .hv-logout:hover {
      background: rgba(255, 255, 255, 0.25);
      color: #fff;
    }

    .hv-content {
      flex: 1;
      max-width: 1200px;
      margin: 0 auto;
      padding: 24px;
      width: 100%;
    }

    .hv-footer {
      background: var(--bg-secondary);
      border-top: 1px solid var(--border);
      padding: 20px 24px;
      text-align: center;
      color: var(--text-secondary);
      font-size: 14px;
    }
  </style>
</head>

<body>

  <!-- HEADER -->
  <header class="hv-header">
    <div class="hv-header-top">
      <div class="hv-logo">UTT EXAM SYSTEM</div>
      <div class="hv-user">
        <span class="hv-user-name"><?= htmlspecialchars($hv["hoten"]) ?></span>
        <a href="<?= BASE_URL ?>/index.php?url=HocvienController/logout" class="hv-logout">Đăng xuất</a>
      </div>
    </div>

    <nav class="hv-nav">
      <a href="<?= BASE_URL ?>/index.php?url=HocvienController/phongthi" class="active">Phòng thi</a>
      <a href="<?= BASE_URL ?>/index.php?url=HocvienController/history">Lịch sử thi</a>
    </nav>
  </header>

  <!-- CONTENT -->
  <main class="hv-content">
    <?php require_once __DIR__ . "/" . $data["page"] . ".php"; ?>
  </main>

  <!-- FOOTER -->
  <footer class="hv-footer">
    © 2026 UTT All Rights Reserved
  </footer>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
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
  </script>
</body>

</html>