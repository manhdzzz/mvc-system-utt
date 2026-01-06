<?php
class App {
  public function __construct(){
    if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

    $url = $_GET["url"] ?? "AuthController/login";
    $arr = explode("/", trim($url,"/"));

    $controller = $arr[0] ?: "AuthController";
    $action     = $arr[1] ?? "login";
    $params     = array_slice($arr,2);

    $file = __DIR__."/../MVC/Controllers/$controller.php";
    if(!file_exists($file)) die("Không tìm thấy Controller: $controller");

    require_once $file;
    $c = new $controller;
    if(!method_exists($c,$action)) die("Không tìm thấy Action: $action");

    call_user_func_array([$c,$action], $params);
  }
}
