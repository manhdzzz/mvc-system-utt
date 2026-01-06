<?php
$stats = $data["stats"] ?? [];
?>

<div class="page-head compact">
    <div class="page-title">DASHBOARD - TỔNG QUAN HỆ THỐNG</div>
</div>

<style>
    .dash-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-top: 20px;
    }

    .dash-card {
        background: var(--bg-secondary);
        border-radius: var(--radius-lg);
        padding: 20px;
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 16px;
        transition: var(--transition);
    }

    .dash-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
        border-color: var(--accent-primary);
    }

    .dash-icon {
        width: 56px;
        height: 56px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        font-weight: 700;
        color: #fff;
    }

    .dash-icon.blue {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
    }

    .dash-icon.green {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    }

    .dash-icon.purple {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    }

    .dash-icon.orange {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
    }

    .dash-icon.pink {
        background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
    }

    .dash-icon.cyan {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
    }

    .dash-icon.red {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }

    .dash-icon.yellow {
        background: linear-gradient(135deg, #eab308 0%, #ca8a04 100%);
    }

    .dash-info h3 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: var(--text-primary);
    }

    .dash-info p {
        margin: 4px 0 0;
        font-size: 13px;
        color: var(--text-secondary);
    }

    .dash-section {
        margin-top: 30px;
    }

    .dash-section-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-secondary);
        margin-bottom: 16px;
    }

    @media (max-width: 1000px) {
        .dash-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 600px) {
        .dash-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="dash-section">
    <div class="dash-section-title">Thống kê tổng quan</div>
    <div class="dash-grid">
        <div class="dash-card">
            <div class="dash-icon blue">HV</div>
            <div class="dash-info">
                <h3><?= $stats["hocvien"] ?? 0 ?></h3>
                <p>Học viên</p>
            </div>
        </div>

        <div class="dash-card">
            <div class="dash-icon green">LH</div>
            <div class="dash-info">
                <h3><?= $stats["lop"] ?? 0 ?></h3>
                <p>Lớp học</p>
            </div>
        </div>

        <div class="dash-card">
            <div class="dash-icon purple">MT</div>
            <div class="dash-info">
                <h3><?= $stats["mon"] ?? 0 ?></h3>
                <p>Môn thi</p>
            </div>
        </div>

        <div class="dash-card">
            <div class="dash-icon orange">DT</div>
            <div class="dash-info">
                <h3><?= $stats["de"] ?? 0 ?></h3>
                <p>Đề thi</p>
            </div>
        </div>

        <div class="dash-card">
            <div class="dash-icon pink">CH</div>
            <div class="dash-info">
                <h3><?= $stats["cauhoi"] ?? 0 ?></h3>
                <p>Câu hỏi</p>
            </div>
        </div>

        <div class="dash-card">
            <div class="dash-icon cyan">PT</div>
            <div class="dash-info">
                <h3><?= $stats["phongthi"] ?? 0 ?></h3>
                <p>Phòng thi</p>
            </div>
        </div>

        <div class="dash-card">
            <div class="dash-icon yellow">BL</div>
            <div class="dash-info">
                <h3><?= $stats["bailam"] ?? 0 ?></h3>
                <p>Bài đã nộp</p>
            </div>
        </div>

        <div class="dash-card">
            <div class="dash-icon red">TH</div>
            <div class="dash-info">
                <h3><?= $stats["dangthi"] ?? 0 ?></h3>
                <p>Đang thi</p>
            </div>
        </div>
    </div>
</div>

<div class="dash-section">
    <div class="dash-section-title">Truy cập nhanh</div>
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
        <a href="<?= BASE_URL ?>/index.php?url=PhongthiController/index" class="btn btn-primary">Quản lý Phòng thi</a>
        <a href="<?= BASE_URL ?>/index.php?url=DethiController/index" class="btn btn-primary">Quản lý Đề thi</a>
        <a href="<?= BASE_URL ?>/index.php?url=CauhoiController/index" class="btn btn-primary">Ngân hàng Câu hỏi</a>
        <a href="<?= BASE_URL ?>/index.php?url=MonthiController/index" class="btn btn-primary">Quản lý Môn thi</a>
        <a href="<?= BASE_URL ?>/index.php?url=HocvienAdminController/index" class="btn btn-primary">Quản lý Học viên</a>
    </div>
</div>