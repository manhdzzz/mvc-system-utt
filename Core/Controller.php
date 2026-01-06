<?php
class Controller
{
  public function model($name)
  {
    require_once __DIR__ . "/../MVC/Models/$name.php";
    return new $name;
  }

  public function view($layout, $data = [])
  {
    require_once __DIR__ . "/../MVC/Views/$layout.php";
  }

  protected function flash($k, $v)
  {
    $_SESSION["_flash"][$k] = $v;
  }

  protected function getFlash($k)
  {
    $v = $_SESSION["_flash"][$k] ?? "";
    unset($_SESSION["_flash"][$k]);
    return $v;
  }

  protected function needLogin()
  {
    if (empty($_SESSION["user"])) {
      header("Location: " . BASE_URL . "/index.php?url=AuthController/login");
      exit;
    }
  }

  protected function needAdmin()
  {
    $this->needLogin();
    $role = $_SESSION["user"]["role"] ?? "";
    if ($role !== "admin") {
      die("Bạn không có quyền truy cập (Admin only)!");
    }
  }

  protected function needGV()
  {
    $this->needLogin();
    $role = $_SESSION["user"]["role"] ?? "";
    if ($role !== "admin" && $role !== "gv") {
      die("Bạn không có quyền truy cập!");
    }
  }

  protected function needHV()
  {
    if (empty($_SESSION["hv"])) {
      header("Location: " . BASE_URL . "/index.php?url=AuthController/login");
      exit;
    }
  }
}
