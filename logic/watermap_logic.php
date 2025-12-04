<?php
// ───────────────────────
// 🧩 เชื่อมต่อฐานข้อมูล
// ───────────────────────
// ตรวจสอบให้แน่ใจว่าพาธนี้ถูกต้องสัมพันธ์กับตำแหน่งของไฟล์ home_logic.php
require_once 'includes/db.php';

$baseURL = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

// 📅 รับวันที่จาก GET หรือใช้วันปัจจุบัน
// 📅 ดึงวันที่ล่าสุดที่ approved = 1 จากตาราง stations
// ดึงวันที่ approve ล่าสุดจากตาราง
$stmt = $pdo->query("
    SELECT MAX(DATE(record_date)) 
    FROM stations 
    WHERE is_approved = 0 AND record_date <= CURDATE()
");
$latestUnapprovedDate = $stmt->fetchColumn(); // เช่น '2025-07-02'


// ถ้าไม่ได้ส่ง ?date= มา ใช้วันที่ที่ approve ล่าสุด
$inputDate = $_GET['date'] ?? null;

if ($inputDate && preg_match('/^\d{4}-\d{2}-\d{2}$/', $inputDate)) {
    list($y, $m, $d) = explode('-', $inputDate);
    if (checkdate((int)$m, (int)$d, (int)$y)) {
        $selectedDate = $inputDate;
    } else {
        $selectedDate = $latestUnapprovedDate ?? date('Y-m-d');
    }
} else {
    $selectedDate = $latestUnapprovedDate ?? date('Y-m-d');
}



// 🗓️ แปลงวันที่เป็นรูปแบบไทย
function formatThaiDate($date)
{
    $thaiMonths = [
        "",
        "มกราคม",
        "กุมภาพันธ์",
        "มีนาคม",
        "เมษายน",
        "พฤษภาคม",
        "มิถุนายน",
        "กรกฎาคม",
        "สิงหาคม",
        "กันยายน",
        "ตุลาคม",
        "พฤศจิกายน",
        "ธันวาคม"
    ];
    $timestamp = strtotime($date);
    return date('j', $timestamp) . " " . $thaiMonths[(int)date('n', $timestamp)] . " " . (date('Y', $timestamp) + 543) . " เวลา 6.00 น.";
}

// 💠 จัดรูปแบบทศนิยมให้เหมาะสม
function formatFloatAtLeastTwoDecimals($value, $minDecimals = 2)
{
    $value = (float) $value;
    $raw = number_format($value, 10, '.', '');
    $trimmed = rtrim(rtrim($raw, '0'), '.');
    $parts = explode('.', $trimmed);
    if (count($parts) === 1 || strlen($parts[1]) < $minDecimals) {
        return number_format($value, $minDecimals, '.', '');
    } else {
        return $trimmed;
    }
}

function formatFloatAtLeastoneDecimals($value, $minDecimals = 1)
{
    $value = (float) $value;
    $raw = number_format($value, 10, '.', '');
    $trimmed = rtrim(rtrim($raw, '0'), '.');
    $parts = explode('.', $trimmed);
    if (count($parts) === 1 || strlen($parts[1]) < $minDecimals) {
        return number_format($value, $minDecimals, '.', '');
    } else {
        return $trimmed;
    }
}

// ───────────────────────
// 📌 ดึงข้อมูลสถานีตามวันที่
// ───────────────────────
$stmt = $pdo->prepare("SELECT * FROM stations WHERE DATE(record_date) = :date");
$stmt->execute(['date' => $selectedDate]);
$stations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 📊 กำหนดระดับน้ำ
$high = 80;
$medium = 50;
$low = 30;

// 🔢 นับจำนวนสถานีแต่ละระดับ
$stmtCount = $pdo->prepare("
    SELECT
        SUM(CASE WHEN capacity > :high THEN 1 ELSE 0 END) AS count_above_high,
        SUM(CASE WHEN capacity > :medium AND capacity <= :high THEN 1 ELSE 0 END) AS count_between_medium_high,
        SUM(CASE WHEN capacity > :low AND capacity <= :medium THEN 1 ELSE 0 END) AS count_between_low_medium,
        SUM(CASE WHEN capacity <= :low THEN 1 ELSE 0 END) AS count_below_low
    FROM stations
    WHERE DATE(record_date) = :date
");

$stmtCount->execute([
    ':high' => $high,
    ':medium' => $medium,
    ':low' => $low,
    ':date' => $selectedDate,
]);

$counts = $stmtCount->fetch(PDO::FETCH_ASSOC);

function getRainfallClass($value)
{
    if ($value >= 0.1 && $value < 0.5) return 'rainfall-SkyBlue';
    elseif ($value >= 0.5 && $value < 1) return 'rainfall-CornflowerBlue';
    elseif ($value >= 1 && $value < 2) return 'rainfall-SteelBlue';
    elseif ($value >= 2 && $value < 3) return 'rainfall-DodgerBlue';
    elseif ($value >= 3 && $value < 5) return 'rainfall-BlueRibbon';
    elseif ($value >= 5 && $value < 7) return 'rainfall-DarkBlue';
    elseif ($value >= 7 && $value < 10) return 'rainfall-PineGreen';
    elseif ($value >= 10 && $value < 15) return 'rainfall-LimeGreen';
    elseif ($value >= 15 && $value < 20) return 'rainfall-Green';
    elseif ($value >= 20 && $value < 25) return 'rainfall-LemonYellow';
    elseif ($value >= 25 && $value < 30) return 'rainfall-OrangeYellow';
    elseif ($value >= 30 && $value < 35) return 'rainfall-BurntOrange';
    elseif ($value >= 35 && $value < 40) return 'rainfall-Tangerine';
    elseif ($value >= 40 && $value < 45) return 'rainfall-Tan';
    elseif ($value >= 45 && $value < 50) return 'rainfall-RedOchre';
    elseif ($value >= 50 && $value < 60) return 'rainfall-Scarlet';
    elseif ($value >= 60 && $value < 70) return 'rainfall-DarkRed';
    elseif ($value >= 70 && $value < 80) return 'rainfall-Maroon';
    elseif ($value >= 80 && $value < 90) return 'rainfall-TyrianPurple';
    elseif ($value >= 90 && $value < 100) return 'rainfall-RoyalPurple';
    elseif ($value >= 100 && $value < 125) return 'rainfall-Amethyst';
    elseif ($value >= 125 && $value < 150) return 'rainfall-LavenderPurple';
    elseif ($value >= 150 && $value < 200) return 'rainfall-LightLavender';
    elseif ($value >= 200 && $value <= 300) return 'rainfall-Silver';
    else return 'rainfall-low';
}


// 📦 ประมวลผลฝนย้อนหลัง 24 และ 72 ชม.
$selectedTimestamp = strtotime($selectedDate . ' 23:59:59');
$startDate = date('Y-m-d H:i:s', $selectedTimestamp - 259200); // 72 ชม.
$endDate = date('Y-m-d H:i:s', $selectedTimestamp);

// ====== ดึงข้อมูล ======
$stmt = $pdo->prepare("
    SELECT station_name, location, rainfall, time, date 
    FROM rain_data 
    WHERE CONCAT(date, ' ', time) BETWEEN :start AND :end 
    ORDER BY location, date DESC, time DESC
");
$stmt->execute([':start' => $startDate, ':end' => $endDate]);
$allData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ====== คำนวณฝน 24h / 72h ======
$locationStmt = $pdo->query("SELECT DISTINCT location FROM rain_data");
$allLocations = $locationStmt->fetchAll(PDO::FETCH_COLUMN);

$rainByLocation = [];
$rain72h = [];

foreach ($allData as $row) {
    $timestamp = strtotime($row['date'] . ' ' . $row['time']);
    $diff = $selectedTimestamp - $timestamp;
    $loc = $row['location'];

    if ($diff >= 0 && $diff <= 86400) {
        if (!isset($rainByLocation[$loc]) || $row['rainfall'] > $rainByLocation[$loc]['rainfall']) {
            $rainByLocation[$loc] = $row;
        }
    }

    if ($diff >= 0 && $diff <= 259200) {
        if (!isset($rain72h[$loc])) $rain72h[$loc] = 0;
        $rain72h[$loc] += $row['rainfall'];
    }
}

// ====== สร้างข้อมูลแสดงผล ======
$displayData = [];
foreach ($allLocations as $loc) {
    if (isset($rainByLocation[$loc])) {
        $entry = $rainByLocation[$loc];
        $displayData[] = [
            'location' => $entry['location'],
            'rainfall_24h' => $entry['rainfall'],
            'rainfall_72h' => $rain72h[$loc] ?? 0
        ];
    } else {
        $displayData[] = [
            'location' => $loc,
            'rainfall_24h' => 0,
            'rainfall_72h' => $rain72h[$loc] ?? 0
        ];
    }
}

// ====== เรียง & กรอง 13 อำเภอไม่ซ้ำ ======
usort($displayData, fn($a, $b) => $b['rainfall_24h'] <=> $a['rainfall_24h']);

$uniqueAmphoeData = [];
$usedAmphoes = [];

foreach ($displayData as $entry) {
    if (preg_match('/อ\.([^\s]+)/u', $entry['location'], $matches)) {
        $amphoe = trim($matches[1]);
        if (!in_array($amphoe, $usedAmphoes)) {
            $usedAmphoes[] = $amphoe;
            $uniqueAmphoeData[] = $entry;
            if (count($uniqueAmphoeData) >= 13) break;
        }
    }
}
$displayData = $uniqueAmphoeData;

// ====== ตำแหน่งแสดงข้อมูล (บนภาพ) ======
$rainTablePositions = [
    ['top' => 195, 'left' => 30],
    ['top' => 220, 'left' => 30],
    ['top' => 245, 'left' => 30],
    ['top' => 267, 'left' => 30],
    ['top' => 292, 'left' => 30],
    ['top' => 315, 'left' => 30],
    ['top' => 338, 'left' => 30],
    ['top' => 362, 'left' => 30],
    ['top' => 387, 'left' => 30],
    ['top' => 410, 'left' => 30],
    ['top' => 433, 'left' => 30],
    ['top' => 457, 'left' => 30],
    ['top' => 480, 'left' => 30]
];

// 📌 ตำแหน่งสรุประดับน้ำ
$summaryPositions = [
    'count_above_high' => ['top' => 139, 'left' => 1490],
    'count_between_medium_high' => ['top' => 159, 'left' => 1490],
    'count_between_low_medium' => ['top' => 180, 'left' => 1490],
    'count_below_low' => ['top' => 200, 'left' => 1490],
];

function getWaterColor($percent)
{
    if ($percent <= 10) return 'brown';
    elseif ($percent <= 30) return 'yellow';
    elseif ($percent <= 70) return '#16d92a';
    elseif ($percent <= 100) return '#28c4ea';
    else return 'red';
}

// ดึงข้อมูลจากฐานข้อมูล
$sql = "SELECT * FROM water WHERE record_date = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$selectedDate]);
$allWaterData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// กำหนดตำแหน่ง x, y สำหรับแท็งก์น้ำบนแผนที่
// ค่าเหล่านี้จะถูกใช้เป็น top/left ใน CSS position: absolute
$selectedTankNames = ['W.10B', 'W.16A', 'W.17'];
$tankPositions = [
    ['x' => 57, 'y' => 565],
    ['x' => 162, 'y' => 565],
    ['x' => 262, 'y' => 565],
];

$tankLabelPositions = [
    'W.10B' => [
        'name_water' =>     ['top' => 641, 'left' => 72],
        'name_location' =>  ['top' => 654, 'left' => 65],
        'location' =>       ['top' => 667, 'left' => 50],
    ],
    'W.16A' => [
        'name_water' =>     ['top' => 641, 'left' => 175],
        'name_location' =>  ['top' => 654, 'left' => 176],
        'location' =>       ['top' => 667, 'left' => 158],
    ],
    'W.17' => [
        'name_water' =>     ['top' => 641, 'left' => 280],
        'name_location' =>  ['top' => 654, 'left' => 267],
        'location' =>       ['top' => 667, 'left' => 259],
    ],
];


$waterLevelPositions = [
    'W.10B' => ['top' => 620, 'left' => 121],   // ตัวอย่างตำแหน่ง
    'W.16A' => ['top' => 620, 'left' => 226],
    'W.17'  => ['top' => 620, 'left' => 326],
];

$tankDataByName = [];
foreach ($allWaterData as $row) {
    if (in_array($row['name_water'], $selectedTankNames)) {
        $tankDataByName[$row['name_water']] = $row;
    }
}

$customStationwater = [
    'W.25' => ['water' => ['top' => 162, 'left' => 1190],],
    'W.16A' => ['water' => ['top' => 222, 'left' => 1195],],
    'W.26' => ['water' => ['top' => 270, 'left' => 1195],],
    'W.10B' => ['water' => ['top' => 355, 'left' => 1197],],
    'W.21' => ['water' => ['top' => 415, 'left' => 1190],],
    'W.1C' => ['water' => ['top' => 427, 'left' => 1190],],
    'W.22' => ['water' => ['top' => 510, 'left' => 1207],],
    'W.23' => ['water' => ['top' => 802, 'left' => 1192],],
    'W.24' => ['water' => ['top' => 826, 'left' => 1192],],
    'W.4A' => ['water' => ['top' => 851, 'left' => 1192],],
    'W.6A' => ['water' => ['top' => 694, 'left' => 1025],],
    'W.3A' => ['water' => ['top' => 707, 'left' => 1015],],
    'W.18A' => ['water' => ['top' => 595, 'left' => 1015],],
    'W.5A' => ['water' => ['top' => 498, 'left' => 1021],],
    'TW.30' => ['water' => ['top' => 479, 'left' => 928],],
    'W.20' => ['water' => ['top' => 428, 'left' => 1035],],
    'TW.29' => ['water' => ['top' => 432, 'left' => 700],],
    'W.17' => ['water' => ['top' => 282, 'left' => 1005],],
    // เพิ่มตำแหน่งอื่นๆ ตามต้องการ
];



function formatDecimal1or2($num)
{
    $formatted = rtrim(rtrim(number_format($num, 2, '.', ''), '0'), '.');
    return (strpos($formatted, '.') === false) ? $formatted . '.0' : $formatted;
}

$customStationPositions = [

    'อ่างเก็บน้ำแม่ฟ้า' => [
        'name' => ['top' => 260, 'left' => 1155],
        'water' => ['top' => 260, 'left' => 1245],
    ],
    'อ่างเก็บน้ำแม่ล้อหัก' => [
        'name' => ['top' => 805, 'left' => 1030],
        'water' => ['top' => 815, 'left' => 1030],
    ],
    'อ่างเก็บน้ำแม่พริก' => [
        'name' => ['top' => 745, 'left' => 777],
        'water' => ['top' => 769, 'left' => 800],
    ],
    'อ่างเก็บน้ำแม่พริกผาวิ่งชู้' => [
        'name' => ['top' => 745, 'left' => 900],
        'water' => ['top' => 768, 'left' => 940],
    ],
    'อ่างเก็บน้ำแม่อาบ' => [
        'name' => ['top' => 730, 'left' => 910],
        'water' => ['top' => 730, 'left' => 1020],
    ],
    'อ่างเก็บน้ำแม่กึ๊ด' => [
        'name' => ['top' => 560, 'left' => 880],
        'water' => ['top' => 570, 'left' => 860],
    ],
    'อ่างเก็บน้ำแม่เลียงพัฒนา' => [
        'name' => ['top' => 595, 'left' => 775],
        'water' => ['top' => 607, 'left' => 745],
    ],
    'อ่างเก็บน้ำแม่ต๋ำตอนล่าง' => [
        'name' => ['top' => 655, 'left' => 980],
        'water' => ['top' => 665, 'left' => 980],
    ],
    'อ่างเก็บน้ำห้วยเกี๋ยง' => [
        'name' => ['top' => 520, 'left' => 880],
        'water' => ['top' => 520, 'left' => 980],
    ],
    'อ่างเก็บน้ำแม่ยาว' => [
        'name' => ['top' => 500, 'left' => 760],
        'water' => ['top' => 510, 'left' => 740],
    ],
    'อ่างเก็บน้ำแม่ปอน' => [
        'name' => ['top' => 467, 'left' => 758],
        'water' => ['top' => 478, 'left' => 760],
    ],
    'อ่างเก็บน้ำแม่ไพร' => [
        'name' => ['top' => 400, 'left' => 755],
        'water' => ['top' => 400, 'left' => 865],
    ],
    'อ่างเก็บน้ำแม่ต๋ำน้อย' => [
        'name' => ['top' => 375, 'left' => 775],
        'water' => ['top' => 375, 'left' => 892],
    ],
    'อ่างเก็บน้ำแม่เฟือง' => [
        'name' => ['top' => 350, 'left' => 778],
        'water' => ['top' => 352, 'left' => 895],
    ],
    'อ่างเก็บน้ำแม่นึง' => [
        'name' => ['top' => 305, 'left' => 790],
        'water' => ['top' => 305, 'left' => 895],
    ],
    'อ่างเก็บน้ำห้วยหลวงวังวัว' => [
        'name' => ['top' => 365, 'left' => 1030],
        'water' => ['top' => 355, 'left' => 1030],
    ],
    'อ่างเก็บน้ำแม่อาง' => [
        'name' => ['top' => 315, 'left' => 1405],
        'water' => ['top' => 325, 'left' => 1410],
    ],
    'อ่างเก็บน้ำแม่ทะ' => [
        'name' => ['top' => 470, 'left' => 1240],
        'water' => ['top' => 482, 'left' => 1205],
    ],
    'อ่างเก็บน้ำแม่ธิ' => [
        'name' => ['top' => 568, 'left' => 1220],
        'water' => ['top' => 580, 'left' => 1200],
    ],
    'อ่างเก็บน้ำแม่ไฮ' => [
        'name' => ['top' => 558, 'left' => 1160],
        'water' => ['top' => 532, 'left' => 1170],
    ],
    'อ่างเก็บน้ำแม่วะ' => [
        'name' => ['top' => 577, 'left' => 1325],
        'water' => ['top' => 590, 'left' => 1335],
    ],
    'อ่างเก็บน้ำแม่ทาน' => [
        'name' => ['top' => 630, 'left' => 1185],
        'water' => ['top' => 630, 'left' => 1300],
    ],
    'อ่างเก็บน้ำห้วยหลวง' => [
        'name' => ['top' => 650, 'left' => 1235],
        'water' => ['top' => 659, 'left' => 1320],
    ],
    'อ่างเก็บน้ำห้วยสมัย' => [
        'name' => ['top' => 690, 'left' => 1370],
        'water' => ['top' => 680, 'left' => 1415],
    ],
    'อ่างเก็บน้ำแม่ทก' => [
        'name' => ['top' => 700, 'left' => 1290],
        'water' => ['top' => 710, 'left' => 1310],
    ],
    'อ่างเก็บน้ำแม่เรียง' => [
        'name' => ['top' => 730, 'left' => 1220],
        'water' => ['top' => 730, 'left' => 1320],
    ],
    'อ่างเก็บน้ำห้วยแม่ค่อม' => [
        'name' => ['top' => 327, 'left' => 779],
        'water' => ['top' => 327, 'left' => 895],
    ],
    // 'อ่างเก็บน้ำห้วยแม่งอน' => [    //หาไม่เจอ
    //     'name' => ['top' => 200, 'left' => 1000],
    //     'water' => ['top' => 200, 'left' => 1100],
    // ],
    // 'อ่างเก็บน้ำแม่อ้อน2' => [ //หาไม่เจอ
    //     'name' => ['top' => 280, 'left' => 1000],
    //     'water' => ['top' => 280, 'left' => 1110],
    // ],
    'อ่างเก็บน้ำห้วยแม่สัน' => [
        'name' => ['top' => 445, 'left' => 830],
        'water' => ['top' => 455, 'left' => 760],
    ],
    // เพิ่มตามต้องการ
];

$customReserviorPositions = [
    'เขื่อนกิ่วคอหมา' => [
        'water' => ['top' => 200, 'left' => 1250],
    ],
    'เขื่อนกิ่วลม' => [
        'water' => ['top' => 320, 'left' => 1230],
    ],
    'เขื่อนแม่จาง' => [
        'water' => ['top' => 465, 'left' => 1480],
    ],
    'เขื่อนแม่ขาม' => [
        'water' => ['top' => 448, 'left' => 1367],
    ],
];

$stmt = $pdo->query("SELECT current_water, capacity, water_inuse FROM station_data LIMIT 9");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// เตรียมค่ารวม
$total = [
    'current_water' => 0,
    'capacity' => 0,
    'water_inuse' => 0
];

// รวมค่าทั้งหมด
foreach ($data as $row) {
    $total['current_water'] += $row['current_water'];
    $total['capacity'] += $row['capacity'];
    $total['water_inuse'] += $row['water_inuse'];
}

$stmt = $pdo->prepare("
    SELECT name_data, current_water, capacity, water_inuse
    FROM station_data
    WHERE name_data IN ('ขนาดใหญ่', 'ขนาดกลาง', 'ขนาดเล็ก')
      AND record_date = :record_date
    ORDER BY 
        FIELD(name_data, 'ขนาดใหญ่', 'ขนาดกลาง', 'ขนาดเล็ก')
");

$stmt->execute(['record_date' => $selectedDate]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$dataBoxPositions = [
     'ขนาดใหญ่' => ['top' => 778, 'left' => 167],
    'ขนาดกลาง' => ['top' => 798, 'left' => 167],
    'ขนาดเล็ก' => ['top' => 820, 'left' => 167],
];

$totalBoxPositions = [
    'capacity' => ['top' => 856, 'left' => 120],
    'current'  => ['top' => 856, 'left' => 171],
    'inuse'    => ['top' => 856, 'left' => 244],
];

$total_capacity = 0;
$total_current_water = 0;
$total_inuse = 0;

foreach ($rows as $row) {
    if (!isset($dataBoxPositions[$row['name_data']])) continue;
    $total_capacity += $row['capacity'];
    $total_current_water += $row['current_water'];
    $total_inuse += $row['water_inuse'];
}
$total_current_percent = ($total_capacity > 0) ? ($total_current_water / $total_capacity * 100) : 0;
$total_inuse_percent = ($total_capacity > 0) ? ($total_inuse / $total_capacity * 100) : 0;

$cap = floatval($row['capacity']);
$current = floatval($row['current_water']);
$inuse = floatval($row['water_inuse']);

$currentPercent = ($cap > 0) ? ($current / $cap * 100) : 0;
$inusePercent = ($cap > 0) ? ($inuse / $cap * 100) : 0;
