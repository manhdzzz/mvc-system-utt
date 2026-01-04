<?php
$rows = $data["rows"] ?? [];
$lops = $data["lops"] ?? [];
$lop_id = (int) ($data["lop_id"] ?? 0);
$q = $data["q"] ?? "";
?>

<div class="page-head compact">
  <div class="page-title">QUẢN LÝ HỌC VIÊN</div>

  <div class="page-actions">
    <a class="btn btn-primary btn-sm" href="<?= BASE_URL ?>/index.php?url=HocvienAdminController/template">Tải mẫu</a>
    <button class="btn btn-success btn-sm" type="button" onclick="openAddModal()">Thêm mới</button>

    <form class="search-form" method="get" action="<?= BASE_URL ?>/index.php">
      <input type="hidden" name="url" value="HocvienAdminController/index">
      <input name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Tìm họ tên / mã học viên...">
      <button type="submit" class="btn-search">Go</button>
    </form>
  </div>
</div>

<div class="box">
  <div class="box-sub">
    <div>
      Lớp:
      <select onchange="location.href='<?= BASE_URL ?>/index.php?url=HocvienAdminController/index&lop_id='+this.value">
        <option value="0">-- Tất cả --</option>
        <?php foreach ($lops as $l): ?>
          <option value="<?= $l['id'] ?>" <?= $lop_id == ((int) $l['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($l["ma_lop"] ?? "") ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <button class="btn btn-warning btn-sm" type="button" onclick="openModal()">Import file</button>
  </div>

  <table class="tbl">
    <thead>
      <tr>
        <th style="width:60px;text-align:center">STT</th>
        <th>Họ tên</th>
        <th style="width:140px;text-align:center">Mã học viên</th>
        <th style="width:120px;text-align:center">Mật khẩu</th>
        <th style="width:100px;text-align:center">Trạng thái</th>
        <th style="width:150px;text-align:center">Thao tác</th>
        <th style="width:160px;text-align:center">Ngày tạo</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($rows)): ?>
        <tr>
          <td colspan="7" style="text-align:center;padding:24px;color:var(--text-muted)">Không có dữ liệu</td>
        </tr>
      <?php endif; ?>

      <?php foreach ($rows as $i => $r): ?>
        <tr>
          <td style="text-align:center"><?= $i + 1 ?></td>
          <td><?= htmlspecialchars($r["hoten"] ?? "") ?></td>
          <td style="text-align:center"><?= htmlspecialchars($r["ma_hv"] ?? "") ?></td>
          <td style="text-align:center"><?= htmlspecialchars($r["matkhau"] ?? "") ?></td>
          <td style="text-align:center">
            <?php $tt = (int) ($r["trangthai"] ?? 1); ?>
            <span class="pill <?= $tt === 1 ? 'yes' : 'no' ?>"><?= $tt === 1 ? 'Yes' : 'No' ?></span>
          </td>
          <td style="text-align:center">
            <button class="action-btn edit" type="button" onclick="openEdit(
                <?= $r['id'] ?>,
                '<?= htmlspecialchars($r['hoten'] ?? '', ENT_QUOTES) ?>',
                '<?= htmlspecialchars($r['matkhau'] ?? '', ENT_QUOTES) ?>',
                <?= (int) ($r['lop_id'] ?? 0) ?>,
                <?= (int) ($r['trangthai'] ?? 1) ?>
              )">Sửa</button>
            <a class="action-btn delete" onclick="return confirm('Xóa học viên?')"
              href="<?= BASE_URL ?>/index.php?url=HocvienAdminController/delete/<?= $r['id'] ?>">Xóa</a>
          </td>
          <td style="text-align:center"><?= htmlspecialchars($r["created_at"] ?? "") ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="pager"><a class="page active" href="#">1</a></div>
</div>

<!-- MODAL THÊM -->
<div id="modalAdd" class="modal">
  <div class="modal-card" style="max-width:520px">
    <div class="modal-head">
      <div>Thêm học viên</div>
      <button class="x" type="button" onclick="closeAddModal()">X</button>
    </div>

    <form method="post" action="<?= BASE_URL ?>/index.php?url=HocvienAdminController/store">
      <label>Họ tên</label>
      <input name="hoten" required>

      <label>Mã học viên</label>
      <input name="ma_hv" required>

      <label>Mật khẩu</label>
      <input name="matkhau" placeholder="VD: 1234">

      <div class="row2">
        <div>
          <label>Lớp</label>
          <select name="lop_id" required>
            <option value="">-- Chọn lớp --</option>
            <?php foreach ($lops as $l): ?>
              <option value="<?= $l['id'] ?>" <?= $lop_id == ((int) $l['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($l["ma_lop"] ?? "") ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label>Trạng thái</label>
          <select name="trangthai">
            <option value="1">Yes</option>
            <option value="0">No</option>
          </select>
        </div>
      </div>

      <div class="modal-actions">
        <button class="btn btn-success" type="submit">Thêm mới</button>
        <button class="btn btn-secondary" type="button" onclick="closeAddModal()">Hủy</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL SỬA -->
<div id="modalEdit" class="modal">
  <div class="modal-card" style="max-width:520px">
    <div class="modal-head">
      <div>Cập nhật học viên</div>
      <button class="x" type="button" onclick="closeEdit()">X</button>
    </div>

    <form method="post" id="editForm">
      <label>Họ tên</label>
      <input name="hoten" id="e_hoten" required>

      <label>Mật khẩu</label>
      <input name="matkhau" id="e_matkhau">

      <div class="row2">
        <div>
          <label>Lớp</label>
          <select name="lop_id" id="e_lop" required>
            <?php foreach ($lops as $l): ?>
              <option value="<?= $l['id'] ?>"><?= htmlspecialchars($l["ma_lop"] ?? "") ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label>Trạng thái</label>
          <select name="trangthai" id="e_trangthai">
            <option value="1">Yes</option>
            <option value="0">No</option>
          </select>
        </div>
      </div>

      <div class="modal-actions">
        <button class="btn btn-success" type="submit">Lưu</button>
        <button class="btn btn-secondary" type="button" onclick="closeEdit()">Hủy</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL IMPORT -->
<div id="modal" class="modal">
  <div class="modal-card">
    <div class="modal-head">
      <div>Import file học viên</div>
      <button class="x" type="button" onclick="closeModal()">X</button>
    </div>

    <form method="post" enctype="multipart/form-data"
      action="<?= BASE_URL ?>/index.php?url=HocvienAdminController/import">
      <label>Chọn lớp</label>
      <select name="lop_id" required>
        <option value="">-- Chọn lớp --</option>
        <?php foreach ($lops as $l): ?>
          <option value="<?= $l['id'] ?>" <?= $lop_id == ((int) $l['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($l["ma_lop"] ?? "") ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label>Chọn tệp (CSV)</label>
      <input type="file" name="file" accept=".csv" required>

      <div class="hint">CSV format: hoten, ma_hv, matkhau, trangthai</div>

      <div class="modal-actions">
        <button class="btn btn-success" type="submit">Import</button>
        <button class="btn btn-secondary" type="button" onclick="closeModal()">Hủy</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openAddModal() { document.getElementById('modalAdd').classList.add('show'); }
  function closeAddModal() { document.getElementById('modalAdd').classList.remove('show'); }
  function openModal() { document.getElementById('modal').classList.add('show'); }
  function closeModal() { document.getElementById('modal').classList.remove('show'); }

  function openEdit(id, hoten, matkhau, lop_id, trangthai) {
    document.getElementById('modalEdit').classList.add('show');
    document.getElementById('e_hoten').value = hoten || "";
    document.getElementById('e_matkhau').value = matkhau || "";
    document.getElementById('e_lop').value = String(lop_id || "");
    document.getElementById('e_trangthai').value = String(trangthai ?? 1);
    document.getElementById('editForm').action = "<?= BASE_URL ?>/index.php?url=HocvienAdminController/update/" + id;
  }
  function closeEdit() { document.getElementById('modalEdit').classList.remove('show'); }
</script>