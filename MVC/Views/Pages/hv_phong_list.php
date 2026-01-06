<?php
$lop = $data["lop"] ?? [];
$rows = $data["rows"] ?? [];
?>

<div class="page-head compact">
  <div class="page-title">PHÒNG THI CỦA BẠN</div>
  <div class="page-actions">
    <span>Lớp: <b><?= htmlspecialchars($lop["ma_lop"] ?? "") ?></b></span>
  </div>
</div>

<div class="box">
  <div class="box-sub">
    <div>Có <b><?= count($rows) ?></b> phòng thi khả dụng</div>
  </div>

  <table class="tbl">
    <thead>
      <tr>
        <th style="width:60px;text-align:center">STT</th>
        <th style="width:120px;text-align:center">Mã Phòng</th>
        <th>Tên Phòng</th>
        <th style="text-align:center">Môn thi</th>
        <th>Bài thi</th>
        <th style="width:160px;text-align:center">Lịch thi</th>
        <th style="width:100px;text-align:center">Thao tác</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($rows)): ?>
        <tr>
          <td colspan="7" style="text-align:center;padding:24px;color:var(--text-muted)">Chưa có phòng thi nào</td>
        </tr>
      <?php endif; ?>

      <?php foreach ($rows as $i => $r): ?>
        <tr>
          <td style="text-align:center"><?= $i + 1 ?></td>
          <td style="text-align:center"><?= htmlspecialchars($r["ma_phong"]) ?></td>
          <td><?= htmlspecialchars($r["ten_phong"]) ?></td>
          <td style="text-align:center"><?= htmlspecialchars($r["ten_mon"]) ?></td>
          <td><?= htmlspecialchars($r["ten_de"]) ?></td>
          <td style="text-align:center"><?= htmlspecialchars($r["bat_dau"]) ?></td>
          <td style="text-align:center">
            <a class="btn btn-success btn-sm" href="<?= BASE_URL ?>/index.php?url=HocvienController/confirm/<?= $r["id"] ?>">
              Làm bài
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>