<?php
$rows = $data["rows"] ?? [];
$q = $data["q"] ?? "";
?>

<div class="page-head compact">
  <div class="page-title">QUẢN LÝ LỚP HỌC</div>

  <div class="page-actions">
    <a class="btn btn-primary btn-sm" href="<?= BASE_URL ?>/index.php?url=LophocController/template">Tải mẫu</a>
    <a class="btn btn-success btn-sm" href="<?= BASE_URL ?>/index.php?url=LophocController/create">Thêm mới</a>

    <form class="search-form" method="get" action="<?= BASE_URL ?>/index.php">
      <input type="hidden" name="url" value="LophocController/index">
      <input name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Tìm mã / tên lớp...">
      <button type="submit" class="btn-search">Go</button>
    </form>
  </div>
</div>

<div class="box">
  <div class="box-sub">
    <div>Tổng số Lớp: <b><?= count($rows) ?></b>
      <?php if ($q !== ""): ?>
        — Kết quả cho: <b><?= htmlspecialchars($q) ?></b>
        <a href="<?= BASE_URL ?>/index.php?url=LophocController/index">Xem tất cả</a>
      <?php endif; ?>
    </div>

    <button class="btn btn-warning btn-sm" type="button" onclick="openModal()">Import file</button>
  </div>

  <table class="tbl">
    <thead>
      <tr>
        <th style="width:60px;text-align:center">STT</th>
        <th style="width:120px;text-align:center">Mã lớp</th>
        <th>Tên lớp</th>
        <th style="width:100px;text-align:center">Trạng thái</th>
        <th style="text-align:center">Người tạo</th>
        <th style="width:130px;text-align:center">Thao tác</th>
        <th style="width:160px;text-align:center">Ngày tạo</th>
        <th style="width:100px;text-align:center">Số HV</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $i => $r): ?>
        <tr>
          <td style="text-align:center"><?= $i + 1 ?></td>
          <td style="text-align:center"><?= htmlspecialchars($r["ma_lop"]) ?></td>
          <td><?= htmlspecialchars($r["ten_lop"]) ?></td>
          <td style="text-align:center">
            <span
              class="pill <?= ((int) $r["trangthai"] === 1 ? 'yes' : 'no') ?>"><?= ((int) $r["trangthai"] === 1 ? 'Yes' : 'No') ?></span>
          </td>
          <td style="text-align:center"><?= htmlspecialchars($r["nguoi_tao"]) ?></td>
          <td style="text-align:center">
            <a class="action-btn edit" href="<?= BASE_URL ?>/index.php?url=LophocController/edit/<?= $r["id"] ?>">Sửa</a>
            <a class="action-btn delete" onclick="return confirm('Xóa lớp này?')"
              href="<?= BASE_URL ?>/index.php?url=LophocController/delete/<?= $r["id"] ?>">Xóa</a>
          </td>
          <td style="text-align:center"><?= htmlspecialchars($r["created_at"] ?? "") ?></td>
          <td style="text-align:center"><?= (int) ($r["so_hoc_vien"] ?? 0) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="pager"><a class="page active" href="#">1</a></div>
</div>

<!-- MODAL IMPORT -->
<div id="modal" class="modal">
  <div class="modal-card">
    <div class="modal-head">
      <div>Import lớp học</div>
      <button class="x" onclick="closeModal()">X</button>
    </div>

    <form method="post" enctype="multipart/form-data" action="<?= BASE_URL ?>/index.php?url=LophocController/import">
      <label>Chọn file (CSV)</label>
      <input type="file" name="file" accept=".csv" required>

      <div class="hint">CSV format: ma_lop, ten_lop, trangthai</div>

      <div class="modal-actions">
        <button class="btn btn-success" type="submit">Import</button>
        <button class="btn btn-secondary" type="button" onclick="closeModal()">Hủy</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openModal() { document.getElementById('modal').classList.add('show'); }
  function closeModal() { document.getElementById('modal').classList.remove('show'); }
</script>