<?php
class Monthi_m extends Database
{

  public function getAll()
  {
    $st = $this->con->query("SELECT * FROM mon_thi ORDER BY id DESC");
    return $st->fetchAll();
  }

  public function list($q = "", $limit = 50, $offset = 0)
  {
    $qLike = "%" . trim($q) . "%";
    $st = $this->con->prepare("
      SELECT m.*, 
        (SELECT COUNT(*) FROM cau_hoi WHERE mon_id=m.id) as so_cau,
        (SELECT COUNT(*) FROM de_thi WHERE mon_id=m.id) as so_de
      FROM mon_thi m
      WHERE m.ma_mon LIKE ? OR m.ten_mon LIKE ?
      ORDER BY m.id DESC
      LIMIT " . (int) $limit . " OFFSET " . (int) $offset);
    $st->execute([$qLike, $qLike]);
    return $st->fetchAll();
  }

  public function count($q = "")
  {
    $qLike = "%" . trim($q) . "%";
    $st = $this->con->prepare("
      SELECT COUNT(*) as c
      FROM mon_thi m
      WHERE m.ma_mon LIKE ? OR m.ten_mon LIKE ?
    ");
    $st->execute([$qLike, $qLike]);
    $row = $st->fetch();
    return $row ? (int) $row["c"] : 0;
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

  public function canDelete($id)
  {
    
    $st = $this->con->prepare("SELECT COUNT(*) c FROM cau_hoi WHERE mon_id=?");
    $st->execute([(int) $id]);
    if ($st->fetch()["c"] > 0)
      return false;

    
    $st = $this->con->prepare("SELECT COUNT(*) c FROM de_thi WHERE mon_id=?");
    $st->execute([(int) $id]);
    if ($st->fetch()["c"] > 0)
      return false;

    
    $st = $this->con->prepare("SELECT COUNT(*) c FROM phong_thi WHERE mon_id=?");
    $st->execute([(int) $id]);
    if ($st->fetch()["c"] > 0)
      return false;

    return true;
  }

  public function delete($id)
  {
    $id = (int) $id;

    
    $st = $this->con->prepare("SELECT id FROM cau_hoi WHERE mon_id=?");
    $st->execute([$id]);
    $qs = $st->fetchAll();
    foreach ($qs as $q) {
      $qid = (int) $q["id"];
      $this->con->prepare("DELETE FROM bai_lam_ct WHERE cauhoi_id=?")->execute([$qid]);
      $this->con->prepare("DELETE FROM de_thi_cau_hoi WHERE cauhoi_id=?")->execute([$qid]);
      $this->con->prepare("DELETE FROM cau_hoi WHERE id=?")->execute([$qid]);
    }

    
    $st = $this->con->prepare("SELECT id FROM de_thi WHERE mon_id=?");
    $st->execute([$id]);
    $ds = $st->fetchAll();
    foreach ($ds as $d) {
      $did = (int) $d["id"];
      $this->con->prepare("DELETE FROM de_thi_cau_hoi WHERE de_id=?")->execute([$did]);
      $this->con->prepare("DELETE FROM de_thi WHERE id=?")->execute([$did]);
    }

    
    $st = $this->con->prepare("SELECT id FROM phong_thi WHERE mon_id=?");
    $st->execute([$id]);
    $ps = $st->fetchAll();
    foreach ($ps as $p) {
      $pid = (int) $p["id"];
      $this->con->prepare("DELETE FROM phong_thi_hoc_vien WHERE phong_id=?")->execute([$pid]);
      $stBl = $this->con->prepare("SELECT id FROM bai_lam WHERE phong_id=?");
      $stBl->execute([$pid]);
      $bls = $stBl->fetchAll();
      foreach ($bls as $b) {
        $this->con->prepare("DELETE FROM bai_lam_ct WHERE bailam_id=?")->execute([(int) $b["id"]]);
      }
      $this->con->prepare("DELETE FROM bai_lam WHERE phong_id=?")->execute([$pid]);
      $this->con->prepare("DELETE FROM phong_thi WHERE id=?")->execute([$pid]);
    }

    $st = $this->con->prepare("DELETE FROM mon_thi WHERE id=?");
    return $st->execute([(int) $id]);
  }
}
