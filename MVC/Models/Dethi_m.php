<?php
class Dethi_m extends Database {

  public function list($mon_id = 0, $q = ""){
    $qLike = "%".trim($q)."%";

    if($mon_id > 0){
      $st = $this->con->prepare("
        SELECT dt.*,
          (SELECT COUNT(*) FROM de_thi_cau_hoi x
            JOIN cau_hoi ch ON ch.id=x.cauhoi_id
            WHERE x.de_id=dt.id AND ch.loai='D') AS cau_de,
          (SELECT COUNT(*) FROM de_thi_cau_hoi x
            JOIN cau_hoi ch ON ch.id=x.cauhoi_id
            WHERE x.de_id=dt.id AND ch.loai='TB') AS cau_tb,
          (SELECT COUNT(*) FROM de_thi_cau_hoi x
            JOIN cau_hoi ch ON ch.id=x.cauhoi_id
            WHERE x.de_id=dt.id AND ch.loai='K') AS cau_kho
        FROM de_thi dt
        WHERE dt.mon_id=? AND (dt.ma_de LIKE ? OR dt.ten_de LIKE ?)
        ORDER BY dt.id DESC
      ");
      $st->execute([(int)$mon_id,$qLike,$qLike]);
      return $st->fetchAll();
    }

    $st = $this->con->prepare("
      SELECT dt.*,
        (SELECT COUNT(*) FROM de_thi_cau_hoi x
          JOIN cau_hoi ch ON ch.id=x.cauhoi_id
          WHERE x.de_id=dt.id AND ch.loai='D') AS cau_de,
        (SELECT COUNT(*) FROM de_thi_cau_hoi x
          JOIN cau_hoi ch ON ch.id=x.cauhoi_id
          WHERE x.de_id=dt.id AND ch.loai='TB') AS cau_tb,
        (SELECT COUNT(*) FROM de_thi_cau_hoi x
          JOIN cau_hoi ch ON ch.id=x.cauhoi_id
          WHERE x.de_id=dt.id AND ch.loai='K') AS cau_kho
      FROM de_thi dt
      WHERE (dt.ma_de LIKE ? OR dt.ten_de LIKE ?)
      ORDER BY dt.id DESC
    ");
    $st->execute([$qLike,$qLike]);
    return $st->fetchAll();
  }

  public function existsMa($ma){
    $st=$this->con->prepare("SELECT id FROM de_thi WHERE ma_de=?");
    $st->execute([trim($ma)]);
    return (bool)$st->fetch();
  }

  public function insertDe($ma,$ten,$time,$mon_id){
    $st=$this->con->prepare("
      INSERT INTO de_thi(ma_de,ten_de,thoi_gian,mon_id) VALUES (?,?,?,?)
    ");
    $st->execute([trim($ma),trim($ten),(int)$time,(int)$mon_id]);
    return (int)$this->con->lastInsertId();
  }

  // lấy ngẫu nhiên câu hỏi theo môn + loại + số lượng
  public function pickQuestions($mon_id,$loai,$limit){
    $limit = (int)$limit;
    if($limit <= 0) return [];

    // NOTE: LIMIT không bind được ở một số driver -> nối số an toàn sau khi cast int
    $sql = "
      SELECT id
      FROM cau_hoi
      WHERE mon_id=? AND loai=? AND kich_hoat=1
      ORDER BY RAND()
      LIMIT $limit
    ";
    $st=$this->con->prepare($sql);
    $st->execute([(int)$mon_id, $loai]);
    return $st->fetchAll();
  }

  public function addQuestionsToDe($de_id, $ids){
    $st=$this->con->prepare("INSERT IGNORE INTO de_thi_cau_hoi(de_id,cauhoi_id) VALUES (?,?)");
    $ok=0;
    foreach($ids as $r){
      $cid=(int)$r["id"];
      if($cid>0){
        $st->execute([(int)$de_id,$cid]);
        $ok++;
      }
    }
    return $ok;
  }

  public function delete($id){
    $st=$this->con->prepare("DELETE FROM de_thi WHERE id=?");
    return $st->execute([(int)$id]);
  }
}
