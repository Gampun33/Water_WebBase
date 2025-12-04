<?php
// ต้องมีไฟล์ db.php สำหรับการเชื่อมต่อฐานข้อมูล PDO ในตัวแปร $pdo
require_once 'includes/db.php';

// กำหนด action จากค่าใน URL (เช่น list, edit, delete, update)
// ถ้าไม่มีการระบุ action จะใช้ 'list' เป็นค่าเริ่มต้น
$action = $_GET['action'] ?? 'list';
// ดึงค่า ID ถ้ามีการส่งมาใน URL
$id = $_GET['id'] ?? null;

// ข้อความแจ้งเตือนผู้ใช้
$message = '';
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'deleted':
            $message = "✅ ลบข้อมูลสำเร็จแล้ว";
            break;
        case 'updated':
            $message = "✅ แก้ไขข้อมูลสำเร็จแล้ว";
            break;
        case 'error':
            $message = "❌ เกิดข้อผิดพลาดในการดำเนินการ";
            break;
    }
}

// --- จัดการการลบข้อมูล ---
// ข้อควรระวัง: โค้ดนี้ยังไม่ได้รวม CSRF token สำหรับการลบ (แนะนำให้เพิ่มเพื่อความปลอดภัย)
if ($action === 'delete' && $id) {
    $stmt = $pdo->prepare("DELETE FROM water WHERE id = ?");
    if ($stmt->execute([$id])) {
        // เปลี่ยนเส้นทางกลับไปที่หน้าเดิมพร้อมข้อความแจ้งเตือน
        header("Location: index.php?page=admin&subpage=water_view&msg=deleted");
    } else {
        header("Location: index.php?page=admin&subpage=water_view&msg=error");
    }
    exit; // หยุดการทำงานของสคริปต์
}

// --- จัดการการอัปเดตข้อมูล ---
// ข้อควรระวัง: โค้ดนี้ยังไม่ได้รวม CSRF token สำหรับการอัปเดต (แนะนำให้เพิ่มเพื่อความปลอดภัย)
if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $stmt = $pdo->prepare("UPDATE water SET name_water=?, location=?, water_level=?, water_current=?, capacity=?, record_date=? WHERE id=?");
    if ($stmt->execute([
        $_POST['name_water'],
        $_POST['location'],
        $_POST['water_level'],
        $_POST['water_current'],
        $_POST['capacity'],
        $_POST['record_date'],
        $_POST['id']
    ])) {
        // เปลี่ยนเส้นทางกลับไปหน้าเดิมพร้อมข้อความแจ้งเตือน
        header("Location: index.php?page=admin&subpage=water_view&msg=updated");
    } else {
        header("Location: index.php?page=admin&subpage=water_view&msg=error");
    }
    exit; // หยุดการทำงานของสคริปต์
}

// --- ดึงข้อมูลสำหรับฟอร์มแก้ไข (เมื่อ action เป็น 'edit' และมี ID) ---
$editData = null; // กำหนดค่าเริ่มต้นเป็น null
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM water WHERE id = ?");
    $stmt->execute([$id]); // รันคำสั่ง
    $editData = $stmt->fetch(PDO::FETCH_ASSOC); // ดึงข้อมูลแถวเดียว
    // หากไม่พบข้อมูล ให้เปลี่ยนเส้นทางกลับไปหน้ารายการ
    if (!$editData) {
        header("Location: index.php?page=admin&subpage=water_view");
        exit;
    }
}

// --- การดึงข้อมูลทั้งหมดจากฐานข้อมูล พร้อมการค้นหา กรอง และเรียงลำดับ (SERVER-SIDE SORTING) ---
$search = $_GET['search'] ?? ''; // ค่าค้นหาจากช่องค้นหา
$selectedDate = $_GET['date'] ?? ''; // วันที่ที่เลือกจากตัวกรอง

// รับค่าคอลัมน์และทิศทางการเรียงลำดับจาก URL
// กำหนดค่าเริ่มต้น: เรียงตาม 'record_date' (วันที่บันทึก) จากมากไปน้อย (DESC)
$sortBy = $_GET['sortBy'] ?? 'record_date';
$sortOrder = $_GET['sortOrder'] ?? 'DESC';

// *** ตรวจสอบคอลัมน์ที่สามารถเรียงลำดับได้ เพื่อป้องกัน SQL injection ***
// (ชื่อคอลัมน์เหล่านี้ต้องตรงกับชื่อคอลัมน์ในฐานข้อมูลของคุณ)
$validSortColumns = ['water_level', 'water_current', 'capacity', 'record_date'];
if (!in_array($sortBy, $validSortColumns)) {
    $sortBy = 'record_date'; // ใช้ค่าเริ่มต้นถ้าคอลัมน์ที่ส่งมาไม่ถูกต้อง
}
// ตรวจสอบทิศทางการเรียงลำดับ
if (!in_array(strtoupper($sortOrder), ['ASC', 'DESC'])) {
    $sortOrder = 'DESC'; // ใช้ค่าเริ่มต้นถ้าทิศทางไม่ถูกต้อง
}

// --- Pagination Logic ---
$itemsPerPage = 10; // กำหนดจำนวนรายการต่อหน้า
$pageNum = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
$offset = ($pageNum - 1) * $itemsPerPage;

// สร้างคำสั่ง SQL หลักสำหรับการดึงข้อมูล
$sql = "SELECT * FROM water WHERE 1"; // WHERE 1 เป็นเทคนิคเพื่อให้สามารถเพิ่มเงื่อนไข AND ได้ง่าย

// เพิ่มเงื่อนไขค้นหาถ้ามีการระบุ
if (!empty($search)) {
    $sql .= " AND (name_water LIKE :search OR location LIKE :search)"; // ค้นหาจากชื่อสถานีหรืออำเภอ
}

// เพิ่มเงื่อนไขกรองวันที่ถ้ามีการระบุ
if (!empty($selectedDate)) {
    // ใช้ DATE() เพื่อเปรียบเทียบเฉพาะส่วนวันที่ หาก record_date เป็น DATETIME/TIMESTAMP
    $sql .= " AND DATE(record_date) = :selectedDate";
}

// *** เพิ่มการเรียงลำดับตามที่ผู้ใช้เลือก (จากพารามิเตอร์ sortBy และ sortOrder) ***
$sql .= " ORDER BY " . $sortBy . " " . $sortOrder;

// เพิ่ม LIMIT และ OFFSET สำหรับ Pagination
$sql .= " LIMIT :limit OFFSET :offset";

// --- นับจำนวนทั้งหมดสำหรับ Pagination ---
$countSql = "SELECT COUNT(*) FROM water WHERE 1";
if (!empty($search)) {
    $countSql .= " AND (name_water LIKE :search OR location LIKE :search)";
}
if (!empty($selectedDate)) {
    $countSql .= " AND DATE(record_date) = :selectedDate";
}
$countStmt = $pdo->prepare($countSql);
if (!empty($search)) {
    $countStmt->bindValue(':search', '%' . $search . '%');
}
if (!empty($selectedDate)) {
    $countStmt->bindValue(':selectedDate', $selectedDate);
}
$countStmt->execute();
$totalItems = $countStmt->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);


// เตรียมและรันคำสั่ง SQL เพื่อดึงข้อมูลสำหรับแสดงผล
$stmt = $pdo->prepare($sql);
if (!empty($search)) {
    $stmt->bindValue(':search', '%' . $search . '%');
}
if (!empty($selectedDate)) {
    $stmt->bindValue(':selectedDate', $selectedDate);
}
$stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute(); // รันคำสั่ง
$data = $stmt->fetchAll(PDO::FETCH_ASSOC); // ดึงข้อมูลทั้งหมดในรูปแบบ Array (associative)
?>
<style>
    .table th,
    .table td {
        text-align: center;
        vertical-align: middle;
    }

    .sortable {
        cursor: pointer;
        /* แสดงว่าคลิกได้ */
        user-select: none;
        /* ป้องกันการเลือกข้อความเมื่อคลิก */
    }

    /* Style เพื่อให้ icon อยู่ติดกับข้อความ */
    .sortable span {
        display: inline-block;
        min-width: 1em;
        /* กำหนดความกว้างขั้นต่ำให้อิโมจิ */
    }
</style>

<div class="container mt-4">
    <h3 class="mb-3">📊 ข้อมูลสถานีวัดน้ำท่าทั้งหมด</h3>

    <?php if (!empty($message)) : ?>
        <div class="alert alert-info alert-dismissible fade show text-center" role="alert">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="get" class="d-flex flex-wrap justify-content-center align-items-center gap-2 mb-4" autocomplete="off">
        <input type="hidden" name="page" value="admin" />
        <input type="hidden" name="subpage" value="water_view" />
        <input type="hidden" name="sortBy" value="<?= htmlspecialchars($sortBy) ?>" />
        <input type="hidden" name="sortOrder" value="<?= htmlspecialchars($sortOrder) ?>" />

        <input type="text" name="search" class="form-control" placeholder="🔍 ค้นหาชื่อสถานี / อำเภอ" value="<?= htmlspecialchars($search) ?>" style="width: 220px;" />

        <label for="date" class="mb-0">เลือกวันที่:</label>
        <input type="text" name="date" id="date" value="<?= htmlspecialchars($selectedDate) ?>" class="form-control flatpickr" style="width: auto;" placeholder="เลือกวันที่" autocomplete="off" />

        <button type="submit" class="btn btn-primary">🔄 โหลดใหม่</button>
        <a href="index.php?page=admin&subpage=water_add" class="btn btn-success">➕ เพิ่มข้อมูลใหม่</a>
    </form>

    <?php if ($action === 'edit' && $editData) : // แสดงฟอร์มแก้ไขถ้า action เป็น 'edit' และมีข้อมูล
    ?>
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">📝 แก้ไขข้อมูลสถานีวัดน้ำ</div>
            <div class="card-body">
                <form method="post" action="index.php?page=admin&subpage=water_view&action=update">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($editData['id']) ?>" />
                    <div class="mb-3">
                        <label for="name_water" class="form-label">ชื่อสถานี:</label>
                        <input type="text" class="form-control" id="name_water" name="name_water" value="<?= htmlspecialchars($editData['name_water']) ?>" required />
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">อำเภอ:</label>
                        <input type="text" class="form-control" id="location" name="location" value="<?= htmlspecialchars($editData['location']) ?>" required />
                    </div>
                    <div class="mb-3">
                        <label for="water_level" class="form-label">ระดับน้ำ:</label>
                        <input type="number" step="0.01" class="form-control" id="water_level" name="water_level" value="<?= htmlspecialchars($editData['water_level']) ?>" required />
                    </div>
                    <div class="mb-3">
                        <label for="water_current" class="form-label">ปริมาณน้ำล่าสุด:</label>
                        <input type="number" step="0.0001" class="form-control" id="water_current" name="water_current" value="<?= htmlspecialchars($editData['water_current']) ?>" required />
                    </div>
                    <div class="mb-3">
                        <label for="capacity" class="form-label">ความจุสูงสุด:</label>
                        <input type="number" step="0.0001" class="form-control" id="capacity" name="capacity" value="<?= htmlspecialchars($editData['capacity']) ?>" required />
                    </div>
                    <div class="mb-3">
                        <label>วันที่บันทึก:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                            <input type="text" name="record_date" class="form-control flatpickr" placeholder="เลือกวันที่..." value="<?= htmlspecialchars($editData['record_date']) ?>"required/>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">💾 บันทึกการแก้ไข</button>
                    <a href="index.php?page=admin&subpage=water_view" class="btn btn-secondary">↩ ยกเลิก</a>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <?php if (count($data) > 0) : // แสดงตารางข้อมูลถ้ามีข้อมูล
    ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover text-center align-middle shadow">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>ชื่อสถานี</th>
                        <th>อำเภอ</th>
                        <th class="sortable" onclick="sortTable('water_current')">ปริมาณน้ำล่าสุด <span id="water_current-sort" class="ms-1">⇅</span></th>
                        <th class="sortable" onclick="sortTable('water_level')">ระดับน้ำ <span id="water_level-sort">⇅</span></th>
                        <th class="sortable" onclick="sortTable('capacity')">ความจุสูงสุด <span id="capacity-sort">⇅</span></th>
                        <th class="sortable" onclick="sortTable('record_date')">วันที่บันทึก <span id="record_date-sort">⇅</span></th>
                        <th><i class="bi bi-tools"></i> จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $index => $row) : // วนลูปแสดงข้อมูลแต่ละแถว
                    ?>
                        <tr>
                            <td><?= ($index + 1) + $offset ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($row['name_water']) ?></td>
                            <td><?= htmlspecialchars($row['location']) ?></td>
                            <td><?= number_format($row['water_current'], 4) ?></td>
                            <td><?= htmlspecialchars($row['water_level']) ?></td>
                            <td><?= number_format($row['capacity'], 4) ?></td>
                            <td><?= date('d/m/Y', strtotime($row['record_date'])) ?></td>
                            <td>
                                <a href="index.php?page=admin&subpage=water_view&action=edit&id=<?= urlencode($row['id']) ?>" class="btn btn-sm btn-outline-primary me-1" title="แก้ไข">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="index.php?page=admin&subpage=water_view&action=delete&id=<?= urlencode($row['id']) ?>" onclick="return confirm('คุณต้องการลบข้อมูลสถานีน้ำนี้หรือไม่?');" class="btn btn-sm btn-outline-danger" title="ลบ">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPages > 1) : ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($pageNum > 1) : ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=admin&subpage=water_view&p=<?= $pageNum - 1 ?>&search=<?= urlencode($search) ?>&date=<?= urlencode($selectedDate) ?>&sortBy=<?= urlencode($sortBy) ?>&sortOrder=<?= urlencode($sortOrder) ?>">«</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?= ($i == $pageNum) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=admin&subpage=water_view&p=<?= $i ?>&search=<?= urlencode($search) ?>&date=<?= urlencode($selectedDate) ?>&sortBy=<?= urlencode($sortBy) ?>&sortOrder=<?= urlencode($sortOrder) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($pageNum < $totalPages) : ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=admin&subpage=water_view&p=<?= $pageNum + 1 ?>&search=<?= urlencode($search) ?>&date=<?= urlencode($selectedDate) ?>&sortBy=<?= urlencode($sortBy) ?>&sortOrder=<?= urlencode($sortOrder) ?>">»</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>

    <?php else : // ถ้าไม่พบข้อมูล
    ?>
        <div class="alert alert-warning text-center">ไม่มีข้อมูลสถานีวัดน้ำในระบบ</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>

<script>
    // การตั้งค่า Flatpickr สำหรับช่องเลือกวันที่
    flatpickr(".flatpickr", {
        dateFormat: "Y-m-d", // รูปแบบวันที่ YYYY-MM-DD
        maxDate: "today", // เลือกวันที่ได้ไม่เกินวันนี้
        locale: "th" // ใช้ภาษาไทย
    });

    /**
     * ฟังก์ชันสำหรับเรียงลำดับข้อมูลโดยการส่งพารามิเตอร์ไปยังเซิร์ฟเวอร์
     * จะโหลดหน้าเว็บใหม่พร้อมพารามิเตอร์ sortBy และ sortOrder
     * @param {string} column ชื่อคอลัมน์ในฐานข้อมูลที่ต้องการเรียงลำดับ
     */
    function sortTable(column) {
        // ดึงค่า sortBy และ sortOrder ปัจจุบันจาก URL ที่ PHP ส่งมาให้ JavaScript
        const currentSortBy = '<?= $sortBy ?>';
        let currentSortOrder = '<?= $sortOrder ?>';

        let newSortOrder = 'ASC'; // กำหนดค่าเริ่มต้นเป็นเรียงจากน้อยไปมาก
        // ถ้าคอลัมน์ที่คลิกคือคอลัมน์ที่กำลังเรียงลำดับอยู่
        if (column === currentSortBy) {
            // สลับทิศทางการเรียงลำดับ: ถ้าเดิมเป็น ASC ก็เปลี่ยนเป็น DESC, ถ้าเดิมเป็น DESC ก็เปลี่ยนเป็น ASC
            newSortOrder = currentSortOrder === 'ASC' ? 'DESC' : 'ASC';
        }

        // สร้าง Object URL ใหม่จาก URL ปัจจุบัน
        const url = new URL(window.location.href);

        // กำหนดพารามิเตอร์ sortBy และ sortOrder ใหม่
        url.searchParams.set('sortBy', column);
        url.searchParams.set('sortOrder', newSortOrder);

        // รีเซ็ตค่า p (เลขหน้า) เป็น 1 เสมอเมื่อมีการเรียงลำดับใหม่
        url.searchParams.set('p', 1);

        // ตรวจสอบและนำค่า search และ date (ถ้ามี) กลับไปด้วย เพื่อไม่ให้ถูกรีเซ็ต
        const searchParam = url.searchParams.get('search');
        if (searchParam) {
            url.searchParams.set('search', searchParam);
        } else {
            url.searchParams.delete('search'); // ลบพารามิเตอร์ถ้าไม่มีค่า
        }

        const dateParam = url.searchParams.get('date');
        if (dateParam) {
            url.searchParams.set('date', dateParam);
        } else {
            url.searchParams.delete('date'); // ลบพารามิเตอร์ถ้าไม่มีค่า
        }

        // นำทางไปยัง URL ใหม่เพื่อโหลดหน้าเว็บพร้อมข้อมูลที่เรียงลำดับแล้วจากเซิร์ฟเวอร์
        window.location.href = url.toString();
    }

    // เมื่อ DOM โหลดเสร็จแล้ว (หน้าเว็บพร้อมแล้ว) ให้อัปเดตไอคอนการเรียงลำดับ
    <?php if (isset($_GET['sortBy']) && isset($_GET['sortOrder'])) : ?>
        document.addEventListener('DOMContentLoaded', () => {
            const currentSortBy = '<?= htmlspecialchars($_GET['sortBy']) ?>';
            const currentSortOrder = '<?= htmlspecialchars($_GET['sortOrder']) ?>';
            // หา Element span ที่มี ID ตรงกับชื่อคอลัมน์ที่กำลังเรียงลำดับอยู่
            const sortSpan = document.getElementById(`${currentSortBy}-sort`);
            if (sortSpan) {
                // อัปเดตข้อความใน span ให้เป็นไอคอนตามทิศทางการเรียงลำดับ
                sortSpan.textContent = currentSortOrder === 'ASC' ? '🔼' : '🔽';
            }
        });
    <?php endif; ?>
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>