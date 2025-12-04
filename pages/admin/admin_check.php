<?php

// 1. ตรวจสอบสิทธิ์ (ล็อกอิน + admin)
// ตรวจสอบสิทธิ์ (ล็อกอิน + admin)
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
  http_response_code(403);
  exit('❌ คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
}

// สร้าง CSRF token
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ดึงวันที่ และตรวจสอบรูปแบบ
$date = $_GET['date'] ?? date('Y-m-d');
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
  $date = date('Y-m-d');
}

require_once __DIR__ . '/../../logic/check_logic.php';

$types = ['rain', 'water', 'dam', 'waterTable'];

$results = [];
foreach ($expected as $key => $conf) {
  $results[$key . 'Result'] = checkData($pdo, $conf['query'], $conf['expected'], $date, $conf['match']);
}
extract($results);

$allComplete = true;
foreach ($types as $type) {
  if (!empty(${$type . 'Result'}['missing'])) {
    $allComplete = false;
    break;
  }
}

// ประมวลผล POST สำหรับอนุมัติ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die('❌ โทเค็นไม่ถูกต้อง');
  }

  $postDate = $_POST['date'] ?? '';
  if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $postDate)) {
    die('❌ วันที่ไม่ถูกต้อง');
  }

  try {
    $pdo->beginTransaction();

    $stmt1 = $pdo->prepare("UPDATE rain_data SET is_approved = 1 WHERE DATE(date) = ?");
    $stmt1->execute([$postDate]);

    $stmt2 = $pdo->prepare("UPDATE water SET is_approved = 1 WHERE DATE(record_date) = ?");
    $stmt2->execute([$postDate]);

    $stmt3 = $pdo->prepare("UPDATE stations SET is_approved = 1 WHERE DATE(record_date) = ?");
    $stmt3->execute([$postDate]);

    $stmt4 = $pdo->prepare("UPDATE station_data SET is_approved = 1 WHERE DATE(record_date) = ?");
    $stmt4->execute([$postDate]);

    $pdo->commit();

    header("Location: index.php?page=admin&subpage=admincheck&date=" . urlencode($postDate) . "&approved=1");
    exit;
  } catch (Exception $e) {
    $pdo->rollBack();
    echo "❌ เกิดข้อผิดพลาด: " . htmlspecialchars($e->getMessage());
    exit;
  }
}

// ฟังก์ชัน renderList ควรมีใน check_logic.php หรือใส่เพิ่มที่นี่ด้วยครับ
// function renderList(...) {...}

?>

<!-- HTML แสดงผล -->

<div class="container mt-4">
  <h2 class="mb-4">📋 ตรวจสอบข้อมูลประจำวันที่ <?= htmlspecialchars($date) ?></h2>

  <table class="table table-bordered table-hover table-striped align-middle text-center custom-table shadow-sm rounded">
    <thead>
      <tr>
        <th>ประเภท</th>
        <th>จำนวนทั้งหมด</th>
        <th>ที่มีในฐานข้อมูล</th>
        <th>ขาด</th>
        <th>เกิน</th>
        <th>ข้อผิดพลาด</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($types as $type):
        $result = ${$type . 'Result'};
      ?>
        <tr>
          <td class="fw-bold"><?= htmlspecialchars($expected[$type]['label']) ?></td>
          <td><?= count($expected[$type]['expected']) ?></td>
          <td><?= count($result['found']) ?></td>
          <td><?= count($result['missing']) ?></td>
          <td><?= count($result['extra']) ?></td>
          <td><?= htmlspecialchars($result['error'] ?? '-') ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <?php foreach ($types as $index => $type):
    $result = ${$type . 'Result'};
    $label = htmlspecialchars($expected[$type]['label']);
    $collapseIdMissing = "collapseMissing_$index";
    $collapseIdFound = "collapseFound_$index";
  ?>
    <div class="row mt-4">
      <div class="col-md-6">
        <h5>
          <button class="btn btn-outline-primary btn-sm btn-collapse d-flex align-items-center gap-2" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $collapseIdMissing ?>" aria-controls="<?= $collapseIdMissing ?>" aria-expanded="false">
            <span class="chevron">▶</span>
            <?= $label ?> ที่ยังไม่ส่ง <small class="text-muted">(คลิกเพื่อเปิด/ปิด)</small>
          </button>
        </h5>
        <div class="collapse" id="<?= $collapseIdMissing ?>">
          <ul class="list-group">
            <?php renderList($result['missing'], $result['fullMap'], '✅ ข้อมูลครบแล้ว'); ?>
          </ul>
        </div>
      </div>
      <div class="col-md-6">
        <h5>
          <button class="btn btn-outline-success btn-sm btn-collapse d-flex align-items-center gap-2" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $collapseIdFound ?>" aria-controls="<?= $collapseIdFound ?>" aria-expanded="false">
            <span class="chevron">▶</span>
            <?= $label ?> ที่มีข้อมูล <small class="text-muted">(คลิกเพื่อเปิด/ปิด)</small>
          </button>
        </h5>
        <div class="collapse" id="<?= $collapseIdFound ?>">
          <ul class="list-group">
            <?php renderList($result['found'], $result['fullMap'], 'ไม่มีข้อมูล', 'list-group-item-success', 'list-group-item-info'); ?>
          </ul>
        </div>
      </div>
    </div>
  <?php endforeach; ?>

  <?php if ($allComplete): ?>
    <form method="post" class="mt-3">
      <input type="hidden" name="date" value="<?= htmlspecialchars($date) ?>">
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
      <button type="submit" class="btn btn-success">✅ ยืนยันข้อมูลครบ วันที่ <?= htmlspecialchars($date) ?></button>
    </form>
  <?php else: ?>
    <div class="alert alert-warning mt-3">❌ ข้อมูลยังไม่ครบ กรุณาตรวจสอบก่อนยืนยัน</div>
  <?php endif; ?>

  <?php if (isset($_GET['approved'])): ?>
    <div class="alert alert-success mt-3">
      🎉 ยืนยันข้อมูลเรียบร้อยแล้วสำหรับวันที่ <?= htmlspecialchars($date) ?>
    </div>
  <?php endif; ?>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />