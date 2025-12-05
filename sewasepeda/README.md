# Website Sewa Sepeda

Aplikasi web untuk sistem sewa sepeda dengan fitur penyewa dan admin untuk validasi.

## Fitur

### Untuk Penyewa:
- Registrasi dan login
- Melihat daftar sepeda tersedia
- Mengajukan sewa sepeda
- Melihat riwayat sewa dan status

### Untuk Admin:
- Login sebagai admin
- Melihat semua permintaan sewa
- Menyetujui atau menolak sewa
- Memberikan catatan pada keputusan

## Instalasi

1. **Setup Database**
   - Buat database MySQL
   - Import file `database.sql`
   - Sesuaikan konfigurasi di `config/config.php`

2. **Login Admin Default**
   - Username: `admin`
   - Password: `password` (hash: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi)

3. **Jalankan Aplikasi**
   - Pastikan web server (Apache/Nginx) dan PHP aktif
   - Akses melalui browser

## Struktur File

```
├── config/
│   ├── config.php          # Konfigurasi database
│   └── config_functions.php
├── models/
│   ├── User.php            # Model untuk user
│   ├── Sepeda.php          # Model untuk sepeda
│   └── Sewa.php            # Model untuk transaksi sewa
├── admin/
│   └── dashboard.php       # Dashboard admin
├── assets/
│   └── css/
│       └── style.css       # Stylesheet
├── index.php               # Halaman utama
├── login.php               # Halaman login
├── register.php            # Halaman registrasi
├── dashboard.php           # Dashboard user
├── sewa.php                # Form sewa sepeda
├── logout.php              # Logout
└── database.sql            # Schema database
```

## Teknologi

- PHP 7.4+
- MySQL 5.7+
- HTML5, CSS3, JavaScript
- PDO untuk database

## Status Sewa

- **Pending**: Menunggu persetujuan admin
- **Approved**: Disetujui admin, sepeda dapat digunakan
- **Rejected**: Ditolak admin
- **Completed**: Sewa selesai