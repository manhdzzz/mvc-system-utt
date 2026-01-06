<?php
$rows = $data["rows"] ?? [];
$q = $data["q"] ?? "";
?>

<div class="page-head compact">
  <div class="page-title">QUẢN LÝ TÀI KHOẢN</div>

  <div class="page-actions">
    <a class="btn btn-primary btn-sm" href="<?= BASE_URL ?>/index.php?url=TaikhoanController/template">Tải mẫu</a>
    <a class="btn btn-success btn-sm" href="<?= BASE_URL ?>/index.php?url=TaikhoanController/create">Thêm mới</a>

    <form class="search-form" method="get" action="<?= BASE_URL ?>/index.php">
      <input type="hidden" name="url" value="TaikhoanController/index">
      <input name="q" value="<?= htmlspecialchars($q ?? '') ?>" placeholder="Tìm họ tên / username / email...">
      <button type="submit" class="btn-search">Go</button>
    </form>
  </div>
</div>

<div class="box">
  <div class="box-sub">
    <div>Tổng số: <b><?= count($rows) ?></b> Thành viên</div>

    <button class="btn btn-warning btn-sm" type="button" onclick="openModal()">Import file</button>
  </div>

  <table class="tbl">
    <thead>
      <tr>
        <th style="width:60px;text-align:center">STT</th>
        <th>Họ tên</th>
        <th>Tài khoản</th>
        <th>Email</th>
        <th style="width:100px;text-align:center">Trạng thái</th>
        <th style="width:130px;text-align:center">Thao tác</th>
        <th style="width:160px;text-align:center">Ngày tạo</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $i => $r): ?>
        <tr>
          <td style="text-align:center"><?= ($i + 1) + (($data['currPage'] ?? 1) - 1) * ($data['limit'] ?? 50) ?></td>
          <td><?= htmlspecialchars($r["hoten"]) ?></td>
          <td><?= htmlspecialchars($r["username"]) ?></td>
          <td><?= htmlspecialchars($r["email"]) ?></td>
          <td style="text-align:center">
            <span
              class="pill <?= ((int) $r["trangthai"] === 1 ? 'yes' : 'no') ?>"><?= ((int) $r["trangthai"] === 1 ? 'Yes' : 'No') ?></span>
          </td>
          <td style="text-align:center">
            <a class="action-btn edit"
              href="<?= BASE_URL ?>/index.php?url=TaikhoanController/edit/<?= $r["id"] ?>">Sửa</a>
            <a class="action-btn delete" href="#"
              onclick="confirmDelete('<?= BASE_URL ?>/index.php?url=TaikhoanController/delete/<?= $r["id"] ?>', 'Xóa tài khoản này?')">Xóa</a>
          </td>
          <td style="text-align:center"><?= htmlspecialchars($r["created_at"]) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="pager">
    <?php for ($p = 1; $p <= ($data["totalPages"] ?? 1); $p++): ?>
      <a class="page <?= $p == ($data["currPage"] ?? 1) ? "active" : "" ?>"
        href="<?= BASE_URL ?>/index.php?url=TaikhoanController/index&q=<?= htmlspecialchars($q) ?>&page=<?= $p ?>">
        <?= $p ?>
      </a>
    <?php endfor; ?>
  </div>
</div>

<!-- MODAL IMPORT -->
<div id="modal" class="modal">
  <div class="modal-card">
    <div class="modal-head">
      <div>Import tài khoản</div>
      <button class="x" onclick="closeModal()">X</button>
    </div>

    <form method="post" enctype="multipart/form-data" action="<?= BASE_URL ?>/index.php?url=TaikhoanController/import">
      <label>Chọn file (CSV)</label>
      <input type="file" name="file" accept=".csv" required>

      <div class="hint">CSV format: hoten, username, email, password, trangthai, role</div>

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
  function confirmDelete(url, message) {
    Swal.fire({ icon: 'warning', title: 'Xác nhận xóa', text: message, showCancelButton: true, confirmButtonText: 'Xóa', cancelButtonText: 'Hủy', confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b' }).then((result) => { if (result.isConfirmed) { window.location.href = url; } });
  }
</script>