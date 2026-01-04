<?php
class CauhoiController extends Controller {

  private function needLogin(){
    if(empty($_SESSION["user"])){
      header("Location: ".BASE_URL."/index.php?url=AuthController/login");
      exit;
    }
  }
  private function flash($k,$v){ $_SESSION["_flash"][$k]=$v; }
  private function getFlash($k){
    $v=$_SESSION["_flash"][$k] ?? "";
    unset($_SESSION["_flash"][$k]);
    return $v;
  }

  public function index(){
    $this->needLogin();
    $mon_id = (int)($_GET["mon_id"] ?? 0);
    $q      = trim($_GET["q"] ?? "");

    $mMon = $this->model("Monthi_m");
    $mons = $mMon->getAll();

    $m = $this->model("Cauhoi_m");
    $rows = $m->list($mon_id, $q);

    $this->view("layout_admin",[
      "page"=>"Pages/ch_list",
      "rows"=>$rows,
      "mons"=>$mons,
      "mon_id"=>$mon_id,
      "q"=>$q,
      "msg"=>$this->getFlash("msg"),
      "err"=>$this->getFlash("err"),
    ]);
  }

  public function create(){
    $this->needLogin();
    $mons = $this->model("Monthi_m")->getAll();
    $this->view("layout_admin",[
      "page"=>"Pages/ch_create",
      "mons"=>$mons,
      "err"=>$this->getFlash("err"),
    ]);
  }

  public function store(){
    $this->needLogin();
    $mon_id=(int)($_POST["mon_id"] ?? 0);
    $nd=trim($_POST["noi_dung"] ?? "");
    $a=trim($_POST["a"] ?? "");
    $b=trim($_POST["b"] ?? "");
    $c=trim($_POST["c"] ?? "");
    $d=trim($_POST["d"] ?? "");
    $dad=strtoupper(trim($_POST["dap_an_dung"] ?? "A"));
    $diem=(int)($_POST["diem"] ?? 1);
    $gt=trim($_POST["giai_thich"] ?? "");
    $loai=trim($_POST["loai"] ?? "D");
    $kich=(int)($_POST["kich_hoat"] ?? 1);

    if($mon_id<=0 || $nd==="" || $a==="" || $b==="" || $c==="" || $d===""){
      $this->flash("err","Vui lòng nhập đủ dữ liệu câu hỏi!");
      header("Location: ".BASE_URL."/index.php?url=CauhoiController/create"); exit;
    }
    if(!in_array($dad,["A","B","C","D"])) $dad="A";

    $this->model("Cauhoi_m")->insert($mon_id,$nd,$a,$b,$c,$d,$dad,$diem,$gt,$loai,$kich);
    $this->flash("msg","Đã thêm câu hỏi!");
    header("Location: ".BASE_URL."/index.php?url=CauhoiController/index&mon_id=".$mon_id); exit;
  }

  public function edit($id){
    $this->needLogin();
    $mons = $this->model("Monthi_m")->getAll();
    $ch = $this->model("Cauhoi_m")->find((int)$id);
    if(!$ch) die("Không tìm thấy câu hỏi!");

    $this->view("layout_admin",[
      "page"=>"Pages/ch_edit",
      "mons"=>$mons,
      "ch"=>$ch,
      "err"=>$this->getFlash("err"),
    ]);
  }

  public function update(){
    $this->needLogin();
    $id=(int)($_POST["id"] ?? 0);

    $mon_id=(int)($_POST["mon_id"] ?? 0);
    $nd=trim($_POST["noi_dung"] ?? "");
    $a=trim($_POST["a"] ?? "");
    $b=trim($_POST["b"] ?? "");
    $c=trim($_POST["c"] ?? "");
    $d=trim($_POST["d"] ?? "");
    $dad=strtoupper(trim($_POST["dap_an_dung"] ?? "A"));
    $diem=(int)($_POST["diem"] ?? 1);
    $gt=trim($_POST["giai_thich"] ?? "");
    $loai=trim($_POST["loai"] ?? "D");
    $kich=(int)($_POST["kich_hoat"] ?? 1);

    if($mon_id<=0 || $nd==="" || $a==="" || $b==="" || $c==="" || $d===""){
      $this->flash("err","Không được để trống!");
      header("Location: ".BASE_URL."/index.php?url=CauhoiController/edit/".$id); exit;
    }
    if(!in_array($dad,["A","B","C","D"])) $dad="A";

    $this->model("Cauhoi_m")->updateBasic($id,$mon_id,$nd,$a,$b,$c,$d,$dad,$diem,$gt,$loai,$kich);
    $this->flash("msg","Đã cập nhật câu hỏi!");
    header("Location: ".BASE_URL."/index.php?url=CauhoiController/index&mon_id=".$mon_id); exit;
  }

  public function delete($id){
    $this->needLogin();
    $ch = $this->model("Cauhoi_m")->find((int)$id);
    if($ch){
      $this->model("Cauhoi_m")->delete((int)$id);
      $this->flash("msg","Đã xóa câu hỏi!");
      header("Location: ".BASE_URL."/index.php?url=CauhoiController/index&mon_id=".$ch["mon_id"]); exit;
    }
    $this->flash("err","Không tìm thấy câu hỏi!");
    header("Location: ".BASE_URL."/index.php?url=CauhoiController/index"); exit;
  }

  // ===== TẢI BẢNG MẪU CSV =====
  public function template(){
    $this->needLogin();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=cauhoi_mau.csv');
    echo "noi_dung,A,B,C,D,dap_an,diem,giai_thich,loai,kich_hoat\n";
    echo "2+4=?,6,7,8,9,A,1,,D,1\n";
    echo "1+1=?,2,1,3,4,A,2,,TB,1\n";
    exit;
  }

  // ===== IMPORT CSV =====
  public function import(){
    $this->needLogin();
    $mon_id = (int)($_POST["mon_id"] ?? 0);

    if($mon_id<=0){
      $this->flash("err","Vui lòng chọn Môn thi!");
      header("Location: ".BASE_URL."/index.php?url=CauhoiController/index"); exit;
    }
    if(empty($_FILES["file"]["tmp_name"])){
      $this->flash("err","Vui lòng chọn file CSV!");
      header("Location: ".BASE_URL."/index.php?url=CauhoiController/index&mon_id=".$mon_id); exit;
    }

    $name = $_FILES["file"]["name"] ?? "";
    if(!preg_match('/\.csv$/i',$name)){
      $this->flash("err","Import hiện hỗ trợ file .CSV (Excel -> Save As CSV).");
      header("Location: ".BASE_URL."/index.php?url=CauhoiController/index&mon_id=".$mon_id); exit;
    }

    $m = $this->model("Cauhoi_m");
    $fh = fopen($_FILES["file"]["tmp_name"], "r");
    $first=true; $ok=0; $skip=0;

    while(($row=fgetcsv($fh))!==false){
      if($first){ $first=false; continue; }

      $nd=trim($row[0] ?? "");
      $a=trim($row[1] ?? "");
      $b=trim($row[2] ?? "");
      $c=trim($row[3] ?? "");
      $d=trim($row[4] ?? "");
      $dad=strtoupper(trim($row[5] ?? "A"));
      $diem=(int)($row[6] ?? 1);
      $gt=trim($row[7] ?? "");
      $loai=trim($row[8] ?? "D");
      $kich=(int)($row[9] ?? 1);

      if($nd===""||$a===""||$b===""||$c===""||$d===""){ $skip++; continue; }
      if(!in_array($dad,["A","B","C","D"])) $dad="A";

      $m->insert($mon_id,$nd,$a,$b,$c,$d,$dad,$diem,$gt,$loai,$kich);
      $ok++;
    }
    fclose($fh);

    $this->flash("msg","Import xong: thêm $ok, bỏ qua $skip dòng.");
    header("Location: ".BASE_URL."/index.php?url=CauhoiController/index&mon_id=".$mon_id); exit;
  }
}
