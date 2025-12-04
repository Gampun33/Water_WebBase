<?php
require_once __DIR__ . '/../Models/StationModel.php';
require_once __DIR__ . '/../Models/RainModel.php';
require_once __DIR__ . '/../Helpers/AppHelper.php';

class HomeController {
    private $stationModel;
    private $rainModel;

    public function __construct() {
        $this->stationModel = new StationModel();
        $this->rainModel = new RainModel();
    }

    public function index() {
        // 1. รับค่าวันที่ (ถ้าไม่มีใช้วันที่อนุมัติล่าสุด หรือ วันปัจจุบัน)
        $approvedDate = $this->stationModel->getLatestApprovedDate();
        $selectedDate = $_GET['date'] ?? $approvedDate ?? date('Y-m-d');

        // 2. ดึงข้อมูลสถานีและระดับน้ำ
        $stations = $this->stationModel->getStationsByDate($selectedDate);
        $counts = $this->stationModel->getSummaryCounts($selectedDate); // High, Medium, Low counts

        // 3. Logic คำนวณฝน 24ชม. และ 72ชม. (ย้ายมาจาก home_logic.php)
        $selectedTimestamp = strtotime($selectedDate . ' 23:59:59');
        $startDate = date('Y-m-d H:i:s', $selectedTimestamp - 259200); // ย้อนหลัง 72 ชม.
        
        $allRainData = $this->rainModel->getRainDataRange($startDate, $selectedDate . ' 23:59:59');

        // --- เริ่มส่วนประมวลผลข้อมูลฝน (Refactor จาก Code เดิม) ---
        $rainByLocation24h = [];
        $latestRain72hByStation = [];

        foreach ($allRainData as $row) {
            $timestamp = strtotime($row['date'] . ' ' . $row['time']);
            $diff = $selectedTimestamp - $timestamp;
            $loc = $row['location'];
            $stationKey = $row['station_name'] . '|' . $loc;
            $rainfall = floatval($row['rainfall']);

            // หาค่าสูงสุด 24 ชม.
            if ($diff >= 0 && $diff <= 86400) {
                if (!isset($rainByLocation24h[$loc]) || $rainfall > $rainByLocation24h[$loc]['rainfall']) {
                    $rainByLocation24h[$loc] = ['location' => $loc, 'rainfall' => $rainfall];
                }
            }
            // หาค่าล่าสุดสำหรับ 72 ชม.
            if ($diff >= 0 && $diff <= 259200) {
                if (!isset($latestRain72hByStation[$stationKey])) {
                    $latestRain72hByStation[$stationKey] = ['rainfall' => $rainfall, 'location' => $loc];
                }
            }
        }

        // รวมยอดตามอำเภอ
        $rainfall72hByAmphoe = [];
        foreach ($latestRain72hByStation as $data) {
            $amphoe = AppHelper::extractAmphoe($data['location']);
            if (!isset($rainfall72hByAmphoe[$amphoe])) $rainfall72hByAmphoe[$amphoe] = 0;
            $rainfall72hByAmphoe[$amphoe] += $data['rainfall'];
        }

        $displayData = [];
        foreach ($rainByLocation24h as $loc => $data24h) {
            $amphoe = AppHelper::extractAmphoe($loc);
            $displayData[] = [
                'location' => $loc,
                'rainfall_24h' => $data24h['rainfall'],
                'rainfall_72h' => $rainfall72hByAmphoe[$amphoe] ?? 0
            ];
        }

        // เรียงลำดับและตัดมาแค่ 13 อันดับแรก
        usort($displayData, fn($a, $b) => $b['rainfall_24h'] <=> $a['rainfall_24h']);
        $displayData = array_slice($displayData, 0, 13);
        // --- จบส่วนประมวลผล ---

        // 4. ส่งข้อมูลไปที่ View
        // (สามารถใช้ compact('stations', 'counts', ...) ได้ถ้ารู้จัก)
        require __DIR__ . '/../../views/layouts/header.php';
        require __DIR__ . '/../../views/home.php';
        require __DIR__ . '/../../views/layouts/footer.php';
    }
}