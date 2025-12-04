<?php
require_once __DIR__ . '/../../config/database.php';

class WaterModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    // ดึงข้อมูลตามวันที่ (Home Use)
    public function getAllWaterData($date)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM water WHERE record_date = ?");
        $stmt->execute([$date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- Admin CRUD ---
    public function getAll($search = '', $date = '', $page = 1, $limit = 10, $sortBy = 'record_date', $sortOrder = 'DESC')
    {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT * FROM water WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (name_water LIKE :search OR location LIKE :search)";
            $params[':search'] = "%$search%";
        }
        if (!empty($date)) {
            $sql .= " AND DATE(record_date) = :date";
            $params[':date'] = $date;
        }

        $validSort = ['water_level', 'water_current', 'capacity', 'record_date'];
        if (!in_array($sortBy, $validSort)) $sortBy = 'record_date';

        $sql .= " ORDER BY $sortBy $sortOrder LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll($search = '', $date = '')
    {
        $sql = "SELECT COUNT(*) FROM water WHERE 1=1";
        $params = [];
        if (!empty($search)) {
            $sql .= " AND (name_water LIKE :search OR location LIKE :search)";
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

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM water WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $sql = "INSERT INTO water (name_water, name_location, location, water_level, water_current, capacity, water_level_current, record_date)
                VALUES (:name_water, :name_location, :location, :water_level, :water_current, :capacity, :water_level_current, :record_date)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':name_water' => $data['name_water'],
            ':name_location' => $data['name_location'],
            ':location' => $data['location'],
            ':water_level' => $data['water_level'],
            ':water_current' => $data['water_current'],
            ':capacity' => $data['capacity'],
            ':water_level_current' => $data['water_level_current'],
            ':record_date' => $data['record_date']
        ]);
    }

    public function update($data)
    {
        $sql = "UPDATE water SET name_water=?, location=?, water_level=?, water_current=?, capacity=?, record_date=? WHERE id=?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['name_water'],
            $data['location'],
            $data['water_level'],
            $data['water_current'],
            $data['capacity'],
            $data['record_date'],
            $data['id']
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM water WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
