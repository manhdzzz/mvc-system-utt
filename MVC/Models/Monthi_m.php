<?php
class Monthi_m extends Database
{

  public function getAll()
  {
    $st = $this->con->query("SELECT * FROM mon_thi ORDER BY id DESC");
    return $st->fetchAll();
  }

  public function list($q = "")
  {
    $qLike = "%" . trim($q) . "%";
    $st = $this->con->prepare("
      SELECT m.*, 
        (SELECT COUNT(*) FROM cau_hoi WHERE mon_id=m.id) as so_cau,
        (SELECT COUNT(*) FROM de_thi WHERE mon_id=m.id) as so_de
      FROM mon_thi m
      WHERE m.ma_mon LIKE ? OR m.ten_mon LIKE ?
      ORDER BY m.id DESC
    ");
    $st->execute([$qLike, $qLike]);
    return $st->fetchAll();
  }

  public function find($id)
  {
    $st = $this->con->prepare("SELECT * FROM mon_thi WHERE id=?");
    $st->execute([(int) $id]);
    return $st->fetch();
  }

  public function existsMa($ma, $ignore_id = 0)
  {
    if ($ignore_id > 0) {
      $st = $this->con->prepare("SELECT id FROM mon_thi WHERE ma_mon=? AND id<>?");
      $st->execute([trim($ma), (int) $ignore_id]);
    } else {
      $st = $this->con->prepare("SELECT id FROM mon_thi WHERE ma_mon=?");
      $st->execute([trim($ma)]);
    }
    return (bool) $st->fetch();
  }

  public function insert($ma, $ten)
  {
    $st = $this->con->prepare("INSERT INTO mon_thi(ma_mon, ten_mon) VALUES(?,?)");
    $st->execute([trim($ma), trim($ten)]);
    return (int) $this->con->lastInsertId();
  }

  public function update($id, $ma, $ten)
  {
    $st = $this->con->prepare("UPDATE mon_thi SET ma_mon=?, ten_mon=? WHERE id=?");
    return $st->execute([trim($ma), trim($ten), (int) $id]);
  }

  public function delete($id)
  {
    $st = $this->con->prepare("DELETE FROM mon_thi WHERE id=?");
    return $st->execute([(int) $id]);
  }
}
