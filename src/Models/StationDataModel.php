<?php
require_once __DIR__ . '/../../config/database.php';

class StationDataModel {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    // ดึงข้อมูลสรุป 3 ขนาด (Home Use)
    public function getSummaryData($date) {
        $sql = "SELECT name_data, current_water, capacity, water_inuse
                FROM station_data
                WHERE name_data IN ('ขนาดใหญ่', 'ขนาดกลาง', 'ขนาดเล็ก')
                AND record_date = :date
                ORDER BY FIELD(name_data, 'ขนาดใหญ่', 'ขนาดกลาง', 'ขนาดเล็ก')";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['date' => $date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- Admin CRUD ---
    public function getAll($search = '', $date = '', $page = 1, $limit = 10, $sortBy = 'record_date', $sortOrder = 'DESC') {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT * FROM station_data WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND name_data LIKE :search";
            $params[':search'] = "%$search%";
        }
        if (!empty($date)) {
            $sql .= " AND DATE(record_date) = :date";
            $params[':date'] = $date;
        }

        $validSort = ['name_data', 'capacity', 'current_water', 'water_inuse', 'record_date'];
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
        $sql = "SELECT COUNT(*) FROM station_data WHERE 1=1";
        $params = [];
        if (!empty($search)) {
            $sql .= " AND name_data LIKE :search";
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
        $stmt = $this->pdo->prepare("SELECT * FROM station_data WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data) {
        $sql = "INSERT INTO station_data (current_water, capacity, water_inuse, name_data, record_date) 
                VALUES (:current_water, :capacity, :water_inuse, :name_data, :record_date)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':current_water' => $data['current_water'],
            ':capacity' => $data['capacity'],
            ':water_inuse' => $data['water_inuse'],
            ':name_data' => $data['name_data'],
            ':record_date' => $data['record_date']
        ]);
    }

    public function update($data) {
        $sql = "UPDATE station_data SET name_data=?, capacity=?, current_water=?, water_inuse=?, record_date=? WHERE id=?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['name_data'], $data['capacity'], $data['current_water'], 
            $data['water_inuse'], $data['record_date'], $data['id']
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM station_data WHERE id = ?");
        return $stmt->execute([$id]);
    }
}