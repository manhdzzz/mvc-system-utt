<?php
$rows = $data["rows"] ?? [];
$q = $data["q"] ?? "";
?>

<div class="page-head compact">
    <div class="page-title">QUẢN LÝ MÔN THI</div>

    <div class="page-actions">
        <a class="btn btn-primary btn-sm" href="<?= BASE_URL ?>/index.php?url=MonthiController/template">Tải mẫu</a>
        <button class="btn btn-success btn-sm" type="button" onclick="openAddModal()">Thêm mới</button>

        <form class="search-form" method="get" action="<?= BASE_URL ?>/index.php">
            <input type="hidden" name="url" value="MonthiController/index">
            <input name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Tìm mã / tên môn...">
            <button type="submit" class="btn-search">Go</button>
        </form>
    </div>
</div>

<div class="box">
    <div class="box-sub">
        <div>Tổng số <b><?= count($rows) ?></b> môn thi</div>
        <button class="btn btn-warning btn-sm" type="button" onclick="openModal()">Import file</button>
    </div>

    <table class="tbl">
        <thead>
            <tr>
                <th style="width:60px;text-align:center">STT</th>
                <th style="width:120px;text-align:center">Mã môn</th>
                <th>Tên môn thi</th>
                <th style="width:100px;text-align:center">Số câu hỏi</th>
                <th style="width:100px;text-align:center">Số đề thi</th>
                <th style="width:130px;text-align:center">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $i => $r): ?>
                <tr>
                    <td style="text-align:center"><?= $i + 1 ?></td>
                    <td style="text-align:center"><?= htmlspecialchars($r["ma_mon"]) ?></td>
                    <td><?= htmlspecialchars($r["ten_mon"]) ?></td>
                    <td style="text-align:center"><?= (int) $r["so_cau"] ?></td>
                    <td style="text-align:center"><?= (int) $r["so_de"] ?></td>
                    <td style="text-align:center">
                        <a class="action-btn edit"
                            href="<?= BASE_URL ?>/index.php?url=MonthiController/edit/<?= $r["id"] ?>">Sửa</a>
                        <a class="action-btn delete" onclick="return confirm('Xóa môn thi này?')"
                            href="<?= BASE_URL ?>/index.php?url=MonthiController/delete/<?= $r["id"] ?>">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($rows)): ?>
                <tr>
                    <td colspan="6" style="text-align:center;padding:24px;color:var(--text-muted)">Chưa có môn thi nào</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="pager"><a class="page active" href="#">1</a></div>
</div>

<!-- MODAL THÊM -->
<div id="modalAdd" class="modal">
    <div class="modal-card">
        <div class="modal-head">
            <div>Thêm môn thi</div>
            <button class="x" onclick="closeAddModal()">X</button>
        </div>

        <form method="post" action="<?= BASE_URL ?>/index.php?url=MonthiController/store">
            <label>Mã môn</label>
            <input name="ma_mon" placeholder="VD: TOAN, LY, HOA..." required>

            <label>Tên môn thi</label>
            <input name="ten_mon" placeholder="VD: Toán học, Vật lý..." required>

            <div class="modal-actions">
                <button class="btn btn-success" type="submit">Thêm mới</button>
                <button class="btn btn-secondary" type="button" onclick="closeAddModal()">Hủy</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL IMPORT -->
<div id="modal" class="modal">
    <div class="modal-card">
        <div class="modal-head">
            <div>Import môn thi</div>
            <button class="x" onclick="closeModal()">X</button>
        </div>

        <form method="post" enctype="multipart/form-data" action="<?= BASE_URL ?>/index.php?url=MonthiController/import">
            <label>Chọn file (CSV)</label>
            <input type="file" name="file" accept=".csv" required>

            <div class="hint">CSV format: ma_mon, ten_mon</div>

            <div class="modal-actions">
                <button class="btn btn-success" type="submit">Import</button>
                <button class="btn btn-secondary" type="button" onclick="closeModal()">Hủy</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() { document.getElementById('modalAdd').classList.add('show'); }
    function closeAddModal() { document.getElementById('modalAdd').classList.remove('show'); }
    function openModal() { document.getElementById('modal').classList.add('show'); }
    function closeModal() { document.getElementById('modal').classList.remove('show'); }
</script>