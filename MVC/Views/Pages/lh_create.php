<?php
$msg=$data["msg"] ?? "";
$err=$data["err"] ?? "";
?>
<?php if($err): ?><div class="alert err"><?=htmlspecialchars($err)?></div><?php endif; ?>
<?php if($msg): ?><div class="alert ok"><?=htmlspecialchars($msg)?></div><?php endif; ?>

<div class="panel">
  <div class="panel-title">➕ THÊM LỚP HỌC</div>
  <div class="panel-actions">
    <a class="btn-green" href="<?=BASE_URL?>/index.php?url=LophocController/index">Quay lại</a>
  </div>
</div>

<div class="box" style="padding:14px">
  <form method="post" action="<?=BASE_URL?>/index.php?url=LophocController/store">
    <div class="row2">
      <div>
        <label>Mã lớp</label>
        <input name="ma_lop" required placeholder="VD: CNT58DH">
      </div>
      <div>
        <label>Tên lớp</label>
        <input name="ten_lop" required placeholder="VD: CNT58DH">
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
