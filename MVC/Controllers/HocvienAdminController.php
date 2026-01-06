<?php
class HocvienAdminController extends Controller
{


  public function index()
  {
    $this->needAdmin();

    $lop_id = (int) ($_GET["lop_id"] ?? 0);
    $q = trim($_GET["q"] ?? "");

    $page = (int) ($_GET["page"] ?? 1);
    if ($page < 1)
      $page = 1;
    $limit = 50;
    $offset = ($page - 1) * $limit;

    $m = $this->model("Hocvien_m");
    $lops = $m->getLops();
    $rows = $m->search($lop_id, $q, $limit, $offset);
    $total = $m->countSearch($lop_id, $q);
    $totalPages = ceil($total / $limit);

    $this->view("layout_admin", [
      "page" => "Pages/hv_list",
      "rows" => $rows,
      "lops" => $lops,
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



    $m->insert($hoten, $ma_hv, $matkhau, $lop_id, $trangthai, (int) $_SESSION["user"]["id"]);

    $this->flash("msg", "Thêm học viên thành công!");
    header("Location: " . BASE_URL . "/index.php?url=HocvienAdminController/index&lop_id=" . $lop_id);
    exit;
  }


  public function delete($id)
  {
    $this->needAdmin();
    $id = (int) $id;

    $this->model("Hocvien_m")->delete($id);
    $this->flash("msg", "Đã xóa học viên và kết quả thi!");
    header("Location: " . BASE_URL . "/index.php?url=HocvienAdminController/index");
    exit;
  }


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
      $this->flash("err", "Chưa chọn file Excel!");
      header("Location: " . BASE_URL . "/index.php?url=HocvienAdminController/index&lop_id=" . $lop_id);
      exit;
    }

    $tmp = $_FILES["file"]["tmp_name"];
    $name = $_FILES["file"]["name"] ?? "";



    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    if ($ext !== "csv") {
      $this->flash("err", "Chỉ hỗ trợ file Excel (.csv)! File của bạn: " . $ext);
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
