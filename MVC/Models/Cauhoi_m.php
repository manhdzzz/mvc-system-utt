<?php
class Cauhoi_m extends Database
{

  public function list($mon_id = 0, $q = "", $offset = 0)
  {
    $q = trim($q);
    $qLike = "%" . $q . "%";

    if ($mon_id > 0) {
      $sql = "
      SELECT ch.*, m.ten_mon
      FROM cau_hoi ch
      JOIN mon_thi m ON m.id = ch.mon_id
      WHERE ch.mon_id=?
        AND (
          ch.noi_dung LIKE ?
          OR ch.dap_an_a LIKE ?
          OR ch.dap_an_b LIKE ?
          OR ch.dap_an_c LIKE ?
          OR ch.dap_an_d LIKE ?
        )
      ORDER BY ch.id DESC LIMIT 50 OFFSET " . (int) $offset;
      $st = $this->con->prepare($sql);
      $st->execute([(int) $mon_id, $qLike, $qLike, $qLike, $qLike, $qLike]);
      return $st->fetchAll();
    }

    $sql = "
    SELECT ch.*, m.ten_mon
    FROM cau_hoi ch
    JOIN mon_thi m ON m.id = ch.mon_id
    WHERE
      ch.noi_dung LIKE ?
      OR ch.dap_an_a LIKE ?
      OR ch.dap_an_b LIKE ?
      OR ch.dap_an_c LIKE ?
      OR ch.dap_an_d LIKE ?
    ORDER BY ch.id DESC LIMIT 50 OFFSET " . (int) $offset;
    $st = $this->con->prepare($sql);
    $st->execute([$qLike, $qLike, $qLike, $qLike, $qLike]);
    return $st->fetchAll();
  }

  public function count($mon_id = 0, $q = "")
  {
    $q = trim($q);
    $qLike = "%" . $q . "%";
    if ($mon_id > 0) {
      $st = $this->con->prepare("SELECT COUNT(*) c FROM cau_hoi WHERE mon_id=? AND (noi_dung LIKE ? OR dap_an_a LIKE ? OR dap_an_b LIKE ? OR dap_an_c LIKE ? OR dap_an_d LIKE ?)");
      $st->execute([(int) $mon_id, $qLike, $qLike, $qLike, $qLike, $qLike]);
    } else {
      $st = $this->con->prepare("SELECT COUNT(*) c FROM cau_hoi WHERE noi_dung LIKE ? OR dap_an_a LIKE ? OR dap_an_b LIKE ? OR dap_an_c LIKE ? OR dap_an_d LIKE ?");
      $st->execute([$qLike, $qLike, $qLike, $qLike, $qLike]);
    }
    return (int) ($st->fetch()["c"] ?? 0);
  }


  public function find($id)
  {
    $st = $this->con->prepare("SELECT * FROM cau_hoi WHERE id=?");
    $st->execute([(int) $id]);
    return $st->fetch();
  }

  public function insert($mon_id, $nd, $a, $b, $c, $d, $dad, $diem, $gt, $loai, $kich)
  {
    $st = $this->con->prepare("
      INSERT INTO cau_hoi(mon_id,noi_dung,dap_an_a,dap_an_b,dap_an_c,dap_an_d,dap_an_dung,diem,giai_thich,loai,kich_hoat)
      VALUES (?,?,?,?,?,?,?,?,?,?,?)
    ");
    return $st->execute([(int) $mon_id, $nd, $a, $b, $c, $d, $dad, (int) $diem, $gt, $loai, (int) $kich]);
  }

  public function updateBasic($id, $mon_id, $nd, $a, $b, $c, $d, $dad, $diem, $gt, $loai, $kich)
  {
    $st = $this->con->prepare("
      UPDATE cau_hoi
      SET mon_id=?, noi_dung=?, dap_an_a=?, dap_an_b=?, dap_an_c=?, dap_an_d=?,
          dap_an_dung=?, diem=?, giai_thich=?, loai=?, kich_hoat=?
      WHERE id=?
    ");
    return $st->execute([(int) $mon_id, $nd, $a, $b, $c, $d, $dad, (int) $diem, $gt, $loai, (int) $kich, (int) $id]);
  }

  public function canDelete($id)
  {

    $st = $this->con->prepare("SELECT COUNT(*) c FROM de_thi_cau_hoi WHERE cauhoi_id=?");
    $st->execute([(int) $id]);
    if ($st->fetch()["c"] > 0)
      return false;


    $st = $this->con->prepare("SELECT COUNT(*) c FROM bai_lam_ct WHERE cauhoi_id=?");
    $st->execute([(int) $id]);
    if ($st->fetch()["c"] > 0)
      return false;

    return true;
  }

  public function delete($id)
  {
    $id = (int) $id;

    $this->con->prepare("DELETE FROM de_thi_cau_hoi WHERE cauhoi_id=?")->execute([$id]);


    $this->con->prepare("DELETE FROM bai_lam_ct WHERE cauhoi_id=?")->execute([$id]);


    $st = $this->con->prepare("DELETE FROM cau_hoi WHERE id=?");
    return $st->execute([$id]);
  }

  public function checkExist($mon_id, $noi_dung)
  {
    $st = $this->con->prepare("SELECT id FROM cau_hoi WHERE mon_id=? AND noi_dung=? LIMIT 1");
    $st->execute([(int) $mon_id, trim($noi_dung)]);
    return (bool) $st->fetch();
  }
}
