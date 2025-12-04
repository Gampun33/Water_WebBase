<?php
require_once __DIR__ . '/../../config/database.php';

class RainModel {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    // ดึงข้อมูลช่วงเวลา (ใช้ในหน้า Home คำนวณ 24/72 ชม.)
    public function getRainDataRange($startDate, $endDate) {
        $sql = "SELECT station_name, location, rainfall, time, date 
                FROM rain_data 
                WHERE CONCAT(date, ' ', time) BETWEEN :start AND :end 
                ORDER BY station_name, location, date DESC, time DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':start' => $startDate, ':end' => $endDate]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ดึงข้อมูลทั้งหมด (ใช้ในหน้า Admin List)
    public function getAll($search = '', $date = '', $page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT * FROM rain_data WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (station_name LIKE :search OR location LIKE :search)";
            $params[':search'] = "%$search%";
        }
        if (!empty($date)) {
            $sql .= " AND date = :date";
            $params[':date'] = $date;
        }

        $sql .= " ORDER BY date DESC, time DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => $val) $stmt->bindValue($key, $val);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // นับจำนวนทั้งหมด (Pagination)
    public function countAll($search = '', $date = '') {
        $sql = "SELECT COUNT(*) FROM rain_data WHERE 1=1";
        $params = [];
        if (!empty($search)) {
            $sql .= " AND (station_name LIKE :search OR location LIKE :search)";
            $params[':search'] = "%$search%";
        }
        if (!empty($date)) {
            $sql .= " AND date = :date";
            $params[':date'] = $date;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM rain_data WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data) {
        $sql = "INSERT INTO rain_data (station_name, location, date, time, rainfall, level) 
                VALUES (:station_name, :location, :date, :time, :rainfall, :level)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':station_name' => $data['station_name'],
            ':location' => $data['location'],
            ':date' => $data['date'],
            ':time' => $data['time'],
            ':rainfall' => $data['rainfall'],
            ':level' => $data['level'] ?? $this->calculateLevel($data['rainfall'])
        ]);
    }

    public function update($data) {
        $sql = "UPDATE rain_data SET station_name=?, location=?, date=?, time=?, rainfall=?, level=? WHERE id=?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['station_name'], $data['location'], $data['date'], $data['time'], 
            $data['rainfall'], $data['level'] ?? $this->calculateLevel($data['rainfall']), 
            $data['id']
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM rain_data WHERE id = ?");
        return $stmt->execute([$id]);
    }

    private function calculateLevel($rainfall) {
        if ($rainfall >= 90) return 'ฝนหนักมาก';
        if ($rainfall >= 35) return 'ฝนหนัก';
        if ($rainfall >= 10) return 'ฝนปานกลาง';
        if ($rainfall > 0) return 'ฝนเล็กน้อย';
        return 'ไม่มีฝน';
    }
}