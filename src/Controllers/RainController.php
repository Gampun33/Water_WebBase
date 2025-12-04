<?php
require_once __DIR__ . '/../Models/RainModel.php';
require_once __DIR__ . '/../Helpers/AppHelper.php';

class RainController {
    private $rainModel;

    public function __construct() {
        $this->rainModel = new RainModel();
        // ตรวจสอบสิทธิ์ Admin ทุกครั้งที่เรียก Controller นี้ (Double check)
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?page=login");
            exit;
        }
    }

    // แสดงหน้ารายการ (List)
    public function index() {
        $search = $_GET['search'] ?? '';
        $date = $_GET['date'] ?? '';
        $page = $_GET['p'] ?? 1;
        
        $data = $this->rainModel->getAll($search, $date, $page); // Model ต้องรองรับ pagination
        $total = $this->rainModel->countAll($search, $date);
        
        // ส่งตัวแปรไป View
        require __DIR__ . '/../../views/admin/rain/list.php';
    }

    // แสดงหน้าฟอร์มเพิ่มข้อมูล (Create)
    public function create() {
        // สร้าง CSRF Token
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $csrf_token = $_SESSION['csrf_token'];
        
        require __DIR__ . '/../../views/admin/rain/add.php';
    }

    // บันทึกข้อมูล (Store)
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        // 1. Check CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die("❌ Invalid CSRF token");
        }

        // 2. Validate Data
        $station_name = trim($_POST['station_name']);
        $rainfall = floatval($_POST['rainfall']);
        // ... รับค่าอื่นๆ ...

        // 3. Save
        if ($this->rainModel->insert($_POST)) {
            $_SESSION['flash_message'] = "✅ บันทึกสำเร็จ";
            header("Location: index.php?page=rain");
        } else {
            $error = "❌ บันทึกไม่สำเร็จ";
            require __DIR__ . '/../../views/admin/rain/add.php';
        }
    }

    // ลบข้อมูล (Delete)
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
             // ควรเช็ค CSRF ตรงนี้ด้วย
             if ($this->rainModel->delete($_POST['id'])) {
                 $_SESSION['flash_message'] = "✅ ลบข้อมูลสำเร็จ";
             }
        }
        header("Location: index.php?page=rain");
    }
}