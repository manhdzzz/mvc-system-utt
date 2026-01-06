<?php
$phong = $data["phong"];
$rows = $data["rows"] ?? [];
$de_info = $data["de_info"] ?? [];
?>

<div class="page-head">
    <div class="page-title">
        <span>GIÁM SÁT THI -
            <?= htmlspecialchars($phong["ten_phong"]) ?>
        </span>
    </div>
    <div class="page-actions">
        <a href="<?= BASE_URL ?>/index.php?url=PhongthiController/vaoPhong/<?= $phong["id"] ?>" class="btn-gray">Quay
            lại</a>
        <a href="<?= BASE_URL ?>/index.php?url=PhongthiController/exportExcel/<?= $phong["id"] ?>"
            class="btn-green btn-sm">Xuất Excel</a>
    </div>
</div>

<style>
    .monitor-info {
        display: flex;
        gap: 20px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }

    .monitor-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 16px 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .monitor-card .num {
        font-size: 28px;
        font-weight: 700;
        color: #0ea5e9;
    }

    .monitor-card .label {
        font-size: 13px;
        color: #64748b;
    }

    .monitor-card.green .num {
        color: #22c55e;
    }

    .monitor-card.yellow .num {
        color: #f59e0b;
    }

    .monitor-card.red .num {
        color: #ef4444;
    }

    .status-doing {
        background: #fef3c7;
        color: #92400e;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-done {
        background: #dcfce7;
        color: #166534;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-pending {
        background: #f1f5f9;
        color: #64748b;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
    }

    .progress-bar {
        width: 100px;
        height: 8px;
        background: #e2e8f0;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-bar .fill {
        height: 100%;
        background: #22c55e;
        transition: width 0.3s;
    }

    .auto-refresh {
        font-size: 12px;
        color: #64748b;
        margin-left: auto;
    }
</style>

<?php
$total = count($rows);
$doing = 0;
$done = 0;
foreach ($rows as $r) {
    if (($r["trang_thai"] ?? "") === "Đã nộp")
        $done++;
    elseif (($r["bl_status"] ?? "") === "Doing")
        $doing++;
}
$pending = $total - $doing - $done;
?>

<div class="monitor-info">
    <div class="monitor-card">
        <div class="num">
            <?= $total ?>
        </div>
        <div class="label">Tổng học viên</div>
    </div>
    <div class="monitor-card yellow">
        <div class="num">
            <?= $doing ?>
        </div>
        <div class="label">Đang thi</div>
    </div>
    <div class="monitor-card green">
        <div class="num">
            <?= $done ?>
        </div>
        <div class="label">Đã nộp</div>
    </div>
    <div class="monitor-card red">
        <div class="num">
            <?= $pending ?>
        </div>
        <div class="label">Chưa thi</div>
    </div>
    <div class="auto-refresh">
        Tự động cập nhật mỗi 10 giây
    </div>
</div>

<div class="box">
    <table class="tbl">
        <thead>
            <tr>
                <th>STT</th>
                <th>Mã HV</th>
                <th>Họ tên</th>
                <th>Trạng thái</th>
                <th>Tiến độ</th>
                <th>Số câu đã làm</th>
                <th>Vi phạm</th>
                <th>Điểm</th>
                <th>Thời gian vào</th>
            </tr>
        </thead>
        <tbody id="monitor-body">
            <?php foreach ($rows as $i => $r):
                $status = $r["trang_thai"] ?? "";
                $bl_status = $r["bl_status"] ?? "";
                $so_da_lam = (int) ($r["so_da_lam"] ?? 0);
                $tong_cau = (int) ($de_info["tong_cau"] ?? 10);
                $pct = $tong_cau > 0 ? round($so_da_lam / $tong_cau * 100) : 0;
                ?>
                <tr>
                    <td style="text-align:center">
                        <?= $i + 1 ?>
                    </td>
                    <td style="text-align:center">
                        <?= htmlspecialchars($r["ma_hv"] ?? "") ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($r["hoten"] ?? "") ?>
                    </td>
                    <td style="text-align:center">
                        <?php if ($status === "Đã nộp"): ?>
                            <span class="status-done">Đã nộp</span>
                        <?php elseif ($bl_status === "Doing"): ?>
                            <span class="status-doing">Đang thi</span>
                        <?php else: ?>
                            <span class="status-pending">Chưa thi</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align:center">
                        <?php if ($bl_status === "Doing" || $status === "Đã nộp"): ?>
                            <div class="progress-bar">
                                <div class="fill" style="width:<?= $pct ?>%"></div>
                            </div>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td style="text-align:center">
                        <?php if ($bl_status === "Doing" || $status === "Đã nộp"): ?>
                            <?= $so_da_lam ?>/
                            <?= $tong_cau ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td style="text-align:center">
                        <?= (int) ($r["so_lan_vi_pham"] ?? 0) ?>
                    </td>
                    <td style="text-align:center">
                        <?php if ($status === "Đã nộp"): ?>
                            <b>
                                <?= $r["diem"] ?? 0 ?>
                            </b>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td style="text-align:center">
                        <?= htmlspecialchars($r["thoi_gian_vao"] ?? "-") ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    // Auto refresh every 10 seconds
    setTimeout(function () {
        location.reload();
    }, 10000);
</script>