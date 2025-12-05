<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] === 'admin') {
    header('Location: login.php');
    exit;
}

require_once 'config/config.php';
require_once 'models/Sewa.php';

$sewaModel = new Sewa($pdo);
$sewaList = $sewaModel->getSewaByUser($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sewa Sepeda</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">🚴 Sewa Sepeda</div>
            <div class="nav-links">
                <a href="index.php">Beranda</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <div style="text-align: center;">
                <h1 style="background: linear-gradient(45deg, #1e3c72, #4facfe); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 10px;">🎆 Dashboard</h1>
                <p style="color: #666; font-size: 1.1rem;">Selamat datang kembali, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>!</p>
            </div>
        </div>

        <div class="card">
            <h2 style="color: #333; margin-bottom: 20px;">📊 Riwayat Sewa Sepeda</h2>
            
            <?php if (empty($sewaList)): ?>
                <p>Belum ada riwayat sewa. <a href="index.php">Mulai sewa sepeda sekarang!</a></p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sepeda</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sewaList as $sewa): ?>
                            <tr>
                                <td><?= htmlspecialchars($sewa['nama_sepeda']) ?> (<?= htmlspecialchars($sewa['jenis']) ?>)</td>
                                <td><?= date('d/m/Y', strtotime($sewa['tanggal_sewa'])) ?></td>
                                <td><?= $sewa['jam_mulai'] ?> - <?= $sewa['jam_selesai'] ?> (<?= $sewa['total_jam'] ?> jam)</td>
                                <td>Rp <?= number_format($sewa['total_harga'], 0, ',', '.') ?></td>
                                <td>
                                    <span class="status status-<?= $sewa['status'] ?>">
                                        <?= ucfirst($sewa['status']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($sewa['catatan'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>