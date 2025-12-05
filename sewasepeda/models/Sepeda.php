<?php
class Sepeda {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function getAllSepeda() {
        $stmt = $this->pdo->query("SELECT * FROM sepeda WHERE status = 'tersedia'");
        return $stmt->fetchAll();
    }
    
    public function getSepedaById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM sepeda WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function updateStatus($id, $status) {
        $stmt = $this->pdo->prepare("UPDATE sepeda SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
}
?>