<?php $page = $data["page"]; ?>
<!doctype html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Đăng nhập - UTT Exam System</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/style.css?v=3">
</head>

<body class="login-bg">

  <?php require_once __DIR__ . "/" . $page . ".php"; ?>

  <!-- FOOTER -->
  <div
    style="position:fixed;bottom:0;left:0;right:0;text-align:center;padding:16px;color:rgba(255,255,255,0.5);font-size:13px;">
    © 2026 UTT All Rights Reserved
  </div>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      <?php $err = $data["err"] ?? ""; ?>
      <?php if ($err !== ""): ?>
        <?php if (strpos($err, "khóa") !== false || strpos($err, "cấm") !== false || strpos($err, "bị") !== false): ?>
          Swal.fire({
            icon: 'error',
            title: 'Tài khoản bị khóa!',
            text: <?= json_encode($err) ?>,
            background: '#1e293b',
            color: '#f1f5f9',
            confirmButtonColor: '#3b82f6'
          });
        <?php else: ?>
          Swal.fire({
            icon: 'error',
            title: 'Đăng nhập thất bại!',
            text: <?= json_encode($err) ?>,
            background: '#1e293b',
            color: '#f1f5f9',
            confirmButtonColor: '#3b82f6'
          });
        <?php endif; ?>
      <?php endif; ?>
    });
  </script>
</body>

</html>