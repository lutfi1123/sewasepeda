CREATE DATABASE sewa_sepeda;
USE sewa_sepeda;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    nama_lengkap VARCHAR(100) NOT NULL,
    no_hp VARCHAR(15),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE sepeda (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_sepeda VARCHAR(100) NOT NULL,
    jenis VARCHAR(50) NOT NULL,
    harga_per_jam DECIMAL(10,2) NOT NULL,
    status ENUM('tersedia', 'disewa', 'maintenance') DEFAULT 'tersedia',
    gambar VARCHAR(255),
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE sewa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    sepeda_id INT NOT NULL,
    tanggal_sewa DATE NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    total_jam INT NOT NULL,
    total_harga DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending',
    catatan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (sepeda_id) REFERENCES sepeda(id)
);

-- Insert admin default
INSERT INTO users (username, email, password, role, nama_lengkap) 
VALUES ('admin', 'admin@sewasepeda.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Administrator');

-- Insert sample sepeda
INSERT INTO sepeda (nama_sepeda, jenis, harga_per_jam, deskripsi) VALUES
('Mountain Bike Pro', 'Mountain Bike', 15000, 'Sepeda gunung untuk medan berat'),
('City Bike Classic', 'City Bike', 10000, 'Sepeda santai untuk berkeliling kota'),
('Road Bike Speed', 'Road Bike', 20000, 'Sepeda balap untuk kecepatan tinggi');