<?php
$de = $data["de"] ?? [];
$mons = $data["mons"] ?? [];
$err = $data["err"] ?? "";
?>

<div class="page-head compact">
    <div class="page-title">SỬA ĐỀ THI</div>
</div>

<div class="box" style="max-width: 600px; margin: 0 auto;">
    <?php if ($err): ?>
        <div class="alert error">
            <?= $err ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>/index.php?url=DethiController/update">
        <input type="hidden" name="id" value="<?= $de["id"] ?>">

        <label>Mã đề</label>
        <input name="ma_de" value="<?= htmlspecialchars($de["ma_de"]) ?>" required>

        <label>Tên đề thi</label>
        <input name="ten_de" value="<?= htmlspecialchars($de["ten_de"]) ?>" required>

        <div class="grid-2">
            <div>
                <label>Thời gian (phút)</label>
                <input type="number" name="thoi_gian" value="<?= (int) $de["thoi_gian"] ?>" required min="1">
            </div>
            <div>
                <label>Môn thi</label>
                <select name="mon_id" required>
                    <?php foreach ($mons as $m): ?>
                        <option value="<?= $m["id"] ?>"
                            <?= $m["id"] == $de["mon_id"] ? "selected" : "" ?>>
                            <?= htmlspecialchars($m["ten_mon"]) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="hint">Lưu ý: Không thể sửa danh sách câu hỏi tại đây. Để thay đổi câu hỏi, vui lòng xóa đề và tạo
            lại.</div>

        <div class="actions" style="margin-top:20px; text-align:right;">
            <a href="<?= BASE_URL ?>/index.php?url=DethiController/index" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        </div>
    </form>
</div>