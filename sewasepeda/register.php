<?php
session_start();
require_once 'config/config.php';
require_once 'models/User.php';

$error = '';
$success = '';

if ($_POST) {
    $userModel = new User($pdo);
    
    if ($_POST['password'] !== $_POST['confirm_password']) {
        $error = 'Password tidak cocok!';
    } else {
        try {
            $result = $userModel->register(
                $_POST['username'],
                $_POST['email'],
                $_POST['password'],
                $_POST['nama_lengkap'],
                $_POST['no_hp']
            );
            
            if ($result) {
                $success = 'Registrasi berhasil! Silakan login.';
            }
        } catch (Exception $e) {
            $error = 'Username atau email sudah digunakan!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sewa Sepeda</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">🚴 Sewa Sepeda</div>
            <div class="nav-links">
                <a href="index.php">Beranda</a>
                <a href="login.php">Login</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card" style="max-width: 550px; margin: 50px auto; text-align: center;">
            <div style="margin-bottom: 30px;">
                <h2 style="background: linear-gradient(45deg, #1e3c72, #4facfe); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 10px;">🎆 Daftar Akun Baru</h2>
                <p style="color: #666;">Bergabunglah dengan komunitas penyewa sepeda</p>
            </div>
            
            <?php if ($error): ?>
                <div style="background: linear-gradient(45deg, #ff416c, #ff4b2b); color: white; padding: 12px; border-radius: 10px; margin-bottom: 20px;"><?= $error ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div style="background: linear-gradient(45deg, #56ab2f, #a8e6cf); color: white; padding: 12px; border-radius: 10px; margin-bottom: 20px;"><?= $success ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" name="username" required>
                </div>
                
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label>Nama Lengkap:</label>
                    <input type="text" name="nama_lengkap" required>
                </div>
                
                <div class="form-group">
                    <label>No. HP:</label>
                    <input type="text" name="no_hp" required>
                </div>
                
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label>Konfirmasi Password:</label>
                    <input type="password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Daftar</button>
            </form>
            
            <p style="margin-top: 15px;">
                Sudah punya akun? <a href="login.php">Login di sini</a>
            </p>
        </div>
    </div>
</body>
</html>