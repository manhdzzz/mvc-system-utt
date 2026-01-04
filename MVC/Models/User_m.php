<?php
class User_m extends Database {

  public function findByUsername($u){
    $st=$this->con->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
    $st->execute([$u]);
    return $st->fetch();
  }

  public function getAll(){
    return $this->con->query("SELECT id,hoten,username,email,trangthai,role,created_at FROM users ORDER BY id")->fetchAll();
  }

  public function find($id){
    $st=$this->con->prepare("SELECT * FROM users WHERE id=?");
    $st->execute([(int)$id]);
    return $st->fetch();
  }

  public function existsUsername($username, $ignoreId=0){
    $st=$this->con->prepare("SELECT id FROM users WHERE username=? AND id<>?");
    $st->execute([$username,(int)$ignoreId]);
    return (bool)$st->fetch();
  }

  public function updateBasic($id,$hoten,$username,$email,$trangthai,$role){
    $st=$this->con->prepare("
      UPDATE users SET hoten=?, username=?, email=?, trangthai=?, role=? WHERE id=?
    ");
    return $st->execute([$hoten,$username,$email,(int)$trangthai,$role,(int)$id]);
  }

  public function delete($id){
    $st=$this->con->prepare("DELETE FROM users WHERE id=?");
    return $st->execute([(int)$id]);
  }
  public function insert($hoten,$username,$email,$password_hash,$trangthai,$role){
  $st=$this->con->prepare("
    INSERT INTO users(hoten,username,email,password_hash,trangthai,role)
    VALUES (?,?,?,?,?,?)
  ");
  return $st->execute([$hoten,$username,$email,$password_hash,(int)$trangthai,$role]);
}
public function search($q){
  $q = "%".trim($q)."%";
  $st = $this->con->prepare("
    SELECT id,hoten,username,email,trangthai,role,created_at
    FROM users
    WHERE hoten LIKE ? OR username LIKE ? OR email LIKE ?
    ORDER BY id DESC
  ");
  $st->execute([$q,$q,$q]);
  return $st->fetchAll();
}

}
