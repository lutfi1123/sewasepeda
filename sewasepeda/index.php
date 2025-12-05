<?php
session_start();
require_once 'config/config.php';
require_once 'models/Sepeda.php';

$sepedaModel = new Sepeda($pdo);
$sepedaList = $sepedaModel->getAllSepeda();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sewa Sepeda Online</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">🚴 Sewa Sepeda</div>
            <div class="nav-links">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="dashboard.php">Dashboard</a>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="hero">
            <h1>🚴‍♂️ Sewa Sepeda Online</h1>
            <p>Jelajahi kota dengan sepeda berkualitas tinggi. Pengalaman sewa yang mudah, aman, dan terpercaya untuk petualangan tak terlupakan!</p>
        </div>

        <div class="card">
            <h2 style="text-align: center; margin-bottom: 10px; color: #333;">✨ Sepeda Premium Tersedia</h2>
            <p style="text-align: center; color: #666;">Pilih sepeda favorit Anda dan mulai petualangan sekarang juga</p>
        </div>
        <div class="sepeda-grid">
            <?php foreach ($sepedaList as $sepeda): ?>
                <div class="sepeda-card">
                    <div class="sepeda-info">
                        <h3><?= htmlspecialchars($sepeda['nama_sepeda']) ?></h3>
                        <p><strong>🚲 Jenis:</strong> <?= htmlspecialchars($sepeda['jenis']) ?></p>
                        <p><strong>💰 Harga:</strong> Rp <?= number_format($sepeda['harga_per_jam'], 0, ',', '.') ?>/jam</p>
                        <p style="color: #666; margin-top: 10px;"><?= htmlspecialchars($sepeda['deskripsi']) ?></p>
                        
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="sewa.php?id=<?= $sepeda['id'] ?>" class="btn btn-primary">Sewa Sekarang</a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-primary">Login untuk Sewa</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>