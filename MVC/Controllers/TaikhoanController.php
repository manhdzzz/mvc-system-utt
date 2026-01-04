<?php
class TaikhoanController extends Controller
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
    $m = $this->model("User_m");

    $rows = ($q !== "") ? $m->search($q) : $m->getAll();

    $this->view("layout_admin", [
      "page" => "Pages/tk_list",
      "rows" => $rows,
      "q" => $q,
      "msg" => $this->getFlash("msg"),
      "err" => $this->getFlash("err")
    ]);
  }

  public function create()
  {
    $this->needLogin();
    $this->view("layout_admin", [
      "page" => "Pages/tk_create",
      "msg" => $this->getFlash("msg"),
      "err" => $this->getFlash("err")
    ]);
  }

  public function store()
  {
    $this->needLogin();

    $hoten = trim($_POST["hoten"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $trangthai = (int) ($_POST["trangthai"] ?? 1);
    $role = trim($_POST["role"] ?? "admin");

    $m = $this->model("User_m");

    if ($hoten === "" || $username === "" || $email === "" || $password === "") {
      $this->flash("err", "Vui lòng nhập đủ thông tin!");
      header("Location: " . BASE_URL . "/index.php?url=TaikhoanController/create");
      exit;
    }
    if ($m->existsUsername($username, 0)) {
      $this->flash("err", "Username đã tồn tại!");
      header("Location: " . BASE_URL . "/index.php?url=TaikhoanController/create");
      exit;
    }

    // theo ý bạn "password hard": cho phép lưu thô hoặc bcrypt
    // Khuyên: dùng bcrypt để ổn hơn
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    $m->insert($hoten, $username, $email, $password_hash, $trangthai, $role);

    $this->flash("msg", "Đã thêm tài khoản!");
    header("Location: " . BASE_URL . "/index.php?url=TaikhoanController/index");
    exit;
  }


  public function edit($id)
  {
    $this->needLogin();
    $m = $this->model("User_m");
    $u = $m->find((int) $id);
    if (!$u)
      die("Không tìm thấy user");
    $this->view("layout_admin", [
      "page" => "Pages/tk_edit",
      "u" => $u,
      "msg" => $this->getFlash("msg"),
      "err" => $this->getFlash("err")
    ]);
  }

  public function update()
  {
    $this->needLogin();
    $id = (int) ($_POST["id"] ?? 0);
    $hoten = trim($_POST["hoten"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $trangthai = (int) ($_POST["trangthai"] ?? 1);
    $role = trim($_POST["role"] ?? "admin");

    $m = $this->model("User_m");

    if ($hoten === "" || $username === "" || $email === "") {
      $this->flash("err", "Không được để trống họ tên / username / email!");
      header("Location: " . BASE_URL . "/index.php?url=TaikhoanController/edit/" . $id);
      exit;
    }
    if ($m->existsUsername($username, $id)) {
      $this->flash("err", "Username đã tồn tại!");
      header("Location: " . BASE_URL . "/index.php?url=TaikhoanController/edit/" . $id);
      exit;
    }

    $m->updateBasic($id, $hoten, $username, $email, $trangthai, $role);
    $this->flash("msg", "Cập nhật tài khoản thành công!");
    header("Location: " . BASE_URL . "/index.php?url=TaikhoanController/index");
    exit;
  }

  public function delete($id)
  {
    $this->needLogin();
    $id = (int) $id;

    // không cho tự xóa mình
    if ((int) ($_SESSION["user"]["id"] ?? 0) === $id) {
      $this->flash("err", "Không thể xóa tài khoản đang đăng nhập!");
      header("Location: " . BASE_URL . "/index.php?url=TaikhoanController/index");
      exit;
    }

    $m = $this->model("User_m");
    $m->delete($id);

    $this->flash("msg", "Đã xóa tài khoản!");
    header("Location: " . BASE_URL . "/index.php?url=TaikhoanController/index");
    exit;
  }

  // ===== TẢI FILE MẪU CSV =====
  public function template()
  {
    $this->needLogin();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=taikhoan_mau.csv');
    echo "\xEF\xBB\xBF";
    echo "hoten,username,email,password,trangthai,role\n";
    echo "Nguyễn Văn A,admin1,admin1@example.com,password123,1,admin\n";
    echo "Trần Thị B,user1,user1@example.com,password456,1,user\n";
    exit;
  }

  // ===== IMPORT CSV =====
  public function import()
  {
    $this->needLogin();

    if (empty($_FILES["file"]["tmp_name"])) {
      $this->flash("err", "Vui lòng chọn file CSV!");
      header("Location: " . BASE_URL . "/index.php?url=TaikhoanController/index");
      exit;
    }

    $name = $_FILES["file"]["name"] ?? "";
    if (!preg_match('/\.csv$/i', $name)) {
      $this->flash("err", "Chỉ hỗ trợ file .CSV!");
      header("Location: " . BASE_URL . "/index.php?url=TaikhoanController/index");
      exit;
    }

    $m = $this->model("User_m");
    $fh = fopen($_FILES["file"]["tmp_name"], "r");
    $first = true;
    $ok = 0;
    $skip = 0;

    while (($row = fgetcsv($fh)) !== false) {
      if ($first) {
        $first = false;
        continue;
      }

      $hoten = trim($row[0] ?? "");
      $username = trim($row[1] ?? "");
      $email = trim($row[2] ?? "");
      $password = trim($row[3] ?? "");
      $trangthai = (int) ($row[4] ?? 1);
      $role = trim($row[5] ?? "admin");

      if ($hoten === "" || $username === "" || $email === "" || $password === "") {
        $skip++;
        continue;
      }
      if ($m->existsUsername($username, 0)) {
        $skip++;
        continue;
      }

      $password_hash = password_hash($password, PASSWORD_BCRYPT);
      $m->insert($hoten, $username, $email, $password_hash, $trangthai, $role);
      $ok++;
    }
    fclose($fh);

    $this->flash("msg", "Import xong: thêm $ok, bỏ qua $skip dòng.");
    header("Location: " . BASE_URL . "/index.php?url=TaikhoanController/index");
    exit;
  }
}

