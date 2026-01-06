<?php
$hv=$data["hv"];
$lops=$data["lops"] ?? [];
$err=$data["err"] ?? "";
?>
<?php if($err): ?><div class="alert err"><?=htmlspecialchars($err)?></div><?php endif; ?>

<div class="panel">
  <div class="panel-title">✏️ SỬA HỌC VIÊN</div>
  <div class="panel-actions">
    <a class="btn-green" href="<?=BASE_URL?>/index.php?url=HocvienController/index&lop_id=<?=$hv["lop_id"]?>">Quay lại</a>
  </div>
</div>

<div class="box" style="padding:14px">
  <form method="post" action="<?=BASE_URL?>/index.php?url=HocvienController/update">
    <input type="hidden" name="id" value="<?=$hv["id"]?>">

    <div class="row2">
      <div>
        <label>Họ tên</label>
        <input name="hoten" value="<?=htmlspecialchars($hv["hoten"])?>" required>
      </div>
      <div>
        <label>Mã học viên</label>
        <input name="ma_hv" value="<?=htmlspecialchars($hv["ma_hv"])?>" required>
      </div>
    </div>

    <div class="row2">
      <div>
        <label>Mật khẩu</label>
        <input name="matkhau" value="<?=htmlspecialchars($hv["matkhau"])?>" required>
      </div>
      <div>
        <label>Lớp</label>
        <select name="lop_id" required>
          <?php foreach($lops as $l): ?>
            <option value="<?=$l["id"]?>" <?=$hv["lop_id"]==((int)$l["id"])?"selected":""?>>
              <?=htmlspecialchars($l["ma_lop"])?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="row2">
      <div>
        <label>Trạng thái</label>
        <select name="trangthai">
          <option value="1" <?=((int)$hv["trangthai"]===1?'selected':'')?>>Yes</option>
          <option value="0" <?=((int)$hv["trangthai"]===0?'selected':'')?>>No</option>
        </select>
      </div>
      <div></div>
    </div>

    <button class="btn-green" type="submit">Lưu</button>
  </form>
</div>
