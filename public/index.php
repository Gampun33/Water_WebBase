<?php
// 1. เริ่ม Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Load ไฟล์ที่จำเป็น (Config & Helpers)
// แนะนำให้ทำระบบ Autoload ในอนาคต แต่ตอนนี้ require เอาแบบนี้ไปก่อนครับ
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Helpers/AppHelper.php';

// 3. Load Controllers ที่มีทั้งหมด
require_once __DIR__ . '/../src/Controllers/HomeController.php';
require_once __DIR__ . '/../src/Controllers/WaterMapController.php'; // ตัวอย่างถ้ามีหน้าแผนที่น้ำ
require_once __DIR__ . '/../src/Controllers/RainController.php';     // ตัวอย่างหน้าจัดการฝน

// 4. รับค่า Page และ Action จาก URL
$page = $_GET['page'] ?? 'home';   // ถ้าไม่ส่งมา ให้ไปหน้า home
$action = $_GET['action'] ?? 'index'; // method ที่จะเรียกใน controller

// 5. ตัวกำหนดเส้นทาง (Router Switch)
switch ($page) {
    case 'home':
        $controller = new HomeController();
        $controller->index(); // เรียกฟังก์ชันหลักของหน้า Home
        break;

    case 'watermap':
        // ตัวอย่าง: ถ้ามีหน้าแผนที่น้ำ
        $controller = new WaterMapController();
        $controller->index();
        break;

    case 'rain':
        // ตัวอย่าง: หน้าจัดการฝน (Admin)
        // เช็คสิทธิ์ Admin ก่อน (ถ้าจำเป็น)
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?page=login");
            exit;
        }
        
        $controller = new RainController();
        if ($action === 'add') {
            $controller->create(); // หน้าฟอร์มเพิ่ม
        } elseif ($action === 'save') {
            $controller->store();  // บันทึกข้อมูล
        } else {
            $controller->index();  // หน้ารายการปกติ
        }
        break;

    case 'login':
        // ถ้ามี LoginController ก็เรียกใช้ที่นี่
        // require_once __DIR__ . '/../src/Controllers/AuthController.php';
        // $auth = new AuthController();
        // $auth->login();
        // หรือถ้ายังไม่ได้ทำ Controller Login ก็ include ไฟล์เดิมไปก่อนชั่วคราว
        require __DIR__ . '/../pages/login.php';
        break;

    default:
        // กรณีหาหน้าไม่เจอ (404)
        http_response_code(404);
        echo "<h1>❌ 404 Not Found</h1>";
        echo "<p>ไม่พบหน้าที่คุณต้องการ</p>";
        echo '<a href="index.php">กลับหน้าแรก</a>';
        break;
}