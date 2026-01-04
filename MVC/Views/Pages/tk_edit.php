<?php
$u=$data["u"];
$msg=$data["msg"] ?? "";
$err=$data["err"] ?? "";
?>

<?php if($err): ?><div class="alert err"><?=htmlspecialchars($err)?></div><?php endif; ?>
<?php if($msg): ?><div class="alert ok"><?=htmlspecialchars($msg)?></div><?php endif; ?>

<div class="panel">
  <div class="panel-title">✏️ SỬA TÀI KHOẢN</div>
  <div class="panel-actions">
    <a class="btn-green" href="<?=BASE_URL?>/index.php?url=TaikhoanController/index">Quay lại</a>
  </div>
</div>

<div class="box" style="padding:14px">
  <form method="post" action="<?=BASE_URL?>/index.php?url=TaikhoanController/update">
    <input type="hidden" name="id" value="<?=$u["id"]?>">

    <div class="row2">
      <div>
        <label>Họ tên</label>
        <input name="hoten" value="<?=htmlspecialchars($u["hoten"])?>" required>
      </div>
      <div>
        <label>Username</label>
        <input name="username" value="<?=htmlspecialchars($u["username"])?>" required>
      </div>
    </div>

    <div class="row2">
      <div>
        <label>Email</label>
        <input name="email" value="<?=htmlspecialchars($u["email"])?>" required>
      </div>
      <div>
        <label>Role</label>
        <select name="role">
          <option value="admin" <?=($u["role"]==="admin"?'selected':'')?>>admin</option>
          <option value="hocvien" <?=($u["role"]==="hocvien"?'selected':'')?>>hocvien</option>
        </select>
      </div>
    </div>

    <div class="row2">
      <div>
        <label>Trạng thái</label>
        <select name="trangthai">
          <option value="1" <?=((int)$u["trangthai"]===1?'selected':'')?>>Yes</option>
          <option value="0" <?=((int)$u["trangthai"]===0?'selected':'')?>>No</option>
        </select>
      </div>
      <div></div>
    </div>

    <button class="btn-green" type="submit">Lưu</button>
  </form>
</div>
