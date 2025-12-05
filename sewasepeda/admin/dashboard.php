<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

require_once '../config/config.php';
require_once '../models/Sewa.php';
require_once '../models/Sepeda.php';

$sewaModel = new Sewa($pdo);
$sepedaModel = new Sepeda($pdo);

// Handle approval/rejection
if ($_POST && isset($_POST['action'])) {
    $sewa_id = $_POST['sewa_id'];
    $action = $_POST['action'];
    $catatan = $_POST['catatan'] ?? '';
    
    if ($action === 'approve') {
        $sewaModel->updateStatus($sewa_id, 'approved', $catatan);
        // Update status sepeda menjadi disewa
        $stmt = $pdo->prepare("SELECT sepeda_id FROM sewa WHERE id = ?");
        $stmt->execute([$sewa_id]);
        $sewa = $stmt->fetch();
        $sepedaModel->updateStatus($sewa['sepeda_id'], 'disewa');
    } elseif ($action === 'reject') {
        $sewaModel->updateStatus($sewa_id, 'rejected', $catatan);
    }
    
    header('Location: dashboard.php');
    exit;
}

$sewaList = $sewaModel->getAllSewa();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Sewa Sepeda</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">🚴 Admin Panel</div>
            <div class="nav-links">
                <a href="../index.php">Lihat Website</a>
                <a href="../logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <div style="text-align: center;">
                <h1 style="background: linear-gradient(45deg, #1e3c72, #4facfe); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 10px;">🛡️ Admin Dashboard</h1>
                <p style="color: #666; font-size: 1.1rem;">Kelola sistem sewa sepeda dengan mudah</p>
                <p style="color: #888; margin-top: 5px;">Selamat datang, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>!</p>
            </div>
        </div>

        <div class="card">
            <h2 style="color: #333; margin-bottom: 20px;">📋 Daftar Permintaan Sewa</h2>
            
            <?php if (empty($sewaList)): ?>
                <p>Belum ada permintaan sewa.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Penyewa</th>
                            <th>Sepeda</th>
                            <th>Tanggal & Waktu</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sewaList as $sewa): ?>
                            <tr>
                                <td><?= $sewa['id'] ?></td>
                                <td><?= htmlspecialchars($sewa['nama_lengkap']) ?></td>
                                <td><?= htmlspecialchars($sewa['nama_sepeda']) ?></td>
                                <td>
                                    <?= date('d/m/Y', strtotime($sewa['tanggal_sewa'])) ?><br>
                                    <?= $sewa['jam_mulai'] ?> - <?= $sewa['jam_selesai'] ?>
                                </td>
                                <td>Rp <?= number_format($sewa['total_harga'], 0, ',', '.') ?></td>
                                <td>
                                    <span class="status status-<?= $sewa['status'] ?>">
                                        <?= ucfirst($sewa['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($sewa['status'] === 'pending'): ?>
                                        <button onclick="showModal(<?= $sewa['id'] ?>, 'approve')" class="btn btn-success">Setujui</button>
                                        <button onclick="showModal(<?= $sewa['id'] ?>, 'reject')" class="btn btn-danger">Tolak</button>
                                    <?php else: ?>
                                        <?= htmlspecialchars($sewa['catatan'] ?? '-') ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 8px; width: 400px;">
            <h3 id="modalTitle">Konfirmasi Aksi</h3>
            <form method="POST">
                <input type="hidden" name="sewa_id" id="sewaId">
                <input type="hidden" name="action" id="action">
                
                <div class="form-group">
                    <label>Catatan (opsional):</label>
                    <textarea name="catatan" rows="3" style="width: 100%; padding: 8px;"></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Konfirmasi</button>
                <button type="button" onclick="hideModal()" class="btn btn-danger">Batal</button>
            </form>
        </div>
    </div>

    <script>
        function showModal(sewaId, action) {
            document.getElementById('sewaId').value = sewaId;
            document.getElementById('action').value = action;
            document.getElementById('modalTitle').textContent = action === 'approve' ? 'Setujui Sewa' : 'Tolak Sewa';
            document.getElementById('modal').style.display = 'block';
        }
        
        function hideModal() {
            document.getElementById('modal').style.display = 'none';
        }
    </script>
</body>
</html>