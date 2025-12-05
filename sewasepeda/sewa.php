<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] === 'admin') {
    header('Location: login.php');
    exit;
}

require_once 'config/config.php';
require_once 'models/Sepeda.php';
require_once 'models/Sewa.php';

$sepedaModel = new Sepeda($pdo);
$sewaModel = new Sewa($pdo);

$sepeda_id = $_GET['id'] ?? 0;
$sepeda = $sepedaModel->getSepedaById($sepeda_id);

if (!$sepeda || $sepeda['status'] !== 'tersedia') {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if ($_POST) {
    $tanggal_sewa = $_POST['tanggal_sewa'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    
    // Hitung total jam
    $start = new DateTime($jam_mulai);
    $end = new DateTime($jam_selesai);
    $interval = $start->diff($end);
    $total_jam = $interval->h + ($interval->i / 60);
    
    if ($total_jam <= 0) {
        $error = 'Jam selesai harus lebih besar dari jam mulai!';
    } else {
        $total_harga = $total_jam * $sepeda['harga_per_jam'];
        
        $result = $sewaModel->createSewa(
            $_SESSION['user_id'],
            $sepeda_id,
            $tanggal_sewa,
            $jam_mulai,
            $jam_selesai,
            ceil($total_jam),
            $total_harga
        );
        
        if ($result) {
            $success = 'Permintaan sewa berhasil diajukan! Menunggu persetujuan admin.';
        } else {
            $error = 'Gagal mengajukan sewa!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sewa Sepeda - <?= htmlspecialchars($sepeda['nama_sepeda']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">🚴 Sewa Sepeda</div>
            <div class="nav-links">
                <a href="index.php">Beranda</a>
                <a href="dashboard.php">Dashboard</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <div style="text-align: center; margin-bottom: 30px;">
                <h1 style="background: linear-gradient(45deg, #1e3c72, #4facfe); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">🚴♂️ Sewa Sepeda</h1>
                <h2 style="color: #333; margin-top: 10px;"><?= htmlspecialchars($sepeda['nama_sepeda']) ?></h2>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 20px;">
                <div>
                    <h3 style="color: #333; margin-bottom: 15px; border-bottom: 2px solid #1e3c72; padding-bottom: 10px;">📝 Detail Sepeda</h3>
                    <p><strong>Nama:</strong> <?= htmlspecialchars($sepeda['nama_sepeda']) ?></p>
                    <p><strong>Jenis:</strong> <?= htmlspecialchars($sepeda['jenis']) ?></p>
                    <p><strong>Harga:</strong> Rp <?= number_format($sepeda['harga_per_jam'], 0, ',', '.') ?>/jam</p>
                    <p><strong>Deskripsi:</strong> <?= htmlspecialchars($sepeda['deskripsi']) ?></p>
                </div>
                
                <div>
                    <h3 style="color: #333; margin-bottom: 15px; border-bottom: 2px solid #1e3c72; padding-bottom: 10px;">📋 Form Sewa</h3>
                    
                    <?php if ($error): ?>
                        <div style="background: linear-gradient(45deg, #ff416c, #ff4b2b); color: white; padding: 12px; border-radius: 10px; margin-bottom: 20px;"><?= $error ?></div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div style="background: linear-gradient(45deg, #56ab2f, #a8e6cf); color: white; padding: 12px; border-radius: 10px; margin-bottom: 20px;"><?= $success ?></div>
                        <a href="dashboard.php" class="btn btn-primary">Lihat Status Sewa</a>
                    <?php else: ?>
                        <form method="POST" id="sewaForm">
                            <div class="form-group">
                                <label>Tanggal Sewa:</label>
                                <input type="date" name="tanggal_sewa" min="<?= date('Y-m-d') ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Jam Mulai:</label>
                                <input type="time" name="jam_mulai" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Jam Selesai:</label>
                                <input type="time" name="jam_selesai" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Estimasi Total Harga:</label>
                                <div id="totalHarga">Rp 0</div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Ajukan Sewa</button>
                            <a href="index.php" class="btn btn-danger">Batal</a>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        const hargaPerJam = <?= $sepeda['harga_per_jam'] ?>;
        
        function hitungTotal() {
            const jamMulai = document.querySelector('input[name="jam_mulai"]').value;
            const jamSelesai = document.querySelector('input[name="jam_selesai"]').value;
            
            if (jamMulai && jamSelesai) {
                const start = new Date('2000-01-01 ' + jamMulai);
                const end = new Date('2000-01-01 ' + jamSelesai);
                const diff = (end - start) / (1000 * 60 * 60); // dalam jam
                
                if (diff > 0) {
                    const total = Math.ceil(diff) * hargaPerJam;
                    document.getElementById('totalHarga').textContent = 'Rp ' + total.toLocaleString('id-ID');
                }
            }
        }
        
        document.querySelector('input[name="jam_mulai"]').addEventListener('change', hitungTotal);
        document.querySelector('input[name="jam_selesai"]').addEventListener('change', hitungTotal);
    </script>
</body>
</html>