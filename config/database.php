<?php
class Database {
    // เก็บตัวแปร connection ไว้แบบ static เพื่อให้เรียกใช้ได้ตลอดโดยไม่ต้องต่อใหม่หลายรอบ
    private static $pdo = null;

    public static function connect() {
        // ถ้าเคยต่อแล้ว ให้ส่งตัวเดิมกลับไปเลย (Singleton Pattern)
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        // ตรวจสอบว่าเป็น Local หรือ Server (อิงจากโค้ดเดิมของน้อง)
        if ($_SERVER['HTTP_HOST'] === 'localhost') {
            $host = 'localhost';
            $db = 'user_system';
            $user = 'root';
            $pass = '';
        } else {
            $host = 'sql105.infinityfree.com';
            $db = 'if0_39155551_user_system';
            $user = 'if0_39155551';
            $pass = 'yDnq2xr2TCeK1H';
        }

        try {
            $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
            self::$pdo = new PDO($dsn, $user, $pass);
            
            // ตั้งค่าให้แจ้งเตือน Error ทันที และดึงข้อมูลเป็น Array ชื่อ (Assoc)
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            return self::$pdo;
        } catch (PDOException $e) {
            die("❌ Database Connection Failed: " . $e->getMessage());
        }
    }
}
?>