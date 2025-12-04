<?php
require_once 'includes/db.php';

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

$message = "";
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'deleted') {
        $message = "✅ ลบข้อมูลสำเร็จแล้ว";
    } elseif ($_GET['msg'] === 'updated') {
        $message = "✅ แก้ไขข้อมูลสำเร็จแล้ว";
    }
}

// สร้าง CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// ลบข้อมูล (POST + CSRF)
if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("❌ Invalid CSRF token");
    }
    $del_id = $_POST['id'] ?? null;
    if ($del_id) {
        $stmt = $pdo->prepare("DELETE FROM rain_data WHERE id = ?");
        $stmt->execute([$del_id]);
        header("Location: index.php?page=admin&subpage=rain&msg=deleted");
        exit;
    }
}

// อัปเดตข้อมูล (POST + CSRF + Validate)
if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("❌ Invalid CSRF token");
    }
    $id = $_POST['id'] ?? null;
    $station_name = trim($_POST['station_name'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $rainfall = floatval($_POST['rainfall'] ?? 0);

    // Basic validation
    if (
        $id && $station_name !== '' && $location !== '' &&
        preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) &&
        preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $time) &&
        $rainfall >= 0
    ) {
        if ($rainfall >= 90) $level = 'ฝนหนักมาก';
        elseif ($rainfall >= 35) $level = 'ฝนหนัก';
        elseif ($rainfall >= 10) $level = 'ฝนปานกลาง';
        elseif ($rainfall > 0) $level = 'ฝนเล็กน้อย';
        else $level = '-';

        $stmt = $pdo->prepare("UPDATE rain_data SET station_name=?, location=?, date=?, time=?, rainfall=?, level=? WHERE id=?");
        $stmt->execute([
            $station_name,
            $location,
            $date,
            $time,
            $rainfall,
            $level,
            $id
        ]);
        header("Location: index.php?page=admin&subpage=rain&msg=updated");
        exit;
    } else {
        $message = "❌ ข้อมูลไม่ถูกต้อง กรุณาตรวจสอบอีกครั้ง";
    }
}

// ดึงข้อมูลสำหรับฟอร์มแก้ไข
$editData = null;
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM rain_data WHERE id = ?");
    $stmt->execute([$id]);
    $editData = $stmt->fetch();
    if (!$editData) {
        header("Location: index.php?page=admin&subpage=rain");
        exit;
    }
}

$itemsPerPage = 20;
$pageNum = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
$offset = ($pageNum - 1) * $itemsPerPage;

// เงื่อนไขการค้นหา
$search = $_GET['search'] ?? '';
$selectedDate = $_GET['date'] ?? '';

$sql = "SELECT * FROM rain_data WHERE 1";

if (!empty($search)) {
    $sql .= " AND (station_name LIKE :search OR location LIKE :search)";
}
if (!empty($selectedDate)) {
    $sql .= " AND date = :selectedDate";
}

$sql .= " ORDER BY rainfall DESC, date DESC LIMIT :limit OFFSET :offset";

// นับจำนวนทั้งหมด
$countSql = "SELECT COUNT(*) FROM rain_data WHERE 1";
if (!empty($search)) {
    $countSql .= " AND (station_name LIKE :search OR location LIKE :search)";
}
if (!empty($selectedDate)) {
    $countSql .= " AND date = :selectedDate";
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


$stmt = $pdo->prepare($sql);
if (!empty($search)) {
    $stmt->bindValue(':search', '%' . $search . '%');
}
if (!empty($selectedDate)) {
    $stmt->bindValue(':selectedDate', $selectedDate);
}

$stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();
$data = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h3 class="mb-3">📋 จัดการข้อมูลปริมาณฝน</h3>

    <?php if ($message): ?>
        <div class="alert alert-info alert-dismissible fade show text-center" role="alert">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($action === 'edit' && $editData): ?>
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                ✏️ แก้ไขข้อมูลปริมาณฝน - <?= htmlspecialchars($editData['station_name']) ?>
            </div>
            <div class="card-body">
                <form method="post" action="index.php?page=admin&subpage=rain&action=update" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($editData['id']) ?>">

                    <div class="mb-3">
                        <label for="station_name" class="form-label">ชื่อสถานี:</label>
                        <input type="text" class="form-control" id="station_name" name="station_name" required maxlength="100" value="<?= htmlspecialchars($editData['station_name']) ?>" autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">ที่ตั้ง:</label>
                        <input type="text" class="form-control" id="location" name="location" required maxlength="100" value="<?= htmlspecialchars($editData['location']) ?>">
                    </div>

                    <div class="mb-3">
                        <label>วันที่บันทึก:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                            <input type="text" name="date" class="form-control flatpickr" placeholder="เลือกวันที่..." value="<?= htmlspecialchars($editData['date']) ?>" required autocomplete="off">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="time" class="form-label">เวลา:</label>
                        <input type="time" class="form-control" id="time" name="time" required value="<?= htmlspecialchars($editData['time']) ?>">
                    </div>

                    <div class="mb-3">
                        <label for="rainfall" class="form-label">ค่าฝน (มม.):</label>
                        <input type="number" step="0.1" min="0" class="form-control" id="rainfall" name="rainfall" required value="<?= htmlspecialchars($editData['rainfall']) ?>">
                    </div>

                    <button type="submit" class="btn btn-success">💾 บันทึกการแก้ไข</button>
                    <a href="index.php?page=admin&subpage=rain" class="btn btn-secondary">↩ ยกเลิก</a>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <form method="get" class="d-flex flex-wrap justify-content-center align-items-center gap-2 mb-4" autocomplete="off">
        <input type="hidden" name="page" value="admin">
        <input type="hidden" name="subpage" value="rain">

        <input type="text" name="search" class="form-control" placeholder="🔍 ค้นหาชื่อสถานี / ที่ตั้ง" value="<?= htmlspecialchars($search) ?>" style="width: 200px;">

        <label for="date">เลือกวันที่:</label>
        <input type="text" name="date" id="date" value="<?= htmlspecialchars($selectedDate) ?>" class="form-control flatpickr" style="width: auto;" placeholder="เลือกวันที่" autocomplete="off" />

        <button type="submit" class="btn btn-primary">🔄 โหลดใหม่</button>

        <a href="index.php?page=admin&subpage=rain_add" class="btn btn-success">➕ เพิ่มข้อมูลใหม่</a>
    </form>

    <?php if (count($data) > 0): ?>
        <table class="table table-striped table-hover text-center align-middle shadow">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th><i class="bi bi-building"></i> ชื่อสถานี</th>
                    <th><i class="bi bi-geo-alt-fill"></i> ที่ตั้ง</th>
                    <th style="cursor:pointer;" onclick="sortTable(3)">
                        <i class="bi bi-calendar-date"></i> วันที่
                        <span id="date-sort" class="ms-1">⇅</span>
                    </th>
                    <th><i class="bi bi-clock"></i> เวลา</th>
                    <th style="cursor:pointer;" onclick="sortTable(5)">
                        <i class="bi bi-cloud-drizzle"></i> ค่าฝน (มม.)
                        <span id="rain-sort" class="ms-1">⇅</span>
                    </th>
                    <th><i class="bi bi-bar-chart-fill"></i> ระดับฝน</th>
                    <th><i class="bi bi-tools"></i> จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $i => $row): ?>
                    <tr>
                        <td><?= ($i + 1) + $offset ?></td>
                        <td class="fw-bold"><?= htmlspecialchars($row['station_name']) ?></td>
                        <td><?= htmlspecialchars($row['location']) ?></td>
                        <td><?= htmlspecialchars($row['date']) ?></td>
                        <td><?= htmlspecialchars($row['time']) ?></td>
                        <td><span class="text-primary fw-bold"><?= number_format($row['rainfall'], 1) ?></span></td>
                        <td>
                            <span class="badge
                            <?php
                            switch ($row['level']) {
                                case 'ฝนหนักมาก':
                                    echo 'bg-danger';
                                    break;
                                case 'ฝนหนัก':
                                    echo 'bg-warning text-dark';
                                    break;
                                case 'ฝนปานกลาง':
                                    echo 'bg-primary';
                                    break;
                                case 'ฝนเล็กน้อย':
                                    echo 'bg-info text-dark';
                                    break;
                                default:
                                    echo 'bg-secondary';
                            }
                            ?>">
                                <?= htmlspecialchars($row['level']) ?>
                            </span>
                        </td>
                        <td>
                            <a href="index.php?page=admin&subpage=rain&action=edit&id=<?= urlencode($row['id']) ?>" class="btn btn-sm btn-outline-primary me-1" title="แก้ไข">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form method="post" action="index.php?page=admin&subpage=rain&action=delete" style="display:inline;" onsubmit="return confirm('ลบข้อมูลนี้?');">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="ลบ">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if ($totalPages > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($pageNum > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=admin&subpage=rain&p=<?= $pageNum - 1 ?>&search=<?= urlencode($search) ?>&date=<?= urlencode($selectedDate) ?>">«</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i == $pageNum) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=admin&subpage=rain&p=<?= $i ?>&search=<?= urlencode($search) ?>&date=<?= urlencode($selectedDate) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($pageNum < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=admin&subpage=rain&p=<?= $pageNum + 1 ?>&search=<?= urlencode($search) ?>&date=<?= urlencode($selectedDate) ?>">»</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>

    <?php else: ?>
        <div class="alert alert-warning text-center">
            ไม่พบข้อมูลฝนในวันที่เลือก หรือไม่มีข้อมูลในระบบ
        </div>
    <?php endif; ?>
</div>

<!-- flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- ภาษาไทย -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>

<script>
    flatpickr(".flatpickr", {
        dateFormat: "Y-m-d",
        maxDate: "today",
        locale: "th"
    });

    let sortDirection = {
        3: 'desc',
        5: 'desc'
    };

    function sortTable(colIndex) {
        const table = document.querySelector("table");
        const rows = Array.from(table.querySelectorAll("tbody > tr"));
        const isDate = colIndex === 3;
        const isRain = colIndex === 5;

        sortDirection[colIndex] = sortDirection[colIndex] === 'asc' ? 'desc' : 'asc';
        const dir = sortDirection[colIndex];

        rows.sort((a, b) => {
            let valA = a.children[colIndex].textContent.trim();
            let valB = b.children[colIndex].textContent.trim();

            if (isDate) {
                valA = new Date(valA);
                valB = new Date(valB);
            } else if (isRain) {
                valA = parseFloat(valA);
                valB = parseFloat(valB);
            }

            return dir === 'asc' ? valA - valB : valB - valA;
        });

        const tbody = table.querySelector("tbody");
        rows.forEach(row => tbody.appendChild(row));

        document.getElementById("date-sort").textContent = colIndex === 3 ? (dir === 'asc' ? '🔼' : '🔽') : '⇅';
        document.getElementById("rain-sort").textContent = colIndex === 5 ? (dir === 'asc' ? '🔼' : '🔽') : '⇅';
    }
</script>