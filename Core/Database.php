<?php
class Database
{
  protected $con;
  public function __construct()
  {
    try {
      $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
      $this->con = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
      ]);
    } catch (PDOException $e) {
      die("Không thể kết nối đến cơ sở dữ liệu.");
    }
  }

  public function getConnection()
  {
    return $this->con;
  }
}
