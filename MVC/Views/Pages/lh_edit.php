<?php
$lh=$data["lh"];
$msg=$data["msg"] ?? "";
$err=$data["err"] ?? "";
?>

<?php if($err): ?><div class="alert err"><?=htmlspecialchars($err)?></div><?php endif; ?>
<?php if($msg): ?><div class="alert ok"><?=htmlspecialchars($msg)?></div><?php endif; ?>

<div class="panel">
  <div class="panel-title">✏️ SỬA LỚP HỌC</div>
  <div class="panel-actions">
    <a class="btn-green" href="<?=BASE_URL?>/index.php?url=LophocController/index">Quay lại</a>
  </div>
</div>

<div class="box" style="padding:14px">
  <form method="post" action="<?=BASE_URL?>/index.php?url=LophocController/update">
    <input type="hidden" name="id" value="<?=$lh["id"]?>">

    <div class="row2">
      <div>
        <label>Mã lớp</label>
        <input name="ma_lop" value="<?=htmlspecialchars($lh["ma_lop"])?>" required>
      </div>
      <div>
        <label>Tên lớp</label>
        <input name="ten_lop" value="<?=htmlspecialchars($lh["ten_lop"])?>" required>
      </div>
    </div>

    <div class="row2">
      <div>
        <label>Trạng thái</label>
        <select name="trangthai">
          <option value="1" <?=((int)$lh["trangthai"]===1?'selected':'')?>>Yes</option>
          <option value="0" <?=((int)$lh["trangthai"]===0?'selected':'')?>>No</option>
        </select>
      </div>
      <div></div>
    </div>

    <button class="btn-green" type="submit">Lưu</button>
  </form>
</div>
