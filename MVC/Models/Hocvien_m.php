<?php
class Hocvien_m extends Database {

  // dùng cho login học viên
  public function findByMaHV($ma_hv){
    $st = $this->con->prepare("SELECT * FROM hoc_vien WHERE ma_hv=? LIMIT 1");
    $st->execute([trim($ma_hv)]);
    return $st->fetch();
  }

  public function getLops(){
    return $this->con->query("SELECT id, ma_lop, ten_lop FROM lop_hoc ORDER BY ma_lop")->fetchAll();
  }

  public function search($lop_id, $q){
    $sql="SELECT hv.*, l.ma_lop, u.hoten AS nguoi_tao
          FROM hoc_vien hv
          JOIN lop_hoc l ON l.id=hv.lop_id
          LEFT JOIN users u ON u.id=hv.created_by
          WHERE 1=1";
    $pa=[];

    if($lop_id>0){ $sql.=" AND hv.lop_id=?"; $pa[]=$lop_id; }
    if($q!==""){
      $sql.=" AND (hv.hoten LIKE ? OR hv.ma_hv LIKE ?)";
      $pa[]="%$q%"; $pa[]="%$q%";
    }
    $sql.=" ORDER BY hv.id DESC";

    $st=$this->con->prepare($sql);
    $st->execute($pa);
    return $st->fetchAll();
  }

  public function existsMaHV($ma_hv){
    $st=$this->con->prepare("SELECT id FROM hoc_vien WHERE ma_hv=? LIMIT 1");
    $st->execute([trim($ma_hv)]);
    return (bool)$st->fetch();
  }

  public function insert($hoten,$ma_hv,$matkhau,$lop_id,$trangthai,$created_by){
    $st=$this->con->prepare("INSERT INTO hoc_vien(hoten,ma_hv,matkhau,trangthai,lop_id,created_by,created_at)
                             VALUES(?,?,?,?,?,?,NOW())");
    $st->execute([$hoten,$ma_hv,$matkhau,$trangthai,$lop_id,$created_by]);
  }

  public function update($id,$hoten,$matkhau,$lop_id,$trangthai){
    $st=$this->con->prepare("UPDATE hoc_vien SET hoten=?, matkhau=?, lop_id=?, trangthai=? WHERE id=?");
    $st->execute([$hoten,$matkhau,$lop_id,$trangthai,$id]);
  }

  public function delete($id){
    $st=$this->con->prepare("DELETE FROM hoc_vien WHERE id=?");
    $st->execute([$id]);
  }
}
