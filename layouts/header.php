<?php
// เริ่ม session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db.php';

// กำหนดหน้าที่ไม่ต้องล็อกอิน
$currentPage = $_GET['page'] ?? 'home';
$publicPages = ['login', 'home'];

if (!in_array($currentPage, $publicPages)) {
    if (!isset($_SESSION['user'])) {
        header("Location: index.php?page=login");
        exit;
    }

    // ✅ ตรวจสอบ timeout เท่านั้น (ไม่ตรวจ token แล้ว)
    $timeout = 900;
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
        session_unset();
        session_destroy();
        header("Location: index.php?page=login&msg=session_expired");
        exit;
    }

    $_SESSION['last_activity'] = time();
}

// ✅ จัดการ logout โดยไม่ต้องล้าง token
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php?page=login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ระบบแผนที่น้ำ</title>

    <link rel="icon" type="image/png" href="assets/favicon.png" />

    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css" />
    <link rel="stylesheet" href="assets/global.css" />

    <?php
    $currentPage = $_GET['page'] ?? 'home';
    if ($currentPage === 'home') {
        echo '<link rel="stylesheet" href="assets/home.css">';
    } elseif ($currentPage === 'admin') {
        echo '<link rel="stylesheet" href="assets/admin_check.css">';
    } elseif ($currentPage === 'watermap') {
        echo '<link rel="stylesheet" href="assets/water.css">';
    }
    ?>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?page=home">Home</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php?page=home">หน้าแรก</a></li>

                    <?php if (isset($_SESSION['user'])): ?>
                        <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
                            <li class="nav-item"><a class="nav-link" href="index.php?page=watermap">เช็คข้อมูล</a></li>
                            <li class="nav-item"><a class="nav-link" href="index.php?page=admin">จัดการระบบ</a></li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="index.php?page=login">เข้าสู่ระบบ</a></li>
                    <?php endif; ?>
                </ul>

                <?php if (isset($_SESSION['user'])): ?>
                    <form method="post" class="d-flex" role="search">
                        <span class="navbar-text text-light me-3">
                            👤 <?= htmlspecialchars($_SESSION['user']['username']) ?>
                        </span>
                        <button class="btn btn-outline-light btn-sm" name="logout" type="submit">ออกจากระบบ</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="content">
