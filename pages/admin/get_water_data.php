<?php
require_once '../../includes/db.php';
header('Content-Type: application/json');

$date = $_GET['date'] ?? date('Y-m-d');

// ตรวจสอบรูปแบบวันที่เบื้องต้น
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid date format']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT name, current_water, capacity 
        FROM stations 
        WHERE DATE(record_date) = ? 
        ORDER BY current_water DESC
    ");
    $stmt->execute([$date]);

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$data) {
        echo json_encode([]);
        exit;
    }

    foreach ($data as &$row) {
        $row['percent'] = ($row['capacity'] > 0) ? round(($row['current_water'] / $row['capacity']) * 100) : 0;
    }
    unset($row); // ป้องกัน reference leak

    echo json_encode($data);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
