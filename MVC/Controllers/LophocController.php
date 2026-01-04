<?php
class LophocController extends Controller
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

  public function index()
  {
    $this->needLogin();

    $q = trim($_GET["q"] ?? "");

    $m = $this->model("Lophoc_m");

    if ($q !== "") {
      $rows = $m->search($q);
    } else {
      $rows = $m->getAll();
    }

    $this->view("layout_admin", [
      "page" => "Pages/lh_list",
      "rows" => $rows,
      "q" => $q,
      "msg" => $this->getFlash("msg"),
      "err" => $this->getFlash("err")
    ]);
  }


  public function edit($id)
  {
    $this->needLogin();
    $m = $this->model("Lophoc_m");
    $lh = $m->find((int) $id);
    if (!$lh)
      die("Không tìm thấy lớp");
    $this->view("layout_admin", [
      "page" => "Pages/lh_edit",
      "lh" => $lh,
      "msg" => $this->getFlash("msg"),
      "err" => $this->getFlash("err")
    ]);
  }

  public function update()
  {
    $this->needLogin();
    $id = (int) ($_POST["id"] ?? 0);
    $ma = trim($_POST["ma_lop"] ?? "");
    $ten = trim($_POST["ten_lop"] ?? "");
    $trangthai = (int) ($_POST["trangthai"] ?? 1);

    $m = $this->model("Lophoc_m");

    if ($ma === "" || $ten === "") {
      $this->flash("err", "Mã lớp và Tên lớp không được để trống!");
      header("Location: " . BASE_URL . "/index.php?url=LophocController/edit/" . $id);
      exit;
    }
    if ($m->existsMaLop($ma, $id)) {
      $this->flash("err", "Mã lớp đã tồn tại!");
      header("Location: " . BASE_URL . "/index.php?url=LophocController/edit/" . $id);
      exit;
    }

    $m->updateBasic($id, $ma, $ten, $trangthai);
    $this->flash("msg", "Cập nhật lớp học thành công!");
    header("Location: " . BASE_URL . "/index.php?url=LophocController/index");
    exit;
  }

  public function delete($id)
  {
    $this->needLogin();
    $id = (int) $id;

    $m = $this->model("Lophoc_m");
    if (!$m->canDelete($id)) {
      $this->flash("err", "Không thể xóa lớp vì đang có học viên!");
      header("Location: " . BASE_URL . "/index.php?url=LophocController/index");
      exit;
    }

    $m->delete($id);
    $this->flash("msg", "Đã xóa lớp học!");
    header("Location: " . BASE_URL . "/index.php?url=LophocController/index");
    exit;
  }
  public function create()
  {
    $this->needLogin();
    $this->view("layout_admin", [
      "page" => "Pages/lh_create",
      "msg" => $this->getFlash("msg"),
      "err" => $this->getFlash("err")
    ]);
  }

  public function store()
  {
    $this->needLogin();
    $ma = trim($_POST["ma_lop"] ?? "");
    $ten = trim($_POST["ten_lop"] ?? "");
    $trangthai = (int) ($_POST["trangthai"] ?? 1);

    $m = $this->model("Lophoc_m");
    if ($ma === "" || $ten === "") {
      $this->flash("err", "Vui lòng nhập Mã lớp và Tên lớp!");
      header("Location: " . BASE_URL . "/index.php?url=LophocController/create");
      exit;
    }
    if ($m->existsMaLop($ma, 0)) {
      $this->flash("err", "Mã lớp đã tồn tại!");
      header("Location: " . BASE_URL . "/index.php?url=LophocController/create");
      exit;
    }

    $nguoi_tao = $_SESSION["user"]["hoten"] ?? "Admin";
    $m->insert($ma, $ten, $trangthai, $nguoi_tao);

    $this->flash("msg", "Đã thêm lớp học!");
    header("Location: " . BASE_URL . "/index.php?url=LophocController/index");
    exit;
  }

  // ===== TẢI FILE MẪU CSV =====
  public function template()
  {
    $this->needLogin();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=lophoc_mau.csv');
    echo "\xEF\xBB\xBF";
    echo "ma_lop,ten_lop,trangthai\n";
    echo "LOP01,Lớp 10A1,1\n";
    echo "LOP02,Lớp 10A2,1\n";
    echo "LOP03,Lớp 11A1,0\n";
    exit;
  }

  // ===== IMPORT CSV =====
  public function import()
  {
    $this->needLogin();

    if (empty($_FILES["file"]["tmp_name"])) {
      $this->flash("err", "Vui lòng chọn file CSV!");
      header("Location: " . BASE_URL . "/index.php?url=LophocController/index");
      exit;
    }

    $name = $_FILES["file"]["name"] ?? "";
    if (!preg_match('/\.csv$/i', $name)) {
      $this->flash("err", "Chỉ hỗ trợ file .CSV!");
      header("Location: " . BASE_URL . "/index.php?url=LophocController/index");
      exit;
    }

    $m = $this->model("Lophoc_m");
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
      $trangthai = (int) ($row[2] ?? 1);

      if ($ma === "" || $ten === "") {
        $skip++;
        continue;
      }
      if ($m->existsMaLop($ma, 0)) {
        $skip++;
        continue;
      }

      $m->insert($ma, $ten, $trangthai, $nguoi_tao);
      $ok++;
    }
    fclose($fh);

    $this->flash("msg", "Import xong: thêm $ok, bỏ qua $skip dòng.");
    header("Location: " . BASE_URL . "/index.php?url=LophocController/index");
    exit;
  }

}

