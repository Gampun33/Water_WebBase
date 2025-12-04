<?php
session_start();
require_once __DIR__ . '/includes/db.php';

$page = $_GET['page'] ?? 'home';
$filepath = __DIR__ . "/pages/" . basename($page) . ".php";

$protected_pages = ['admin', 'watermap'];
$admin_only_pages = ['admin'];

if (in_array($page, $protected_pages) && !isset($_SESSION['user'])) {
    header("Location: index.php?page=login");
    exit;
}

if (in_array($page, $admin_only_pages) && ($_SESSION['user']['role'] ?? '') !== 'admin') {
    include __DIR__ . "/components/header.php";
    echo "<div class='container my-5'><h3 class='text-danger'>คุณไม่มีสิทธิ์เข้าถึงหน้านี้</h3></div>";
    include __DIR__ . "/components/footer.php";
    exit;
}

include __DIR__ . "/components/header.php";

if (file_exists($filepath)) {
    include $filepath;
} else {
    echo "<div class='container my-5'><h1>404 - ไม่พบหน้านี้</h1></div>";
}

include __DIR__ . "/components/footer.php";
