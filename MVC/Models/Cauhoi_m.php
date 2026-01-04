<?php
class Cauhoi_m extends Database {

public function list($mon_id = 0, $q = ""){
  $q = trim($q);
  $qLike = "%".$q."%";

  if($mon_id > 0){
    $st = $this->con->prepare("
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
      ORDER BY ch.id DESC
    ");
    $st->execute([(int)$mon_id, $qLike, $qLike, $qLike, $qLike, $qLike]);
    return $st->fetchAll();
  }

  $st = $this->con->prepare("
    SELECT ch.*, m.ten_mon
    FROM cau_hoi ch
    JOIN mon_thi m ON m.id = ch.mon_id
    WHERE
      ch.noi_dung LIKE ?
      OR ch.dap_an_a LIKE ?
      OR ch.dap_an_b LIKE ?
      OR ch.dap_an_c LIKE ?
      OR ch.dap_an_d LIKE ?
    ORDER BY ch.id DESC
  ");
  $st->execute([$qLike, $qLike, $qLike, $qLike, $qLike]);
  return $st->fetchAll();
}


  public function find($id){
    $st = $this->con->prepare("SELECT * FROM cau_hoi WHERE id=?");
    $st->execute([(int)$id]);
    return $st->fetch();
  }

  public function insert($mon_id,$nd,$a,$b,$c,$d,$dad,$diem,$gt,$loai,$kich){
    $st = $this->con->prepare("
      INSERT INTO cau_hoi(mon_id,noi_dung,dap_an_a,dap_an_b,dap_an_c,dap_an_d,dap_an_dung,diem,giai_thich,loai,kich_hoat)
      VALUES (?,?,?,?,?,?,?,?,?,?,?)
    ");
    return $st->execute([(int)$mon_id,$nd,$a,$b,$c,$d,$dad,(int)$diem,$gt,$loai,(int)$kich]);
  }

  public function updateBasic($id,$mon_id,$nd,$a,$b,$c,$d,$dad,$diem,$gt,$loai,$kich){
    $st = $this->con->prepare("
      UPDATE cau_hoi
      SET mon_id=?, noi_dung=?, dap_an_a=?, dap_an_b=?, dap_an_c=?, dap_an_d=?,
          dap_an_dung=?, diem=?, giai_thich=?, loai=?, kich_hoat=?
      WHERE id=?
    ");
    return $st->execute([(int)$mon_id,$nd,$a,$b,$c,$d,$dad,(int)$diem,$gt,$loai,(int)$kich,(int)$id]);
  }

  public function delete($id){
    $st = $this->con->prepare("DELETE FROM cau_hoi WHERE id=?");
    return $st->execute([(int)$id]);
  }
}
