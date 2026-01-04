<?php
$phong=$data["phong"];
$lops=$data["lops"] ?? [];
$mons=$data["mons"] ?? [];
$des =$data["des"] ?? [];
$err =$data["err"] ?? "";
?>
<?php if($err): ?><div class="alert err"><?=htmlspecialchars($err)?></div><?php endif; ?>

<div class="page-head compact">
  <div class="page-title"><span class="icon">✏️</span><span>SỬA PHÒNG THI</span></div>
  <div class="page-actions">
    <a class="btn-gray btn-sm" href="<?=BASE_URL?>/index.php?url=PhongthiController/index&lop_id=<?=$phong["lop_id"]?>">Quay lại</a>
  </div>
</div>

<div class="box" style="padding:14px">
  <form method="post" action="<?=BASE_URL?>/index.php?url=PhongthiController/update">
    <input type="hidden" name="id" value="<?=$phong["id"]?>">

    <label>Mã Phòng</label>
    <input name="ma_phong" required value="<?=htmlspecialchars($phong["ma_phong"])?>">

    <label>Tên phòng</label>
    <input name="ten_phong" required value="<?=htmlspecialchars($phong["ten_phong"])?>">

    <label>Ngày bắt đầu thi</label>
    <?php
      $dtLocal="";
      if(!empty($phong["bat_dau"])) $dtLocal = str_replace(" ","T",$phong["bat_dau"]);
    ?>
    <input type="datetime-local" name="bat_dau" value="<?=htmlspecialchars($dtLocal)?>">

    <div class="row2">
      <div>
        <label>Môn</label>
        <select name="mon_id" required>
          <?php foreach($mons as $m): ?>
            <option value="<?=$m["id"]?>" <?=((int)$phong["mon_id"]===(int)$m["id"])?"selected":""?>>
              <?=htmlspecialchars($m["ten_mon"])?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label>Lớp</label>
        <select name="lop_id" required>
          <?php foreach($lops as $l): ?>
            <option value="<?=$l["id"]?>" <?=((int)$phong["lop_id"]===(int)$l["id"])?"selected":""?>>
              <?=htmlspecialchars($l["ma_lop"])?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <label>Bài thi (Đề thi)</label>
    <select name="de_id" required>
      <?php foreach($des as $d): ?>
        <option value="<?=$d["id"]?>" <?=((int)$phong["de_id"]===(int)$d["id"])?"selected":""?>>
          <?=htmlspecialchars($d["ten_de"])?> (<?=htmlspecialchars($d["ma_de"] ?? "")?>)
        </option>
      <?php endforeach; ?>
    </select>

    <label>Trạng thái</label>
    <select name="trangthai">
      <option value="1" <?=((int)$phong["trangthai"]===1)?"selected":""?>>Yes</option>
      <option value="0" <?=((int)$phong["trangthai"]===0)?"selected":""?>>No</option>
    </select>

    <button class="btn-green" type="submit">Cập nhật</button>
  </form>
</div>
