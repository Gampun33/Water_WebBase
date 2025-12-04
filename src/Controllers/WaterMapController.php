<?php
require_once __DIR__ . '/../Models/StationModel.php';
require_once __DIR__ . '/../Models/WaterModel.php';
require_once __DIR__ . '/../Models/StationDataModel.php'; // 1. ✅ เพิ่มบรรทัดนี้
require_once __DIR__ . '/../Helpers/AppHelper.php';

class WaterMapController {
    private $stationModel;
    private $waterModel;
    private $stationDataModel; // 2. ✅ เพิ่มตัวแปรนี้

    public function __construct() {
        $this->stationModel = new StationModel();
        $this->waterModel = new WaterModel();
        $this->stationDataModel = new StationDataModel(); // 3. ✅ สร้าง Object
    }

    public function index() {
        // ... (โค้ดส่วนตรวจสอบวันที่ เหมือนเดิม) ...
        $latestUnapproved = $this->stationModel->getLatestUnapprovedDate();
        $selectedDate = $_GET['date'] ?? $latestUnapproved ?? date('Y-m-d');

        // ... (โค้ดดึง stations, counts เหมือนเดิม) ...
        $stations = $this->stationModel->getStationsByDate($selectedDate);
        $counts = $this->stationModel->getSummaryCounts($selectedDate);
        
        // 4. ✅ เรียก getAllWaterData (ชื่อตรงกับที่แก้ในข้อ 1 แล้ว)
        $allWaterData = $this->waterModel->getAllWaterData($selectedDate);

        // 5. ✅ เปลี่ยนมาเรียก getSummaryData จาก stationDataModel แทน
        // (ใน Model ชื่อ getSummaryData แต่ถ้าน้องอยากให้ชื่อตรงกับ error เดิมคือ getDamSummary ก็แก้ชื่อใน Model ได้ครับ แต่ใช้แบบนี้สื่อความหมายถูกแล้ว)
        $damSummary = $this->stationDataModel->getSummaryData($selectedDate); 

        require __DIR__ . '/../../views/layouts/header.php';
        require __DIR__ . '/../../views/watermap.php';
        require __DIR__ . '/../../views/layouts/footer.php';
    }
}