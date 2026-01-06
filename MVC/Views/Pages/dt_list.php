<?php
$rows = $data["rows"] ?? [];
$mons = $data["mons"] ?? [];
$mon_id = (int) ($data["mon_id"] ?? 0);
$q = $data["q"] ?? "";
?>

<div class="page-head compact">
  <div class="page-title">QUẢN LÝ ĐỀ THI</div>

  <div class="page-actions">
    <a class="btn btn-primary btn-sm" href="<?= BASE_URL ?>/index.php?url=DethiController/template">Tải mẫu</a>
    <button class="btn btn-success btn-sm" type="button" onclick="openAddModal()">Thêm mới</button>

    <form class="search-form" method="get" action="<?= BASE_URL ?>/index.php">
      <input type="hidden" name="url" value="DethiController/index">
      <input type="hidden" name="mon_id" value="<?= $mon_id ?>">
      <input name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Tìm mã / tên bài thi...">
      <button type="submit" class="btn-search">Go</button>
    </form>
  </div>
</div>

<div class="box">
  <div class="box-sub">
    <div>
      Môn:
      <select onchange="location.href='<?= BASE_URL ?>/index.php?url=DethiController/index&mon_id='+this.value">
        <option value="0">-- Tất cả --</option>
        <?php foreach ($mons as $m): ?>
          <option value="<?= $m["id"] ?>" <?= $mon_id == ((int) $m["id"]) ? "selected" : "" ?>>
            <?= htmlspecialchars($m["ten_mon"]) ?>
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
        <th style="width:100px;text-align:center">Mã</th>
        <th>Tên bài thi</th>
        <th style="width:80px;text-align:center">Time</th>
        <th style="width:140px;text-align:center">Ngày tạo</th>
        <th style="width:80px;text-align:center">Câu dễ</th>
        <th style="width:80px;text-align:center">Câu TB</th>
        <th style="width:80px;text-align:center">Câu Khó</th>
        <th style="width:80px;text-align:center">Thao tác</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $i => $r): ?>
        <tr>
          <td style="text-align:center"><?= $i + 1 ?></td>
          <td style="text-align:center"><?= htmlspecialchars($r["ma_de"]) ?></td>
          <td><?= htmlspecialchars($r["ten_de"]) ?></td>
          <td style="text-align:center"><?= (int) $r["thoi_gian"] ?></td>
          <td style="text-align:center"><?= htmlspecialchars($r["created_at"] ?? "") ?></td>
          <td style="text-align:center"><?= (int) ($r["cau_de"] ?? 0) ?></td>
          <td style="text-align:center"><?= (int) ($r["cau_tb"] ?? 0) ?></td>
          <td style="text-align:center"><?= (int) ($r["cau_kho"] ?? 0) ?></td>
          <td style="text-align:center">
            <a class="action-btn delete" href="#"
              onclick="confirmDelete('<?= BASE_URL ?>/index.php?url=DethiController/delete/<?= $r["id"] ?>', 'Xóa đề thi này?')">Xóa</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="pager"><a class="page active" href="#">1</a></div>
</div>

<!-- MODAL THÊM ĐỀ -->
<div id="modalAdd" class="modal">
  <div class="modal-card" style="max-width:480px">
    <div class="modal-head">
      <div>Thêm đề thi</div>
      <button class="x" onclick="closeAddModal()">X</button>
    </div>

    <form method="post" action="<?= BASE_URL ?>/index.php?url=DethiController/store">
      <label>Mã Bài thi</label>
      <input name="ma_de" required>

      <label>Tên bài thi</label>
      <input name="ten_de" required>

      <label>Thời gian thi (phút)</label>
      <input type="number" min="1" name="thoi_gian" value="30" required>

      <label>Môn thi</label>
      <select name="mon_id" required>
        <option value="">-- Chọn môn --</option>
        <?php foreach ($mons as $m): ?>
          <option value="<?= $m["id"] ?>" <?= $mon_id == ((int) $m["id"]) ? "selected" : "" ?>>
            <?= htmlspecialchars($m["ten_mon"]) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <div class="row3">
        <div>
          <label>Câu dễ</label>
          <input type="number" min="0" name="so_de" value="0">
        </div>
        <div>
          <label>Câu TB</label>
          <input type="number" min="0" name="so_tb" value="0">
        </div>
        <div>
          <label>Câu khó</label>
          <input type="number" min="0" name="so_kho" value="0">
        </div>
      </div>

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
      <div>Import đề thi</div>
      <button class="x" onclick="closeModal()">X</button>
    </div>

    <form method="post" enctype="multipart/form-data" action="<?= BASE_URL ?>/index.php?url=DethiController/import">
      <label>Chọn Môn thi</label>
      <select name="mon_id">
        <option value="0">-- Chọn môn --</option>
        <?php foreach ($mons as $m): ?>
          <option value="<?= $m["id"] ?>" <?= $mon_id == ((int) $m["id"]) ? "selected" : "" ?>>
            <?= htmlspecialchars($m["ten_mon"]) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label>Chọn file (CSV)</label>
      <input type="file" name="file" accept=".csv" required>

      <div class="hint">CSV format: ma_de, ten_de, thoi_gian, mon_id, so_de, so_tb, so_kho</div>

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
  function confirmDelete(url, message) {
    Swal.fire({ icon: 'warning', title: 'Xác nhận xóa', text: message, showCancelButton: true, confirmButtonText: 'Xóa', cancelButtonText: 'Hủy', confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b' }).then((result) => { if (result.isConfirmed) { window.location.href = url; } });
  }
</script>