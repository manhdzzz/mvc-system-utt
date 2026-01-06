<?php
$mons = $data["mons"] ?? [];
$ch = $data["ch"] ?? null;
$err = $data["err"] ?? "";
if (!$ch)
  die("Không có dữ liệu câu hỏi");
?>
<?php if ($err): ?>
  <div class="alert err"><?= htmlspecialchars($err) ?></div><?php endif; ?>

<div class="page-head compact">
  <div class="page-title"><span>SỬA CÂU HỎI</span></div>
  <div class="page-actions">
    <a class="btn-gray btn-sm" href="<?= BASE_URL ?>/index.php?url=CauhoiController/index&mon_id=<?= $ch["mon_id"] ?>">Quay
      lại</a>
  </div>
</div>

<div class="box" style="padding:14px">
  <form method="post" action="<?= BASE_URL ?>/index.php?url=CauhoiController/update">
    <input type="hidden" name="id" value="<?= $ch["id"] ?>">

    <div class="row2">
      <div>
        <label>Môn thi</label>
        <select name="mon_id" required>
          <?php foreach ($mons as $m): ?>
            <option value="<?= $m["id"] ?>" <?= ((int) $ch["mon_id"] === (int) $m["id"]) ? "selected" : "" ?>>
              <?= htmlspecialchars($m["ten_mon"]) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label>Loại</label>
        <select name="loai">
          <?php
          $loai = $ch["loai"] ?? "D";
          ?>
          <option value="D" <?= $loai === "D" ? "selected" : "" ?>>D</option>
          <option value="TB" <?= $loai === "TB" ? "selected" : "" ?>>TB</option>
          <option value="K" <?= $loai === "K" ? "selected" : "" ?>>K</option>
        </select>
      </div>
    </div>

    <div>
      <label>Câu hỏi</label>
      <input name="noi_dung" required value="<?= htmlspecialchars($ch["noi_dung"]) ?>">
    </div>

    <div class="row2">
      <div>
        <label>Đáp án A</label>
        <input name="a" required value="<?= htmlspecialchars($ch["dap_an_a"]) ?>">
      </div>
      <div>
        <label>Đáp án B</label>
        <input name="b" required value="<?= htmlspecialchars($ch["dap_an_b"]) ?>">
      </div>
    </div>

    <div class="row2">
      <div>
        <label>Đáp án C</label>
        <input name="c" required value="<?= htmlspecialchars($ch["dap_an_c"]) ?>">
      </div>
      <div>
        <label>Đáp án D</label>
        <input name="d" required value="<?= htmlspecialchars($ch["dap_an_d"]) ?>">
      </div>
    </div>

    <div class="row3">
      <div>
        <label>Đáp án đúng</label>
        <?php $dad = strtoupper($ch["dap_an_dung"] ?? "A"); ?>
        <select name="dap_an_dung">
          <option value="A" <?= $dad === "A" ? "selected" : "" ?>>A</option>
          <option value="B" <?= $dad === "B" ? "selected" : "" ?>>B</option>
          <option value="C" <?= $dad === "C" ? "selected" : "" ?>>C</option>
          <option value="D" <?= $dad === "D" ? "selected" : "" ?>>D</option>
        </select>
      </div>

      <div>
        <label>Điểm</label>
        <input type="number" name="diem" min="1" value="<?= (int) ($ch["diem"] ?? 1) ?>">
      </div>

      <div>
        <label>Kích hoạt</label>
        <select name="kich_hoat">
          <option value="1" <?= ((int) $ch["kich_hoat"] === 1) ? "selected" : "" ?>>Yes</option>
          <option value="0" <?= ((int) $ch["kich_hoat"] === 0) ? "selected" : "" ?>>No</option>
        </select>
      </div>
    </div>

    <div>
      <label>Giải thích</label>
      <textarea name="giai_thich" rows="3"><?= htmlspecialchars($ch["giai_thich"] ?? "") ?></textarea>
    </div>

    <button class="btn-green" type="submit">Cập nhật</button>
  </form>
</div>