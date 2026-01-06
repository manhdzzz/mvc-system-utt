<?php
$phong = $data["phong"];
$so_cau = (int) $data["so_cau"];
$so_lan = (int) $data["so_lan_thi"];
?>

<div class="confirm-bg">
  <div class="confirm-card">
    <div class="confirm-title"><?= htmlspecialchars($phong["ten_de"]) ?></div>

    <div class="confirm-row">Môn thi: <b><?= htmlspecialchars($phong["ten_mon"] ?? "") ?></b></div>
    <div class="confirm-row">Số câu hỏi: <b><?= $so_cau ?></b></div>
    <div class="confirm-row">Thời gian: <b><?= (int) $phong["time_phut"] ?> phút</b></div>
    <div class="confirm-row">Số lần thi: <b><?= $so_lan ?></b></div>

    <form method="post" action="<?= BASE_URL ?>/index.php?url=HocvienController/start" style="margin-top:20px;">
      <input type="hidden" name="phong_id" value="<?= $phong["id"] ?>">
      <button class="btn btn-success" type="submit" style="width:100%">Bắt đầu làm bài</button>
    </form>

    <a class="confirm-back" href="<?= BASE_URL ?>/index.php?url=HocvienController/phongthi">Quay lại</a>
  </div>
</div>