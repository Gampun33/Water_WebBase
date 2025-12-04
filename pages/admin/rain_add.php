<?php
require_once 'includes/db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("❌ Invalid CSRF token");
    }

    $station_name = trim($_POST['station_name']);
    $location = trim($_POST['location']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $rainfall = floatval($_POST['rainfall']);

    // Validate inputs
    if (strlen($station_name) > 100 || strlen($location) > 100) {
        $message = "❌ ชื่อสถานี หรือ ที่ตั้ง ยาวเกินไป (ไม่เกิน 100 ตัวอักษร)";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $message = "❌ รูปแบบวันที่ไม่ถูกต้อง (ควรเป็น YYYY-MM-DD)";
    } elseif (!preg_match('/^\d{2}:\d{2}$/', $time)) {
        $message = "❌ รูปแบบเวลาไม่ถูกต้อง (ควรเป็น HH:MM)";
    } elseif ($rainfall < 0) {
        $message = "❌ ค่าฝนต้องเป็นค่าบวก";
    } else {
        // คำนวณระดับฝน
        if ($rainfall == 0) {
            $level = 'ไม่มีฝน';
        } elseif ($rainfall < 10) {
            $level = 'ฝนเล็กน้อย';
        } elseif ($rainfall < 35) {
            $level = 'ฝนปานกลาง';
        } elseif ($rainfall < 90) {
            $level = 'ฝนหนัก';
        } else {
            $level = 'ฝนหนักมาก';
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO rain_data (station_name, location, date, time, rainfall, level) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$station_name, $location, $date, $time, $rainfall, $level]);
            $message = "✅ บันทึกข้อมูลเรียบร้อยแล้ว!";
        } catch (PDOException $e) {
            $message = "❌ เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . htmlspecialchars($e->getMessage());
        }
    }
}

// สร้าง csrf token สำหรับฟอร์ม
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
?>

<style>
    .container.my-4 {
        width: 90%;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }
</style>

<div class="container my-4">
    <h2 class="mb-4">📝 กรอกข้อมูลปริมาณฝน</h2>

    <?php if ($message): ?>
        <div class="alert alert-<?= strpos($message, '✅') === 0 ? 'success' : 'danger' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="post" class="card p-4 rounded shadow" novalidate>
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

        <div class="mb-3">
            <label class="form-label" for="station_name">ชื่อสถานี</label>
            <input type="text" id="station_name" name="station_name" class="form-control" required maxlength="100" autofocus placeholder="ชื่อสถานี">
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label" for="location">ที่ตั้ง</label>
                <input type="text" id="location" name="location" class="form-control" required maxlength="100" placeholder="ต.ชื่อตำบล อ.อำเภอ">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label" for="rainfall">ค่าฝน (มิลลิเมตร)</label>
                <input type="number" id="rainfall" name="rainfall" class="form-control" min="0" step="0.1" required placeholder="ปริมาณฝน"> 
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col-md-6">
                <label class="form-label" for="date">วันที่</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                    <input type="text" id="date" name="date" class="form-control flatpickr" placeholder="เลือกวันที่..." required autocomplete="off">
                </div>
            </div>

            <div class="mb-3 col-md-6">
                <label class="form-label" for="time">เวลา</label>
                <input type="time" id="time" name="time" class="form-control" value="<?= date('H:i') ?>" required>
            </div>
        </div>

        <div class="row">
            <button type="submit" class="btn btn-primary w-50">💾 บันทึกข้อมูล</button>
            <a href="index.php?page=admin&subpage=rain" class="btn btn-success w-50">↩ กลับ</a>
        </div>
    </form>
</div>

<!-- flatpickr -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
<script>
    flatpickr(".flatpickr", {
        dateFormat: "Y-m-d",
        maxDate: "today",
        locale: "th"
    });
</script>
