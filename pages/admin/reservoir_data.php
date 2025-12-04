<?php
require_once 'includes/db.php';

// รับค่าจาก URL
$search = $_GET['search'] ?? '';
$selectedDate = $_GET['date'] ?? '';
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? null;
$message = '';

// --- Pagination & Sorting Parameters ---
$itemsPerPage = 10; // จำนวนรายการต่อหน้า
$pageNum = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
$offset = ($pageNum - 1) * $itemsPerPage;

$sortBy = $_GET['sortBy'] ?? 'record_date';
$sortOrder = $_GET['sortOrder'] ?? 'DESC';

// ตรวจสอบคอลัมน์ที่อนุญาตให้เรียงลำดับ (ใช้ชื่อคอลัมน์จริงใน DB)
$allowedSortBy = [
    'name_data',
    'capacity',
    'current_water',
    'water_inuse',
    'record_date'
];
if (!in_array($sortBy, $allowedSortBy)) {
    $sortBy = 'record_date'; // ค่าเริ่มต้น
}
if (!in_array(strtoupper($sortOrder), ['ASC', 'DESC'])) {
    $sortOrder = 'DESC'; // ค่าเริ่มต้น
}

// ข้อความแจ้งเตือน
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'updated') {
        $message = "✅ แก้ไขข้อมูลสำเร็จแล้ว";
    } elseif ($_GET['msg'] === 'deleted') {
        $message = "✅ ลบข้อมูลสำเร็จแล้ว";
    } elseif ($_GET['msg'] === 'added') {
        $message = "✅ เพิ่มข้อมูลใหม่สำเร็จแล้ว";
    }
}

// ลิงก์พื้นฐานสำหรับ redirect เพื่อคงค่า query parameters
function getBaseRedirectUrl($currentSearch, $currentDate, $currentPageNum, $currentSortBy, $currentSortOrder)
{
    $url = "index.php?page=admin&subpage=reservoir_data";
    if (!empty($currentSearch)) {
        $url .= "&search=" . urlencode($currentSearch);
    }
    if (!empty($currentDate)) {
        $url .= "&date=" . urlencode($currentDate);
    }
    $url .= "&p=" . urlencode($currentPageNum);
    $url .= "&sortBy=" . urlencode($currentSortBy);
    $url .= "&sortOrder=" . urlencode($currentSortOrder);
    return $url;
}


// ดึงข้อมูลสถานีถ้า action=edit
$editData = null;
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM station_data WHERE id = ?");
    $stmt->execute([$id]);
    $editData = $stmt->fetch(PDO::FETCH_ASSOC);
}

// อัปเดตข้อมูลเมื่อส่งฟอร์มแก้ไข
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $stmt = $pdo->prepare("UPDATE station_data SET name_data = ?, capacity = ?, current_water = ?, water_inuse = ?, record_date = ? WHERE id = ?");
    $updated = $stmt->execute([
        $_POST['name_data'],
        $_POST['capacity'],
        $_POST['current_water'],
        $_POST['water_inuse'],
        $_POST['record_date'],
        $_POST['update_id']
    ]);
    if ($updated) {
        // Redirect กลับไปหน้าเดิมพร้อม message และคงค่า query parameters
        header("Location: " . getBaseRedirectUrl($search, $selectedDate, $pageNum, $sortBy, $sortOrder) . "&msg=updated");
        exit;
    } else {
        $message = "❌ อัปเดตข้อมูลไม่สำเร็จ";
    }
}

// ลบข้อมูล
if ($action === 'delete' && $id) {
    $stmt = $pdo->prepare("DELETE FROM station_data WHERE id = ?");
    if ($stmt->execute([$id])) {
        // Redirect กลับไปหน้าเดิมพร้อม message และคงค่า query parameters
        header("Location: " . getBaseRedirectUrl($search, $selectedDate, $pageNum, $sortBy, $sortOrder) . "&msg=deleted");
        exit;
    } else {
        $message = "❌ ลบข้อมูลไม่สำเร็จ";
    }
}

// SQL สำหรับนับจำนวนรายการทั้งหมด (สำหรับ Pagination)
$countSql = "SELECT COUNT(*) FROM station_data WHERE 1=1";
$countParams = [];
if (!empty($search)) {
    $countSql .= " AND name_data LIKE :search";
    $countParams[':search'] = "%$search%";
}
if (!empty($selectedDate)) {
    $countSql .= " AND DATE(record_date) = :date";
    $countParams[':date'] = $selectedDate;
}
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($countParams);
$totalItems = $countStmt->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);


// ดึงข้อมูลสำหรับหน้าปัจจุบัน
$sql = "SELECT * FROM station_data WHERE 1=1";
$params = [];
if (!empty($search)) {
    $sql .= " AND name_data LIKE :search";
    $params[':search'] = "%$search%";
}
if (!empty($selectedDate)) {
    $sql .= " AND DATE(record_date) = :date";
    $params[':date'] = $selectedDate;
}

// เพิ่มการเรียงลำดับ
$sql .= " ORDER BY " . $sortBy . " " . $sortOrder;

// เพิ่ม LIMIT และ OFFSET สำหรับ Pagination
$sql .= " LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
if (!empty($search)) {
    $stmt->bindValue(':search', $params[':search']);
}
if (!empty($selectedDate)) {
    $stmt->bindValue(':date', $params[':date']);
}
$stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    /* ซ่อนลูกศรเริ่มต้นและแสดงเมื่อ hover หรือ active */
    th.sortable span {
        font-size: 0.9em;
        user-select: none;
        opacity: 0;
        /* ซ่อนตั้งแต่โหลดหน้า */
        transition: opacity 0.2s ease-in-out;
    }

    th.sortable:hover span {
        opacity: 1;
        /* แสดงตอน hover */
    }

    th.sortable.active-sort span {
        opacity: 1;
        /* แสดงตอน active */
    }


    th.sortable {
        cursor: pointer;
        user-select: none;
    }

    table tbody tr:hover {
        background-color: #f1f3f5;
    }

    table th,
    table td {
        vertical-align: middle !important;
        text-align: center !important;
    }
</style>

<div class="container mt-4">
    <h3 class="mb-3">📊 ข้อมูลสถานีอ่างเก็บน้ำ</h3>

    <?php if ($message) : ?>
        <div class="alert alert-info alert-dismissible fade show text-center" role="alert">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($editData) : ?>
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                ✏️ แก้ไขข้อมูลสถานี - <?= htmlspecialchars($editData['name_data']) ?>
            </div>
            <div class="card-body">
                <form method="post" action="index.php?page=admin&subpage=reservoir_data&action=edit&id=<?= $editData['id'] ?>&search=<?= urlencode($search) ?>&date=<?= urlencode($selectedDate) ?>&p=<?= urlencode($pageNum) ?>&sortBy=<?= urlencode($sortBy) ?>&sortOrder=<?= urlencode($sortOrder) ?>">
                    <input type="hidden" name="update_id" value="<?= htmlspecialchars($editData['id']) ?>">

                    <div class="mb-3">
                        <label class="form-label">ชื่อสถานี</label>
                        <input type="text" name="name_data" class="form-control" required value="<?= htmlspecialchars($editData['name_data']) ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ความจุ (ล้าน ลบ.ม.)</label>
                        <input type="number" step="0.01" name="capacity" class="form-control" value="<?= htmlspecialchars($editData['capacity']) ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">น้ำเก็บกัก</label>
                        <input type="number" step="0.01" name="current_water" class="form-control" value="<?= htmlspecialchars($editData['current_water']) ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">น้ำใช้การ</label>
                        <input type="number" step="0.01" name="water_inuse" class="form-control" value="<?= htmlspecialchars($editData['water_inuse']) ?>">
                    </div>

                    <div class="mb-3">
                        <label>วันที่บันทึก</label>
                        <input type="text" name="record_date" class="form-control flatpickr" value="<?= htmlspecialchars($editData['record_date']) ?>" required>
                    </div>

                    <button type="submit" class="btn btn-success">💾 บันทึกการแก้ไข</button>
                    <a href="index.php?page=admin&subpage=reservoir_data&search=<?= urlencode($search) ?>&date=<?= urlencode($selectedDate) ?>&p=<?= urlencode($pageNum) ?>&sortBy=<?= urlencode($sortBy) ?>&sortOrder=<?= urlencode($sortOrder) ?>" class="btn btn-secondary">↩ ยกเลิก</a>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <form method="get" class="mb-4">
        <input type="hidden" name="page" value="admin">
        <input type="hidden" name="subpage" value="reservoir_data">
        <input type="hidden" name="sortBy" value="<?= htmlspecialchars($sortBy) ?>">
        <input type="hidden" name="sortOrder" value="<?= htmlspecialchars($sortOrder) ?>">
        <input type="hidden" name="p" value="<?= htmlspecialchars($pageNum) ?>">

        <div class="row g-2 align-items-center justify-content-center">
            <div class="col-auto">
                <input type="text" name="search" class="form-control" placeholder="🔍 ค้นหาชื่อสถานี" value="<?= htmlspecialchars($search) ?>" style="width: 200px;">
            </div>

            <div class="col-auto">
                <label for="date" class="col-form-label">เลือกวันที่:</label>
            </div>
            <div class="col-auto">
                <input type="text" name="date" id="date" value="<?= htmlspecialchars($selectedDate) ?>" class="form-control flatpickr" style="width: 150px;" placeholder="เลือกวันที่" autocomplete="off" />
            </div>

            <div class="col-auto">
                <button type="submit" class="btn btn-primary">🔄 โหลดใหม่</button>
            </div>

            <div class="col-auto">
                <a href="index.php?page=admin&subpage=reservoir_data_add" class="btn btn-success">➕ เพิ่มข้อมูลใหม่</a>
            </div>
        </div>
    </form>

    <?php if (count($data) > 0) : ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover text-center align-middle shadow">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th class="sortable" data-column="name_data">ชื่อสถานี <span id="sort-name_data"></span></th>
                        <th class="sortable" data-column="capacity">ความจุ <span id="sort-capacity"></span></th>
                        <th class="sortable" data-column="current_water">น้ำเก็บกัก <span id="sort-current_water"></span></th>
                        <th class="sortable" data-column="water_inuse">น้ำใช้การ <span id="sort-water_inuse"></span></th>
                        <th class="sortable" data-column="record_date">วันที่ <span id="sort-record_date"></span></th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $i => $row) : ?>
                        <tr>
                            <td><?= ($i + 1) + $offset ?></td>
                            <td><?= htmlspecialchars($row['name_data']) ?></td>
                            <td><?= number_format($row['capacity'], 2) ?></td>
                            <td><?= number_format($row['current_water'], 2) ?></td>
                            <td><?= number_format($row['water_inuse'], 2) ?></td>
                            <td><?= date('d/m/Y', strtotime($row['record_date'])) ?></td>
                            <td>
                                <a href="index.php?page=admin&subpage=reservoir_data&action=edit&id=<?= urlencode($row['id']) ?>&search=<?= urlencode($search) ?>&date=<?= urlencode($selectedDate) ?>&p=<?= urlencode($pageNum) ?>&sortBy=<?= urlencode($sortBy) ?>&sortOrder=<?= urlencode($sortOrder) ?>" class="btn btn-sm btn-outline-primary me-1" title="แก้ไข">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="index.php?page=admin&subpage=reservoir_data&action=delete&id=<?= urlencode($row['id']) ?>&search=<?= urlencode($search) ?>&date=<?= urlencode($selectedDate) ?>&p=<?= urlencode($pageNum) ?>&sortBy=<?= urlencode($sortBy) ?>&sortOrder=<?= urlencode($sortOrder) ?>" onclick="return confirm('คุณต้องการลบข้อมูลนี้หรือไม่?');" class="btn btn-sm btn-outline-danger" title="ลบ">
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
                            <a class="page-link" href="?page=admin&subpage=reservoir_data&p=<?= $pageNum - 1 ?>&search=<?= urlencode($search) ?>&date=<?= urlencode($selectedDate) ?>&sortBy=<?= urlencode($sortBy) ?>&sortOrder=<?= urlencode($sortOrder) ?>">«</a>
                        </li>
                    <?php endif; ?>

                    <?php
                    // แสดง pagination link รอบๆ หน้าปัจจุบัน
                    $startPage = max(1, $pageNum - 2);
                    $endPage = min($totalPages, $pageNum + 2);

                    if ($startPage > 1) {
                        echo '<li class="page-item"><a class="page-link" href="?page=admin&subpage=reservoir_data&p=1&search=' . urlencode($search) . '&date=' . urlencode($selectedDate) . '&sortBy=' . urlencode($sortBy) . '&sortOrder=' . urlencode($sortOrder) . '">1</a></li>';
                        if ($startPage > 2) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                    }

                    for ($i = $startPage; $i <= $endPage; $i++) : ?>
                        <li class="page-item <?= ($i == $pageNum) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=admin&subpage=reservoir_data&p=<?= $i ?>&search=<?= urlencode($search) ?>&date=<?= urlencode($selectedDate) ?>&sortBy=<?= urlencode($sortBy) ?>&sortOrder=<?= urlencode($sortOrder) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php
                    if ($endPage < $totalPages) {
                        if ($endPage < $totalPages - 1) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                        echo '<li class="page-item"><a class="page-link" href="?page=admin&subpage=reservoir_data&p=' . $totalPages . '&search=' . urlencode($search) . '&date=' . urlencode($selectedDate) . '&sortBy=' . urlencode($sortBy) . '&sortOrder=' . urlencode($sortOrder) . '">' . $totalPages . '</a></li>';
                    }
                    ?>

                    <?php if ($pageNum < $totalPages) : ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=admin&subpage=reservoir_data&p=<?= $pageNum + 1 ?>&search=<?= urlencode($search) ?>&date=<?= urlencode($selectedDate) ?>&sortBy=<?= urlencode($sortBy) ?>&sortOrder=<?= urlencode($sortOrder) ?>">»</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>

    <?php else : ?>
        <div class="alert alert-warning text-center">ไม่มีข้อมูลในระบบ</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        flatpickr(".flatpickr", {
            locale: "th",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d/m/Y",
            allowInput: true,
            maxDate: "today"
        });

        // Function to handle sorting
        function sortTable(column) {
            const url = new URL(window.location.href);
            const currentSortBy = url.searchParams.get('sortBy') || 'record_date';
            const currentSortOrder = url.searchParams.get('sortOrder') || 'DESC';

            let newSortOrder = 'ASC';
            if (column === currentSortBy) {
                newSortOrder = currentSortOrder === 'ASC' ? 'DESC' : 'ASC';
            }

            url.searchParams.set('sortBy', column);
            url.searchParams.set('sortOrder', newSortOrder);
            url.searchParams.set('p', 1); // Reset to first page on sort

            window.location.href = url.toString();
        }

        // Add click event listeners to sortable headers
        document.querySelectorAll('th.sortable').forEach(th => {
            th.addEventListener('click', () => {
                const column = th.getAttribute('data-column');
                if (column) {
                    sortTable(column);
                }
            });
        });

        // Display sort arrows and active class on page load
        const currentSortBy = '<?= htmlspecialchars($sortBy) ?>';
        const currentSortOrder = '<?= htmlspecialchars($sortOrder) ?>';

        const sortSpan = document.getElementById(`sort-${currentSortBy}`);
        if (sortSpan) {
            sortSpan.textContent = currentSortOrder === 'ASC' ? '🔼' : '🔽';
            sortSpan.closest('th.sortable').classList.add('active-sort');
        }


    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>