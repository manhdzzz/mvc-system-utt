<?php
class PhongthiController extends Controller
{


  public function index()
  {
    $this->needGV();
    $lop_id = (int) ($_GET["lop_id"] ?? 0);
    $q = trim($_GET["q"] ?? "");

    $page = (int) ($_GET["page"] ?? 1);
    if ($page < 1)
      $page = 1;
    $limit = 50;
    $offset = ($page - 1) * $limit;

    $m = $this->model("Phongthi_m");
    $rows = $m->list($lop_id, $q, $limit, $offset);
    $total = $m->count($lop_id, $q);
    $totalPages = ceil($total / $limit);




    $lops = $this->model("Lophoc_m")->getAll();
    $mons = $this->model("Monthi_m")->getAll();
    $des = $this->model("Dethi_m")->list(0, "");

    $this->view("layout_admin", [
      "page" => "Pages/pt_list",
      "rows" => $rows,
      "lops" => $lops,
      "mons" => $mons,
      "des" => $des,
      "lop_id" => $lop_id,
      "q" => $q,
      "currPage" => $page,
      "totalPages" => $totalPages,
      "limit" => $limit,
      "total" => $total,
      "msg" => $this->getFlash("msg"),
      "err" => $this->getFlash("err"),
    ]);
  }


  public function store()
  {
    $this->needAdmin();

    $ma = trim($_POST["ma_phong"] ?? "");
    $ten = trim($_POST["ten_phong"] ?? "");
    $mon_id = (int) ($_POST["mon_id"] ?? 0);
    $de_id = (int) ($_POST["de_id"] ?? 0);
    $lop_id = (int) ($_POST["lop_id"] ?? 0);
    $bat_dau = trim($_POST["bat_dau"] ?? "");
    $trangthai = (int) ($_POST["trangthai"] ?? 1);

    if ($ma === "" || $ten === "" || $mon_id <= 0 || $de_id <= 0 || $lop_id <= 0) {
      $this->flash("err", "Vui lòng nhập đủ thông tin phòng thi!");
      header("Location: " . BASE_URL . "/index.php?url=PhongthiController/index");
      exit;
    }


    $lopModel = $this->model("Lophoc_m");
    $lop = $lopModel->find($lop_id);
    if (!$lop || (int) ($lop["trangthai"] ?? 0) !== 1) {
      $this->flash("err", "Không thể tạo phòng thi vì lớp học này đã bị khóa!");
      header("Location: " . BASE_URL . "/index.php?url=PhongthiController/index");
      exit;
    }

    $m = $this->model("Phongthi_m");
    if ($m->existsMa($ma)) {
      $this->flash("err", "Mã phòng đã tồn tại!");
      header("Location: " . BASE_URL . "/index.php?url=PhongthiController/index");
      exit;
    }

    $nguoi_tao = $_SESSION["user"]["hoten"] ?? "";
    $phong_id = $m->insert($ma, $ten, $mon_id, $de_id, $lop_id, $bat_dau, $nguoi_tao, $trangthai);


    $m->addAllHocvienFromLop($phong_id, $lop_id);

    $this->flash("msg", "Đã tạo phòng thi!");
    header("Location: " . BASE_URL . "/index.php?url=PhongthiController/index&lop_id=" . $lop_id);
    exit;
  }


  public function edit($id)
  {
    $this->needAdmin();
    $phong = $this->model("Phongthi_m")->find((int) $id);
    if (!$phong)
      die("Không tìm thấy phòng!");

    $lops = $this->model("Lophoc_m")->getAll();
    $mons = $this->model("Monthi_m")->getAll();
    $des = $this->model("Dethi_m")->list(0, "");

    $this->view("layout_admin", [
      "page" => "Pages/pt_edit",
      "phong" => $phong,
      "lops" => $lops,
      "mons" => $mons,
      "des" => $des,
      "err" => $this->getFlash("err"),
    ]);
  }


  public function update()
  {
    $this->needAdmin();
    $id = (int) ($_POST["id"] ?? 0);

    $ma = trim($_POST["ma_phong"] ?? "");
    $ten = trim($_POST["ten_phong"] ?? "");
    $mon_id = (int) ($_POST["mon_id"] ?? 0);
    $de_id = (int) ($_POST["de_id"] ?? 0);
    $lop_id = (int) ($_POST["lop_id"] ?? 0);
    $bat_dau = trim($_POST["bat_dau"] ?? "");
    $trangthai = (int) ($_POST["trangthai"] ?? 1);

    if ($id <= 0 || $ma === "" || $ten === "" || $mon_id <= 0 || $de_id <= 0 || $lop_id <= 0) {
      $this->flash("err", "Không được để trống!");
      header("Location: " . BASE_URL . "/index.php?url=PhongthiController/edit/" . $id);
      exit;
    }

    $m = $this->model("Phongthi_m");
    if ($m->existsMa($ma, $id)) {
      $this->flash("err", "Mã phòng đã tồn tại!");
      header("Location: " . BASE_URL . "/index.php?url=PhongthiController/edit/" . $id);
      exit;
    }

    $m->update($id, $ma, $ten, $mon_id, $de_id, $lop_id, $bat_dau, $trangthai);
    $m->addAllHocvienFromLop($id, $lop_id);

    $this->flash("msg", "Đã cập nhật phòng thi!");
    header("Location: " . BASE_URL . "/index.php?url=PhongthiController/index&lop_id=" . $lop_id);
    exit;
  }


  public function delete($id)
  {
    $this->needAdmin();
    $this->model("Phongthi_m")->delete((int) $id);
    $this->flash("msg", "Đã xóa phòng thi và dữ liệu liên quan!");
    header("Location: " . BASE_URL . "/index.php?url=PhongthiController/index");
    exit;
  }


  public function vaoPhong($id)
  {
    $this->needGV();
    $q = trim($_GET["q"] ?? "");

    $m = $this->model("Phongthi_m");
    $phong = $m->find((int) $id);
    if (!$phong)
      die("Không tìm thấy phòng thi!");

    $m->addAllHocvienFromLop((int) $id, (int) $phong["lop_id"]);
    $rows = $m->listHocvienInPhong((int) $id, $q);

    $this->view("layout_admin", [
      "page" => "Pages/pt_lambaithi",
      "phong" => $phong,
      "rows" => $rows,
      "q" => $q,
      "msg" => $this->getFlash("msg"),
      "err" => $this->getFlash("err"),
    ]);
  }


  public function kichhoat()
  {
    $this->needGV();
    $phong_id = (int) ($_POST["phong_id"] ?? 0);
    $hocvien_id = (int) ($_POST["hocvien_id"] ?? 0);
    $val = (int) ($_POST["val"] ?? 1);

    $this->model("Phongthi_m")->setKichHoat($phong_id, $hocvien_id, $val);
    $this->flash("msg", $val == 1 ? "Đã kích hoạt học viên!" : "Đã khóa học viên!");
    header("Location: " . BASE_URL . "/index.php?url=PhongthiController/vaoPhong/" . $phong_id);
    exit;
  }

  public function reset()
  {
    $this->needGV();
    $phong_id = (int) ($_POST["phong_id"] ?? 0);
    $hocvien_id = (int) ($_POST["hocvien_id"] ?? 0);

    $this->model("Phongthi_m")->resetLamLai($phong_id, $hocvien_id);
    $this->flash("msg", "Đã reset cho học viên làm lại!");
    header("Location: " . BASE_URL . "/index.php?url=PhongthiController/vaoPhong/" . $phong_id);
    exit;
  }

  public function huy()
  {
    $this->needGV();
    $phong_id = (int) ($_POST["phong_id"] ?? 0);
    $hocvien_id = (int) ($_POST["hocvien_id"] ?? 0);

    $this->model("Phongthi_m")->huyBai($phong_id, $hocvien_id);
    $this->flash("msg", "Đã hủy bài của học viên!");
    header("Location: " . BASE_URL . "/index.php?url=PhongthiController/vaoPhong/" . $phong_id);
    exit;
  }

  public function ghichu()
  {
    $this->needGV();
    $phong_id = (int) ($_POST["phong_id"] ?? 0);
    $hocvien_id = (int) ($_POST["hocvien_id"] ?? 0);
    $note = trim($_POST["ghi_chu"] ?? "");

    $this->model("Phongthi_m")->updateGhiChu($phong_id, $hocvien_id, $note);
    $this->flash("msg", "Đã cập nhật ghi chú!");
    header("Location: " . BASE_URL . "/index.php?url=PhongthiController/vaoPhong/" . $phong_id);
    exit;
  }


  public function vipham()
  {
    $this->needGV();
    $phong_id = (int) ($_POST["phong_id"] ?? 0);
    $hocvien_id = (int) ($_POST["hocvien_id"] ?? 0);

    $result = $this->model("Phongthi_m")->tangViPham($phong_id, $hocvien_id);

    if ($result && $result["cam_thi"]) {

      $lamBaiM = $this->model("LamBai_m");
      $bl = $lamBaiM->getBaiLamDoing($phong_id, $hocvien_id);
      if ($bl) {
        $kq = $lamBaiM->finishExam($bl["id"]);
        $lamBaiM->updatePhongHVAfterSubmit($phong_id, $hocvien_id, $kq);
      }
      $this->flash("msg", "Học viên đã bị cấm thi (Vi phạm lần " . $result["so_vp"] . ")! Đã tự động thu bài.");
    } else if ($result) {
      $this->flash("msg", "Đã ghi nhận vi phạm lần " . $result["so_vp"] . "! Trừ: " . $result["tru"] . " điểm.");
    } else {
      $this->flash("err", "Không thể ghi nhận vi phạm!");
    }

    header("Location: " . BASE_URL . "/index.php?url=PhongthiController/vaoPhong/" . $phong_id);
    exit;
  }


  public function monitor($id)
  {
    $this->needLogin();

    $m = $this->model("Phongthi_m");
    $phong = $m->find((int) $id);
    if (!$phong)
      die("Không tìm thấy phòng thi!");

    $lamBaiM = $this->model("LamBai_m");
    $rows = $lamBaiM->getPhongStatus((int) $id);


    $deModel = $this->model("Dethi_m");
    $de = $deModel->find((int) $phong["de_id"]);

    $tong_cau = 0;
    $duration = 0;
    if ($de) {
      $tong_cau = (int) $de["cau_de"] + (int) $de["cau_tb"] + (int) $de["cau_kho"];
      $duration = (int) $de["thoi_gian"];
    }


    $now = time();
    foreach ($rows as &$r) {
      if ($r["bl_status"] === 'Doing' && !empty($r["start_at"])) {
        $start = strtotime($r["start_at"]);
        $elapsed = $now - $start;

        if ($elapsed >= ($duration * 60 + 30)) {

          $kq = $lamBaiM->finishExam($r["bailam_id"]);
          $lamBaiM->updatePhongHVAfterSubmit($id, $r["hocvien_id"], $kq);
          $r["bl_status"] = 'Done';
        }
      }
    }
    if ($tong_cau <= 0)
      $tong_cau = 10;

    $this->view("layout_admin", [
      "page" => "Pages/pt_monitor",
      "phong" => $phong,
      "rows" => $rows,
      "de_info" => ["tong_cau" => $tong_cau]
    ]);
  }


  public function exportExcel($id)
  {
    $this->needLogin();

    $m = $this->model("Phongthi_m");
    $phong = $m->find((int) $id);
    if (!$phong)
      die("Không tìm thấy phòng thi!");

    $rows = $m->listHocvienInPhong((int) $id, "");


    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="ketqua_' . $phong["ma_phong"] . '_' . date('Ymd_His') . '.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');


    echo "\xEF\xBB\xBF";



    echo '"BẢNG ĐIỂM THI - ' . str_replace('"', '""', $phong["ten_phong"]) . '"' . "\n";
    echo '"Môn: ' . str_replace('"', '""', $phong["ten_mon"] ?? '') . ' | Đề: ' . str_replace('"', '""', $phong["ten_de"] ?? '') . ' | Lớp: ' . str_replace('"', '""', $phong["ma_lop"]) . '"' . "\n";
    echo '"Ngày xuất: ' . date('d/m/Y H:i') . '"' . "\n";
    echo "\n";


    echo '"STT","Mã HV","Họ tên","Lớp","Thời gian vào","Trừ","Còn","Ghi chú"' . "\n";


    foreach ($rows as $i => $r) {
      echo '"' . ($i + 1) . '",';
      echo '"' . str_replace('"', '""', $r["ma_hv"] ?? "") . '",';
      echo '"' . str_replace('"', '""', $r["hoten"] ?? "") . '",';
      echo '"' . str_replace('"', '""', $r["ma_lop"] ?? "") . '",';
      echo '"' . str_replace('"', '""', $r["thoi_gian_vao"] ?? "") . '",';
      echo '"' . ($r["tru"] ?? 0) . '",';
      echo '"' . ($r["con_lai"] ?? 0) . '",';
      echo '"' . str_replace('"', '""', $r["ghi_chu"] ?? "") . '"';
      echo "\n";
    }

    exit;
  }






  public function template()
  {
    $this->needLogin();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=phongthi_mau.csv');
    echo "\xEF\xBB\xBF";
    echo "ma_phong,ten_phong,ma_mon,ma_de,ma_lop,bat_dau,trangthai\n";
    echo "PT001,Phong thi 1,TOAN,DE_TOAN_1,CNTT1,2026-01-15 08:00,1\n";
    exit;
  }


  public function import()
  {
    $this->needAdmin();

    if (empty($_FILES["file"]["tmp_name"])) {
      $this->flash("err", "Vui lòng chọn file Excel!");
      header("Location: " . BASE_URL . "/index.php?url=PhongthiController/index");
      exit;
    }

    $name = $_FILES["file"]["name"] ?? "";
    if (!preg_match('/\.csv$/i', $name)) {
      $this->flash("err", "Chỉ hỗ trợ file Excel (.csv)!");
      header("Location: " . BASE_URL . "/index.php?url=PhongthiController/index");
      exit;
    }

    $m = $this->model("Phongthi_m");
    $monModel = $this->model("Monthi_m");
    $deModel = $this->model("Dethi_m");
    $lopModel = $this->model("Lophoc_m");


    $mons = $monModel->getAll();
    $mapMon = [];
    foreach ($mons as $x)
      $mapMon[strtoupper(trim($x["ma_mon"]))] = $x["id"];

    $des = $deModel->list(0, "");
    $mapDe = [];
    foreach ($des as $x)
      $mapDe[strtoupper(trim($x["ma_de"]))] = $x["id"];

    $lops = $lopModel->getAll();
    $mapLop = [];
    foreach ($lops as $x)
      $mapLop[strtoupper(trim($x["ma_lop"]))] = $x["id"];

    $fh = fopen($_FILES["file"]["tmp_name"], "r");
    $first = true;
    $ok = 0;
    $skip = 0;
    $nguoi_tao = $_SESSION["user"]["hoten"] ?? "Admin";


    $removeBOM = function ($str) {
      return preg_replace("/^\xEF\xBB\xBF/", '', $str);
    };

    while (($row = fgetcsv($fh)) !== false) {
      if ($first) {
        $first = false;
        continue;
      }

      $ma = trim($row[0] ?? "");
      $ma = $removeBOM($ma);
      $ten = trim($row[1] ?? "");
      $ma_mon = strtoupper(trim($row[2] ?? ""));
      $ma_de = strtoupper(trim($row[3] ?? ""));
      $ma_lop = strtoupper(trim($row[4] ?? ""));
      $bat_dau = trim($row[5] ?? "");
      $trangthai = (int) ($row[6] ?? 1);

      $mon_id = $mapMon[$ma_mon] ?? 0;
      $de_id = $mapDe[$ma_de] ?? 0;
      $lop_id = $mapLop[$ma_lop] ?? 0;

      if ($ma === "" || $ten === "" || $mon_id <= 0 || $de_id <= 0 || $lop_id <= 0) {
        $skip++;
        continue;
      }
      if ($m->existsMa($ma)) {
        $skip++;
        continue;
      }

      $phong_id = $m->insert($ma, $ten, $mon_id, $de_id, $lop_id, $bat_dau, $nguoi_tao, $trangthai);
      $m->addAllHocvienFromLop($phong_id, $lop_id);
      $ok++;
    }
    fclose($fh);

    $this->flash("msg", "Import xong: thêm $ok, bỏ qua $skip dòng.");
    header("Location: " . BASE_URL . "/index.php?url=PhongthiController/index");
    exit;
  }
}

