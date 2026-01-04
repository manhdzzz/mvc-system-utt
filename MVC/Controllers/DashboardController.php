<?php
class DashboardController extends Controller
{

    private function needLogin()
    {
        if (empty($_SESSION["user"])) {
            header("Location: " . BASE_URL . "/index.php?url=AuthController/login");
            exit;
        }
    }

    public function index()
    {
        $this->needLogin();

        // Lấy thống kê
        $stats = $this->getStats();

        $this->view("layout_admin", [
            "page" => "Pages/dashboard",
            "stats" => $stats
        ]);
    }

    private function getStats()
    {
        $db = new Database();
        $con = $db->getConnection();

        // Đếm học viên
        $st = $con->query("SELECT COUNT(*) as cnt FROM hoc_vien");
        $hocvien = $st->fetch()["cnt"];

        // Đếm lớp học
        $st = $con->query("SELECT COUNT(*) as cnt FROM lop_hoc");
        $lop = $st->fetch()["cnt"];

        // Đếm môn thi
        $st = $con->query("SELECT COUNT(*) as cnt FROM mon_thi");
        $mon = $st->fetch()["cnt"];

        // Đếm đề thi
        $st = $con->query("SELECT COUNT(*) as cnt FROM de_thi");
        $de = $st->fetch()["cnt"];

        // Đếm câu hỏi
        $st = $con->query("SELECT COUNT(*) as cnt FROM cau_hoi");
        $cauhoi = $st->fetch()["cnt"];

        // Đếm phòng thi
        $st = $con->query("SELECT COUNT(*) as cnt FROM phong_thi");
        $phongthi = $st->fetch()["cnt"];

        // Đếm bài làm hoàn thành
        $st = $con->query("SELECT COUNT(*) as cnt FROM bai_lam WHERE status='Done'");
        $bailam = $st->fetch()["cnt"];

        // Đếm đang thi
        $st = $con->query("SELECT COUNT(*) as cnt FROM bai_lam WHERE status='Doing'");
        $dangthi = $st->fetch()["cnt"];

        return [
            "hocvien" => $hocvien,
            "lop" => $lop,
            "mon" => $mon,
            "de" => $de,
            "cauhoi" => $cauhoi,
            "phongthi" => $phongthi,
            "bailam" => $bailam,
            "dangthi" => $dangthi
        ];
    }
}
