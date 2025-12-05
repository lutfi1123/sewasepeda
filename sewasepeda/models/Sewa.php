<?php
class Sewa {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function createSewa($user_id, $sepeda_id, $tanggal_sewa, $jam_mulai, $jam_selesai, $total_jam, $total_harga) {
        $stmt = $this->pdo->prepare("INSERT INTO sewa (user_id, sepeda_id, tanggal_sewa, jam_mulai, jam_selesai, total_jam, total_harga) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$user_id, $sepeda_id, $tanggal_sewa, $jam_mulai, $jam_selesai, $total_jam, $total_harga]);
    }
    
    public function getSewaByUser($user_id) {
        $stmt = $this->pdo->prepare("SELECT s.*, sp.nama_sepeda, sp.jenis FROM sewa s JOIN sepeda sp ON s.sepeda_id = sp.id WHERE s.user_id = ? ORDER BY s.created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }
    
    public function getAllSewa() {
        $stmt = $this->pdo->query("SELECT s.*, u.nama_lengkap, sp.nama_sepeda FROM sewa s JOIN users u ON s.user_id = u.id JOIN sepeda sp ON s.sepeda_id = sp.id ORDER BY s.created_at DESC");
        return $stmt->fetchAll();
    }
    
    public function updateStatus($id, $status, $catatan = null) {
        $stmt = $this->pdo->prepare("UPDATE sewa SET status = ?, catatan = ? WHERE id = ?");
        return $stmt->execute([$status, $catatan, $id]);
    }
}
?>