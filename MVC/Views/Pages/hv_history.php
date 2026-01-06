<?php $rows = $data["rows"] ?? []; ?>

<div class="page-head compact">
  <div class="page-title">LỊCH SỬ THI</div>
</div>

<div class="box">
  <div class="box-sub">
    <div>Tổng số: <b><?= count($rows) ?></b> bài thi</div>
  </div>

  <table class="tbl">
    <thead>
      <tr>
        <th style="width:60px;text-align:center">STT</th>
        <th style="width:80px;text-align:center">Mã</th>
        <th>Bài thi</th>
        <th style="width:100px;text-align:center">Câu đúng</th>
        <th style="width:100px;text-align:center">Điểm</th>
        <th style="width:120px;text-align:center">Xem đáp án</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($rows)): ?>
        <tr>
          <td colspan="6" style="text-align:center;padding:24px;color:var(--text-muted)">Chưa có bài thi nào</td>
        </tr>
      <?php endif; ?>

      <?php foreach ($rows as $i => $r): ?>
        <tr>
          <td style="text-align:center"><?= $i + 1 ?></td>
          <td style="text-align:center"><?= $r["id"] ?></td>
          <td><?= htmlspecialchars($r["ten_de"]) ?></td>
          <td style="text-align:center"><?= $r["correct_cnt"] ?>/<?= $r["total_cnt"] ?></td>
          <td style="text-align:center"><b><?= htmlspecialchars($r["score"]) ?></b></td>
          <td style="text-align:center">
            <a class="btn btn-primary btn-sm"
              href="<?= BASE_URL ?>/index.php?url=HocvienController/result/<?= $r["id"] ?>">Xem</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>