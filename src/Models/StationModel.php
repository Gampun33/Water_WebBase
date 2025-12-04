<?php
require_once __DIR__ . '/../../config/database.php';

class StationModel {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    // ดึงวันที่อนุมัติล่าสุด
    public function getLatestApprovedDate() {
        $stmt = $this->pdo->query("SELECT MAX(DATE(record_date)) FROM stations WHERE is_approved = 1 AND record_date <= CURDATE()");
        return $stmt->fetchColumn();
    }

    // ดึงวันที่ยังไม่อนุมัติล่าสุด
    public function getLatestUnapprovedDate() {
        $stmt = $this->pdo->query("SELECT MAX(DATE(record_date)) FROM stations WHERE is_approved = 0 AND record_date <= CURDATE()");
        return $stmt->fetchColumn();
    }

    // ดึงข้อมูลสถานีตามวันที่ (Home/WaterMap)
    public function getStationsByDate($date) {
        $stmt = $this->pdo->prepare("SELECT * FROM stations WHERE DATE(record_date) = :date");
        $stmt->execute(['date' => $date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // นับจำนวนระดับน้ำ (High/Medium/Low)
    public function getSummaryCounts($date) {
        $sql = "SELECT
            SUM(CASE WHEN capacity > 80 THEN 1 ELSE 0 END) AS count_above_high,
            SUM(CASE WHEN capacity > 50 AND capacity <= 80 THEN 1 ELSE 0 END) AS count_between_medium_high,
            SUM(CASE WHEN capacity > 30 AND capacity <= 50 THEN 1 ELSE 0 END) AS count_between_low_medium,
            SUM(CASE WHEN capacity <= 30 THEN 1 ELSE 0 END) AS count_below_low
        FROM stations WHERE DATE(record_date) = :date
        AND name NOT IN ('เขื่อนกิ่วคอหมา', 'เขื่อนกิ่วลม', 'เขื่อนแม่จาง', 'เขื่อนแม่ขาม')"; // ตัวอย่างเงื่อนไขเดิม
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['date' => $date]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // --- ส่วน Admin ---
    public function getAll($search = '', $date = '', $page = 1, $limit = 10, $sortBy = 'record_date', $sortOrder = 'DESC') {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT * FROM stations WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND name LIKE :search";
            $params[':search'] = "%$search%";
        }
        if (!empty($date)) {
            $sql .= " AND DATE(record_date) = :date";
            $params[':date'] = $date;
        }

        $validSort = ['current_water', 'inflow', 'outflow', 'capacity', 'record_date'];
        if (!in_array($sortBy, $validSort)) $sortBy = 'record_date';
        
        $sql .= " ORDER BY $sortBy $sortOrder LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll($search = '', $date = '') {
        $sql = "SELECT COUNT(*) FROM stations WHERE 1=1";
        $params = [];
        if (!empty($search)) {
            $sql .= " AND name LIKE :search";
            $params[':search'] = "%$search%";
        }
        if (!empty($date)) {
            $sql .= " AND DATE(record_date) = :date";
            $params[':date'] = $date;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM stations WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data) {
        $sql = "INSERT INTO stations (name, current_water, inflow, outflow, capacity, record_date) 
                VALUES (:name, :current_water, :inflow, :outflow, :capacity, :record_date)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':current_water' => $data['current_water'],
            ':inflow' => $data['inflow'],
            ':outflow' => $data['outflow'],
            ':capacity' => $data['capacity'],
            ':record_date' => $data['record_date']
        ]);
    }

    public function update($data) {
        $sql = "UPDATE stations SET name=?, current_water=?, inflow=?, outflow=?, capacity=?, record_date=? WHERE id=?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['name'], $data['current_water'], $data['inflow'], 
            $data['outflow'], $data['capacity'], $data['record_date'], 
            $data['id']
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM stations WHERE id = ?");
        return $stmt->execute([$id]);
    }
}