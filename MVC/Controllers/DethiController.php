<?php
class DethiController extends Controller
{

  public function index()
  {
    $this->needAdmin();
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


  public function store()
  {
    $this->needAdmin();

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

    $idsD = $m->pickQuestions($mon_id, "D", $soD);
    $idsTB = $m->pickQuestions($mon_id, "TB", $soTB);
    $idsK = $m->pickQuestions($mon_id, "K", $soK);

    if (count($idsD) < $soD || count($idsTB) < $soTB || count($idsK) < $soK) {
      $this->flash("err", "Không đủ câu hỏi theo loại (D/TB/K) trong ngân hàng câu hỏi!");
      header("Location: " . BASE_URL . "/index.php?url=DethiController/index&mon_id=" . $mon_id);
      exit;
    }

    $de_id = $m->insertDe($ma, $ten, $time, $mon_id);

    $m->addQuestionsToDe($de_id, $idsD);
    $m->addQuestionsToDe($de_id, $idsTB);
    $m->addQuestionsToDe($de_id, $idsK);

    $this->flash("msg", "Đã tạo đề thi và tự chọn câu hỏi!");
    header("Location: " . BASE_URL . "/index.php?url=DethiController/index&mon_id=" . $mon_id);
    exit;
  }

  public function delete($id)
  {
    $this->needAdmin();
    $this->model("Dethi_m")->delete((int) $id);
    $this->flash("msg", "Đã xóa đề thi và dữ liệu liên quan!");
    header("Location: " . BASE_URL . "/index.php?url=DethiController/index");
    exit;
  }




  public function template()
  {
    $this->needAdmin();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=dethi_mau.csv');
    echo "\xEF\xBB\xBF";
    echo "ma_de,ten_de,thoi_gian,ma_mon,so_de,so_tb,so_kho\n";


    $mons = $this->model("Monthi_m")->getAll();
    if (empty($mons)) {
      echo "DE001,De thi mau,30,TOAN,5,3,2\n";
    } else {
      foreach ($mons as $m) {
        $code = $m["ma_mon"] ?? "TOAN";
        echo "DE_" . rand(100, 999) . ",Đề thi môn $code,45,$code,5,3,2\n";
      }
    }
    exit;
  }


  public function import()
  {
    $this->needAdmin();


    if (empty($_FILES["file"]["tmp_name"])) {
      $this->flash("err", "Vui lòng chọn file Excel!");
      header("Location: " . BASE_URL . "/index.php?url=DethiController/index");
      exit;
    }

    $name = $_FILES["file"]["name"] ?? "";
    if (!preg_match('/\.csv$/i', $name)) {
      $this->flash("err", "Chỉ hỗ trợ file Excel (.csv)!");
      header("Location: " . BASE_URL . "/index.php?url=DethiController/index");
      exit;
    }


    $mons = $this->model("Monthi_m")->getAll();
    $mapMon = [];
    $validIds = [];
    foreach ($mons as $mn) {
      $mapMon[strtoupper(trim($mn["ma_mon"]))] = (int) $mn["id"];
      $validIds[(int) $mn["id"]] = true;
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
      $monRaw = strtoupper(trim($row[3] ?? ""));
      $soD = (int) ($row[4] ?? 0);
      $soTB = (int) ($row[5] ?? 0);
      $soK = (int) ($row[6] ?? 0);


      $mon = 0;
      if (isset($mapMon[$monRaw]))
        $mon = $mapMon[$monRaw];
      elseif (is_numeric($monRaw) && isset($validIds[(int) $monRaw]))
        $mon = (int) $monRaw;

      if ($ma === "" || $ten === "" || $mon <= 0) {
        $skip++;
        continue;
      }

      try {
        if ($m->existsMa($ma)) {
          $skip++;
          continue;
        }

        $de_id = $m->insertDe($ma, $ten, $time, $mon);


        $idsD = $m->pickQuestions($mon, "D", $soD);
        $idsTB = $m->pickQuestions($mon, "TB", $soTB);
        $idsK = $m->pickQuestions($mon, "K", $soK);

        $m->addQuestionsToDe($de_id, $idsD);
        $m->addQuestionsToDe($de_id, $idsTB);
        $m->addQuestionsToDe($de_id, $idsK);

        $ok++;
      } catch (Exception $e) {
        $skip++;
      }
    }
    fclose($fh);

    $this->flash("msg", "Import xong: thêm $ok, bỏ qua $skip dòng.");
    header("Location: " . BASE_URL . "/index.php?url=DethiController/index");
    exit;
  }
}

