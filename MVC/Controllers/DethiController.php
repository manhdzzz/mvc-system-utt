<?php
class DethiController extends Controller
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
    $mon_id = (int) ($_GET["mon_id"] ?? 0);
    $q = trim($_GET["q"] ?? "");

    $mons = $this->model("Monthi_m")->getAll();
    $rows = $this->model("Dethi_m")->list($mon_id, $q);

    $this->view("layout_admin", [
      "page" => "Pages/dt_list",
      "rows" => $rows,
      "mons" => $mons,
      "mon_id" => $mon_id,
      "q" => $q,
      "msg" => $this->getFlash("msg"),
      "err" => $this->getFlash("err"),
    ]);
  }

  // xử lý thêm mới từ modal
  public function store()
  {
    $this->needLogin();

    $ma = trim($_POST["ma_de"] ?? "");
    $ten = trim($_POST["ten_de"] ?? "");
    $time = (int) ($_POST["thoi_gian"] ?? 30);
    $mon_id = (int) ($_POST["mon_id"] ?? 0);

    $soD = (int) ($_POST["so_de"] ?? 0);
    $soTB = (int) ($_POST["so_tb"] ?? 0);
    $soK = (int) ($_POST["so_kho"] ?? 0);

    if ($ma === "" || $ten === "" || $time <= 0 || $mon_id <= 0) {
      $this->flash("err", "Vui lòng nhập đủ thông tin đề thi!");
      header("Location: " . BASE_URL . "/index.php?url=DethiController/index&mon_id=" . $mon_id);
      exit;
    }

    $m = $this->model("Dethi_m");
    if ($m->existsMa($ma)) {
      $this->flash("err", "Mã đề đã tồn tại!");
      header("Location: " . BASE_URL . "/index.php?url=DethiController/index&mon_id=" . $mon_id);
      exit;
    }

    // tạo đề
    $de_id = $m->insertDe($ma, $ten, $time, $mon_id);

    // pick câu theo loại
    $idsD = $m->pickQuestions($mon_id, "D", $soD);
    $idsTB = $m->pickQuestions($mon_id, "TB", $soTB);
    $idsK = $m->pickQuestions($mon_id, "K", $soK);

    // kiểm đủ câu (nếu thiếu sẽ báo)
    if (count($idsD) < $soD || count($idsTB) < $soTB || count($idsK) < $soK) {
      $this->flash("err", "Không đủ câu hỏi theo loại (D/TB/K) trong ngân hàng câu hỏi!");
      header("Location: " . BASE_URL . "/index.php?url=DethiController/index&mon_id=" . $mon_id);
      exit;
    }

    $m->addQuestionsToDe($de_id, $idsD);
    $m->addQuestionsToDe($de_id, $idsTB);
    $m->addQuestionsToDe($de_id, $idsK);

    $this->flash("msg", "Đã tạo đề thi và tự chọn câu hỏi!");
    header("Location: " . BASE_URL . "/index.php?url=DethiController/index&mon_id=" . $mon_id);
    exit;
  }

  public function delete($id)
  {
    $this->needLogin();
    $this->model("Dethi_m")->delete((int) $id);
    $this->flash("msg", "Đã xóa đề thi!");
    header("Location: " . BASE_URL . "/index.php?url=DethiController/index");
    exit;
  }

  // ===== TẢI FILE MẪU CSV =====
  public function template()
  {
    $this->needLogin();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=dethi_mau.csv');
    echo "\xEF\xBB\xBF";
    echo "ma_de,ten_de,thoi_gian,mon_id,so_de,so_tb,so_kho\n";
    echo "DE001,De thi Toan 1,30,1,5,3,2\n";
    echo "DE002,De thi Ly 1,45,2,4,4,2\n";
    exit;
  }

  // ===== IMPORT CSV =====
  public function import()
  {
    $this->needLogin();
    $mon_id = (int) ($_POST["mon_id"] ?? 0);

    if (empty($_FILES["file"]["tmp_name"])) {
      $this->flash("err", "Vui lòng chọn file CSV!");
      header("Location: " . BASE_URL . "/index.php?url=DethiController/index");
      exit;
    }

    $name = $_FILES["file"]["name"] ?? "";
    if (!preg_match('/\.csv$/i', $name)) {
      $this->flash("err", "Chỉ hỗ trợ file .CSV!");
      header("Location: " . BASE_URL . "/index.php?url=DethiController/index");
      exit;
    }

    $m = $this->model("Dethi_m");
    $fh = fopen($_FILES["file"]["tmp_name"], "r");
    $first = true;
    $ok = 0;
    $skip = 0;

    while (($row = fgetcsv($fh)) !== false) {
      if ($first) {
        $first = false;
        continue;
      }

      $ma = trim($row[0] ?? "");
      $ten = trim($row[1] ?? "");
      $time = (int) ($row[2] ?? 30);
      $mon = (int) ($row[3] ?? $mon_id);
      $soD = (int) ($row[4] ?? 0);
      $soTB = (int) ($row[5] ?? 0);
      $soK = (int) ($row[6] ?? 0);

      if ($ma === "" || $ten === "" || $mon <= 0) {
        $skip++;
        continue;
      }
      if ($m->existsMa($ma)) {
        $skip++;
        continue;
      }

      $de_id = $m->insertDe($ma, $ten, $time, $mon);

      // pick câu theo loại
      $idsD = $m->pickQuestions($mon, "D", $soD);
      $idsTB = $m->pickQuestions($mon, "TB", $soTB);
      $idsK = $m->pickQuestions($mon, "K", $soK);

      $m->addQuestionsToDe($de_id, $idsD);
      $m->addQuestionsToDe($de_id, $idsTB);
      $m->addQuestionsToDe($de_id, $idsK);

      $ok++;
    }
    fclose($fh);

    $this->flash("msg", "Import xong: thêm $ok, bỏ qua $skip dòng.");
    header("Location: " . BASE_URL . "/index.php?url=DethiController/index&mon_id=" . $mon_id);
    exit;
  }
}

