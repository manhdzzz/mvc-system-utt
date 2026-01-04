<?php
$rows = $data["rows"] ?? [];
$lops = $data["lops"] ?? [];
$mons = $data["mons"] ?? [];
$des = $data["des"] ?? [];
$lop_id = (int) ($data["lop_id"] ?? 0);
$q = $data["q"] ?? "";
?>

<div class="page-head compact">
  <div class="page-title">QUẢN LÝ PHÒNG THI</div>

  <div class="page-actions">
    <a class="btn btn-primary btn-sm" href="<?= BASE_URL ?>/index.php?url=PhongthiController/template">Tải mẫu</a>
    <button class="btn btn-success btn-sm" type="button" onclick="openAddModal()">Thêm mới</button>

    <form class="search-form" method="get" action="<?= BASE_URL ?>/index.php">
      <input type="hidden" name="url" value="PhongthiController/index">
      <input type="hidden" name="lop_id" value="<?= $lop_id ?>">
      <input name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Tìm kiếm...">
      <button type="submit" class="btn-search">Go</button>
    </form>
  </div>
</div>

<div class="box">
  <div class="box-sub">
    <div>
      Lớp:
      <select onchange="location.href='<?= BASE_URL ?>/index.php?url=PhongthiController/index&lop_id='+this.value">
        <option value="0">-- Tất cả --</option>
        <?php foreach ($lops as $l): ?>
          <option value="<?= $l["id"] ?>" <?= $lop_id == ((int) $l["id"]) ? "selected" : "" ?>>
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
        <th style="width:50px;text-align:center">STT</th>
        <th style="width:100px;text-align:center">Mã Phòng</th>
        <th>Tên phòng</th>
        <th style="text-align:center">Môn</th>
        <th>Bài thi</th>
        <th style="text-align:center">Lớp</th>
        <th style="width:90px;text-align:center">Trạng thái</th>
        <th style="width:140px;text-align:center">Thao tác</th>
        <th style="width:140px;text-align:center">Bắt đầu</th>
        <th style="text-align:center">Người tạo</th>
        <th style="width:80px;text-align:center">Vào</th>
      </tr>
    </thead>

    <tbody>
      <?php if (empty($rows)): ?>
        <tr>
          <td colspan="11" style="text-align:center;padding:24px;color:var(--text-muted)">Không có dữ liệu</td>
        </tr>
      <?php endif; ?>

      <?php foreach ($rows as $i => $r): ?>
        <tr>
          <td style="text-align:center"><?= $i + 1 ?></td>
          <td style="text-align:center"><?= htmlspecialchars($r["ma_phong"] ?? "") ?></td>
          <td><?= htmlspecialchars($r["ten_phong"] ?? "") ?></td>
          <td style="text-align:center"><?= htmlspecialchars($r["ten_mon"] ?? "") ?></td>
          <td><?= htmlspecialchars($r["ten_de"] ?? "") ?></td>
          <td style="text-align:center"><?= htmlspecialchars($r["ma_lop"] ?? "") ?></td>
          <td style="text-align:center">
            <span
              class="pill <?= ((int) ($r["trangthai"] ?? 0) === 1 ? 'yes' : 'no') ?>"><?= ((int) ($r["trangthai"] ?? 0) === 1 ? 'Yes' : 'No') ?></span>
          </td>
          <td style="text-align:center">
            <a class="action-btn edit"
              href="<?= BASE_URL ?>/index.php?url=PhongthiController/edit/<?= $r["id"] ?>">Sửa</a>
            <a class="action-btn delete" onclick="return confirm('Xóa phòng thi này?')"
              href="<?= BASE_URL ?>/index.php?url=PhongthiController/delete/<?= $r["id"] ?>">Xóa</a>
          </td>
          <td style="text-align:center"><?= htmlspecialchars($r["bat_dau"] ?? "") ?></td>
          <td style="text-align:center"><?= htmlspecialchars($r["nguoi_tao"] ?? "") ?></td>
          <td style="text-align:center">
            <a class="btn btn-primary btn-xs"
              href="<?= BASE_URL ?>/index.php?url=PhongthiController/vaoPhong/<?= $r["id"] ?>">Vào</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="pager"><a class="page active" href="#">1</a></div>
</div>

<!-- MODAL THÊM PHÒNG -->
<div id="modalAdd" class="modal">
  <div class="modal-card" style="max-width:540px">
    <div class="modal-head">
      <div>Thêm phòng thi</div>
      <button class="x" type="button" onclick="closeAddModal()">X</button>
    </div>

    <form method="post" action="<?= BASE_URL ?>/index.php?url=PhongthiController/store">
      <label>Mã Phòng</label>
      <input name="ma_phong" required>

      <label>Tên phòng</label>
      <input name="ten_phong" required>

      <label>Ngày bắt đầu thi</label>
      <input type="datetime-local" name="bat_dau">

      <div class="row2">
        <div>
          <label>Môn</label>
          <select name="mon_id" required>
            <option value="">-- Chọn môn --</option>
            <?php foreach ($mons as $m): ?>
              <option value="<?= $m["id"] ?>"><?= htmlspecialchars($m["ten_mon"] ?? "") ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label>Lớp</label>
          <select name="lop_id" required>
            <option value="">-- Chọn lớp --</option>
            <?php foreach ($lops as $l): ?>
              <option value="<?= $l["id"] ?>"><?= htmlspecialchars($l["ma_lop"] ?? "") ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <label>Bài thi (Đề thi)</label>
      <select name="de_id" required>
        <option value="">-- Chọn đề --</option>
        <?php foreach ($des as $d): ?>
          <option value="<?= $d["id"] ?>">
            <?= htmlspecialchars($d["ten_de"] ?? "") ?>
            <?php if (!empty($d["ma_de"])): ?>(<?= htmlspecialchars($d["ma_de"]) ?>)<?php endif; ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label>Trạng thái</label>
      <select name="trangthai">
        <option value="1">Yes</option>
        <option value="0">No</option>
      </select>

      <div class="modal-actions">
        <button class="btn btn-success" type="submit">Thêm mới</button>
        <button class="btn btn-secondary" type="button" onclick="closeAddModal()">Hủy</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL IMPORT -->
<div id="modal" class="modal">
  <div class="modal-card">
    <div class="modal-head">
      <div>Import phòng thi</div>
      <button class="x" onclick="closeModal()">X</button>
    </div>

    <form method="post" enctype="multipart/form-data" action="<?= BASE_URL ?>/index.php?url=PhongthiController/import">
      <label>Chọn file (CSV)</label>
      <input type="file" name="file" accept=".csv" required>

      <div class="hint">CSV format: ma_phong, ten_phong, mon_id, de_id, lop_id, bat_dau, trangthai</div>

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
</script>