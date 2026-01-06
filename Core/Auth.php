<?php
class Auth {
  public static function user(){
    return $_SESSION["user"] ?? null;
  }

  public static function requireLogin(){
    if(empty($_SESSION["user"])){
      header("Location: ".BASE_URL."/AuthController/login");
      exit;
    }
  }

  public static function requireAdmin(){
    self::requireLogin();
    if(($_SESSION["user"]["role"] ?? "") !== "admin"){
      die("403 - Bạn không có quyền truy cập");
    }
  }
}
