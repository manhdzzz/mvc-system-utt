<?php
class HocvienController extends Controller
{

  private function needHV()
  {
    if (empty($_SESSION["hv"])) {
      // GỘP LOGIN: quay về AuthController/login
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

  // ✅ FIX LỖI "Không tìm thấy Action: index"
  public function index()
  {
    // Trang mặc định của học viên = danh sách phòng thi
    $this->phongthi();
  }

  // (ảnh CNT58DH + danh sách phòng + nút Làm bài)
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

  // popup confirm (ảnh nền xanh + thông tin bài)
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
    if ((int) $rel["kich_hoat"] !== 1) {
      $this->flash("err", "Bạn chưa được kích hoạt vào phòng thi!");
      header("Location: " . BASE_URL . "/index.php?url=HocvienController/phongthi");
      exit;
    }

    $so_cau = ((int) $phong["cau_de"] + (int) $phong["cau_tb"] + (int) $phong["cau_kho"]);
    if ($so_cau <= 0)
      $so_cau = 10; // fallback

    $this->view("layout_hv_blank", [
      "page" => "Pages/hv_confirm",
      "phong" => $phong,
      "so_cau" => $so_cau,
      "so_lan_thi" => (int) ($rel["lam_lai"] ?? 0),
    ]);
  }

  // tạo bài làm + chuyển sang làm bài
  public function start()
  {
    $this->needHV();
    $hv_id = (int) $_SESSION["hv"]["id"];
    $phong_id = (int) ($_POST["phong_id"] ?? 0);

    $m = $this->model("LamBai_m");
    $phong = $m->phongInfo($phong_id);
    if (!$phong)
      die("Không tìm thấy phòng");

    $rel = $m->hvInPhong($phong_id, $hv_id);
    if (!$rel || (int) $rel["kich_hoat"] !== 1) {
      $this->flash("err", "Bạn chưa được kích hoạt!");
      header("Location: " . BASE_URL . "/index.php?url=HocvienController/phongthi");
      exit;
    }

    // nếu đang Doing thì dùng lại
    $doing = $m->getBaiLamDoing($phong_id, $hv_id);
    if ($doing) {
      header("Location: " . BASE_URL . "/index.php?url=HocvienController/do/" . $doing["id"]);
      exit;
    }

    // Kiểm tra nếu đã nộp bài rồi (trang_thai = 'Đã nộp') thì không cho làm lại
    // Admin reset sẽ đổi trang_thai về 'Chưa thi' để cho phép làm lại
    $trangThai = $rel["trang_thai"] ?? "";
    if ($trangThai === "Đã nộp" || $trangThai === "Hủy") {
      $this->flash("err", "Bạn đã hoàn thành bài thi này rồi! Liên hệ Admin để làm lại.");
      header("Location: " . BASE_URL . "/index.php?url=HocvienController/phongthi");
      exit;
    }

    $qs = $m->pickQuestions($phong); // lấy câu hỏi theo đề
    $bailam_id = $m->createBaiLam($phong_id, $hv_id, (int) $phong["de_id"], count($qs));
    foreach ($qs as $q) {
      $m->insertBaiLamCT($bailam_id, (int) $q["id"]);
    }

    $m->setVaoPhongTime($phong_id, $hv_id);

    header("Location: " . BASE_URL . "/index.php?url=HocvienController/do/" . $bailam_id);
    exit;
  }

  // trang làm bài (trắc nghiệm + đếm giờ)
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
    $time_remaining = $m->getRemainingTime((int) $bailam_id);

    // Nếu hết giờ, tự động nộp bài
    if ($time_remaining <= 0) {
      $answers = [];
      foreach ($answered as $cid => $ans) {
        if ($ans)
          $answers[$cid] = $ans;
      }
      $kq = $m->submit($bailam_id, $answers);
      $m->updatePhongHVAfterSubmit((int) $bl["phong_id"], $hv_id, $kq);
      header("Location: " . BASE_URL . "/index.php?url=HocvienController/result/" . $bailam_id);
      exit;
    }

    $this->view("layout_hv_blank", [
      "page" => "Pages/hv_do",
      "bl" => $bl,
      "qs" => $qs,
      "answered" => $answered,
      "time_remaining" => $time_remaining
    ]);
  }

  // AJAX: lưu đáp án realtime
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

    // Kiểm tra hết giờ
    $remaining = $m->getRemainingTime($bailam_id);
    if ($remaining <= 0) {
      echo json_encode(["ok" => false, "msg" => "Hết giờ", "timeout" => true]);
      exit;
    }

    $m->saveAnswer($bailam_id, $cauhoi_id, $answer);
    echo json_encode(["ok" => true, "remaining" => $remaining]);
    exit;
  }

  // nộp bài
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

  // lịch sử + kết quả (ảnh bảng điểm)
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

  // xem đáp án (icon con mắt)
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

  // Đăng xuất học viên
  public function logout()
  {
    unset($_SESSION["hv"]);
    header("Location: " . BASE_URL . "/index.php?url=AuthController/login");
    exit;
  }
}
