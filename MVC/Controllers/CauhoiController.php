<?php
class CauhoiController extends Controller
{

  public function index()
  {
    $this->needAdmin();
    $mon_id = (int) ($_GET["mon_id"] ?? 0);
    $q = trim($_GET["q"] ?? "");

    $mMon = $this->model("Monthi_m");
    $mons = $mMon->getAll();

    $page = (int) ($_GET["page"] ?? 1);
    if ($page < 1)
      $page = 1;
    $limit = 50;
    $offset = ($page - 1) * $limit;

    $m = $this->model("Cauhoi_m");
    $rows = $m->list($mon_id, $q, $offset);
    $total = $m->count($mon_id, $q);
    $totalPages = ceil($total / $limit);

    $this->view("layout_admin", [
      "page" => "Pages/ch_list",
      "rows" => $rows,
      "mons" => $mons,
      "mon_id" => $mon_id,
      "q" => $q,
      "currPage" => $page,
      "totalPages" => $totalPages,
      "limit" => $limit,
      "total" => $total,
      "msg" => $this->getFlash("msg"),
      "err" => $this->getFlash("err"),
    ]);
  }

  public function create()
  {
    $this->needAdmin();
    $mons = $this->model("Monthi_m")->getAll();
    $this->view("layout_admin", [
      "page" => "Pages/ch_create",
      "mons" => $mons,
      "err" => $this->getFlash("err"),
    ]);
  }

  public function store()
  {
    $this->needAdmin();
    $mon_id = (int) ($_POST["mon_id"] ?? 0);
    $nd = trim($_POST["noi_dung"] ?? "");
    $a = trim($_POST["a"] ?? "");
    $b = trim($_POST["b"] ?? "");
    $c = trim($_POST["c"] ?? "");
    $d = trim($_POST["d"] ?? "");
    $dad = strtoupper(trim($_POST["dap_an_dung"] ?? "A"));
    $diem = (int) ($_POST["diem"] ?? 1);
    $gt = trim($_POST["giai_thich"] ?? "");
    $loai = trim($_POST["loai"] ?? "D");
    $kich = (int) ($_POST["kich_hoat"] ?? 1);

    if ($mon_id <= 0 || $nd === "" || $a === "" || $b === "" || $c === "" || $d === "") {
      $this->flash("err", "Vui lòng nhập đủ dữ liệu câu hỏi!");
      header("Location: " . BASE_URL . "/index.php?url=CauhoiController/create");
      exit;
    }
    if (!in_array($dad, ["A", "B", "C", "D"]))
      $dad = "A";

    $this->model("Cauhoi_m")->insert($mon_id, $nd, $a, $b, $c, $d, $dad, $diem, $gt, $loai, $kich);
    $this->flash("msg", "Đã thêm câu hỏi!");
    header("Location: " . BASE_URL . "/index.php?url=CauhoiController/index&mon_id=" . $mon_id);
    exit;
  }

  public function edit($id)
  {
    $this->needAdmin();
    $mons = $this->model("Monthi_m")->getAll();
    $ch = $this->model("Cauhoi_m")->find((int) $id);
    if (!$ch)
      die("Không tìm thấy câu hỏi!");

    $this->view("layout_admin", [
      "page" => "Pages/ch_edit",
      "mons" => $mons,
      "ch" => $ch,
      "err" => $this->getFlash("err"),
    ]);
  }

  public function update()
  {
    $this->needAdmin();
    $id = (int) ($_POST["id"] ?? 0);

    $mon_id = (int) ($_POST["mon_id"] ?? 0);
    $nd = trim($_POST["noi_dung"] ?? "");
    $a = trim($_POST["a"] ?? "");
    $b = trim($_POST["b"] ?? "");
    $c = trim($_POST["c"] ?? "");
    $d = trim($_POST["d"] ?? "");
    $dad = strtoupper(trim($_POST["dap_an_dung"] ?? "A"));
    $diem = (int) ($_POST["diem"] ?? 1);
    $gt = trim($_POST["giai_thich"] ?? "");
    $loai = trim($_POST["loai"] ?? "D");
    $kich = (int) ($_POST["kich_hoat"] ?? 1);

    if ($mon_id <= 0 || $nd === "" || $a === "" || $b === "" || $c === "" || $d === "") {
      $this->flash("err", "Không được để trống!");
      header("Location: " . BASE_URL . "/index.php?url=CauhoiController/edit/" . $id);
      exit;
    }
    if (!in_array($dad, ["A", "B", "C", "D"]))
      $dad = "A";

    $this->model("Cauhoi_m")->updateBasic($id, $mon_id, $nd, $a, $b, $c, $d, $dad, $diem, $gt, $loai, $kich);
    $this->flash("msg", "Đã cập nhật câu hỏi!");
    header("Location: " . BASE_URL . "/index.php?url=CauhoiController/index&mon_id=" . $mon_id);
    exit;
  }

  public function delete($id)
  {
    $this->needAdmin();
    $m = $this->model("Cauhoi_m");
    $ch = $m->find((int) $id);

    if ($ch) {
      $m->delete((int) $id);
      $this->flash("msg", "Đã xóa câu hỏi và dữ liệu liên quan!");
      header("Location: " . BASE_URL . "/index.php?url=CauhoiController/index&mon_id=" . $ch["mon_id"]);
      exit;
    }
    $this->flash("err", "Không tìm thấy câu hỏi!");
    header("Location: " . BASE_URL . "/index.php?url=CauhoiController/index");
    exit;
  }


  public function template()
  {
    $this->needAdmin();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=cauhoi_mau.csv');
    echo "\xEF\xBB\xBF";
    echo "ma_mon,noi_dung,A,B,C,D,dap_an,diem,giai_thich,loai,kich_hoat\n";


    $mons = $this->model("Monthi_m")->getAll();
    if (empty($mons)) {
      echo "TOAN,2+4=?,6,7,8,9,A,1,,D,1\n";
    } else {
      foreach ($mons as $m) {
        $code = $m["ma_mon"] ?? "MA_MON";
        echo "$code,Câu hỏi mẫu môn $code?,A,B,C,D,A,1,,D,1\n";
      }
    }
    exit;
  }


  public function import()
  {
    $this->needAdmin();

    if (empty($_FILES["file"]["tmp_name"])) {
      $this->flash("err", "Vui lòng chọn file Excel!");
      header("Location: " . BASE_URL . "/index.php?url=CauhoiController/index");
      exit;
    }

    $m = $this->model("Cauhoi_m");
    $monModel = $this->model("Monthi_m");


    $mons = $monModel->getAll();
    $mapMon = [];
    $mapTen = [];
    foreach ($mons as $mn) {
      $code = strtoupper(trim($mn["ma_mon"]));
      $name = strtoupper(trim($mn["ten_mon"]));
      $mapMon[$code] = (int) $mn["id"];
      if (!isset($mapTen[$name]))
        $mapTen[$name] = (int) $mn["id"];
    }

    $fh = fopen($_FILES["file"]["tmp_name"], "r");
    $first = true;
    $ok = 0;
    $skip = 0;
    $errDetail = "";


    $removeBOM = function ($str) {
      return preg_replace("/^\xEF\xBB\xBF/", '', $str);
    };

    while (($row = fgetcsv($fh)) !== false) {
      if ($first) {
        $first = false;
        continue;
      }


      $rawSubject = trim($row[0] ?? "");
      $rawSubject = $removeBOM($rawSubject);
      $subjectKey = strtoupper($rawSubject);


      $noi_dung = trim($row[1] ?? "");

      if ($subjectKey === "" || $noi_dung === "") {
        $skip++;
        continue;
      }


      $mon_id = $mapMon[$subjectKey] ?? 0;
      if ($mon_id <= 0) {

        $mon_id = $mapTen[$subjectKey] ?? 0;
      }


      if ($mon_id <= 0) {
        if (!$monModel->existsMa($rawSubject)) {
          $newName = "Môn " . $rawSubject;
          $mon_id_new = $monModel->insert($rawSubject, $newName);
          if ($mon_id_new > 0) {
            $mon_id = $mon_id_new;
            $mapMon[$subjectKey] = $mon_id;
            $mapTen[strtoupper($newName)] = $mon_id;
          }
        }
      }

      if ($mon_id <= 0) {
        if ($skip < 5)
          $errDetail .= " [Lỗi môn '$rawSubject']";
        $skip++;
        continue;
      }


      if ($m->checkExist($mon_id, $noi_dung)) {
        $skip++;
        continue;
      }


      $ansA = trim($row[2] ?? "");
      $ansB = trim($row[3] ?? "");
      $ansC = trim($row[4] ?? "");
      $ansD = trim($row[5] ?? "");
      $correct = strtoupper(trim($row[6] ?? ""));
      $score = (float) ($row[7] ?? 1);
      $explain = trim($row[8] ?? "");
      $type = trim($row[9] ?? "TB");

      if (!in_array($correct, ["A", "B", "C", "D"]))
        $correct = "A";

      $m->insert($mon_id, $noi_dung, $ansA, $ansB, $ansC, $ansD, $correct, $score, $explain, $type, 1);
      $ok++;
    }
    fclose($fh);

    $msg = "Import xong: Thêm $ok câu. Bỏ qua $skip câu (Trùng/Lỗi).";
    if ($errDetail)
      $msg .= " Chi tiết lỗi: " . $errDetail;

    $this->flash($skip > 0 ? "err" : "msg", $msg);
    header("Location: " . BASE_URL . "/index.php?url=CauhoiController/index");
    exit;
  }
}
