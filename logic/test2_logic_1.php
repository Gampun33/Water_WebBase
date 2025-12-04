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
    WHERE is_approved = 1 AND record_date <= CURDATE()
");
$approvedDate = $stmt->fetchColumn(); // เช่น '2025-06-29'

// ถ้าไม่ได้ส่ง ?date= มา ใช้วันที่ที่ approve ล่าสุด
$inputDate = $_GET['date'] ?? null;

if ($inputDate && preg_match('/^\d{4}-\d{2}-\d{2}$/', $inputDate)) {
    list($y, $m, $d) = explode('-', $inputDate);
    if (checkdate((int)$m, (int)$d, (int)$y)) {
        $selectedDate = $inputDate;
    } else {
        $selectedDate = $approvedDate ?? date('Y-m-d');
    }
} else {
    $selectedDate = $approvedDate ?? date('Y-m-d');
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
    ['top' => 569, 'left' => 80],
    ['top' => 640, 'left' => 80],
    ['top' => 709, 'left' => 80],
    ['top' => 779, 'left' => 80],
    ['top' => 849, 'left' => 80],
    ['top' => 919, 'left' => 80],
    ['top' => 989, 'left' => 80],
    ['top' => 1059, 'left' => 80],
    ['top' => 1129, 'left' => 80],
    ['top' => 1199, 'left' => 80],
    ['top' => 1269, 'left' => 80],
    ['top' => 1339, 'left' => 80],
    ['top' => 1409, 'left' => 80]
];

// 📌 ตำแหน่งสรุประดับน้ำ
$summaryPositions = [
    'count_above_high' => ['top' => 385, 'left' => 4350],
    'count_between_medium_high' => ['top' => 445, 'left' => 4350],
    'count_between_low_medium' => ['top' => 502, 'left' => 4350],
    'count_below_low' => ['top' => 565, 'left' => 4350],
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
    ['x' => 200, 'y' => 1715],
    ['x' => 500, 'y' => 1715],
    ['x' => 800, 'y' => 1715],
];

$tankLabelPositions = [
    'W.10B' => [
        'name_water' =>     ['top' => 1880, 'left' => 250],
        'name_location' =>  ['top' => 1915, 'left' => 245],
        'location' =>       ['top' => 1940, 'left' => 230],
    ],
    'W.16A' => [
        'name_water' =>     ['top' => 1880, 'left' => 550],
        'name_location' =>  ['top' => 1915, 'left' => 565],
        'location' =>       ['top' => 1940, 'left' => 530],
    ],
    'W.17' => [
        'name_water' =>     ['top' => 1880, 'left' => 860],
        'name_location' =>  ['top' => 1915, 'left' => 840],
        'location' =>       ['top' => 1940, 'left' => 830],
    ],
];


$waterLevelPositions = [
    'W.10B' => ['top' => 1820, 'left' => 380],   // ตัวอย่างตำแหน่ง
    'W.16A' => ['top' => 1820, 'left' => 682],
    'W.17'  => ['top' => 1820, 'left' => 980],
];

$tankDataByName = [];
foreach ($allWaterData as $row) {
    if (in_array($row['name_water'], $selectedTankNames)) {
        $tankDataByName[$row['name_water']] = $row;
    }
}

$customStationwater = [
    'W.25' => ['water' => ['top' => 465, 'left' => 3360],],
    'W.16A' => ['water' => ['top' => 660, 'left' => 3285],],
    'W.26' => ['water' => ['top' => 780, 'left' => 3380],],
    'W.10B' => ['water' => ['top' => 1025, 'left' => 3380],],
    'W.21' => ['water' => ['top' => 1200, 'left' => 3350],],
    'W.1C' => ['water' => ['top' => 1230, 'left' => 3360],],
    'W.22' => ['water' => ['top' => 1480, 'left' => 3400],],
    'W.23' => ['water' => ['top' => 2335, 'left' => 3360],],
    'W.24' => ['water' => ['top' => 2405, 'left' => 3360],],
    'W.4A' => ['water' => ['top' => 2478, 'left' => 3360],],
    'W.6A' => ['water' => ['top' => 2020, 'left' => 2950],],
    'W.3A' => ['water' => ['top' => 2055, 'left' => 2920],],
    'W.18A' => ['water' => ['top' => 1720, 'left' => 2920],],
    'W.5A' => ['water' => ['top' => 1445, 'left' => 2940],],
    'TW.30' => ['water' => ['top' => 1445, 'left' => 2690],],
    'W.20' => ['water' => ['top' => 1250, 'left' => 3020],],
    'TW.29' => ['water' => ['top' => 1180, 'left' => 2000],],
    'W.17' => ['water' => ['top' => 810, 'left' => 2890],],
    // เพิ่มตำแหน่งอื่นๆ ตามต้องการ
];



function formatDecimal1or2($num)
{
    $formatted = rtrim(rtrim(number_format($num, 2, '.', ''), '0'), '.');
    return (strpos($formatted, '.') === false) ? $formatted . '.0' : $formatted;
}

$customStationPositions = [

    'อ่างเก็บน้ำแม่ฟ้า' => [
        'name' => ['top' => 730, 'left' => 3450],
        'water' => ['top' => 750, 'left' => 3450],
    ],
    'อ่างเก็บน้ำแม่ล้อหัก' => [
        'name' => ['top' => 2330, 'left' => 2990],
        'water' => ['top' => 2360, 'left' => 2920],
    ],
    'อ่างเก็บน้ำแม่พริก' => [
        'name' => ['top' => 2170, 'left' => 2200],
        'water' => ['top' => 2140, 'left' => 2200],
    ],
    'อ่างเก็บน้ำแม่พริกผาวิ่งชู้' => [
        'name' => ['top' => 2230, 'left' => 2680],
        'water' => ['top' => 2260, 'left' => 2680],
    ],
    'อ่างเก็บน้ำแม่อาบ' => [
        'name' => ['top' => 2110, 'left' => 2840],
        'water' => ['top' => 2130, 'left' => 2840],
    ],
    'อ่างเก็บน้ำแม่กึ๊ด' => [
        'name' => ['top' => 1630, 'left' => 2530],
        'water' => ['top' => 1660, 'left' => 2400],
    ],
    'อ่างเก็บน้ำแม่เลียงพัฒนา' => [
        'name' => ['top' => 1740, 'left' => 2190],
        'water' => ['top' => 1710, 'left' => 2190],
    ],
    'อ่างเก็บน้ำแม่ต๋ำตอนล่าง' => [
        'name' => ['top' => 1890, 'left' => 2390],
        'water' => ['top' => 1920, 'left' => 2350],
    ],
    'อ่างเก็บน้ำห้วยเกี๋ยง' => [
        'name' => ['top' => 1500, 'left' => 2450],
        'water' => ['top' => 1520, 'left' => 2450],
    ],
    'อ่างเก็บน้ำแม่ยาว' => [
        'name' => ['top' => 1410, 'left' => 2030],
        'water' => ['top' => 1440, 'left' => 2030],
    ],
    'อ่างเก็บน้ำแม่ปอน' => [
        'name' => ['top' => 1350, 'left' => 2120],
        'water' => ['top' => 1370, 'left' => 2050],
    ],
    'อ่างเก็บน้ำแม่ไพร' => [
        'name' => ['top' => 1160, 'left' => 2150],
        'water' => ['top' => 1160, 'left' => 2390],
    ],
    'อ่างเก็บน้ำแม่ต๋ำน้อย' => [
        'name' => ['top' => 1090, 'left' => 2180],
        'water' => ['top' => 1090, 'left' => 2460],
    ],
    'อ่างเก็บน้ำแม่เฟือง' => [
        'name' => ['top' => 1020, 'left' => 2200],
        'water' => ['top' => 1020, 'left' => 2460],
    ],
    'อ่างเก็บน้ำแม่นึง' => [
        'name' => ['top' => 880, 'left' => 2260],
        'water' => ['top' => 880, 'left' => 2480],
    ],
    'อ่างเก็บน้ำห้วยหลวงวังวัว' => [
        'name' => ['top' => 1040, 'left' => 2920],
        'water' => ['top' => 1060, 'left' => 2920],
    ],
    'อ่างเก็บน้ำแม่อาง' => [
        'name' => ['top' => 880, 'left' => 3990],
        'water' => ['top' => 900, 'left' => 4000],
    ],
    'อ่างเก็บน้ำแม่ทะ' => [
        'name' => ['top' => 1380, 'left' => 3560],
        'water' => ['top' => 1400, 'left' => 3410],
    ],
    'อ่างเก็บน้ำแม่ธิ' => [
        'name' => ['top' => 1650, 'left' => 3490],
        'water' => ['top' => 1670, 'left' => 3390],
    ],
    'อ่างเก็บน้ำแม่ไฮ' => [
        'name' => ['top' => 1610, 'left' => 3350],
        'water' => ['top' => 1550, 'left' => 3280],
    ],
    'อ่างเก็บน้ำแม่วะ' => [
        'name' => ['top' => 1700, 'left' => 3770],
        'water' => ['top' => 1670, 'left' => 3770],
    ],
    'อ่างเก็บน้ำแม่ทาน' => [
        'name' => ['top' => 1820, 'left' => 3400],
        'water' => ['top' => 1840, 'left' => 3380],
    ],
    'อ่างเก็บน้ำห้วยหลวง' => [
        'name' => ['top' => 1920, 'left' => 3680],
        'water' => ['top' => 1900, 'left' => 3680],
    ],
    'อ่างเก็บน้ำห้วยสมัย' => [
        'name' => ['top' => 2000, 'left' => 3900],
        'water' => ['top' => 2020, 'left' => 3900],
    ],
    'อ่างเก็บน้ำแม่ทก' => [
        'name' => ['top' => 2050, 'left' => 3680],
        'water' => ['top' => 2070, 'left' => 3650],
    ],
    'อ่างเก็บน้ำแม่เรียง' => [
        'name' => ['top' => 2080, 'left' => 3340],
        'water' => ['top' => 2100, 'left' => 3290],
    ],
    'อ่างเก็บน้ำห้วยแม่ค่อม' => [
        'name' => ['top' => 950, 'left' => 2180],
        'water' => ['top' => 950, 'left' => 2480],
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
        'name' => ['top' => 1285, 'left' => 2360],
        'water' => ['top' => 1310, 'left' => 2280],
    ],
    'อ่างเก็บน้ำแม่ทรายคำ' => [
        'name' => ['top' => 1130, 'left' => 2825],
        'water' => ['top' => 1190, 'left' => 2825],
    ],

    // เพิ่มตามต้องการ
];

$customStationPositions_1 = [
    'อ่างฯห้วยทราย' => [
        'name' => ['top' => 1380, 'left' => 3910],
        'water' => ['top' => 1400, 'left' => 3910],
    ],
];

$customReserviorPositions = [
    'เขื่อนกิ่วคอหมา' => [
        'water' => ['top' => 535, 'left' => 3545],
    ],
    'เขื่อนกิ่วลม' => [
        'water' => ['top' => 885, 'left' => 3510],
    ],
    'เขื่อนแม่จาง' => [
        'water' => ['top' => 1300, 'left' => 4220],
    ],
    'เขื่อนแม่ขาม' => [
        'water' => ['top' => 1265, 'left' => 3900],
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
    'ขนาดใหญ่' => ['top' => 2300, 'left' => 500],
    'ขนาดกลาง' => ['top' => 2370, 'left' => 500],
    'ขนาดเล็ก' => ['top' => 2440, 'left' => 500],
];

$totalBoxPositions = [
    'capacity' => ['top' => 2528, 'left' => 490],
    'current'  => ['top' => 2528, 'left' => 605],
    'inuse'    => ['top' => 2528, 'left' => 820],
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
