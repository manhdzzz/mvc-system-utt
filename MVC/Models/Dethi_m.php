<?php
class Dethi_m extends Database
{

  public function list($mon_id = 0, $q = "")
  {
    $qLike = "%" . trim($q) . "%";

    if ($mon_id > 0) {
      $st = $this->con->prepare("
        SELECT dt.*,
          SUM(CASE WHEN ch.loai='D' THEN 1 ELSE 0 END) AS cau_de,
          SUM(CASE WHEN ch.loai='TB' THEN 1 ELSE 0 END) AS cau_tb,
          SUM(CASE WHEN ch.loai='K' THEN 1 ELSE 0 END) AS cau_kho
        FROM de_thi dt
        LEFT JOIN de_thi_cau_hoi x ON x.de_id = dt.id
        LEFT JOIN cau_hoi ch ON ch.id = x.cauhoi_id
        WHERE dt.mon_id=? AND (dt.ma_de LIKE ? OR dt.ten_de LIKE ?)
        GROUP BY dt.id
        ORDER BY dt.id DESC
      ");
      $st->execute([(int) $mon_id, $qLike, $qLike]);
      return $st->fetchAll();
    }

    $st = $this->con->prepare("
      SELECT dt.*,
        SUM(CASE WHEN ch.loai='D' THEN 1 ELSE 0 END) AS cau_de,
        SUM(CASE WHEN ch.loai='TB' THEN 1 ELSE 0 END) AS cau_tb,
        SUM(CASE WHEN ch.loai='K' THEN 1 ELSE 0 END) AS cau_kho
      FROM de_thi dt
      LEFT JOIN de_thi_cau_hoi x ON x.de_id = dt.id
      LEFT JOIN cau_hoi ch ON ch.id = x.cauhoi_id
      WHERE (dt.ma_de LIKE ? OR dt.ten_de LIKE ?)
      GROUP BY dt.id
      ORDER BY dt.id DESC
    ");
    $st->execute([$qLike, $qLike]);
    return $st->fetchAll();
  }

  public function find($id)
  {
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
      WHERE dt.id=?
    ");
    $st->execute([(int) $id]);
    return $st->fetch();
  }

  public function existsMa($ma)
  {
    $st = $this->con->prepare("SELECT id FROM de_thi WHERE ma_de=?");
    $st->execute([trim($ma)]);
    return (bool) $st->fetch();
  }

  public function insertDe($ma, $ten, $time, $mon_id)
  {
    $st = $this->con->prepare("
      INSERT INTO de_thi(ma_de,ten_de,thoi_gian,mon_id) VALUES (?,?,?,?)
    ");
    $st->execute([trim($ma), trim($ten), (int) $time, (int) $mon_id]);
    return (int) $this->con->lastInsertId();
  }

  public function update($id, $ma, $ten, $time, $mon_id)
  {
    $st = $this->con->prepare("
      UPDATE de_thi SET ma_de=?, ten_de=?, thoi_gian=?, mon_id=? WHERE id=?
    ");
    return $st->execute([trim($ma), trim($ten), (int) $time, (int) $mon_id, (int) $id]);
  }


  public function pickQuestions($mon_id, $loai, $limit)
  {
    $limit = (int) $limit;
    if ($limit <= 0)
      return [];


    $sql = "
      SELECT id
      FROM cau_hoi
      WHERE mon_id=? AND loai=? AND kich_hoat=1
      ORDER BY RAND()
      LIMIT $limit
    ";
    $st = $this->con->prepare($sql);
    $st->execute([(int) $mon_id, $loai]);
    return $st->fetchAll();
  }

  public function addQuestionsToDe($de_id, $ids)
  {
    $st = $this->con->prepare("INSERT IGNORE INTO de_thi_cau_hoi(de_id,cauhoi_id) VALUES (?,?)");
    $ok = 0;
    foreach ($ids as $r) {
      $cid = (int) $r["id"];
      if ($cid > 0) {
        $st->execute([(int) $de_id, $cid]);
        $ok++;
      }
    }
    return $ok;
  }

  public function canDelete($id)
  {
    $st = $this->con->prepare("SELECT COUNT(*) as c FROM phong_thi WHERE de_id=?");
    $st->execute([(int) $id]);
    $row = $st->fetch();
    return ((int) ($row["c"] ?? 0)) === 0;
  }

  public function delete($id)
  {
    $id = (int) $id;

    $this->con->prepare("DELETE FROM de_thi_cau_hoi WHERE de_id=?")->execute([$id]);


    $st = $this->con->prepare("SELECT id FROM phong_thi WHERE de_id=?");
    $st->execute([$id]);
    $phongs = $st->fetchAll();
    foreach ($phongs as $p) {
      $pid = (int) $p["id"];
      $this->con->prepare("DELETE FROM phong_thi_hoc_vien WHERE phong_id=?")->execute([$pid]);

      $st2 = $this->con->prepare("SELECT id FROM bai_lam WHERE phong_id=?");
      $st2->execute([$pid]);
      $bails = $st2->fetchAll();
      foreach ($bails as $bl) {
        $this->con->prepare("DELETE FROM bai_lam_ct WHERE bailam_id=?")->execute([(int) $bl["id"]]);
      }
      $this->con->prepare("DELETE FROM bai_lam WHERE phong_id=?")->execute([$pid]);
      $this->con->prepare("DELETE FROM phong_thi WHERE id=?")->execute([$pid]);
    }


    $st = $this->con->prepare("DELETE FROM de_thi WHERE id=?");
    return $st->execute([$id]);
  }
}
