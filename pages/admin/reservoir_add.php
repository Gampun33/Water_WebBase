<?php
require_once 'includes/db.php'; // เชื่อมต่อ PDO ($pdo)

$message = "";

// รายชื่ออ่างเก็บน้ำ
$stations = [
    'เขื่อนกิ่วคอหมา',
    'เขื่อนกิ่วลม',
    'เขื่อนแม่จาง',
    'เขื่อนแม่ขาม',
    'อ่างเก็บน้ำแม่เมาะ',
    'อ่างเก็บน้ำแม่นึง',
    'อ่างเก็บน้ำแม่ทะ',
    'อ่างเก็บน้ำแม่ทรายคำ',
    'อ่างเก็บน้ำห้วยหลวงวังวัว',
    'อ่างเก็บน้ำห้วยแม่ค่อม',
    'อ่างเก็บน้ำแม่ต๋ำน้อย',
    'อ่างเก็บน้ำแม่เฟือง',
    'อ่างเก็บน้ำแม่อาง',
    'อ่างเก็บน้ำแม่วะ',
    'อ่างเก็บน้ำแม่ไฮ',
    'อ่างเก็บน้ำแม่ธิ',
    'อ่างเก็บน้ำห้วยเกี๋ยง',
    'อ่างเก็บน้ำแม่ปอน',
    'อ่างเก็บน้ำแม่ยาว',
    'อ่างเก็บน้ำแม่ไพร',
    'อ่างเก็บน้ำห้วยแม่สัน',
    'อ่างเก็บน้ำแม่ทาน',
    'อ่างเก็บน้ำห้วยสมัย',
    'อ่างเก็บน้ำแม่ทก',
    'อ่างเก็บน้ำแม่เรียง',
    'อ่างเก็บน้ำห้วยหลวง',
    'อ่างเก็บน้ำแม่เลียงพัฒนา',
    'อ่างเก็บน้ำแม่ต๋ำตอนล่าง',
    'อ่างเก็บน้ำแม่กึ๊ด',
    'อ่างเก็บน้ำแม่อาบ',
    'อ่างเก็บน้ำแม่พริก',
    'อ่างเก็บน้ำแม่ล้อหัก',
    'อ่างเก็บน้ำแม่พริกผาวิ่งชู้',
    'อ่างเก็บน้ำแม่ฟ้า',
    'อ่างฯห้วยทราย'
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $current_water = floatval($_POST['current_water'] ?? 0);
    $inflow = floatval($_POST['inflow'] ?? 0);
    $outflow = floatval($_POST['outflow'] ?? 0);
    $capacity = floatval($_POST['capacity'] ?? 0);
    $record_date = $_POST['record_date'] ?? date('Y-m-d');

    $sql = "INSERT INTO stations 
        (name, current_water, inflow, outflow, capacity, record_date) 
        VALUES (:name, :current_water, :inflow, :outflow, :capacity, :record_date)";

    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([
        ':name' => $name,
        ':current_water' => $current_water,
        ':inflow' => $inflow,
        ':outflow' => $outflow,
        ':capacity' => $capacity,
        ':record_date' => $record_date
    ])) {
        $message = "✅ บันทึกข้อมูลเรียบร้อยแล้ว!";
    } else {
        $message = "❌ เกิดข้อผิดพลาดในการบันทึก";
    }
}
?>

<!-- HTML -->
<div class="container my-5">
    <h2 class="text-center mb-4">📝 กรอกข้อมูลปริมาณน้ำอ่าง</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="POST" class="card shadow p-4 bg-white">
                <div class="mb-3">
                    <label for="stationSelect" class="form-label">เลือกชื่ออ่างเก็บน้ำ</label>
                    <select name="name" id="stationSelect" class="form-select" required>
                        <option value="">-- พิมพ์เพื่อค้นหาอ่างเก็บน้ำ --</option>
                        <?php foreach ($stations as $stationName): ?>
                            <option value="<?= htmlspecialchars($stationName) ?>"><?= htmlspecialchars($stationName) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">ค่าปริมาณ (มม.)</label>
                        <input type="number" name="current_water" step="0.0001" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">ปริมาณน้ำเข้า (inflow)</label>
                        <input type="number" name="inflow" step="0.0001" class="form-control">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">ปริมาณน้ำออก (outflow)</label>
                        <input type="number" name="outflow" step="0.0001" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">ความจุ (capacity)</label>
                        <input type="number" name="capacity" step="0.0001" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">วันที่บันทึก</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                        <input type="text" id="record_date" name="record_date" class="form-control flatpickr" placeholder="เลือกวันที่..." required>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">💾 บันทึกข้อมูล</button>
                    <a href="index.php?page=admin&subpage=reservoir" class="btn btn-success flex-grow-1">↩ กลับ</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- เพิ่มลิงก์ CSS/JS ที่จำเป็น -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    $(document).ready(function() {
        $('#stationSelect').select2({
            placeholder: "-- พิมพ์เพื่อค้นหาอ่างเก็บน้ำ --",
            allowClear: true,
            width: '100%',
        });

        // ตั้งค่า flatpickr สำหรับ input วันที่
        flatpickr(".flatpickr", {
            dateFormat: "Y-m-d",
            maxDate: "today", // ไม่เลือกวันในอนาคต
            locale: "th"
        });
    });
</script>