<?php
class HocvienAdminController extends Controller
{

  private function needAdmin()
  {
    if (empty($_SESSION["user"]) || (($_SESSION["user"]["role"] ?? "admin") !== "admin")) {
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

  // Danh sách + tìm kiếm + filter lớp
  public function index()
  {
    $this->needAdmin();

    $lop_id = (int) ($_GET["lop_id"] ?? 0);
    $q = trim($_GET["q"] ?? "");

    $m = $this->model("Hocvien_m");
    $lops = $m->getLops();
    $rows = $m->search($lop_id, $q);

    $this->view("layout_admin", [
      "page" => "Pages/hv_list",
      "rows" => $rows,
      "lops" => $lops,
      "lop_id" => $lop_id,
      "q" => $q,
      "msg" => $this->getFlash("msg"),
      "err" => $this->getFlash("err"),
    ]);
  }

  // Thêm mới 1 học viên (nút Thêm mới)
  public function store()
  {
    $this->needAdmin();

    $hoten = trim($_POST["hoten"] ?? "");
    $ma_hv = trim($_POST["ma_hv"] ?? "");
    $matkhau = trim($_POST["matkhau"] ?? "");
    $lop_id = (int) ($_POST["lop_id"] ?? 0);
    $trangthai = (int) ($_POST["trangthai"] ?? 1);

    if ($hoten === "" || $ma_hv === "" || $lop_id <= 0) {
      $this->flash("err", "Vui lòng nhập đủ Họ tên, Mã học viên, Lớp!");
      header("Location: " . BASE_URL . "/index.php?url=HocvienAdminController/index&lop_id=" . $lop_id);
      exit;
    }

    $m = $this->model("Hocvien_m");
    if ($m->existsMaHV($ma_hv)) {
      $this->flash("err", "Mã học viên đã tồn tại!");
      header("Location: " . BASE_URL . "/index.php?url=HocvienAdminController/index&lop_id=" . $lop_id);
      exit;
    }

    // theo ảnh: mật khẩu hiển thị plain => lưu plain (demo bài tập)
    // nếu bạn muốn hash thì đổi sang password_hash
    $m->insert($hoten, $ma_hv, $matkhau, $lop_id, $trangthai, (int) $_SESSION["user"]["id"]);

    $this->flash("msg", "Thêm học viên thành công!");
    header("Location: " . BASE_URL . "/index.php?url=HocvienAdminController/index&lop_id=" . $lop_id);
    exit;
  }

  // Xóa
  public function delete($id)
  {
    $this->needAdmin();
    $id = (int) $id;

    $m = $this->model("Hocvien_m");
    $m->delete($id);

    $this->flash("msg", "Đã xóa học viên!");
    header("Location: " . BASE_URL . "/index.php?url=HocvienAdminController/index");
    exit;
  }

  // Sửa (update)
  public function update($id)
  {
    $this->needAdmin();
    $id = (int) $id;

    $hoten = trim($_POST["hoten"] ?? "");
    $matkhau = trim($_POST["matkhau"] ?? "");
    $lop_id = (int) ($_POST["lop_id"] ?? 0);
    $trangthai = (int) ($_POST["trangthai"] ?? 1);

    if ($hoten === "" || $lop_id <= 0) {
      $this->flash("err", "Thiếu dữ liệu cập nhật!");
      header("Location: " . BASE_URL . "/index.php?url=HocvienAdminController/index");
      exit;
    }

    $m = $this->model("Hocvien_m");
    $m->update($id, $hoten, $matkhau, $lop_id, $trangthai);

    $this->flash("msg", "Cập nhật thành công!");
    header("Location: " . BASE_URL . "/index.php?url=HocvienAdminController/index&lop_id=" . $lop_id);
    exit;
  }

  // Import file (CSV) — demo giống popup ảnh
  public function import()
  {
    $this->needAdmin();

    $lop_id = (int) ($_POST["lop_id"] ?? 0);
    if ($lop_id <= 0) {
      $this->flash("err", "Chọn lớp trước khi import!");
      header("Location: " . BASE_URL . "/index.php?url=HocvienAdminController/index");
      exit;
    }

    if (empty($_FILES["file"]["tmp_name"])) {
      $this->flash("err", "Chưa chọn file!");
      header("Location: " . BASE_URL . "/index.php?url=HocvienAdminController/index&lop_id=" . $lop_id);
      exit;
    }

    $tmp = $_FILES["file"]["tmp_name"];
    $name = $_FILES["file"]["name"] ?? "";

    // Demo: dùng CSV cho chắc chạy (excel xlsx cần thư viện)
    // CSV format: hoten,ma_hv,matkhau,trangthai(0/1)
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    if ($ext !== "csv") {
      $this->flash("err", "Chỉ hỗ trợ CSV (để chạy chắc). File của bạn: " . $ext);
      header("Location: " . BASE_URL . "/index.php?url=HocvienAdminController/index&lop_id=" . $lop_id);
      exit;
    }

    $m = $this->model("Hocvien_m");

    $fh = fopen($tmp, "r");
    if (!$fh) {
      $this->flash("err", "Không đọc được file!");
      header("Location: " . BASE_URL . "/index.php?url=HocvienAdminController/index&lop_id=" . $lop_id);
      exit;
    }

    $count = 0;
    while (($row = fgetcsv($fh)) !== false) {
      // bỏ dòng header nếu có
      if ($count == 0 && isset($row[0]) && stripos($row[0], 'hoten') !== false) {
        continue;
      }

      $hoten = trim($row[0] ?? "");
      $ma_hv = trim($row[1] ?? "");
      $matkhau = trim($row[2] ?? "");
      $trangthai = (int) ($row[3] ?? 1);

      if ($hoten === "" || $ma_hv === "")
        continue;
      if ($m->existsMaHV($ma_hv))
        continue;

      $m->insert($hoten, $ma_hv, $matkhau, $lop_id, $trangthai, (int) $_SESSION["user"]["id"]);
      $count++;
    }
    fclose($fh);

    $this->flash("msg", "Import xong: " . $count . " học viên.");
    header("Location: " . BASE_URL . "/index.php?url=HocvienAdminController/index&lop_id=" . $lop_id);
    exit;
  }

  // ===== TẢI FILE MẪU CSV =====
  public function template()
  {
    $this->needAdmin();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=hocvien_mau.csv');
    echo "\xEF\xBB\xBF";
    echo "hoten,ma_hv,matkhau,trangthai\n";
    echo "Nguyễn Văn A,HV001,1234,1\n";
    echo "Trần Thị B,HV002,5678,1\n";
    echo "Lê Văn C,HV003,abcd,0\n";
    exit;
  }
}

