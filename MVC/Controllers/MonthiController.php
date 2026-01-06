<?php
class MonthiController extends Controller
{


    public function index()
    {
        $this->needAdmin();
        $q = trim($_GET["q"] ?? "");
        $page = (int) ($_GET["page"] ?? 1);
        if ($page < 1)
            $page = 1;
        $limit = 50;
        $offset = ($page - 1) * $limit;

        $m = $this->model("Monthi_m");
        $rows = $m->list($q, $limit, $offset);
        $total = $m->count($q);
        $totalPages = ceil($total / $limit);

        $this->view("layout_admin", [
            "page" => "Pages/mt_list",
            "rows" => $rows,
            "q" => $q,
            "currPage" => $page,
            "totalPages" => $totalPages,
            "total" => $total,
            "msg" => $this->getFlash("msg"),
            "err" => $this->getFlash("err"),
        ]);
    }


    public function store()
    {
        $this->needAdmin();

        $ma = trim($_POST["ma_mon"] ?? "");
        $ten = trim($_POST["ten_mon"] ?? "");

        if ($ma === "" || $ten === "") {
            $this->flash("err", "Vui lòng nhập đầy đủ mã và tên môn thi!");
            header("Location: " . BASE_URL . "/index.php?url=MonthiController/index");
            exit;
        }

        $m = $this->model("Monthi_m");
        if ($m->existsMa($ma)) {
            $this->flash("err", "Mã môn đã tồn tại!");
            header("Location: " . BASE_URL . "/index.php?url=MonthiController/index");
            exit;
        }

        $m->insert($ma, $ten);
        $this->flash("msg", "Đã thêm môn thi thành công!");
        header("Location: " . BASE_URL . "/index.php?url=MonthiController/index");
        exit;
    }


    public function edit($id)
    {
        $this->needAdmin();
        $mon = $this->model("Monthi_m")->find((int) $id);
        if (!$mon)
            die("Không tìm thấy môn thi!");

        $this->view("layout_admin", [
            "page" => "Pages/mt_edit",
            "mon" => $mon,
            "err" => $this->getFlash("err"),
        ]);
    }


    public function update()
    {
        $this->needAdmin();
        $id = (int) ($_POST["id"] ?? 0);
        $ma = trim($_POST["ma_mon"] ?? "");
        $ten = trim($_POST["ten_mon"] ?? "");

        if ($id <= 0 || $ma === "" || $ten === "") {
            $this->flash("err", "Vui lòng nhập đầy đủ thông tin!");
            header("Location: " . BASE_URL . "/index.php?url=MonthiController/edit/" . $id);
            exit;
        }

        $m = $this->model("Monthi_m");
        if ($m->existsMa($ma, $id)) {
            $this->flash("err", "Mã môn đã tồn tại!");
            header("Location: " . BASE_URL . "/index.php?url=MonthiController/edit/" . $id);
            exit;
        }

        $m->update($id, $ma, $ten);
        $this->flash("msg", "Đã cập nhật môn thi!");
        header("Location: " . BASE_URL . "/index.php?url=MonthiController/index");
        exit;
    }


    public function delete($id)
    {
        $this->needAdmin();
        $this->model("Monthi_m")->delete((int) $id);
        $this->flash("msg", "Đã xóa môn thi và dữ liệu liên quan!");
        header("Location: " . BASE_URL . "/index.php?url=MonthiController/index");
        exit;
    }


    public function template()
    {
        $this->needAdmin();
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=monthi_mau.csv');
        echo "\xEF\xBB\xBF";
        echo "ma_mon,ten_mon\n";
        echo "TOAN,Toán học\n";
        echo "LY,Vật lý\n";
        echo "HOA,Hóa học\n";
        exit;
    }


    public function import()
    {
        $this->needAdmin();

        if (empty($_FILES["file"]["tmp_name"])) {
            $this->flash("err", "Vui lòng chọn file Excel!");
            header("Location: " . BASE_URL . "/index.php?url=MonthiController/index");
            exit;
        }

        $name = $_FILES["file"]["name"] ?? "";
        if (!preg_match('/\.csv$/i', $name)) {
            $this->flash("err", "Chỉ hỗ trợ file Excel (.csv)!");
            header("Location: " . BASE_URL . "/index.php?url=MonthiController/index");
            exit;
        }

        $m = $this->model("Monthi_m");
        $fh = fopen($_FILES["file"]["tmp_name"], "r");
        $first = true;
        $ok = 0;
        $skip = 0;

        while (($row = fgetcsv($fh)) !== false) {
            if ($first) {
                $first = false;
                continue;
            }

            $ma = trim($row[0] ?? "");
            $ten = trim($row[1] ?? "");

            if ($ma === "" || $ten === "") {
                $skip++;
                continue;
            }
            if ($m->existsMa($ma)) {
                $skip++;
                continue;
            }

            $m->insert($ma, $ten);
            $ok++;
        }
        fclose($fh);

        $this->flash("msg", "Import xong: thêm $ok, bỏ qua $skip dòng.");
        header("Location: " . BASE_URL . "/index.php?url=MonthiController/index");
        exit;
    }
}
