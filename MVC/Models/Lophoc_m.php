<?php
class Lophoc_m extends Database {

  // Danh sách lớp + số học viên (để hiển thị cột "Số học viên")
  public function getAll(){
    $sql = "
      SELECT l.*,
             (SELECT COUNT(*) FROM users u WHERE u.lop_id = l.id) AS so_hoc_vien
      FROM lop_hoc l
      ORDER BY l.id DESC
    ";
    return $this->con->query($sql)->fetchAll();
  }

  // Lấy 1 lớp theo id
  public function find($id){
    $st = $this->con->prepare("SELECT * FROM lop_hoc WHERE id=?");
    $st->execute([(int)$id]);
    return $st->fetch();
  }

  // Kiểm tra mã lớp đã tồn tại chưa (dùng cho thêm / sửa)
  // ignoreId: dùng khi sửa để bỏ qua chính nó
  public function existsMaLop($ma_lop, $ignoreId = 0){
    $st = $this->con->prepare("SELECT id FROM lop_hoc WHERE ma_lop=? AND id<>?");
    $st->execute([trim($ma_lop), (int)$ignoreId]);
    return (bool)$st->fetch();
  }

  // Thêm lớp học
  public function insert($ma_lop, $ten_lop, $trangthai, $nguoi_tao){
    $sql = "INSERT INTO lop_hoc(ma_lop, ten_lop, trangthai, nguoi_tao)
            VALUES (?,?,?,?)";
    $st = $this->con->prepare($sql);
    return $st->execute([
      trim($ma_lop),
      trim($ten_lop),
      (int)$trangthai,
      trim($nguoi_tao)
    ]);
  }

  // Cập nhật lớp học
  public function updateBasic($id, $ma_lop, $ten_lop, $trangthai){
    $sql = "UPDATE lop_hoc
            SET ma_lop=?, ten_lop=?, trangthai=?
            WHERE id=?";
    $st = $this->con->prepare($sql);
    return $st->execute([
      trim($ma_lop),
      trim($ten_lop),
      (int)$trangthai,
      (int)$id
    ]);
  }

  // Kiểm tra lớp có học viên không (đang dựa vào bảng users: users.lop_id)
  // Nếu bạn dùng bảng hoc_vien riêng thì đổi bảng ở đây
  public function canDelete($id){
    $st = $this->con->prepare("SELECT COUNT(*) c FROM users WHERE lop_id=?");
    $st->execute([(int)$id]);
    return ((int)$st->fetch()["c"] === 0);
  }

  // Xóa lớp
  public function delete($id){
    $st = $this->con->prepare("DELETE FROM lop_hoc WHERE id=?");
    return $st->execute([(int)$id]);
  }

  // (Tuỳ chọn) Tìm kiếm theo mã/tên (nếu cần sau)
  public function search($q){
    $q = "%".trim($q)."%";
    $st = $this->con->prepare("
      SELECT l.*,
             (SELECT COUNT(*) FROM users u WHERE u.lop_id = l.id) AS so_hoc_vien
      FROM lop_hoc l
      WHERE l.ma_lop LIKE ? OR l.ten_lop LIKE ?
      ORDER BY l.id DESC
    ");
    $st->execute([$q,$q]);
    return $st->fetchAll();
  }
}
