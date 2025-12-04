<?php
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

$charset = 'utf8mb4';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
