<?php
$msg=$data["msg"] ?? "";
$err=$data["err"] ?? "";
?>
<?php if($err): ?><div class="alert err"><?=htmlspecialchars($err)?></div><?php endif; ?>
<?php if($msg): ?><div class="alert ok"><?=htmlspecialchars($msg)?></div><?php endif; ?>

<div class="panel">
  <div class="panel-title">➕ THÊM TÀI KHOẢN</div>
  <div class="panel-actions">
    <a class="btn-green" href="<?=BASE_URL?>/index.php?url=TaikhoanController/index">Quay lại</a>
  </div>
</div>

<div class="box" style="padding:14px">
  <form method="post" action="<?=BASE_URL?>/index.php?url=TaikhoanController/store">
    <div class="row2">
      <div>
        <label>Họ tên</label>
        <input name="hoten" required>
      </div>
      <div>
        <label>Username</label>
        <input name="username" required>
      </div>
    </div>

    <div class="row2">
      <div>
        <label>Email</label>
        <input name="email" required>
      </div>
      <div>
        <label>Mật khẩu</label>
        <input name="password" required>
      </div>
    </div>

    <div class="row2">
      <div>
        <label>Role</label>
        <select name="role">
          <option value="admin">admin</option>
          <option value="hocvien">hocvien</option>
        </select>
      </div>
      <div>
        <label>Trạng thái</label>
        <select name="trangthai">
          <option value="1">Yes</option>
          <option value="0">No</option>
        </select>
      </div>
    </div>

    <button class="btn-green" type="submit">Lưu</button>
  </form>
</div>
