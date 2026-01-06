<?php
class Lophoc_m extends Database
{

  
  public function getAll()
  {
    $sql = "
      SELECT l.*,
             (SELECT COUNT(*) FROM hoc_vien hv WHERE hv.lop_id = l.id) AS so_hoc_vien
      FROM lop_hoc l
      ORDER BY l.id DESC
    ";
    return $this->con->query($sql)->fetchAll();
  }

  
  public function find($id)
  {
    $st = $this->con->prepare("SELECT * FROM lop_hoc WHERE id=?");
    $st->execute([(int) $id]);
    return $st->fetch();
  }

  
  
  public function existsMaLop($ma_lop, $ignoreId = 0)
  {
    $st = $this->con->prepare("SELECT id FROM lop_hoc WHERE ma_lop=? AND id<>?");
    $st->execute([trim($ma_lop), (int) $ignoreId]);
    return (bool) $st->fetch();
  }

  
  public function insert($ma_lop, $ten_lop, $trangthai, $nguoi_tao)
  {
    $sql = "INSERT INTO lop_hoc(ma_lop, ten_lop, trangthai, nguoi_tao)
            VALUES (?,?,?,?)";
    $st = $this->con->prepare($sql);
    return $st->execute([
      trim($ma_lop),
      trim($ten_lop),
      (int) $trangthai,
      trim($nguoi_tao)
    ]);
  }

  
  public function updateBasic($id, $ma_lop, $ten_lop, $trangthai)
  {
    $sql = "UPDATE lop_hoc
            SET ma_lop=?, ten_lop=?, trangthai=?
            WHERE id=?";
    $st = $this->con->prepare($sql);
    return $st->execute([
      trim($ma_lop),
      trim($ten_lop),
      (int) $trangthai,
      (int) $id
    ]);
  }

  
  public function canDelete($id)
  {
    
    $st = $this->con->prepare("SELECT COUNT(*) c FROM users WHERE lop_id=?");
    $st->execute([(int) $id]);
    if ($st->fetch()["c"] > 0)
      return false;

    
    $st = $this->con->prepare("SELECT COUNT(*) c FROM hoc_vien WHERE lop_id=?");
    $st->execute([(int) $id]);
    if ($st->fetch()["c"] > 0)
      return false;

    
    $st = $this->con->prepare("SELECT COUNT(*) c FROM phong_thi WHERE lop_id=?");
    $st->execute([(int) $id]);
    if ($st->fetch()["c"] > 0)
      return false;

    return true;
  }

  
  public function delete($id)
  {
    $id = (int) $id;

    
    $st = $this->con->prepare("SELECT id FROM hoc_vien WHERE lop_id=?");
    $st->execute([$id]);
    $hvs = $st->fetchAll();
    foreach ($hvs as $hw) {
      $hid = (int) $hw["id"];
      
      $bls = $this->con->prepare("SELECT id FROM bai_lam WHERE hocvien_id=?");
      $bls->execute([$hid]);
      $rows = $bls->fetchAll();
      foreach ($rows as $r) {
        $this->con->prepare("DELETE FROM bai_lam_ct WHERE bailam_id=?")->execute([(int) $r["id"]]);
      }
      $this->con->prepare("DELETE FROM bai_lam WHERE hocvien_id=?")->execute([$hid]);
      
      $this->con->prepare("DELETE FROM phong_thi_hoc_vien WHERE hocvien_id=?")->execute([$hid]);
      
      $this->con->prepare("DELETE FROM hoc_vien WHERE id=?")->execute([$hid]);
    }

    
    $st = $this->con->prepare("SELECT id FROM phong_thi WHERE lop_id=?");
    $st->execute([$id]);
    $ps = $st->fetchAll();
    foreach ($ps as $p) {
      $pid = (int) $p["id"];
      $this->con->prepare("DELETE FROM phong_thi_hoc_vien WHERE phong_id=?")->execute([$pid]);
      
      $bls = $this->con->prepare("SELECT id FROM bai_lam WHERE phong_id=?");
      $bls->execute([$pid]);
      $rows = $bls->fetchAll();
      foreach ($rows as $r) {
        $this->con->prepare("DELETE FROM bai_lam_ct WHERE bailam_id=?")->execute([(int) $r["id"]]);
      }
      $this->con->prepare("DELETE FROM bai_lam WHERE phong_id=?")->execute([$pid]);
      $this->con->prepare("DELETE FROM phong_thi WHERE id=?")->execute([$pid]);
    }

    $st = $this->con->prepare("DELETE FROM lop_hoc WHERE id=?");
    return $st->execute([$id]);
  }

  
  public function search($q)
  {
    $q = "%" . trim($q) . "%";
    $st = $this->con->prepare("
      SELECT l.*,
             (SELECT COUNT(*) FROM hoc_vien hv WHERE hv.lop_id = l.id) AS so_hoc_vien
      FROM lop_hoc l
      WHERE l.ma_lop LIKE ? OR l.ten_lop LIKE ?
      ORDER BY l.id DESC
    ");
    $st->execute([$q, $q]);
    return $st->fetchAll();
  }

  public function listPaged($q = "", $limit = 50, $offset = 0)
  {
    $q = "%" . trim($q) . "%";
    $sql = "
      SELECT l.*,
             (SELECT COUNT(*) FROM hoc_vien hv WHERE hv.lop_id = l.id) AS so_hoc_vien
      FROM lop_hoc l
      WHERE l.ma_lop LIKE ? OR l.ten_lop LIKE ?
      ORDER BY l.id DESC LIMIT $limit OFFSET " . (int) $offset;
    $st = $this->con->prepare($sql);
    $st->execute([$q, $q]);
    return $st->fetchAll();
  }

  public function count($q = "")
  {
    $q = "%" . trim($q) . "%";
    $st = $this->con->prepare("SELECT COUNT(*) c FROM lop_hoc WHERE ma_lop LIKE ? OR ten_lop LIKE ?");
    $st->execute([$q, $q]);
    return (int) ($st->fetch()["c"] ?? 0);
  }
}
