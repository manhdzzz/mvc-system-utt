<?php
class LamBai_m extends Database
{

  // phòng thi học viên được thấy (theo lớp của học viên)
  public function listPhongThiByHocVien($hocvien_id)
  {
    $st = $this->con->prepare("
      SELECT hv.lop_id, l.ma_lop
      FROM hoc_vien hv JOIN lop_hoc l ON l.id=hv.lop_id
      WHERE hv.id=?
    ");
    $st->execute([(int) $hocvien_id]);
    $info = $st->fetch();
    if (!$info)
      return ["lop" => null, "rows" => []];

    $st2 = $this->con->prepare("
      SELECT p.id, p.ma_phong, p.ten_phong, p.bat_dau, p.trangthai,
             m.ten_mon, d.ten_de, d.thoi_gian as time_phut, p.de_id
      FROM phong_thi p
      JOIN mon_thi m ON m.id=p.mon_id
      JOIN de_thi d ON d.id=p.de_id
      WHERE p.lop_id=? AND p.trangthai=1
      ORDER BY p.id DESC
    ");
    $st2->execute([(int) $info["lop_id"]]);
    return ["lop" => $info, "rows" => $st2->fetchAll()];
  }

  public function phongInfo($phong_id)
  {
    $st = $this->con->prepare("
      SELECT p.*, d.ten_de, d.thoi_gian as time_phut, d.mon_id, m.ten_mon, l.ma_lop,
        (SELECT COUNT(*) FROM de_thi_cau_hoi x
          JOIN cau_hoi ch ON ch.id=x.cauhoi_id
          WHERE x.de_id=d.id AND ch.loai='D') AS cau_de,
        (SELECT COUNT(*) FROM de_thi_cau_hoi x
          JOIN cau_hoi ch ON ch.id=x.cauhoi_id
          WHERE x.de_id=d.id AND ch.loai='TB') AS cau_tb,
        (SELECT COUNT(*) FROM de_thi_cau_hoi x
          JOIN cau_hoi ch ON ch.id=x.cauhoi_id
          WHERE x.de_id=d.id AND ch.loai='K') AS cau_kho
      FROM phong_thi p
      JOIN de_thi d ON d.id=p.de_id
      JOIN mon_thi m ON m.id=d.mon_id
      JOIN lop_hoc l ON l.id=p.lop_id
      WHERE p.id=?
    ");
    $st->execute([(int) $phong_id]);
    return $st->fetch();
  }

  public function getHV($hocvien_id)
  {
    $st = $this->con->prepare("SELECT * FROM hoc_vien WHERE id=?");
    $st->execute([(int) $hocvien_id]);
    return $st->fetch();
  }

  public function hvInPhong($phong_id, $hocvien_id)
  {
    $st = $this->con->prepare("
      SELECT * FROM phong_thi_hoc_vien
      WHERE phong_id=? AND hocvien_id=?
    ");
    $st->execute([(int) $phong_id, (int) $hocvien_id]);
    return $st->fetch();
  }

  public function setVaoPhongTime($phong_id, $hocvien_id)
  {
    $st = $this->con->prepare("
      UPDATE phong_thi_hoc_vien
      SET thoi_gian_vao=IFNULL(thoi_gian_vao, NOW())
      WHERE phong_id=? AND hocvien_id=?
    ");
    $st->execute([(int) $phong_id, (int) $hocvien_id]);
  }

  // lấy câu hỏi theo cấu hình đề (D/TB/K). Nếu 0 thì lấy tất cả theo môn.
  public function pickQuestions($de)
  {
    $mon_id = (int) $de["mon_id"];
    $cD = (int) $de["cau_de"];
    $cTB = (int) $de["cau_tb"];
    $cK = (int) $de["cau_kho"];

    $out = [];

    $pick = function ($loai, $n) use ($mon_id, &$out) {
      if ($n <= 0)
        return;
      $st = $this->con->prepare("
        SELECT * FROM cau_hoi
        WHERE mon_id=? AND kich_hoat=1 AND loai=?
        ORDER BY RAND()
        LIMIT $n
      ");
      $st->execute([$mon_id, $loai]);
      $out = array_merge($out, $st->fetchAll());
    };

    if ($cD + $cTB + $cK > 0) {
      $pick("D", $cD);
      $pick("TB", $cTB);
      $pick("K", $cK);
    } else {
      $st = $this->con->prepare("SELECT * FROM cau_hoi WHERE mon_id=? AND kich_hoat=1 ORDER BY RAND() LIMIT 50");
      $st->execute([$mon_id]);
      $out = $st->fetchAll();
    }

    return $out;
  }

  public function createBaiLam($phong_id, $hocvien_id, $de_id, $total)
  {
    $st = $this->con->prepare("
      INSERT INTO bai_lam(phong_id,hocvien_id,de_id,start_at,total_cnt,status)
      VALUES(?,?,?,NOW(),?,'Doing')
    ");
    $st->execute([(int) $phong_id, (int) $hocvien_id, (int) $de_id, (int) $total]);
    return (int) $this->con->lastInsertId();
  }

  public function insertBaiLamCT($bailam_id, $cauhoi_id)
  {
    $st = $this->con->prepare("
      INSERT INTO bai_lam_ct(bailam_id,cauhoi_id) VALUES(?,?)
    ");
    $st->execute([(int) $bailam_id, (int) $cauhoi_id]);
  }

  public function getBaiLamDoing($phong_id, $hocvien_id)
  {
    $st = $this->con->prepare("
      SELECT * FROM bai_lam
      WHERE phong_id=? AND hocvien_id=? AND status='Doing'
      ORDER BY id DESC LIMIT 1
    ");
    $st->execute([(int) $phong_id, (int) $hocvien_id]);
    return $st->fetch();
  }

  public function getBaiLamDone($phong_id, $hocvien_id)
  {
    $st = $this->con->prepare("
      SELECT * FROM bai_lam
      WHERE phong_id=? AND hocvien_id=? AND status='Done'
      ORDER BY id DESC LIMIT 1
    ");
    $st->execute([(int) $phong_id, (int) $hocvien_id]);
    return $st->fetch();
  }

  public function getQuestionsOfBaiLam($bailam_id)
  {
    $st = $this->con->prepare("
      SELECT ct.*, ch.noi_dung, ch.dap_an_a as a, ch.dap_an_b as b, ch.dap_an_c as c, ch.dap_an_d as d, ch.dap_an_dung as dap_an, ch.diem, ch.giai_thich
      FROM bai_lam_ct ct
      JOIN cau_hoi ch ON ch.id=ct.cauhoi_id
      WHERE ct.bailam_id=?
      ORDER BY ct.id
    ");
    $st->execute([(int) $bailam_id]);
    return $st->fetchAll();
  }

  public function submit($bailam_id, $answers)
  { // answers[cauhoi_id]=A/B/C/D
    $qs = $this->getQuestionsOfBaiLam($bailam_id);

    $correct = 0;
    $score = 0;
    $total = count($qs);

    $upd = $this->con->prepare("
      UPDATE bai_lam_ct SET chon=?, dung=?, diem=? WHERE bailam_id=? AND cauhoi_id=?
    ");

    foreach ($qs as $q) {
      $cid = (int) $q["cauhoi_id"];
      $chon = strtoupper(trim($answers[$cid] ?? ""));
      if (!in_array($chon, ["A", "B", "C", "D"]))
        $chon = null;

      $dung = ($chon && $chon === strtoupper($q["dap_an"])) ? 1 : 0;
      $diem = $dung ? (float) $q["diem"] : 0;

      if ($dung) {
        $correct++;
        $score += $diem;
      }

      $upd->execute([$chon, $dung, $diem, (int) $bailam_id, $cid]);
    }

    $st = $this->con->prepare("UPDATE bai_lam SET end_at=NOW(), score=?, correct_cnt=?, status='Done' WHERE id=?");
    $st->execute([$score, $correct, (int) $bailam_id]);

    return ["score" => $score, "correct" => $correct, "total" => $total];
  }

  public function updatePhongHVAfterSubmit($phong_id, $hocvien_id, $kq)
  {
    $st = $this->con->prepare("
      UPDATE phong_thi_hoc_vien
      SET diem=?, cau_dung=?, trang_thai='Đã nộp'
      WHERE phong_id=? AND hocvien_id=?
    ");
    $st->execute([(float) $kq["score"], (int) $kq["correct"], (int) $phong_id, (int) $hocvien_id]);
  }

  public function listHistory($hocvien_id)
  {
    $st = $this->con->prepare("
      SELECT bl.*, d.ten_de, m.ten_mon
      FROM bai_lam bl
      JOIN de_thi d ON d.id=bl.de_id
      JOIN mon_thi m ON m.id=d.mon_id
      WHERE bl.hocvien_id=? AND bl.status='Done'
      ORDER BY bl.id DESC
    ");
    $st->execute([(int) $hocvien_id]);
    return $st->fetchAll();
  }

  public function getBaiLam($id, $hocvien_id)
  {
    $st = $this->con->prepare("
      SELECT bl.*, d.ten_de, d.thoi_gian as time_phut, m.ten_mon
      FROM bai_lam bl
      JOIN de_thi d ON d.id=bl.de_id
      JOIN mon_thi m ON m.id=d.mon_id
      WHERE bl.id=? AND bl.hocvien_id=?
    ");
    $st->execute([(int) $id, (int) $hocvien_id]);
    return $st->fetch();
  }

  // Tính thời gian còn lại (giây) dựa trên MySQL time để tránh lệch timezone
  public function getRemainingTime($bailam_id)
  {
    $st = $this->con->prepare("
      SELECT 
        d.thoi_gian * 60 as duration_seconds,
        TIMESTAMPDIFF(SECOND, bl.start_at, NOW()) as elapsed_seconds
      FROM bai_lam bl
      JOIN de_thi d ON d.id=bl.de_id
      WHERE bl.id=?
    ");
    $st->execute([(int) $bailam_id]);
    $row = $st->fetch();
    if (!$row)
      return 0;

    $duration = (int) $row["duration_seconds"];
    $elapsed = (int) $row["elapsed_seconds"];
    $remaining = $duration - $elapsed;

    return max(0, $remaining);
  }

  // Lưu đáp án realtime (AJAX)
  public function saveAnswer($bailam_id, $cauhoi_id, $answer)
  {
    $answer = strtoupper(trim($answer));
    if (!in_array($answer, ["A", "B", "C", "D"]))
      $answer = null;

    $st = $this->con->prepare("
      UPDATE bai_lam_ct SET chon=? WHERE bailam_id=? AND cauhoi_id=?
    ");
    return $st->execute([$answer, (int) $bailam_id, (int) $cauhoi_id]);
  }

  // Lấy trạng thái đã trả lời của từng câu
  public function getAnsweredStatus($bailam_id)
  {
    $st = $this->con->prepare("
      SELECT cauhoi_id, chon FROM bai_lam_ct WHERE bailam_id=?
    ");
    $st->execute([(int) $bailam_id]);
    $result = [];
    foreach ($st->fetchAll() as $r) {
      $result[$r["cauhoi_id"]] = $r["chon"];
    }
    return $result;
  }

  // Lấy danh sách phòng thi kèm trạng thái cho học viên
  public function getPhongStatus($phong_id)
  {
    $st = $this->con->prepare("
      SELECT phv.*, hv.ma_hv, hv.hoten, l.ma_lop,
             bl.id as bailam_id, bl.status as bl_status, bl.start_at,
             (SELECT COUNT(*) FROM bai_lam_ct WHERE bailam_id=bl.id AND chon IS NOT NULL) as so_da_lam
      FROM phong_thi_hoc_vien phv
      JOIN hoc_vien hv ON hv.id=phv.hocvien_id
      JOIN lop_hoc l ON l.id=hv.lop_id
      LEFT JOIN bai_lam bl ON bl.phong_id=phv.phong_id AND bl.hocvien_id=phv.hocvien_id AND bl.status='Doing'
      WHERE phv.phong_id=?
      ORDER BY hv.hoten
    ");
    $st->execute([(int) $phong_id]);
    return $st->fetchAll();
  }
}
