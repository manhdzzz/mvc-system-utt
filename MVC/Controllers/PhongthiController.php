<?php
class PhongthiController extends Controller
{

  private function needLogin()
  {
    if (empty($_SESSION["user"])) {
      header("Location: " . BASE_URL . "/index.php?url=AuthController/login");
      exit;
    }
  }
  private function flash($k, $v)
  {
    $_SESSION["_flash"][$k] = $v;
  }
  private function getFlash($k)
  {
    $v = $_SESSION["_flash"][$k] ?? "";
    unset($_SESSION["_flash"][$k]);
    return $v;
  }

  // ====== LIST + SEARCH + FILTER ======
  public function index()
  {
    $this->needLogin();
    $lop_id = (int) ($_GET["lop_id"] ?? 0);
    $q = trim($_GET["q"] ?? "");

    $rows = $this->model("Phongthi_m")->list($lop_id, $q);

    $lops = $this->model("Lophoc_m")->getAll();
    $mons = $this->model("Monthi_m")->getAll();
    $des = $this->model("Dethi_m")->list(0, ""); // load hết

    $this->view("layout_admin", [
      "page" => "Pages/pt_list",
      "rows" => $rows,
      "lops" => $lops,
      "mons" => $mons,
      "des" => $des,
      "lop_id" => $lop_id,
      "q" => $q,
      "msg" => $this->getFlash("msg"),
      "err" => $this->getFlash("err"),
    ]);
  }

  // ====== THÊM ======
  public function store()
  {
    $this->needLogin();

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

    $m = $this->model("Phongthi_m");
    if ($m->existsMa($ma)) {
      $this->flash("err", "Mã phòng đã tồn tại!");
      header("Location: " . BASE_URL . "/index.php?url=PhongthiController/index");
      exit;
    }

    $nguoi_tao = $_SESSION["user"]["hoten"] ?? "";
    $phong_id = $m->insert($ma, $ten, $mon_id, $de_id, $lop_id, $bat_dau, $nguoi_tao, $trangthai);

    // add học viên lớp vào phòng luôn (để vào phòng có data)
    $m->addAllHocvienFromLop($phong_id, $lop_id);

    $this->flash("msg", "Đã tạo phòng thi!");
    header("Location: " . BASE_URL . "/index.php?url=PhongthiController/index&lop_id=" . $lop_id);
    exit;
  }

  // ====== SỬA (GET form) ======
  public function edit($id)
  {
    $this->needLogin();
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

  // ====== UPDATE (POST) ======
  public function update()
  {
    $this->needLogin();
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
    $m->addAllHocvienFromLop($id, $lop_id); // đổi lớp thì add học viên lớp mới

    $this->flash("msg", "Đã cập nhật phòng thi!");
    header("Location: " . BASE_URL . "/index.php?url=PhongthiController/index&lop_id=" . $lop_id);
    exit;
  }

  // ====== XÓA ======
  public function delete($id)
  {
    $this->needLogin();
    $this->model("Phongthi_m")->delete((int) $id);
    $this->flash("msg", "Đã xóa phòng thi!");
    header("Location: " . BASE_URL . "/index.php?url=PhongthiController/index");
    exit;
  }

  // ====== VÀO PHÒNG (màn 2) ======
  public function vaoPhong($id)
  {
    $this->needLogin();
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

  // ====== ACTIONS TRONG PHÒNG ======
  public function kichhoat()
  {
    $this->needLogin();
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
    $this->needLogin();
    $phong_id = (int) ($_POST["phong_id"] ?? 0);
    $hocvien_id = (int) ($_POST["hocvien_id"] ?? 0);

    $this->model("Phongthi_m")->resetLamLai($phong_id, $hocvien_id);
    $this->flash("msg", "Đã reset cho học viên làm lại!");
    header("Location: " . BASE_URL . "/index.php?url=PhongthiController/vaoPhong/" . $phong_id);
    exit;
  }

  public function huy()
  {
    $this->needLogin();
    $phong_id = (int) ($_POST["phong_id"] ?? 0);
    $hocvien_id = (int) ($_POST["hocvien_id"] ?? 0);

    $this->model("Phongthi_m")->huyBai($phong_id, $hocvien_id);
    $this->flash("msg", "Đã hủy bài của học viên!");
    header("Location: " . BASE_URL . "/index.php?url=PhongthiController/vaoPhong/" . $phong_id);
    exit;
  }

  public function ghichu()
  {
    $this->needLogin();
    $phong_id = (int) ($_POST["phong_id"] ?? 0);
    $hocvien_id = (int) ($_POST["hocvien_id"] ?? 0);
    $note = trim($_POST["ghi_chu"] ?? "");

    $this->model("Phongthi_m")->updateGhiChu($phong_id, $hocvien_id, $note);
    $this->flash("msg", "Đã cập nhật ghi chú!");
    header("Location: " . BASE_URL . "/index.php?url=PhongthiController/vaoPhong/" . $phong_id);
    exit;
  }

  // ====== GIÁM SÁT THI REALTIME ======
  public function monitor($id)
  {
    $this->needLogin();

    $m = $this->model("Phongthi_m");
    $phong = $m->find((int) $id);
    if (!$phong)
      die("Không tìm thấy phòng thi!");

    $lamBaiM = $this->model("LamBai_m");
    $rows = $lamBaiM->getPhongStatus((int) $id);

    // Lấy thông tin đề để biết tổng số câu
    $de = $this->model("Dethi_m")->list((int) $phong["mon_id"], "");
    $tong_cau = 0;
    foreach ($de as $d) {
      if ((int) $d["id"] === (int) $phong["de_id"]) {
        $tong_cau = (int) $d["cau_de"] + (int) $d["cau_tb"] + (int) $d["cau_kho"];
        break;
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

  // ====== XUẤT CSV KẾT QUẢ ======
  public function exportCSV($id)
  {
    $this->needLogin();

    $m = $this->model("Phongthi_m");
    $phong = $m->find((int) $id);
    if (!$phong)
      die("Không tìm thấy phòng thi!");

    $rows = $m->listHocvienInPhong((int) $id, "");

    // CSV Headers
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="ketqua_' . $phong["ma_phong"] . '_' . date('Ymd_His') . '.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    // BOM for UTF-8 (Excel compatibility)
    echo "\xEF\xBB\xBF";

    // CSV Content
    // Title rows
    echo '"BẢNG ĐIỂM THI - ' . str_replace('"', '""', $phong["ten_phong"]) . '"' . "\n";
    echo '"Môn: ' . str_replace('"', '""', $phong["ten_mon"] ?? '') . ' | Đề: ' . str_replace('"', '""', $phong["ten_de"] ?? '') . ' | Lớp: ' . str_replace('"', '""', $phong["ma_lop"]) . '"' . "\n";
    echo '"Ngày xuất: ' . date('d/m/Y H:i') . '"' . "\n";
    echo "\n";

    // Header row
    echo '"STT","Mã HV","Họ tên","Lớp","Điểm","Câu đúng","Trạng thái","Thời gian vào"' . "\n";

    // Data rows
    foreach ($rows as $i => $r) {
      echo '"' . ($i + 1) . '",';
      echo '"' . str_replace('"', '""', $r["ma_hv"] ?? "") . '",';
      echo '"' . str_replace('"', '""', $r["hoten"] ?? "") . '",';
      echo '"' . str_replace('"', '""', $r["ma_lop"] ?? "") . '",';
      echo '"' . ($r["diem"] ?? 0) . '",';
      echo '"' . ($r["cau_dung"] ?? 0) . '",';
      echo '"' . str_replace('"', '""', $r["trang_thai"] ?? "") . '",';
      echo '"' . str_replace('"', '""', $r["thoi_gian_vao"] ?? "") . '"';
      echo "\n";
    }

    exit;
  }

  // Legacy alias for backwards compatibility
  public function exportExcel($id)
  {
    $this->exportCSV($id);
  }

  // ====== TẢI FILE MẪU CSV ======
  public function template()
  {
    $this->needLogin();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=phongthi_mau.csv');
    echo "\xEF\xBB\xBF";
    echo "ma_phong,ten_phong,mon_id,de_id,lop_id,bat_dau,trangthai\n";
    echo "PT001,Phong thi 1,1,1,1,2026-01-15 08:00,1\n";
    echo "PT002,Phong thi 2,1,2,2,2026-01-15 10:00,1\n";
    exit;
  }

  // ====== IMPORT CSV ======
  public function import()
  {
    $this->needLogin();

    if (empty($_FILES["file"]["tmp_name"])) {
      $this->flash("err", "Vui lòng chọn file CSV!");
      header("Location: " . BASE_URL . "/index.php?url=PhongthiController/index");
      exit;
    }

    $name = $_FILES["file"]["name"] ?? "";
    if (!preg_match('/\.csv$/i', $name)) {
      $this->flash("err", "Chỉ hỗ trợ file .CSV!");
      header("Location: " . BASE_URL . "/index.php?url=PhongthiController/index");
      exit;
    }

    $m = $this->model("Phongthi_m");
    $fh = fopen($_FILES["file"]["tmp_name"], "r");
    $first = true;
    $ok = 0;
    $skip = 0;
    $nguoi_tao = $_SESSION["user"]["hoten"] ?? "Admin";

    while (($row = fgetcsv($fh)) !== false) {
      if ($first) {
        $first = false;
        continue;
      }

      $ma = trim($row[0] ?? "");
      $ten = trim($row[1] ?? "");
      $mon_id = (int) ($row[2] ?? 0);
      $de_id = (int) ($row[3] ?? 0);
      $lop_id = (int) ($row[4] ?? 0);
      $bat_dau = trim($row[5] ?? "");
      $trangthai = (int) ($row[6] ?? 1);

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

