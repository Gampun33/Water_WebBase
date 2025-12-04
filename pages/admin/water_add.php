<?php
require_once 'includes/db.php'; // เชื่อมต่อ PDO ใน $pdo

// รายการสถานี
$points = [
    ['name' => 'W.25', 'location' => 'อ.วังเหนือ จ.ลำปาง', 'name_location' => 'บ้านร่องเคาะ'],
    ['name' => 'W.16A', 'location' => 'อ.แจ้ห่ม จ.ลำปาง', 'name_location' => 'บ้านไฮ'],
    ['name' => 'W.26', 'location' => 'อ.แจ้ห่ม จ.ลำปาง', 'name_location' => 'บ้านเมืองมาย'],
    ['name' => 'W.10B', 'location' => 'อ.เมือง จ.ลำปาง', 'name_location' => 'บ้านดอนมูล'],
    ['name' => 'W.21', 'location' => 'อ.เมือง จ.ลำปาง', 'name_location' => 'บ้านท่าเดื่อ'],
    ['name' => 'W.1C', 'location' => 'อ.เมือง จ.ลำปาง', 'name_location' => 'สะพานเสตุวารี'],
    ['name' => 'W.22', 'location' => 'อ.เกาะคา จ.ลำปาง', 'name_location' => 'บ้านวังพร้าว'],
    ['name' => 'W.23', 'location' => 'อ.สามเงา จ.ตาก', 'name_location' => 'บ้านแม่เชียงราย'],
    ['name' => 'W.24', 'location' => 'อ.สามเงา จ.ตาก', 'name_location' => 'บ้านท่าไผ่'],
    ['name' => 'W.4A', 'location' => 'อ.สามเงา จ.ตาก', 'name_location' => 'บ้านวังหมัน'],
    ['name' => 'W.6A', 'location' => 'อ.สบปราป จ.ลำปาง', 'name_location' => 'บ้านสบปราป'],
    ['name' => 'W.3A', 'location' => 'อ.เถิน จ.ลำปาง', 'name_location' => 'บ้านดอนชัย'],
    ['name' => 'W.18A', 'location' => 'อ.เกาะคา จ.ลำปาง', 'name_location' => 'บ้านสบต่ำ'],
    ['name' => 'W.5A', 'location' => 'อ.เกาะคา จ.ลำปาง', 'name_location' => 'บ้านเกาะคา'],
    ['name' => 'TW.30', 'location' => 'อ.เกาะคา จ.ลำปาง', 'name_location' => 'บ้านใหม่พัฒนา'],
    ['name' => 'W.20', 'location' => 'อ.เมือง จ.ลำปาง', 'name_location' => 'บ้านท่าล้อ'],
    ['name' => 'TW.29', 'location' => 'อ.ห้างฉัตร จ.ลำปาง', 'name_location' => 'บ้านม่วง'],
    ['name' => 'W.17', 'location' => 'อ.แจ้ห่ม จ.ลำปาง', 'name_location' => 'บ้านหนองนาว'],
];

// หากส่งฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name_water = $_POST['name_water'] ?? '';
    $location = $_POST['location'] ?? '';
    $name_location = $_POST['name_location'] ?? '';
    $water_level = $_POST['water_level'] ?? '';
    $water_current = floatval($_POST['water_current'] ?? 0);
    $capacity = floatval($_POST['capacity'] ?? 0);
    $water_level_current = floatval($_POST['water_level_current'] ?? 0);
    $record_date = $_POST['record_date'] ?? date('Y-m-d');
    $record_date = date('Y-m-d H:i:s', strtotime($record_date));

    $sql = "INSERT INTO water 
        (name_water, name_location, location, water_level, water_current, capacity,water_level_current, record_date)
        VALUES (:name_water, :name_location, :location, :water_level, :water_current, :capacity,:water_level_current, :record_date)";
    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([
        ':name_water' => $name_water,
        ':name_location' => $name_location,
        ':location' => $location,
        ':water_level' => $water_level,
        ':water_current' => $water_current,
        ':capacity' => $capacity,
        ':water_level_current' => $water_level_current,
        ':record_date' => $record_date
    ]);

    header("Location: index.php?page=admin&subpage=water_add&success=" . ($success ? "1" : "0"));
    exit();
}
?>

<style>
    .container.my-5 {
        width: 90%;
        max-width: 700px;
        margin: auto;
    }
</style>

<div class="container my-5">
    <h3 class="mb-4 text-center">📝 กรอกข้อมูลน้ำท่า</h3>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-<?= $_GET['success'] == '1' ? 'success' : 'danger' ?> text-center">
            <?= $_GET['success'] == '1' ? "✅ บันทึกสำเร็จแล้ว!" : "❌ บันทึกล้มเหลว กรุณาลองใหม่" ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="card p-4 shadow">
        <div class="mb-3">
            <label class="form-label">ชื่อสถานีวัดน้ำท่า</label>
            <select name="name_water" id="name_water" class="form-select" required onchange="setStationInfo()">
                <option value="">-- เลือกสถานี --</option>
                <?php foreach ($points as $point): ?>
                    <option value="<?= htmlspecialchars($point['name']) ?>"
                        data-location="<?= htmlspecialchars($point['location']) ?>"
                        data-name_location="<?= htmlspecialchars($point['name_location']) ?>">
                        <?= htmlspecialchars($point['name']) ?> - <?= htmlspecialchars($point['name_location']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Hidden Fields -->
        <input type="hidden" name="location" id="location">
        <input type="hidden" name="name_location" id="name_location">

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">ระดับตลิ่ง</label>
                <input type="text" name="water_level" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">ปริมาณน้ำ</label>
                <input type="number" name="water_current" step="0.0001" class="form-control">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">ความจุสูงสุด</label>
                <input type="number" name="capacity" step="0.0001" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">ระดับน้ำ</label>
                <input type="number" name="water_level_current" step="0.0001" class="form-control" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">วันที่บันทึก</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                <input type="text" name="record_date" id="record_date" class="form-control" required placeholder="เลือกวันที่...">
            </div>
        </div>

        <div class="row g-2">
            <div class="col-6">
                <button type="submit" class="btn btn-primary w-100">💾 บันทึกข้อมูล</button>
            </div>
            <div class="col-6">
                <a href="index.php?page=admin&subpage=water_view" class="btn btn-success w-100">↩ กลับ</a>
            </div>
        </div>
    </form>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
<script>
    flatpickr("#record_date", {
        dateFormat: "Y-m-d",
        locale: "th",
        defaultDate: new Date()
    });

    function setStationInfo() {
        const select = document.getElementById("name_water");
        const selected = select.options[select.selectedIndex];

        if (selected) {
            document.getElementById("location").value = selected.getAttribute("data-location") || '';
            document.getElementById("name_location").value = selected.getAttribute("data-name_location") || '';
        }
    }

    // เรียกฟังก์ชันตอนโหลดหน้า เพื่อ set ค่าจาก dropdown เริ่มต้น
    document.addEventListener("DOMContentLoaded", function() {
        setStationInfo();
    });
</script>