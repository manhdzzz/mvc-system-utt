<?php
$rows = $data["rows"] ?? [];
$mons = $data["mons"] ?? [];
$mon_id = (int) ($data["mon_id"] ?? 0);
$q = $data["q"] ?? "";
?>

<div class="page-head compact">
  <div class="page-title">QUẢN LÝ CÂU HỎI</div>

  <div class="page-actions">
    <a class="btn btn-primary btn-sm" href="<?= BASE_URL ?>/index.php?url=CauhoiController/template">Tải mẫu</a>
    <a class="btn btn-success btn-sm" href="<?= BASE_URL ?>/index.php?url=CauhoiController/create">Thêm mới</a>

    <form class="search-form" method="get" action="<?= BASE_URL ?>/index.php">
      <input type="hidden" name="url" value="CauhoiController/index">
      <input type="hidden" name="mon_id" value="<?= $mon_id ?>">
      <input name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Tìm nội dung / đáp án...">
      <button type="submit" class="btn-search">Go</button>
    </form>
  </div>
</div>

<div class="box">
  <div class="box-sub">
    <div>
      Môn:
      <select onchange="location.href='<?= BASE_URL ?>/index.php?url=CauhoiController/index&mon_id='+this.value">
        <option value="0">-- Chọn môn --</option>
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
        <th>Môn thi</th>
        <th>Câu hỏi</th>
        <th>A</th>
        <th>B</th>
        <th>C</th>
        <th>D</th>
        <th style="width:70px;text-align:center">Đáp án</th>
        <th style="width:60px;text-align:center">Điểm</th>
        <th>Giải thích</th>
        <th style="width:70px;text-align:center">Loại</th>
        <th style="width:80px;text-align:center">Kích hoạt</th>
        <th style="width:130px;text-align:center">Thao tác</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $i => $r): ?>
        <tr>
          <td style="text-align:center"><?= ($i + 1) + (($data['currPage'] ?? 1) - 1) * ($data['limit'] ?? 50) ?></td>
          <td><?= htmlspecialchars($r["noi_dung"]) ?></td>
          <td><?= htmlspecialchars($r["noi_dung"]) ?></td>
          <td><?= htmlspecialchars($r["dap_an_a"]) ?></td>
          <td><?= htmlspecialchars($r["dap_an_b"]) ?></td>
          <td><?= htmlspecialchars($r["dap_an_c"]) ?></td>
          <td><?= htmlspecialchars($r["dap_an_d"]) ?></td>
          <td style="text-align:center"><b><?= htmlspecialchars($r["dap_an_dung"]) ?></b></td>
          <td style="text-align:center"><?= (int) $r["diem"] ?></td>
          <td><?= htmlspecialchars($r["giai_thich"] ?? "") ?></td>
          <td style="text-align:center"><?= htmlspecialchars($r["loai"] ?? "") ?></td>
          <td style="text-align:center">
            <span
              class="pill <?= ((int) $r["kich_hoat"] === 1 ? 'yes' : 'no') ?>"><?= ((int) $r["kich_hoat"] === 1 ? 'Yes' : 'No') ?></span>
          </td>
          <td style="text-align:center">
            <a class="action-btn edit" href="<?= BASE_URL ?>/index.php?url=CauhoiController/edit/<?= $r["id"] ?>">Sửa</a>
            <a class="action-btn delete" href="#"
              onclick="confirmDelete('<?= BASE_URL ?>/index.php?url=CauhoiController/delete/<?= $r["id"] ?>', 'Xóa câu hỏi này?')">Xóa</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="pager">
    <?php for ($p = 1; $p <= ($data["totalPages"] ?? 1); $p++): ?>
      <a class="page <?= $p == ($data["currPage"] ?? 1) ? "active" : "" ?>"
        href="<?= BASE_URL ?>/index.php?url=CauhoiController/index&mon_id=<?= $mon_id ?>&q=<?= htmlspecialchars($q) ?>&page=<?= $p ?>">
        <?= $p ?>
      </a>
    <?php endfor; ?>
  </div>
</div>

<!-- MODAL IMPORT -->
<div id="modal" class="modal">
  <div class="modal-card">
    <div class="modal-head">
      <div>Import câu hỏi</div>
      <button class="x" onclick="closeModal()">X</button>
    </div>

    <form method="post" enctype="multipart/form-data" action="<?= BASE_URL ?>/index.php?url=CauhoiController/import">
      <label>Chọn file (CSV)</label>
      <input type="file" name="file" accept=".csv" required>
      <div class="hint">CSV format: ma_mon, noi_dung, ... (Tự động nhận diện mã môn)</div>

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