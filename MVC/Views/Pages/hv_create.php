<?php $lops=$data["lops"] ?? []; $err=$data["err"] ?? ""; ?>
<?php if($err): ?><div class="alert err"><?=htmlspecialchars($err)?></div><?php endif; ?>

<div class="panel">
  <div class="panel-title">➕ THÊM HỌC VIÊN</div>
  <div class="panel-actions">
    <a class="btn-green" href="<?=BASE_URL?>/index.php?url=HocvienController/index">Quay lại</a>
  </div>
</div>

<div class="box" style="padding:14px">
  <form method="post" action="<?=BASE_URL?>/index.php?url=HocvienController/store">
    <div class="row2">
      <div>
        <label>Họ tên</label>
        <input name="hoten" required>
      </div>
      <div>
        <label>Mã học viên</label>
        <input name="ma_hv" required>
      </div>
    </div>

    <div class="row2">
      <div>
        <label>Mật khẩu</label>
        <input name="matkhau" required>
      </div>
      <div>
        <label>Lớp</label>
        <select name="lop_id" required>
          <option value="">-- Chọn lớp --</option>
          <?php foreach($lops as $l): ?>
            <option value="<?=$l["id"]?>"><?=htmlspecialchars($l["ma_lop"])?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="row2">
      <div>
        <label>Trạng thái</label>
        <select name="trangthai">
          <option value="1">Yes</option>
          <option value="0">No</option>
        </select>
      </div>
      <div></div>
    </div>

    <button class="btn-green" type="submit">Lưu</button>
  </form>
</div>
