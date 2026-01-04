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

<!-- Print Header (only shows when printing) -->
<div class="print-header">
  <h2>BẢNG ĐIỂM THI - <?= htmlspecialchars($phong['ten_phong']) ?></h2>
  <p>Môn: <?= htmlspecialchars($phong['ten_mon'] ?? '') ?> | Đề: <?= htmlspecialchars($phong['ten_de'] ?? '') ?> | Lớp:
    <?= htmlspecialchars($phong['ma_lop']) ?>
  </p>
  <p>Ngày in: <?= date('d/m/Y H:i') ?></p>
</div>

<div class="page-head compact no-print">
  <div class="page-title"><span class="icon">🧾</span><span>LÀM BÀI THI</span></div>

  <div class="page-actions">
    <button class="btn-blue btn-sm" type="button"
      onclick="alert('Phòng: <?= htmlspecialchars($phong['ten_phong']) ?>\nMã: <?= htmlspecialchars($phong['ma_phong']) ?>\nĐề: <?= htmlspecialchars($phong['ten_de']) ?>\nLớp: <?= htmlspecialchars($phong['ma_lop']) ?>')">
      ℹ Thông tin
    </button>

    <a class="btn-green btn-sm" href="<?= BASE_URL ?>/index.php?url=PhongthiController/edit/<?= $phong["id"] ?>">⚙ Thiết
      lập</a>

    <a class="btn-blue btn-sm" href="<?= BASE_URL ?>/index.php?url=PhongthiController/monitor/<?= $phong["id"] ?>">📡
      Giám sát</a>

    <a class="btn-yellow btn-sm"
      href="<?= BASE_URL ?>/index.php?url=PhongthiController/exportCSV/<?= $phong["id"] ?>">📥 Xuất CSV</a>

    <form class="search-form" method="get" action="<?= BASE_URL ?>/index.php">
      <input type="hidden" name="url" value="PhongthiController/vaoPhong/<?= $phong["id"] ?>">
      <input name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Tìm mã HV / họ tên...">
      <button class="btn-search btn-sm">🔍</button>
    </form>
  </div>
</div>

<div class="box">
  <div class="box-sub" style="display:flex;justify-content:space-between;align-items:center">
    <div>Tổng số <b><?= count($rows) ?></b></div>
    <button class="btn-blue btn-sm no-print" type="button" onclick="window.print()">🖨 In bảng điểm</button>
  </div>


  <table class="tbl">
    <thead>
      <tr>
        <th>STT</th>
        <th>Mã HV</th>
        <th>Họ tên</th>
        <th>Lớp</th>
        <th class="no-print-col">Thời gian vào</th>
        <th class="no-print-col">Kích hoạt</th>
        <th>Điểm</th>
        <th class="no-print-col">Câu Đúng</th>
        <th class="no-print-col">Trạng thái</th>
        <th class="no-print-col">Làm lại</th>
        <th>Trừ</th>
        <th>Còn</th>
        <th class="no-print-col">Ghi chú</th>
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
          <td style="text-align:center" class="no-print-col"><?= htmlspecialchars($r["thoi_gian_vao"] ?? "") ?></td>

          <td style="text-align:center" class="no-print-col">
            <?php if ((int) $r["kich_hoat"] === 1): ?>
              <form method="post" action="<?= BASE_URL ?>/index.php?url=PhongthiController/kichhoat" style="display:inline">
                <input type="hidden" name="phong_id" value="<?= $phong["id"] ?>">
                <input type="hidden" name="hocvien_id" value="<?= $r["hocvien_id"] ?>">
                <input type="hidden" name="val" value="0">
                <button class="pill yes" type="submit" onclick="return confirm('Khóa học viên này?')">Yes</button>
              </form>
            <?php else: ?>
              <form method="post" action="<?= BASE_URL ?>/index.php?url=PhongthiController/kichhoat" style="display:inline">
                <input type="hidden" name="phong_id" value="<?= $phong["id"] ?>">
                <input type="hidden" name="hocvien_id" value="<?= $r["hocvien_id"] ?>">
                <input type="hidden" name="val" value="1">
                <button class="pill no" type="submit" onclick="return confirm('Kích hoạt học viên này?')">No</button>
              </form>
            <?php endif; ?>
          </td>

          <td style="text-align:center"><?= (int) ($r["diem"] ?? 0) ?></td>
          <td style="text-align:center" class="no-print-col"><?= (int) ($r["cau_dung"] ?? 0) ?></td>
          <td style="text-align:center" class="no-print-col"><?= htmlspecialchars($r["trang_thai"] ?? "") ?></td>

          <td style="text-align:center" class="no-print-col">
            <form method="post" action="<?= BASE_URL ?>/index.php?url=PhongthiController/reset" style="display:inline">
              <input type="hidden" name="phong_id" value="<?= $phong["id"] ?>">
              <input type="hidden" name="hocvien_id" value="<?= $r["hocvien_id"] ?>">
              <button class="btn-gray btn-sm" type="submit"
                onclick="return confirm('Reset để học viên làm lại?')">Reset</button>
            </form>
          </td>

          <td style="text-align:center"><?= (int) ($r["tru"] ?? 0) ?></td>
          <td style="text-align:center"><?= (int) ($r["con_lai"] ?? 0) ?></td>

          <td class="no-print-col">
            <form method="post" action="<?= BASE_URL ?>/index.php?url=PhongthiController/ghichu"
              style="display:flex;gap:6px;align-items:center">
              <input type="hidden" name="phong_id" value="<?= $phong["id"] ?>">
              <input type="hidden" name="hocvien_id" value="<?= $r["hocvien_id"] ?>">
              <input name="ghi_chu" value="<?= htmlspecialchars($r["ghi_chu"] ?? "") ?>" placeholder="Ghi chú..."
                style="height:34px">
              <button class="btn-blue btn-sm" type="submit">Lưu</button>
            </form>
          </td>

          <td style="text-align:center" class="no-print-col">
            <form method="post" action="<?= BASE_URL ?>/index.php?url=PhongthiController/huy" style="display:inline">
              <input type="hidden" name="phong_id" value="<?= $phong["id"] ?>">
              <input type="hidden" name="hocvien_id" value="<?= $r["hocvien_id"] ?>">
              <button class="btn-yellow btn-sm" type="submit"
                onclick="return confirm('Hủy bài của học viên?')">Hủy</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>