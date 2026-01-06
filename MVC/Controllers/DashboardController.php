<?php
class DashboardController extends Controller
{

    public function index()
    {
        $this->needAdmin();


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


        $st = $con->query("SELECT COUNT(*) as cnt FROM hoc_vien");
        $hocvien = $st->fetch()["cnt"];


        $st = $con->query("SELECT COUNT(*) as cnt FROM lop_hoc");
        $lop = $st->fetch()["cnt"];


        $st = $con->query("SELECT COUNT(*) as cnt FROM mon_thi");
        $mon = $st->fetch()["cnt"];


        $st = $con->query("SELECT COUNT(*) as cnt FROM de_thi");
        $de = $st->fetch()["cnt"];


        $st = $con->query("SELECT COUNT(*) as cnt FROM cau_hoi");
        $cauhoi = $st->fetch()["cnt"];


        $st = $con->query("SELECT COUNT(*) as cnt FROM phong_thi");
        $phongthi = $st->fetch()["cnt"];


        $st = $con->query("SELECT COUNT(*) as cnt FROM bai_lam WHERE status='Done'");
        $bailam = $st->fetch()["cnt"];


        $st = $con->query("SELECT COUNT(*) as cnt FROM phong_thi_hoc_vien WHERE trang_thai LIKE 'Đang thi'");
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
