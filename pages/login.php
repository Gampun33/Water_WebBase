<?php
session_start();
require_once 'includes/db.php';

$msg = $_GET['msg'] ?? '';
if ($msg === 'session_expired') {
    echo '<div class="alert alert-warning">⏱️ Session หมดอายุ กรุณาเข้าสู่ระบบใหม่</div>';
} elseif ($msg === 'logged_in_elsewhere') {
    echo '<div class="alert alert-danger">🚫 บัญชีนี้ถูกใช้งานจากที่อื่น กรุณาเข้าสู่ระบบใหม่</div>';
}

$error = '';

if (isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // บันทึก session ธรรมดา ไม่ใช้ session_token แล้ว
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role'],
        ];
        $_SESSION['last_activity'] = time();

        header("Location: index.php?page=admin&subpage=admin_check");
        exit;
    } else {
        $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    }
}
?>

<style>
    .login-container {
        background: #f0f2f5;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-box {
        background: white;
        padding: 40px 30px;
        border-radius: 15px;
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
    }

    h1 {
        font-size: 24px;
        margin-bottom: 25px;
        text-align: center;
    }
</style>

<div class="login-container">
    <div class="login-box">
        <h1>เข้าสู่ระบบ</h1>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">ชื่อผู้ใช้</label>
                <input type="text" name="username" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">รหัสผ่าน</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100" name="login">เข้าสู่ระบบ</button>
        </form>
    </div>
</div>
