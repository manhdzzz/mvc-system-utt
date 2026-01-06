<?php
$phong = $data["phong"];
$rows = $data["rows"] ?? [];
$q = $data["q"] ?? "";
$msg = $data["msg"] ?? "";
$err = $data["err"] ?? "";
?>
<?php if ($err): ?>
  <div class="alert err"><?= htmlspecialchars($err) ?></div><?php endif; ?>
<?php if ($msg): ?>
  <div class="alert ok"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

<!-- Print Styles -->
<style>
  @media print {

    .no-print,
    .no-print-col,
    .page-head,
    .box-sub button,
    .alert,
    .site-header,
    .site-footer {
      display: none !important;
    }

    .print-header {
      display: block !important;
      text-align: center;
      margin-bottom: 20px;
    }

    .print-header h2 {
      margin: 0 0 10px 0;
      font-size: 18px;
    }

    .print-header p {
      margin: 5px 0;
      font-size: 14px;
    }

    body {
      background: #fff !important;
    }

    .box {
      border: none !important;
      background: #fff !important;
    }

    .tbl {
      border: 1px solid #000;
    }

    .tbl th,
    .tbl td {
      border: 1px solid #000;
      color: #000 !important;
      background: #fff !important;
      padding: 8px !important;
    }

    .tbl thead th {
      background: #f0f0f0 !important;
    }

    /* Hide columns that should not print */
    .no-print-col {
      display: none !important;
    }
  }

  @media screen {
    .print-header {
      display: none;
    }
  }
</style>

<!-- Print Header (only shows when printing) -->
<div class="print-header">
  <h2>BẢNG ĐIỂM THI - <?= htmlspecialchars($phong['ten_phong']) ?></h2>
  <p>Môn: <?= htmlspecialchars($phong['ten_mon'] ?? '') ?> | Đề: <?= htmlspecialchars($phong['ten_de'] ?? '') ?> | Lớp:
    <?= htmlspecialchars($phong['ma_lop']) ?>
  </p>
  <p>Ngày in: <?= date('d/m/Y H:i') ?></p>
</div>

<div class="page-head compact no-print">
  <div class="page-title"><span>LÀM BÀI THI</span></div>

  <div class="page-actions">
    <button class="btn-blue btn-sm" type="button"
      onclick="Swal.fire({icon:'info',title:'Thông tin phòng thi',html:'<b>Phòng:</b> <?= htmlspecialchars($phong['ten_phong']) ?><br><b>Mã:</b> <?= htmlspecialchars($phong['ma_phong']) ?><br><b>Đề:</b> <?= htmlspecialchars($phong['ten_de']) ?><br><b>Lớp:</b> <?= htmlspecialchars($phong['ma_lop']) ?>',confirmButtonText:'Đóng',confirmButtonColor:'#0ea5e9'})">
      Thông tin
    </button>

    <a class="btn-green btn-sm" href="<?= BASE_URL ?>/index.php?url=PhongthiController/edit/<?= $phong["id"] ?>">Thiết
      lập</a>

    <a class="btn-blue btn-sm" href="<?= BASE_URL ?>/index.php?url=PhongthiController/monitor/<?= $phong["id"] ?>">
      Giám sát</a>

    <a class="btn-yellow btn-sm"
      href="<?= BASE_URL ?>/index.php?url=PhongthiController/exportExcel/<?= $phong["id"] ?>">Xuất Excel</a>

    <form class="search-form" method="get" action="<?= BASE_URL ?>/index.php">
      <input type="hidden" name="url" value="PhongthiController/vaoPhong/<?= $phong["id"] ?>">
      <input name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Tìm mã HV / họ tên...">
      <button class="btn-search btn-sm">Tìm</button>
    </form>
  </div>
</div>

<div class="box">
  <div class="box-sub" style="display:flex;justify-content:space-between;align-items:center">
    <div>Tổng số <b><?= count($rows) ?></b></div>
    <button class="btn-blue btn-sm no-print" type="button" onclick="window.print()">In bảng điểm</button>
  </div>


  <table class="tbl">
    <thead>
      <tr>
        <th>STT</th>
        <th>Mã HV</th>
        <th>Họ tên</th>
        <th>Lớp</th>
        <th>Thời gian vào</th>
        <th class="no-print-col">Kích hoạt</th>
        <th class="no-print-col">Điểm</th>
        <th class="no-print-col">Câu Đúng</th>
        <th class="no-print-col">Trạng thái</th>
        <th class="no-print-col">Vi phạm</th>
        <th class="no-print-col">Làm lại</th>
        <th>Trừ</th>
        <th>Tổng</th>
        <th>Ghi chú</th>
        <th class="no-print-col">Hủy</th>
      </tr>
    </thead>

    <tbody>
      <?php foreach ($rows as $i => $r): ?>
        <tr>
          <td style="text-align:center"><?= $i + 1 ?></td>
          <td style="text-align:center"><?= htmlspecialchars($r["ma_hv"] ?? "") ?></td>
          <td><?= htmlspecialchars($r["hoten"] ?? "") ?></td>
          <td style="text-align:center"><?= htmlspecialchars($r["ma_lop"] ?? "") ?></td>
          <td style="text-align:center"><?= htmlspecialchars($r["thoi_gian_vao"] ?? "") ?></td>

          <td style="text-align:center" class="no-print-col">
            <?php if ((int) $r["kich_hoat"] === 1): ?>
              <form method="post" action="<?= BASE_URL ?>/index.php?url=PhongthiController/kichhoat" style="display:inline"
                id="form-khoa-<?= $r["hocvien_id"] ?>">
                <input type="hidden" name="phong_id" value="<?= $phong["id"] ?>">
                <input type="hidden" name="hocvien_id" value="<?= $r["hocvien_id"] ?>">
                <input type="hidden" name="val" value="0">
                <button class="pill yes" type="button"
                  onclick="confirmAction('form-khoa-<?= $r["hocvien_id"] ?>', 'Khóa học viên này?', 'warning')">Yes</button>
              </form>
            <?php else: ?>
              <form method="post" action="<?= BASE_URL ?>/index.php?url=PhongthiController/kichhoat" style="display:inline"
                id="form-kh-<?= $r["hocvien_id"] ?>">
                <input type="hidden" name="phong_id" value="<?= $phong["id"] ?>">
                <input type="hidden" name="hocvien_id" value="<?= $r["hocvien_id"] ?>">
                <input type="hidden" name="val" value="1">
                <button class="pill no" type="button"
                  onclick="confirmAction('form-kh-<?= $r["hocvien_id"] ?>', 'Kích hoạt học viên này?', 'question')">No</button>
              </form>
            <?php endif; ?>
          </td>

          <td style="text-align:center" class="no-print-col"><?= (int) ($r["diem"] ?? 0) ?></td>
          <td style="text-align:center" class="no-print-col"><?= (int) ($r["cau_dung"] ?? 0) ?></td>
          <td style="text-align:center" class="no-print-col"><?= htmlspecialchars($r["trang_thai"] ?? "") ?></td>

          <td style="text-align:center" class="no-print-col">
            <form method="post" action="<?= BASE_URL ?>/index.php?url=PhongthiController/vipham" style="display:inline"
              id="form-vp-<?= $r["hocvien_id"] ?>">
              <input type="hidden" name="phong_id" value="<?= $phong["id"] ?>">
              <input type="hidden" name="hocvien_id" value="<?= $r["hocvien_id"] ?>">
              <button class="btn-danger btn-sm" type="button"
                onclick="confirmAction('form-vp-<?= $r["hocvien_id"] ?>', 'Ghi nhận vi phạm cho học viên này?', 'warning')"><?= (int) ($r["so_lan_vi_pham"] ?? 0) ?>
                (+1)</button>
            </form>
          </td>

          <td style="text-align:center" class="no-print-col">
            <form method="post" action="<?= BASE_URL ?>/index.php?url=PhongthiController/reset" style="display:inline"
              id="form-reset-<?= $r["hocvien_id"] ?>">
              <input type="hidden" name="phong_id" value="<?= $phong["id"] ?>">
              <input type="hidden" name="hocvien_id" value="<?= $r["hocvien_id"] ?>">
              <button class="btn-gray btn-sm" type="button"
                onclick="confirmAction('form-reset-<?= $r["hocvien_id"] ?>', 'Reset để học viên làm lại?<br><small>(Xóa điểm, vi phạm, ghi chú)</small>', 'question')">Reset</button>
            </form>
          </td>

          <td style="text-align:center"><?= (int) ($r["tru"] ?? 0) ?></td>
          <td style="text-align:center"><?= max(0, (int) ($r["diem"] ?? 0) - (int) ($r["tru"] ?? 0)) ?></td>

          <td>
            <span class="print-only"><?= htmlspecialchars($r["ghi_chu"] ?? "") ?></span>
            <form method="post" action="<?= BASE_URL ?>/index.php?url=PhongthiController/ghichu"
              style="display:flex;gap:6px;align-items:center" class="no-print">
              <input type="hidden" name="phong_id" value="<?= $phong["id"] ?>">
              <input type="hidden" name="hocvien_id" value="<?= $r["hocvien_id"] ?>">
              <input name="ghi_chu" value="<?= htmlspecialchars($r["ghi_chu"] ?? "") ?>" placeholder="Ghi chú..."
                style="height:34px">
              <button class="btn-blue btn-sm" type="submit">Lưu</button>
            </form>
          </td>

          <td style="text-align:center" class="no-print-col">
            <form method="post" action="<?= BASE_URL ?>/index.php?url=PhongthiController/huy" style="display:inline"
              id="form-huy-<?= $r["hocvien_id"] ?>">
              <input type="hidden" name="phong_id" value="<?= $phong["id"] ?>">
              <input type="hidden" name="hocvien_id" value="<?= $r["hocvien_id"] ?>">
              <button class="btn-yellow btn-sm" type="button"
                onclick="confirmAction('form-huy-<?= $r["hocvien_id"] ?>', 'Hủy bài của học viên?', 'warning')">Hủy</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script>
  function confirmAction(formId, message, icon) {
    Swal.fire({
      icon: icon,
      title: 'Xác nhận',
      html: message,
      showCancelButton: true,
      confirmButtonText: 'Đồng ý',
      cancelButtonText: 'Hủy',
      confirmButtonColor: '#e94560',
      cancelButtonColor: '#64748b'
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById(formId).submit();
      }
    });
  }
</script>