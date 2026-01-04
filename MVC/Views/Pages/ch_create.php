<?php
$mons = $data["mons"] ?? [];
$err  = $data["err"] ?? "";
?>
<?php if($err): ?><div class="alert err"><?=htmlspecialchars($err)?></div><?php endif; ?>

<div class="page-head compact">
  <div class="page-title"><span class="icon">➕</span><span>THÊM CÂU HỎI</span></div>
  <div class="page-actions">
    <a class="btn-gray btn-sm" href="<?=BASE_URL?>/index.php?url=CauhoiController/index">Quay lại</a>
  </div>
</div>

<div class="box" style="padding:14px">
  <form method="post" action="<?=BASE_URL?>/index.php?url=CauhoiController/store">

    <div class="row2">
      <div>
        <label>Môn thi</label>
        <select name="mon_id" required>
          <option value="">-- Chọn môn --</option>
          <?php foreach($mons as $m): ?>
            <option value="<?=$m["id"]?>"><?=htmlspecialchars($m["ten_mon"])?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label>Loại</label>
        <select name="loai">
          <option value="D">D</option>
          <option value="TB">TB</option>
          <option value="K">K</option>
        </select>
      </div>
    </div>

    <div>
      <label>Câu hỏi</label>
      <input name="noi_dung" required placeholder="VD: 2 + 4 = ?">
    </div>

    <div class="row2">
      <div>
        <label>Đáp án A</label>
        <input name="a" required>
      </div>
      <div>
        <label>Đáp án B</label>
        <input name="b" required>
      </div>
    </div>

    <div class="row2">
      <div>
        <label>Đáp án C</label>
        <input name="c" required>
      </div>
      <div>
        <label>Đáp án D</label>
        <input name="d" required>
      </div>
    </div>

    <div class="row3">
      <div>
        <label>Đáp án đúng</label>
        <select name="dap_an_dung">
          <option value="A">A</option>
          <option value="B">B</option>
          <option value="C">C</option>
          <option value="D">D</option>
        </select>
      </div>

      <div>
        <label>Điểm</label>
        <input type="number" name="diem" value="1" min="1">
      </div>

      <div>
        <label>Kích hoạt</label>
        <select name="kich_hoat">
          <option value="1">Yes</option>
          <option value="0">No</option>
        </select>
      </div>
    </div>

    <div>
      <label>Giải thích</label>
      <textarea name="giai_thich" rows="3" placeholder="(có thể bỏ trống)"></textarea>
    </div>

    <button class="btn-green" type="submit">Lưu</button>
  </form>
</div>
