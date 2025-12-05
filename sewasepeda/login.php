<?php
session_start();
require_once 'config/config.php';
require_once 'models/User.php';

$error = '';

if ($_POST) {
    $userModel = new User($pdo);
    $user = $userModel->login($_POST['username'], $_POST['password']);
    
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        if ($user['role'] === 'admin') {
            header('Location: admin/dashboard.php');
        } else {
            header('Location: dashboard.php');
        }
        exit;
    } else {
        $error = 'Username atau password salah!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sewa Sepeda</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">🚴 Sewa Sepeda</div>
            <div class="nav-links">
                <a href="index.php">Beranda</a>
                <a href="register.php">Register</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card" style="max-width: 450px; margin: 50px auto; text-align: center;">
            <div style="margin-bottom: 30px;">
                <h2 style="background: linear-gradient(45deg, #1e3c72, #4facfe); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 10px;">🔑 Masuk ke Akun</h2>
                <p style="color: #666;">Silakan masuk untuk melanjutkan</p>
            </div>
            
            <?php if ($error): ?>
                <div style="background: linear-gradient(45deg, #ff416c, #ff4b2b); color: white; padding: 12px; border-radius: 10px; margin-bottom: 20px;"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Username atau Email:</label>
                    <input type="text" name="username" required>
                </div>
                
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
            
            <p style="margin-top: 15px;">
                Belum punya akun? <a href="register.php">Daftar di sini</a>
            </p>
        </div>
    </div>
</body>
</html>