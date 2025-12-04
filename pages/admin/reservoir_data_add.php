<?php

require_once 'includes/db.php';

$boxNames = ['ขนาดใหญ่', 'ขนาดกลาง', 'ขนาดเล็ก'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $capacity_arr = $_POST['capacity'] ?? [];
    $current_water_arr = $_POST['current_water'] ?? [];
    $water_inuse_arr = $_POST['water_inuse'] ?? [];
    $record_date_arr = $_POST['record_date'] ?? [];

    // ลบข้อมูลเดิมที่มีในชื่อสถานีทั้ง 3 แถว
    $stmtDelete = $pdo->prepare("DELETE FROM station_data WHERE name_data = ?");

    // เตรียม statement insert (เพิ่ม record_date)
    $stmtInsert = $pdo->prepare("INSERT INTO station_data (current_water, capacity, water_inuse, name_data, record_date) VALUES (?, ?, ?, ?, ?)");

    $success = true;

    for ($i = 0; $i < 3; $i++) {
        $name_data = $boxNames[$i];

        // ลบข้อมูลเก่าของสถานีนี้ก่อน
        //  $stmtDelete->execute([$name_data]);

        $cap = isset($capacity_arr[$i]) ? floatval($capacity_arr[$i]) : 0;
        $cw = isset($current_water_arr[$i]) ? floatval($current_water_arr[$i]) : 0;
        $wiu = isset($water_inuse_arr[$i]) ? floatval($water_inuse_arr[$i]) : 0;
        $date = isset($record_date_arr[$i]) ? $_POST['record_date'][$i] : null;

        if (!$date) {
            $success = false;
            continue;
        }

        $result = $stmtInsert->execute([$cw, $cap, $wiu, $name_data, $date]);

        if (!$result) {
            $success = false;
        }
    }

    $_SESSION['flash_success'] = $success ? 1 : 0;
    $redirectUrl = $_SERVER['PHP_SELF'] . '?page=admin&subpage=reservoir_data_add';
    header("Location: " . $redirectUrl);
    exit();
}
?>

<style>
    /* ซ่อนลูกศร input number (Chrome, Edge, Safari) */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
        appearance: textfield;
    }

    /* ✅ เปลี่ยนหัวตารางเป็นสีดำ */
    thead th {
        color: black !important;
    }

    form .table thead th {
        color: #000 !important;
    }
</style>


<div class="container my-5">
    <h3 class="text-center mb-4">📝 เพิ่มข้อมูลสถานีใหม่ 3 แถว</h3>

    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="alert text-center <?= $_SESSION['flash_success'] == 1 ? 'alert-success' : 'alert-danger' ?>">
            <?= $_SESSION['flash_success'] == 1 ? '✅ บันทึกข้อมูลสำเร็จ' : '❌ บันทึกข้อมูลไม่สำเร็จ' ?>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <form method="POST" class="card shadow p-4 bg-white">
                <div class="table-responsive ">
                    <table class="table no-border text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 120px;">อ่างเก็บน้ำ</th>
                                <th>ความจุ</th>
                                <th>น้ำเก็บกัก</th>
                                <th>น้ำใช้การ</th>
                                <th>วันที่บันทึก</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < 3; $i++): ?>
                                <tr>
                                    <td class="fw-bold"><?= htmlspecialchars($boxNames[$i]) ?></td>
                                    <td>
                                        <input type="number" class="form-control" name="capacity[]" step="0.01" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="current_water[]" step="0.01" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="water_inuse[]" step="0.01" required>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                                            <input type="text" name="record_date[]" class="form-control flatpickr" placeholder="เลือกวันที่..." required>
                                        </div>
                                    </td>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6 text-center">
                        <button type="submit" class="btn btn-primary w-100">💾 บันทึก</button>
                    </div>
                    <div class="col-md-6 text-center">
                        <a href="index.php?page=admin&subpage=reservoir_data" class="btn btn-success w-100">↩ กลับ</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- ภาษาไทย -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>

<script>
    flatpickr(".flatpickr", {
        dateFormat: "Y-m-d",
        maxDate: "today",
        locale: "th"
    });
</script>