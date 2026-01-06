<?php
$bl = $data["bl"];
$qs = $data["qs"] ?? [];
?>

<div class="page-head compact">
  <div class="page-title">KẾT QUẢ BÀI THI</div>
  <div class="page-actions">
    <a class="btn btn-secondary btn-sm" href="<?= BASE_URL ?>/index.php?url=HocvienController/history">Quay lại</a>
  </div>
</div>

<div class="box" style="padding:20px">
  <div
    style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--border)">
    <div>
      <h3 style="margin:0;color:var(--text-primary)"><?= htmlspecialchars($bl["ten_de"]) ?></h3>
      <div style="color:var(--text-secondary);margin-top:4px"><?= htmlspecialchars($bl["ten_mon"] ?? "") ?></div>
    </div>
    <div style="text-align:right">
      <div style="font-size:28px;font-weight:700;color:var(--accent-primary)"><?= $bl["score"] ?></div>
      <div style="color:var(--text-secondary);font-size:13px">Đúng: <?= $bl["correct_cnt"] ?>/<?= $bl["total_cnt"] ?>
      </div>
    </div>
  </div>
</div>

<style>
  .result-item {
    background: var(--bg-tertiary);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 20px;
    margin-bottom: 16px;
  }

  .result-item.correct {
    border-left: 4px solid var(--success);
  }

  .result-item.wrong {
    border-left: 4px solid var(--danger);
  }

  .result-question {
    font-weight: 600;
    margin-bottom: 12px;
    color: var(--text-primary);
  }

  .result-answer {
    color: var(--text-secondary);
    font-size: 14px;
    margin-bottom: 8px;
  }

  .result-answer b {
    color: var(--text-primary);
  }

  .result-tag {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
  }

  .result-tag.ok {
    background: rgba(34, 197, 94, 0.2);
    color: var(--success);
  }

  .result-tag.err {
    background: rgba(239, 68, 68, 0.2);
    color: var(--danger);
  }

  .result-explain {
    margin-top: 12px;
    padding: 12px;
    background: rgba(59, 130, 246, 0.1);
    border-radius: var(--radius-sm);
    font-size: 13px;
    color: var(--text-secondary);
  }
</style>

<?php foreach ($qs as $i => $q):
  $chon = $q["chon"] ?? "";
  $dapan = $q["dap_an"] ?? "";
  $dung = (int) ($q["dung"] ?? 0) === 1;

  $opts = ["A" => $q["a"] ?? "", "B" => $q["b"] ?? "", "C" => $q["c"] ?? "", "D" => $q["d"] ?? ""];
  $chonText = ($chon && isset($opts[$chon])) ? $opts[$chon] : "";
  $dapanText = ($dapan && isset($opts[$dapan])) ? $opts[$dapan] : "";
  ?>
  <div class="result-item <?= $dung ? 'correct' : 'wrong' ?>">
    <div class="result-question">Câu <?= $i + 1 ?>: <?= htmlspecialchars($q["noi_dung"]) ?></div>

    <div class="result-answer">
      <b>Bạn chọn:</b>
      <?php if ($chon): ?>
        <span
          style="color:<?= $dung ? 'var(--success)' : 'var(--danger)' ?>;font-weight:700"><?= htmlspecialchars($chon) ?></span>
        - <?= htmlspecialchars($chonText) ?>
      <?php else: ?>
        <span style="color:var(--text-muted)">Không trả lời</span>
      <?php endif; ?>
    </div>

    <div class="result-answer">
      <b>Đáp án đúng:</b>
      <span style="color:var(--success);font-weight:700"><?= htmlspecialchars($dapan) ?></span>
      - <?= htmlspecialchars($dapanText) ?>
    </div>

    <span class="result-tag <?= $dung ? 'ok' : 'err' ?>"><?= $dung ? 'Đúng' : 'Sai' ?></span>

    <?php if (!empty($q["giai_thich"])): ?>
      <div class="result-explain">
        <b>Giải thích:</b> <?= htmlspecialchars($q["giai_thich"]) ?>
      </div>
    <?php endif; ?>
  </div>
<?php endforeach; ?>