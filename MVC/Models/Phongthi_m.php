<?php
class Phongthi_m extends Database
{

  
  public function list($lop_id = 0, $q = "", $limit = 50, $offset = 0)
  {
    $qLike = "%" . trim($q) . "%";

    if ($lop_id > 0) {
      $st = $this->con->prepare(
        "
        SELECT p.*, m.ten_mon, d.ten_de, l.ma_lop
        FROM phong_thi p
        JOIN mon_thi m ON m.id=p.mon_id
        JOIN de_thi d ON d.id=p.de_id
        JOIN lop_hoc l ON l.id=p.lop_id
        WHERE p.lop_id=? AND (p.ma_phong LIKE ? OR p.ten_phong LIKE ?)
        ORDER BY p.id DESC LIMIT $limit OFFSET " . (int) $offset
      );
      $st->execute([(int) $lop_id, $qLike, $qLike]);
      return $st->fetchAll();
    }

    $st = $this->con->prepare(
      "
      SELECT p.*, m.ten_mon, d.ten_de, l.ma_lop
      FROM phong_thi p
      JOIN mon_thi m ON m.id=p.mon_id
      JOIN de_thi d ON d.id=p.de_id
      JOIN lop_hoc l ON l.id=p.lop_id
      WHERE (p.ma_phong LIKE ? OR p.ten_phong LIKE ?)
      ORDER BY p.id DESC LIMIT $limit OFFSET " . (int) $offset
    );
    $st->execute([$qLike, $qLike]);
    return $st->fetchAll();
  }

  public function count($lop_id = 0, $q = "")
  {
    $qLike = "%" . trim($q) . "%";
    if ($lop_id > 0) {
      $st = $this->con->prepare("SELECT COUNT(*) c FROM phong_thi WHERE lop_id=? AND (ma_phong LIKE ? OR ten_phong LIKE ?)");
      $st->execute([(int) $lop_id, $qLike, $qLike]);
    } else {
      $st = $this->con->prepare("SELECT COUNT(*) c FROM phong_thi WHERE (ma_phong LIKE ? OR ten_phong LIKE ?)");
      $st->execute([$qLike, $qLike]);
    }
    return (int) ($st->fetch()["c"] ?? 0);
  }

  public function find($id)
  {
    $st = $this->con->prepare("
      SELECT p.*, m.ten_mon, d.ten_de, l.ma_lop
      FROM phong_thi p
      JOIN mon_thi m ON m.id=p.mon_id
      JOIN de_thi d ON d.id=p.de_id
      JOIN lop_hoc l ON l.id=p.lop_id
      WHERE p.id=?
    ");
    $st->execute([(int) $id]);
    return $st->fetch();
  }

  public function existsMa($ma, $ignore_id = 0)
  {
    $ma = trim($ma);
    if ($ignore_id > 0) {
      $st = $this->con->prepare("SELECT id FROM phong_thi WHERE ma_phong=? AND id<>?");
      $st->execute([$ma, (int) $ignore_id]);
    } else {
      $st = $this->con->prepare("SELECT id FROM phong_thi WHERE ma_phong=?");
      $st->execute([$ma]);
    }
    return (bool) $st->fetch();
  }

  public function insert($ma, $ten, $mon_id, $de_id, $lop_id, $bat_dau, $nguoi_tao, $trangthai = 1)
  {
    $st = $this->con->prepare("
      INSERT INTO phong_thi(ma_phong,ten_phong,mon_id,de_id,lop_id,bat_dau,nguoi_tao,trangthai)
      VALUES (?,?,?,?,?,?,?,?)
    ");
    $st->execute([trim($ma), trim($ten), (int) $mon_id, (int) $de_id, (int) $lop_id, $bat_dau, trim($nguoi_tao), (int) $trangthai]);
    return (int) $this->con->lastInsertId();
  }

  public function update($id, $ma, $ten, $mon_id, $de_id, $lop_id, $bat_dau, $trangthai)
  {
    $st = $this->con->prepare("
      UPDATE phong_thi
      SET ma_phong=?, ten_phong=?, mon_id=?, de_id=?, lop_id=?, bat_dau=?, trangthai=?
      WHERE id=?
    ");
    return $st->execute([trim($ma), trim($ten), (int) $mon_id, (int) $de_id, (int) $lop_id, $bat_dau, (int) $trangthai, (int) $id]);
  }

  public function canDelete($id)
  {
    
    $st = $this->con->prepare("SELECT COUNT(*) c FROM phong_thi_hoc_vien WHERE phong_id=?");
    $st->execute([(int) $id]);
    if ($st->fetch()["c"] > 0)
      return false;

    
    $st = $this->con->prepare("SELECT COUNT(*) c FROM bai_lam WHERE phong_id=?");
    $st->execute([(int) $id]);
    if ($st->fetch()["c"] > 0)
      return false;

    return true;
  }

  public function delete($id)
  {
    $id = (int) $id;
    
    $this->con->prepare("DELETE FROM phong_thi_hoc_vien WHERE phong_id=?")->execute([$id]);

    
    $st = $this->con->prepare("SELECT id FROM bai_lam WHERE phong_id=?");
    $st->execute([$id]);
    $rows = $st->fetchAll();
    foreach ($rows as $r) {
      $this->con->prepare("DELETE FROM bai_lam_ct WHERE bailam_id=?")->execute([(int) $r["id"]]);
    }
    $this->con->prepare("DELETE FROM bai_lam WHERE phong_id=?")->execute([$id]);

    
    $st = $this->con->prepare("DELETE FROM phong_thi WHERE id=?");
    return $st->execute([$id]);
  }

  
  public function addAllHocvienFromLop($phong_id, $lop_id)
  {
    $st = $this->con->prepare("SELECT id FROM hoc_vien WHERE lop_id=?");
    $st->execute([(int) $lop_id]);
    $hvs = $st->fetchAll();

    $ins = $this->con->prepare("INSERT IGNORE INTO phong_thi_hoc_vien(phong_id,hocvien_id) VALUES (?,?)");
    $ok = 0;
    foreach ($hvs as $r) {
      $ins->execute([(int) $phong_id, (int) $r["id"]]);
      $ok++;
    }
    return $ok;
  }

  public function listHocvienInPhong($phong_id, $q = "")
  {
    $qLike = "%" . trim($q) . "%";
    $st = $this->con->prepare("
      SELECT phv.*, hv.ma_hv, hv.hoten, l.ma_lop
      FROM phong_thi_hoc_vien phv
      JOIN hoc_vien hv ON hv.id=phv.hocvien_id
      JOIN lop_hoc l ON l.id=hv.lop_id
      WHERE phv.phong_id=? AND (hv.hoten LIKE ? OR hv.ma_hv LIKE ?)
      ORDER BY hv.hoten
    ");
    $st->execute([(int) $phong_id, $qLike, $qLike]);
    return $st->fetchAll();
  }

  public function setKichHoat($phong_id, $hocvien_id, $val)
  {
    $st = $this->con->prepare("
      UPDATE phong_thi_hoc_vien SET kich_hoat=? WHERE phong_id=? AND hocvien_id=?
    ");
    return $st->execute([(int) $val, (int) $phong_id, (int) $hocvien_id]);
  }

  public function resetLamLai($phong_id, $hocvien_id)
  {
    $st = $this->con->prepare("
      UPDATE phong_thi_hoc_vien
      SET diem=0,cau_dung=0,trang_thai='Chưa thi',lam_lai=lam_lai+1,tru=0,con_lai=0,ghi_chu='',so_lan_vi_pham=0,kich_hoat=1
      WHERE phong_id=? AND hocvien_id=?
    ");
    return $st->execute([(int) $phong_id, (int) $hocvien_id]);
  }

  public function huyBai($phong_id, $hocvien_id)
  {
    $st = $this->con->prepare("
      UPDATE phong_thi_hoc_vien
      SET trang_thai='Hủy', kich_hoat=0, ghi_chu='Bị hủy bài'
      WHERE phong_id=? AND hocvien_id=?
    ");
    return $st->execute([(int) $phong_id, (int) $hocvien_id]);
  }

  public function updateGhiChu($phong_id, $hocvien_id, $note)
  {
    $st = $this->con->prepare("
      UPDATE phong_thi_hoc_vien SET ghi_chu=? WHERE phong_id=? AND hocvien_id=?
    ");
    return $st->execute([trim($note), (int) $phong_id, (int) $hocvien_id]);
  }

  
  public function tangViPham($phong_id, $hocvien_id)
  {
    
    $st = $this->con->prepare("
      SELECT so_lan_vi_pham, diem FROM phong_thi_hoc_vien 
      WHERE phong_id=? AND hocvien_id=?
    ");
    $st->execute([(int) $phong_id, (int) $hocvien_id]);
    $row = $st->fetch();
    if (!$row)
      return false;

    $so_vp = (int) ($row["so_lan_vi_pham"] ?? 0) + 1;
    $diem = (float) ($row["diem"] ?? 0);

    
    $tru = floor($so_vp / 3);
    $con_lai = max(0, $diem - $tru);

    
    if ($so_vp > 3) {
      $st2 = $this->con->prepare("
        UPDATE phong_thi_hoc_vien 
        SET so_lan_vi_pham=?, tru=?, con_lai=?, kich_hoat=0, trang_thai='Đã nộp', ghi_chu='Vi phạm quy chế thi - Bị cấm thi'
        WHERE phong_id=? AND hocvien_id=?
      ");
      $st2->execute([$so_vp, $tru, $con_lai, (int) $phong_id, (int) $hocvien_id]);
    } else {
      $st2 = $this->con->prepare("
        UPDATE phong_thi_hoc_vien 
        SET so_lan_vi_pham=?, tru=?, con_lai=?
        WHERE phong_id=? AND hocvien_id=?
      ");
      $st2->execute([$so_vp, $tru, $con_lai, (int) $phong_id, (int) $hocvien_id]);
    }

    return ["so_vp" => $so_vp, "tru" => $tru, "con_lai" => $con_lai, "cam_thi" => ($so_vp > 3)];
  }

  public function getViPhamStatus($phong_id, $hocvien_id)
  {
    $st = $this->con->prepare("
      SELECT kich_hoat, ghi_chu, so_lan_vi_pham FROM phong_thi_hoc_vien 
      WHERE phong_id=? AND hocvien_id=?
    ");
    $st->execute([(int) $phong_id, (int) $hocvien_id]);
    return $st->fetch();
  }
}

