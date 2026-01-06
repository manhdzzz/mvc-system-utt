<?php
class HocvienController extends Controller
{


  public function index()
  {

    $this->phongthi();
  }


  public function phongthi()
  {
    $this->needHV();
    $hv_id = (int) $_SESSION["hv"]["id"];
    $m = $this->model("LamBai_m");
    $pack = $m->listPhongThiByHocVien($hv_id);

    $this->view("layout_hv", [
      "page" => "Pages/hv_phong_list",
      "lop" => $pack["lop"],
      "rows" => $pack["rows"],
      "msg" => $this->getFlash("msg"),
      "err" => $this->getFlash("err"),
    ]);
  }


  public function confirm($phong_id)
  {
    $this->needHV();
    $hv_id = (int) $_SESSION["hv"]["id"];
    $m = $this->model("LamBai_m");

    $phong = $m->phongInfo((int) $phong_id);
    if (!$phong)
      die("Không tìm thấy phòng");

    $rel = $m->hvInPhong((int) $phong_id, $hv_id);
    if (!$rel)
      die("Bạn không thuộc phòng này");


    if (strtotime($phong["bat_dau"]) > time()) {
      $this->flash("err", "Chưa đến giờ thi, vui lòng kiểm tra lại");
      header("Location: " . BASE_URL . "/index.php?url=HocvienController/phongthi");
      exit;
    }


    $lopModel = $this->model("Lophoc_m");
    $lop = $lopModel->find((int) $phong["lop_id"]);
    if (!$lop || (int) ($lop["trangthai"] ?? 0) !== 1) {
      $this->flash("err", "Lớp học đã bị khóa, không thể tham gia thi!");
      header("Location: " . BASE_URL . "/index.php?url=HocvienController/phongthi");
      exit;
    }


    if ((int) $rel["kich_hoat"] !== 1) {
      $ghi_chu = $rel["ghi_chu"] ?? "";
      if (stripos($ghi_chu, "Vi phạm") !== false) {
        $this->flash("err", "Bạn đã bị cấm thi - " . $ghi_chu);
      } else {
        $this->flash("err", "Bạn chưa được kích hoạt vào phòng thi!");
      }
      header("Location: " . BASE_URL . "/index.php?url=HocvienController/phongthi");
      exit;
    }

    $so_cau = ((int) $phong["cau_de"] + (int) $phong["cau_tb"] + (int) $phong["cau_kho"]);
    if ($so_cau <= 0)
      $so_cau = 10;

    $this->view("layout_hv_blank", [
      "page" => "Pages/hv_confirm",
      "phong" => $phong,
      "so_cau" => $so_cau,
      "so_lan_thi" => (int) ($rel["lam_lai"] ?? 0),
    ]);
  }


  public function start()
  {
    $this->needHV();
    $hv_id = (int) $_SESSION["hv"]["id"];
    $phong_id = (int) ($_POST["phong_id"] ?? 0);

    $m = $this->model("LamBai_m");
    $phong = $m->phongInfo($phong_id);
    if (!$phong)
      die("Không tìm thấy phòng");


    $lopModel = $this->model("Lophoc_m");
    $lop = $lopModel->find((int) $phong["lop_id"]);
    if (!$lop || (int) ($lop["trangthai"] ?? 0) !== 1) {
      $this->flash("err", "Lớp học đã bị khóa, không thể tham gia thi!");
      header("Location: " . BASE_URL . "/index.php?url=HocvienController/phongthi");
      exit;
    }


    if (strtotime($phong["bat_dau"]) > time()) {
      $this->flash("err", "Chưa đến giờ thi, vui lòng kiểm tra lại");
      header("Location: " . BASE_URL . "/index.php?url=HocvienController/phongthi");
      exit;
    }

    $rel = $m->hvInPhong($phong_id, $hv_id);
    if (!$rel || (int) $rel["kich_hoat"] !== 1) {
      $ghi_chu = $rel["ghi_chu"] ?? "";
      if (stripos($ghi_chu, "Vi phạm") !== false) {
        $this->flash("err", "Bạn đã bị cấm thi - " . $ghi_chu);
      } else {
        $this->flash("err", "Bạn chưa được kích hoạt!");
      }
      header("Location: " . BASE_URL . "/index.php?url=HocvienController/phongthi");
      exit;
    }


    $doing = $m->getBaiLamDoing($phong_id, $hv_id);
    if ($doing) {
      header("Location: " . BASE_URL . "/index.php?url=HocvienController/do/" . $doing["id"]);
      exit;
    }



    $trangThai = $rel["trang_thai"] ?? "";
    if ($trangThai === "Đã nộp" || $trangThai === "Hủy") {
      $this->flash("err", "Bạn đã hoàn thành bài thi này rồi! Liên hệ Admin để làm lại.");
      header("Location: " . BASE_URL . "/index.php?url=HocvienController/phongthi");
      exit;
    }

    $qs = $m->pickQuestions($phong);
    $bailam_id = $m->createBaiLam($phong_id, $hv_id, (int) $phong["de_id"], count($qs));
    foreach ($qs as $q) {
      $m->insertBaiLamCT($bailam_id, (int) $q["id"]);
    }

    $m->setVaoPhongTime($phong_id, $hv_id);

    header("Location: " . BASE_URL . "/index.php?url=HocvienController/do/" . $bailam_id);
    exit;
  }


  public function do($bailam_id = null)
  {
    $this->needHV();
    $hv_id = (int) $_SESSION["hv"]["id"];

    $m = $this->model("LamBai_m");
    $bl = $m->getBaiLam((int) $bailam_id, $hv_id);
    if (!$bl)
      die("Không tìm thấy bài làm");

    if ($bl["status"] !== "Doing") {
      header("Location: " . BASE_URL . "/index.php?url=HocvienController/result/" . $bl["id"]);
      exit;
    }

    $qs = $m->getQuestionsOfBaiLam((int) $bailam_id);
    $answered = $m->getAnsweredStatus((int) $bailam_id);
    $reviewStatus = $m->getReviewStatus((int) $bailam_id);
    $time_remaining = $m->getRemainingTime((int) $bailam_id);


    $pm = $this->model("Phongthi_m");
    $vpStatus = $pm->getViPhamStatus((int) $bl["phong_id"], $hv_id);
    $so_vp = (int) ($vpStatus["so_lan_vi_pham"] ?? 0);


    if ($time_remaining <= 0) {
      $answers = [];
      foreach ($answered as $cid => $ans) {
        if ($ans)
          $answers[$cid] = $ans;
      }
      $kq = $m->submit($bailam_id, $answers);
      $m->updatePhongHVAfterSubmit((int) $bl["phong_id"], $hv_id, $kq);
      $this->flash("err", "Hết giờ làm bài! Hệ thống đã tự động nộp bài.");
      header("Location: " . BASE_URL . "/index.php?url=HocvienController/result/" . $bailam_id);
      exit;
    }

    $this->view("layout_hv_blank", [
      "page" => "Pages/hv_do",
      "bl" => $bl,
      "qs" => $qs,
      "answered" => $answered,
      "reviewStatus" => $reviewStatus,
      "time_remaining" => $time_remaining,
      "so_vp" => $so_vp
    ]);
  }



  public function saveAnswer()
  {
    header("Content-Type: application/json; charset=utf-8");

    if (empty($_SESSION["hv"])) {
      echo json_encode(["ok" => false, "msg" => "Chưa đăng nhập"]);
      exit;
    }

    $hv_id = (int) $_SESSION["hv"]["id"];
    $bailam_id = (int) ($_POST["bailam_id"] ?? 0);
    $cauhoi_id = (int) ($_POST["cauhoi_id"] ?? 0);
    $answer = trim($_POST["answer"] ?? "");

    $m = $this->model("LamBai_m");
    $bl = $m->getBaiLam($bailam_id, $hv_id);
    if (!$bl || $bl["status"] !== "Doing") {
      echo json_encode(["ok" => false, "msg" => "Bài làm không hợp lệ"]);
      exit;
    }


    $remaining = $m->getRemainingTime($bailam_id);
    if ($remaining <= 0) {
      echo json_encode(["ok" => false, "msg" => "Hết giờ", "timeout" => true]);
      exit;
    }

    $m->saveAnswer($bailam_id, $cauhoi_id, $answer);
    echo json_encode(["ok" => true, "remaining" => $remaining]);
    exit;
  }


  public function saveViolation()
  {
    header("Content-Type: application/json; charset=utf-8");

    if (empty($_SESSION["hv"])) {
      echo json_encode(["ok" => false, "msg" => "Chưa đăng nhập"]);
      exit;
    }

    $hv_id = (int) $_SESSION["hv"]["id"];
    $bailam_id = (int) ($_POST["bailam_id"] ?? 0);

    $m = $this->model("LamBai_m");
    $bl = $m->getBaiLam($bailam_id, $hv_id);

    if (!$bl || $bl["status"] !== "Doing") {
      echo json_encode(["ok" => false, "msg" => "Bài làm không hợp lệ"]);
      exit;
    }


    $pm = $this->model("Phongthi_m");
    $result = $pm->tangViPham((int) $bl["phong_id"], $hv_id);

    echo json_encode([
      "ok" => true,
      "so_vp" => $result["so_vp"] ?? 0,
      "cam_thi" => $result["cam_thi"] ?? false
    ]);
    exit;
  }


  public function markReview()
  {
    header("Content-Type: application/json; charset=utf-8");

    if (empty($_SESSION["hv"])) {
      echo json_encode(["ok" => false]);
      exit;
    }

    $hv_id = (int) $_SESSION["hv"]["id"];
    $bailam_id = (int) ($_POST["bailam_id"] ?? 0);
    $cauhoi_id = (int) ($_POST["cauhoi_id"] ?? 0);
    $phan_van = (int) ($_POST["phan_van"] ?? 0);

    $m = $this->model("LamBai_m");
    $bl = $m->getBaiLam($bailam_id, $hv_id);

    if (!$bl || $bl["status"] !== "Doing") {
      echo json_encode(["ok" => false]);
      exit;
    }

    $m->markReview($bailam_id, $cauhoi_id, $phan_van);
    echo json_encode(["ok" => true]);
    exit;
  }


  public function submit()
  {
    $this->needHV();
    $hv_id = (int) $_SESSION["hv"]["id"];

    $bailam_id = (int) ($_POST["bailam_id"] ?? 0);
    $answers = $_POST["ans"] ?? [];

    $m = $this->model("LamBai_m");
    $bl = $m->getBaiLam($bailam_id, $hv_id);
    if (!$bl)
      die("Không tìm thấy bài làm");

    $kq = $m->submit($bailam_id, $answers);
    $m->updatePhongHVAfterSubmit((int) $bl["phong_id"], $hv_id, $kq);

    header("Location: " . BASE_URL . "/index.php?url=HocvienController/result/" . $bailam_id);
    exit;
  }


  public function history()
  {
    $this->needHV();
    $hv_id = (int) $_SESSION["hv"]["id"];
    $rows = $this->model("LamBai_m")->listHistory($hv_id);

    $this->view("layout_hv", [
      "page" => "Pages/hv_history",
      "rows" => $rows
    ]);
  }


  public function result($bailam_id)
  {
    $this->needHV();
    $hv_id = (int) $_SESSION["hv"]["id"];
    $m = $this->model("LamBai_m");

    $bl = $m->getBaiLam((int) $bailam_id, $hv_id);
    if (!$bl)
      die("Không tìm thấy");

    $qs = $m->getQuestionsOfBaiLam((int) $bailam_id);

    $this->view("layout_hv", [
      "page" => "Pages/hv_result",
      "bl" => $bl,
      "qs" => $qs
    ]);
  }


  public function logout()
  {
    unset($_SESSION["hv"]);





    header("Location: " . BASE_URL . "/index.php?url=AuthController/login");
    exit;
  }
}
