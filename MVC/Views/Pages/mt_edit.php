<?php
$mon = $data["mon"];
$err = $data["err"] ?? "";
?>
<?php if ($err): ?>
    <div class="alert err">
        <?= htmlspecialchars($err) ?>
    </div>
<?php endif; ?>

<div class="page-head">
    <div class="page-title">
        <span>SỬA MÔN THI</span>
    </div>
    <div class="page-actions">
        <a href="<?= BASE_URL ?>/index.php?url=MonthiController/index" class="btn-gray">← Quay lại</a>
    </div>
</div>

<div class="box" style="padding:20px;max-width:500px">
    <form method="post" action="<?= BASE_URL ?>/index.php?url=MonthiController/update">
        <input type="hidden" name="id" value="<?= $mon["id"] ?>">

        <div style="margin-bottom:16px">
            <label>Mã môn</label>
            <input type="text" name="ma_mon" value="<?= htmlspecialchars($mon["ma_mon"]) ?>" required>
        </div>

        <div style="margin-bottom:16px">
            <label>Tên môn thi</label>
            <input type="text" name="ten_mon" value="<?= htmlspecialchars($mon["ten_mon"]) ?>" required>
        </div>

        <div style="display:flex;gap:12px">
            <a href="<?= BASE_URL ?>/index.php?url=MonthiController/index" class="btn-gray">Hủy</a>
            <button type="submit" class="btn-green">Cập nhật</button>
        </div>
    </form>
</div>