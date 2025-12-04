<?php
require_once 'includes/db.php';

$date = $_GET['date'] ?? date('Y-m-d');
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    die('วันที่ไม่ถูกต้อง');
}

$types = ['rain', 'water', 'dam', 'waterTable'];

$expected = [
    'rain' => [
        'label' => 'ข้อมูลฝน',
        'expected' => ['อ.งาว', 'อ.เมือง', 'อ.เกาะคา', 'อ.เสริมงาม', 'อ.แม่ทะ', 'อ.สบปราบ', 'อ.ห้างฉัตร', 'อ.แม่เมาะ', 'อ.เถิน', 'อ.แม่พริก', 'อ.วังเหนือ', 'อ.แจ้ห่ม', 'อ.เมืองปาน'],
        'query' => "SELECT DISTINCT TRIM(location) FROM rain_data WHERE DATE(date) = ?",
        'match' => 'partial'
    ],
    'water' => [
        'label' => 'อ่างเก็บน้ำ,เขื่อน',
        'expected' => ['เขื่อนกิ่วคอหมา', 'เขื่อนกิ่วลม', 'เขื่อนแม่จาง', 'เขื่อนแม่ขาม',
            'อ่างฯห้วยทราย', 'อ่างเก็บน้ำแม่เมาะ', 'อ่างเก็บน้ำแม่นึง',
            'อ่างเก็บน้ำแม่ทะ', 'อ่างเก็บน้ำแม่ทรายคำ', 'อ่างเก็บน้ำห้วยหลวงวังวัว',
            'อ่างเก็บน้ำห้วยแม่ค่อม', 'อ่างเก็บน้ำแม่ต๋ำน้อย', 'อ่างเก็บน้ำแม่เฟือง',
            'อ่างเก็บน้ำแม่อาง', 'อ่างเก็บน้ำแม่วะ','อ่างเก็บน้ำแม่ไฮ', 'อ่างเก็บน้ำแม่ธิ',
            'อ่างเก็บน้ำห้วยเกี๋ยง', 'อ่างเก็บน้ำแม่ปอน', 'อ่างเก็บน้ำแม่ยาว',
            'อ่างเก็บน้ำแม่ไพร', 'อ่างเก็บน้ำห้วยแม่สัน', 'อ่างเก็บน้ำแม่ทาน',
            'อ่างเก็บน้ำห้วยสมัย', 'อ่างเก็บน้ำแม่ทก', 'อ่างเก็บน้ำแม่เรียง',
            'อ่างเก็บน้ำห้วยหลวง', 'อ่างเก็บน้ำแม่เลียงพัฒนา', 'อ่างเก็บน้ำแม่ต๋ำตอนล่าง',
            'อ่างเก็บน้ำแม่กึ๊ด', 'อ่างเก็บน้ำแม่อาบ', 'อ่างเก็บน้ำแม่พริก',
            'อ่างเก็บน้ำแม่ล้อหัก', 'อ่างเก็บน้ำแม่พริกผาวิ่งชู้', 'อ่างเก็บน้ำแม่ฟ้า'],
        'query' => "SELECT DISTINCT TRIM(name) FROM stations WHERE DATE(record_date) = ?",
        'match' => 'exact'
    ],
    'dam' => [
        'label' => 'ค่ารวมอ่างเก็บน้ำ',
        'expected' => ['ขนาดใหญ่', 'ขนาดกลาง', 'ขนาดเล็ก'],
        'query' => "SELECT DISTINCT TRIM(name_data) FROM station_data WHERE DATE(record_date) = ?",
        'match' => 'exact'
    ],
    'waterTable' => [
        'label' => 'สถานีน้ำท่า',
        'expected' => ['W.25', 'W.16A', 'W.26', 'W.10B', 'W.21', 'W.1C', 'W.22', 'W.24', 'W.4A', 'W.6A', 'W.3A', 'W.18A', 'W.5A', 'TW.30', 'W.20', 'TW.29', 'W.17'],
        'query' => "SELECT DISTINCT TRIM(name_water) FROM water WHERE DATE(record_date) = ?",
        'match' => 'exact'
    ]
];

function checkData(PDO $pdo, string $query, array $expected, string $date, string $match): array {
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute([$date]);
        $raw = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $expected = array_map('trim', $expected);
        $found = [];
        $fullMap = [];

        foreach ($expected as $exp) {
            foreach ($raw as $r) {
                $rNorm = trim($r);
                if (($match === 'partial' && mb_stripos($rNorm, $exp) !== false) || ($match === 'exact' && $rNorm === $exp)) {
                    $found[] = $exp;
                    $fullMap[$exp][] = $rNorm;
                }
            }
        }

        $missing = array_diff($expected, $found);
        $extra = array_filter($raw, function ($r) use ($expected, $match) {
            $rNorm = trim($r);
            foreach ($expected as $exp) {
                if (($match === 'partial' && mb_stripos($rNorm, $exp) !== false) || ($match === 'exact' && $rNorm === $exp)) {
                    return false;
                }
            }
            return true;
        });

        return compact('missing', 'extra', 'found', 'fullMap', 'raw');
    } catch (Exception $e) {
        return [
            'missing' => $expected,
            'extra' => [],
            'found' => [],
            'fullMap' => [],
            'raw' => [],
            'error' => $e->getMessage()
        ];
    }
}

function renderList(array $items, array $full = [], string $emptyText = '✅ ครบแล้ว', string $emptyClass = 'list-group-item-success', string $itemClass = 'list-group-item-danger') {
    if (empty($items)) {
        echo "<li class='list-group-item {$emptyClass}'>" . htmlspecialchars($emptyText) . "</li>";
        return;
    }
    foreach ($items as $item) {
        echo "<li class='list-group-item {$itemClass}'>" . htmlspecialchars($item);
        if (!empty($full[$item])) {
            echo '<ul class="list-unstyled ms-3">';
            foreach ($full[$item] as $sub) {
                echo '<li>- ' . htmlspecialchars($sub) . '</li>';
            }
            echo '</ul>';
        }
        echo '</li>';
    }
}

// ตรวจสอบข้อมูล
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

// การ Approve
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date'])) {
    $postDate = $_POST['date'];
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $postDate)) {
        die('วันที่ไม่ถูกต้อง');
    }

    try {
        $pdo->beginTransaction();

        $pdo->prepare("UPDATE rain_data SET is_approved = 1 WHERE DATE(date) = ?")->execute([$postDate]);
        $pdo->prepare("UPDATE water SET is_approved = 1 WHERE DATE(record_date) = ?")->execute([$postDate]);
        $pdo->prepare("UPDATE stations SET is_approved = 1 WHERE DATE(record_date) = ?")->execute([$postDate]);
        $pdo->prepare("UPDATE station_data SET is_approved = 1 WHERE DATE(record_date) = ?")->execute([$postDate]);

        $pdo->commit();
        header("Location: index.php?page=admin&subpage=admin_check&approved=1");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "เกิดข้อผิดพลาด: " . htmlspecialchars($e->getMessage());
    }
}
?>
