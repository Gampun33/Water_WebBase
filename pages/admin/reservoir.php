<?php
// ต้องมีไฟล์ db.php สำหรับการเชื่อมต่อฐานข้อมูล PDO
require_once 'includes/db.php';

// รับค่าจาก URL
$search = $_GET['search'] ?? '';
$selectedDate = $_GET['date'] ?? '';
$action = $_GET['action'] ?? '';
$editId = ($action === 'edit') ? ($_GET['id'] ?? null) : null;
$deleteId = ($action === 'delete') ? ($_GET['id'] ?? null) : null;

$message = "";

// ข้อความแจ้งเตือน
if (isset($_GET['msg'])) {
  if ($_GET['msg'] === 'deleted') {
    $message = "✅ ลบข้อมูลสำเร็จแล้ว";
  } elseif ($_GET['msg'] === 'updated') {
    $message = "✅ อัปเดตข้อมูลสำเร็จแล้ว";
  }
}

// ลบข้อมูล
if ($deleteId) {
  $stmt = $pdo->prepare("DELETE FROM stations WHERE id = ?");
  if ($stmt->execute([$deleteId])) {
    // คงค่า search, date, pagination, sort ไว้เมื่อ redirect หลังจากลบ
    header("Location: index.php?page=admin&subpage=reservoir&search=" . urlencode($search) . "&date=" . urlencode($selectedDate) . "&msg=deleted&p=" . urlencode($_GET['p'] ?? 1) . "&sortBy=" . urlencode($_GET['sortBy'] ?? 'record_date') . "&sortOrder=" . urlencode($_GET['sortOrder'] ?? 'DESC'));
    exit;
  } else {
    $message = "❌ ลบข้อมูลไม่สำเร็จ";
  }
}

// อัปเดตข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
  $sql = "UPDATE stations SET
            name = :name,
            current_water = :current_water,
            inflow = :inflow,
            outflow = :outflow,
            capacity = :capacity,
            record_date = :record_date
            WHERE id = :id";
  $stmt = $pdo->prepare($sql);
  if ($stmt->execute([
    ':name' => $_POST['name'],
    ':current_water' => $_POST['current_water'],
    ':inflow' => $_POST['inflow'],
    ':outflow' => $_POST['outflow'],
    ':capacity' => $_POST['capacity'],
    ':record_date' => $_POST['record_date'],
    ':id' => $_POST['update_id']
  ])) {
    // คงค่า search, date, pagination, sort ไว้เมื่อ redirect หลังจากอัปเดต
    header("Location: index.php?page=admin&subpage=reservoir&search=" . urlencode($search) . "&date=" . urlencode($selectedDate) . "&msg=updated&p=" . urlencode($_GET['p'] ?? 1) . "&sortBy=" . urlencode($_GET['sortBy'] ?? 'record_date') . "&sortOrder=" . urlencode($_GET['sortOrder'] ?? 'DESC'));
    exit;
  } else {
    $message = "❌ อัปเดตข้อมูลไม่สำเร็จ";
  }
}

// ดึงข้อมูลสำหรับฟอร์มแก้ไข
$editData = null;
if ($editId) {
  $stmt = $pdo->prepare("SELECT * FROM stations WHERE id = ?");
  $stmt->execute([$editId]);
  $editData = $stmt->fetch(PDO::FETCH_ASSOC);
}

// การเรียงลำดับ
$sortBy = $_GET['sortBy'] ?? 'record_date';
$sortOrder = $_GET['sortOrder'] ?? 'DESC';

$allowedSortBy = ['current_water', 'inflow', 'outflow', 'capacity', 'record_date'];
$allowedSortOrder = ['ASC', 'DESC'];

if (!in_array($sortBy, $allowedSortBy)) $sortBy = 'record_date';
if (!in_array($sortOrder, $allowedSortOrder)) $sortOrder = 'DESC';

// --- Pagination Logic ---
$itemsPerPage = 10; // จำนวนรายการต่อหน้า
$pageNum = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
$offset = ($pageNum - 1) * $itemsPerPage;

// SQL สำหรับนับจำนวนรายการทั้งหมด (สำหรับ Pagination)
$countSql = "SELECT COUNT(*) FROM stations WHERE 1=1";
$countParams = [];
if (!empty($search)) {
  $countSql .= " AND name LIKE :search";
  $countParams[':search'] = '%' . $search . '%';
}
if (!empty($selectedDate)) {
  $countSql .= " AND record_date = :date";
  $countParams[':date'] = $selectedDate;
}
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($countParams);
$totalItems = $countStmt->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);

// SQL สำหรับดึงข้อมูลในหน้าปัจจุบัน
$sql = "SELECT * FROM stations WHERE 1=1";
$params = [];
if (!empty($search)) {
  $sql .= " AND name LIKE :search";
  $params[':search'] = '%' . $search . '%';
}
if (!empty($selectedDate)) {
  $sql .= " AND record_date = :date";
  $params[':date'] = $selectedDate;
}
$sql .= " ORDER BY " . $sortBy . " " . $sortOrder;
$sql .= " LIMIT :limit OFFSET :offset"; // เพิ่ม LIMIT และ OFFSET

$stmt = $pdo->prepare($sql);
// Bind parameter สำหรับการค้นหาและวันที่
if (!empty($search)) {
  $stmt->bindValue(':search', '%' . $search . '%');
}
if (!empty($selectedDate)) {
  $stmt->bindValue(':date', $selectedDate);
}
// Bind parameter สำหรับ LIMIT และ OFFSET
$stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();
$stations = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<style>
  /* ซ่อนลูกศรเริ่มต้นและแสดงเมื่อ hover หรือ active */
  th.sortable span {
    font-size: 0.9em;
    user-select: none;
    opacity: 0;
    /* ซ่อนเริ่มต้น */
  }

  /* แสดงลูกศรเมื่อเมาส์ชี้ */
  th.sortable span {
    font-size: 0.9em;
    user-select: none;
    /* แสดงลูกศร ⇅ เป็นค่าเริ่มต้นตลอด */
    opacity: 1;
    color: white;
    /* สีเทาอ่อนสำหรับ ⇅ */
  }

  th.sortable.active-sort span {
    color: #000;
    /* สีเข้มสำหรับ 🔼 หรือ 🔽 */
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
  <h3 class="mb-3">📊 ข้อมูลปริมาณน้ำในอ่างเก็บน้ำ</h3>

  <?php if (!empty($message)) : ?>
    <div class="alert alert-info alert-dismissible fade show text-center" role="alert">
      <?= htmlspecialchars($message) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <form method="get" class="d-flex flex-wrap justify-content-center align-items-center gap-2 mb-4">
    <input type="hidden" name="page" value="admin">
    <input type="hidden" name="subpage" value="reservoir">
    <input type="hidden" name="sortBy" value="<?= htmlspecialchars($sortBy) ?>">
    <input type="hidden" name="sortOrder" value="<?= htmlspecialchars($sortOrder) ?>">


    <input type="text" name="search" class="form-control" placeholder="🔍 ค้นหาชื่อสถานี" value="<?= htmlspecialchars($search) ?>" style="width: 200px;">

    <label for="date">เลือกวันที่:</label>
    <input type="text" name="date" id="date" value="<?= htmlspecialchars($selectedDate) ?>" class="form-control flatpickr" style="width: auto;" placeholder="เลือกวันที่" autocomplete="off" />

    <button type="submit" class="btn btn-primary">🔄 โหลดใหม่</button>
    <a href="index.php?page=admin&subpage=reservoir_add" class="btn btn-success">➕ เพิ่มข้อมูลใหม่</a>
  </form>

  <?php if ($editData) : ?>
    <div class="card p-4 mb-4">
      <h5>✏ แก้ไขข้อมูล: <?= htmlspecialchars($editData['name']) ?></h5>
      <form method="POST" action="index.php?page=admin&subpage=reservoir&action=edit&id=<?= $editId ?>&search=<?= urlencode($search) ?>&date=<?= urlencode($selectedDate) ?>&p=<?= urlencode($pageNum) ?>&sortBy=<?= urlencode($sortBy) ?>&sortOrder=<?= urlencode($sortOrder) ?>">
        <input type="hidden" name="update_id" value="<?= $editData['id'] ?>">

        <div class="row mb-3">
          <div class="col-md-6">
            <label>ชื่ออ่าง:</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($editData['name']) ?>" required>
          </div>
          <div class="col-md-6">
            <label>วันที่บันทึก:</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
              <input type="text" name="record_date" class="form-control flatpickr" placeholder="เลือกวันที่..." value="<?= htmlspecialchars($editData['record_date']) ?>" required>
            </div>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-3">
            <label>ปริมาณน้ำ (มม.)</label>
            <input type="number" name="current_water" class="form-control" step="0.0001" value="<?= $editData['current_water'] ?>">
          </div>
          <div class="col-md-3">
            <label>น้ำเข้า</label>
            <input type="number" name="inflow" class="form-control" step="0.0001" value="<?= $editData['inflow'] ?>">
          </div>
          <div class="col-md-3">
            <label>น้ำออก</label>
            <input type="number" name="outflow" class="form-control" step="0.0001" value="<?= $editData['outflow'] ?>">
          </div>
          <div class="col-md-3">
            <label>ความจุ</label>
            <input type="number" name="capacity" class="form-control" step="0.0001" value="<?= $editData['capacity'] ?>">
          </div>
        </div>

        <div class="col-12">
          <button type="submit" class="btn btn-success">💾 บันทึกการแก้ไข</button>
          <a href="index.php?page=admin&subpage=reservoir&search=<?= urlencode($search) ?>&date=<?= urlencode($selectedDate) ?>&p=<?= urlencode($pageNum) ?>&sortBy=<?= urlencode($sortBy) ?>&sortOrder=<?= urlencode($sortOrder) ?>" class="btn btn-secondary">↩ ยกเลิก</a>
        </div>
      </form>
    </div>
  <?php endif; ?>

  <?php if (!empty($stations)) : ?>
    <div class="table-responsive">
      <table class="table table-striped table-hover text-center align-middle shadow">
        <thead class="table-dark text-center">
          <tr>
            <th>#</th>
            <th>ชื่ออ่างเก็บน้ำ</th>
            <th class="sortable" data-column="current_water">ปริมาณน้ำ (มม.) <span id="current_water-sort"></span></th>
            <th class="sortable" data-column="inflow">น้ำเข้า <span id="inflow-sort"></span></th>
            <th class="sortable" data-column="outflow">น้ำออก <span id="outflow-sort"></span></th>
            <th class="sortable" data-column="capacity">ความจุ <span id="capacity-sort"></span></th>
            <th class="sortable" data-column="record_date">วันที่บันทึก <span id="record_date-sort"></span></th>
            <th>การจัดการ</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($stations as $index => $station) : ?>
            <tr>
              <td><?= ($index + 1) + $offset ?></td>
              <td class="fw-bold"><?= htmlspecialchars($station['name']) ?></td>
              <td><?= number_format($station['current_water'] ?? 0, 2) ?></td>
              <td><?= number_format($station['inflow'] ?? 0, 2) ?></td>
              <td><?= number_format($station['outflow'] ?? 0, 2) ?></td>
              <td><?= number_format($station['capacity'] ?? 0, 2) ?></td>
              <td><?= htmlspecialchars(date('d/m/Y', strtotime($station['record_date']))) ?></td>
              <td>
                <a href="index.php?page=admin&subpage=reservoir&action=edit&id=<?= urlencode($station['id']) ?>&search=<?= urlencode($search) ?>&date=<?= urlencode($selectedDate) ?>&p=<?= urlencode($pageNum) ?>&sortBy=<?= urlencode($sortBy) ?>&sortOrder=<?= urlencode($sortOrder) ?>" class="btn btn-sm btn-outline-primary me-1" title="แก้ไข">
                  <i class="bi bi-pencil-square"></i>
                </a>
                <a href="index.php?page=admin&subpage=reservoir&action=delete&id=<?= urlencode($station['id']) ?>&search=<?= urlencode($search) ?>&date=<?= urlencode($selectedDate) ?>&p=<?= urlencode($pageNum) ?>&sortBy=<?= urlencode($sortBy) ?>&sortOrder=<?= urlencode($sortOrder) ?>" onclick="return confirm('คุณต้องการลบข้อมูลสถานีน้ำนี้หรือไม่?');" class="btn btn-sm btn-outline-danger" title="ลบ">
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
              <a class="page-link" href="?page=admin&subpage=reservoir&p=<?= $pageNum - 1 ?>&search=<?= urlencode($search) ?>&date=<?= urlencode($selectedDate) ?>&sortBy=<?= urlencode($sortBy) ?>&sortOrder=<?= urlencode($sortOrder) ?>">«</a>
            </li>
          <?php endif; ?>

          <?php
          // แสดง pagination link รอบๆ หน้าปัจจุบัน
          $startPage = max(1, $pageNum - 2);
          $endPage = min($totalPages, $pageNum + 2);

          if ($startPage > 1) {
            echo '<li class="page-item"><a class="page-link" href="?page=admin&subpage=reservoir&p=1&search=' . urlencode($search) . '&date=' . urlencode($selectedDate) . '&sortBy=' . urlencode($sortBy) . '&sortOrder=' . urlencode($sortOrder) . '">1</a></li>';
            if ($startPage > 2) {
              echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
          }

          for ($i = $startPage; $i <= $endPage; $i++) : ?>
            <li class="page-item <?= ($i == $pageNum) ? 'active' : '' ?>">
              <a class="page-link" href="?page=admin&subpage=reservoir&p=<?= $i ?>&search=<?= urlencode($search) ?>&date=<?= urlencode($selectedDate) ?>&sortBy=<?= urlencode($sortBy) ?>&sortOrder=<?= urlencode($sortOrder) ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>

          <?php
          if ($endPage < $totalPages) {
            if ($endPage < $totalPages - 1) {
              echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            echo '<li class="page-item"><a class="page-link" href="?page=admin&subpage=reservoir&p=' . $totalPages . '&search=' . urlencode($search) . '&date=' . urlencode($selectedDate) . '&sortBy=' . urlencode($sortBy) . '&sortOrder=' . urlencode($sortOrder) . '">' . $totalPages . '</a></li>';
          }
          ?>

          <?php if ($pageNum < $totalPages) : ?>
            <li class="page-item">
              <a class="page-link" href="?page=admin&subpage=reservoir&p=<?= $pageNum + 1 ?>&search=<?= urlencode($search) ?>&date=<?= urlencode($selectedDate) ?>&sortBy=<?= urlencode($sortBy) ?>&sortOrder=<?= urlencode($sortOrder) ?>">»</a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
    <?php endif; ?>

  <?php else : ?>
    <div class="alert alert-warning text-center">ไม่พบข้อมูลที่ตรงกับเงื่อนไข</div>
  <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    flatpickr(".flatpickr", {
      dateFormat: "Y-m-d",
      maxDate: "today",
      locale: "th"
    });

    function sortTable(column) {
      const url = new URL(window.location.href);
      const currentSortBy = url.searchParams.get('sortBy') || '';
      const currentSortOrder = url.searchParams.get('sortOrder') || 'ASC'; // Default ASC for initial sort

      let newSortOrder = 'ASC';
      if (column === currentSortBy) {
        newSortOrder = currentSortOrder === 'ASC' ? 'DESC' : 'ASC';
      }

      url.searchParams.set('sortBy', column);
      url.searchParams.set('sortOrder', newSortOrder);
      url.searchParams.set('p', 1); // Reset to first page on sort

      window.location.href = url.toString();
    }

    // เริ่มด้วยตั้งลูกศร ⇅ ให้ทุกหัวคอลัมน์
    document.querySelectorAll('th.sortable span').forEach(span => {
      span.textContent = '⇅';
      span.closest('th.sortable').classList.remove('active-sort');
    });

    // หาคอลัมน์ที่กำลังเรียงลำดับ
    const currentSortBy = '<?= htmlspecialchars($sortBy) ?>';
    const currentSortOrder = '<?= htmlspecialchars($sortOrder) ?>';

    const sortSpan = document.getElementById(`${currentSortBy}-sort`);
    if (sortSpan) {
      sortSpan.textContent = currentSortOrder === 'ASC' ? '🔼' : '🔽';
      sortSpan.closest('th.sortable').classList.add('active-sort');
    }

    // ผูก event click สำหรับเรียงลำดับเหมือนเดิม
    document.querySelectorAll('th.sortable').forEach(th => {
      th.addEventListener('click', () => {
        const column = th.getAttribute('data-column');
        if (column) {
          sortTable(column);
        }
      });
    });
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>