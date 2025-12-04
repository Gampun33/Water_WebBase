<?php

// ตั้งค่า $subpage ให้เป็น 'admin_check' หากไม่มีการระบุ
$subpage = $_GET['subpage'] ?? 'admin_check';
?>

<div class="container mt-4">
    <center>
        <h3>🛠️ จัดการระบบ</h3>
    </center>

    <ul class="nav nav-tabs mb-4 justify-content-center">
        <li class="nav-item">
            <a class="nav-link <?= $subpage === 'admin_check' ? 'active' : '' ?>" href="index.php?page=admin&subpage=admin_check">ตรวจข้อมูล</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $subpage === 'rain' ? 'active' : '' ?>" href="index.php?page=admin&subpage=rain">ข้อมูลฝน</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $subpage === 'water_view' ? 'active' : '' ?>" href="index.php?page=admin&subpage=water_view">ข้อมูลน้ำท่า</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $subpage === 'reservoir' ? 'active' : '' ?>" href="index.php?page=admin&subpage=reservoir">ข้อมูลเขื่อน</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $subpage === 'reservoir_data' ? 'active' : '' ?>" href="index.php?page=admin&subpage=reservoir_data">น้ำในอ่าง 83 แห่ง</a>
        </li>
    </ul>

    <?php
    // รวม path ปลอดภัย
    $basePath = __DIR__ . '/admin/';
    switch ($subpage) {
        case 'rain':
        case 'reservoir':
        case 'rain_add':
        case 'reservoir_add':
        case 'water_add':
        case 'water_view':
        case 'admin_check':
        case 'reservoir_data':
        case 'reservoir_data_add':
            $filepath = $basePath . $subpage . '.php';
            if (file_exists($filepath)) {
                include $filepath;
            } else {
                echo "<div class='alert alert-danger'>❌ ไม่พบไฟล์ย่อย: $subpage</div>";
            }
            break;
        default:
            include $basePath . 'admin_check.php';
            break;
    }
    ?>
</div>
