<?php
class AuthController extends Controller
{

  public function login()
  {
    $this->view("layout_login", [
      "page" => "Pages/login",
      "err" => ($_SESSION["err"] ?? "")
    ]);
    unset($_SESSION["err"]);
  }

  public function doLogin()
  {
    $u = trim($_POST["username"] ?? "");
    $p = $_POST["password"] ?? "";

    
    
    $mUser = $this->model("User_m");
    $user = $mUser->findByUsername($u);

    if ($user) {
      
      $dbPass = $user["password_hash"];
      $ok = false;

      
      if (is_string($dbPass) && strlen($dbPass) > 20 && strpos($dbPass, '$2y$') === 0) {
        if (password_verify($p, $dbPass))
          $ok = true;
      } else {
        if ($p === $dbPass)
          $ok = true;
      }

      if (!$ok) {
        $_SESSION["err"] = "Sai tài khoản hoặc mật khẩu!";
        header("Location: " . BASE_URL . "/index.php?url=AuthController/login");
        exit;
      }

      
      if ((int) ($user["trangthai"] ?? 1) !== 1) {
        $_SESSION["err"] = "Tài khoản đang bị khóa!";
        header("Location: " . BASE_URL . "/index.php?url=AuthController/login");
        exit;
      }

      
      $_SESSION["user"] = [
        "id" => $user["id"],
        "hoten" => $user["hoten"],
        "username" => $user["username"],
        "role" => $user["role"] ?? "admin"
      ];

      if (($_SESSION["user"]["role"] ?? "admin") === "gv") {
        header("Location: " . BASE_URL . "/index.php?url=PhongthiController/index");
      } else {
        header("Location: " . BASE_URL . "/index.php?url=DashboardController/index");
      }
      exit;
    }

    
    $mhvModel = $this->model("Hocvien_m");
    $hv = $mhvModel->findByMaHV($u);

    if (!$hv) {
      
      $_SESSION["err"] = "Sai tài khoản hoặc mật khẩu!";
      header("Location: " . BASE_URL . "/index.php?url=AuthController/login");
      exit;
    }

    
    $dbPassHV = $hv["password_hash"] ?? ($hv["matkhau"] ?? "");
    $okHV = false;

    
    if (is_string($dbPassHV) && strlen($dbPassHV) > 20 && strpos($dbPassHV, '$2y$') === 0) {
      if (password_verify($p, $dbPassHV))
        $okHV = true;
    } else {
      if ($p === $dbPassHV)
        $okHV = true;
    }

    if (!$okHV) {
      $_SESSION["err"] = "Sai tài khoản hoặc mật khẩu!";
      header("Location: " . BASE_URL . "/index.php?url=AuthController/login");
      exit;
    }

    
    if ((int) ($hv["trangthai"] ?? 1) !== 1) {
      $_SESSION["err"] = "Tài khoản học viên đang bị khóa!";
      header("Location: " . BASE_URL . "/index.php?url=AuthController/login");
      exit;
    }

    
    $_SESSION["hv"] = [
      "id" => $hv["id"],
      "hoten" => $hv["hoten"] ?? ($hv["tenhv"] ?? ""),
      "ma_hv" => $hv["ma_hv"] ?? ($hv["mahv"] ?? $u),
      "lop_id" => $hv["lop_id"] ?? ($hv["malop"] ?? 0)
    ];

    header("Location: " . BASE_URL . "/index.php?url=HocvienController/phongthi");
    exit;
  }

  public function logout()
  {
    session_destroy();
    header("Location: " . BASE_URL . "/index.php?url=AuthController/login");
    exit;
  }
}
