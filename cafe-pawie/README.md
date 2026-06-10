# Cafe Pawie
Cafe Pawie adalah website reservasi cafe bertema kucing yang dibuat untuk memudahkan pelanggan melakukan pemesanan kunjungan secara online serta membantu admin mengelola data reservasi dan laporan pendapatan.

## Daftar Anggota Kelompok
- Riza Arvin Mahardhika (2410631170106)
- Lacinta Theo Gracia Hutasoit (2410631170078)
- Nadrina Pratama Hilyawanti (2410631170160)

## Deskripsi dan Tujuan Website
Website **Cafe Pawie** merupakan aplikasi berbasis web untuk layanan reservasi kunjungan cafe. Pengguna dapat membuat akun, login, melakukan reservasi, mengunggah bukti pembayaran, melihat riwayat reservasi, dan mencetak atau melihat resi reservasi. Tujuan pembuatan website ini adalah:
- Mempermudah pelanggan dalam melakukan reservasi tanpa harus datang langsung ke cafe.
- Membantu admin mengelola data reservasi pelanggan.
- Membantu admin memantau status pembayaran dan status pesanan.
- Menyediakan laporan pendapatan berdasarkan data reservasi yang telah selesai.

## Fitur Utama Website

### Fitur Pengguna / Pengunjung
- Registrasi akun pengguna.
- Login pengguna.
- Dashboard pengguna.
- Form reservasi kunjungan.
- Input data pemesan, tanggal kunjungan, jam kunjungan, dan jumlah orang.
- Pembayaran reservasi dengan unggah bukti transfer.
- Riwayat reservasi.
- Melihat resi reservasi.
- Halaman akun pengguna.

### Fitur Admin
- Login admin.
- Dashboard admin.
- Melihat total booking hari ini.
- Melihat estimasi pendapatan hari ini.
- Mengelola status pembayaran reservasi.
- Mengelola status order reservasi.
- Melihat laporan keuangan.
- Menghapus data laporan reservasi.
- Melihat data reservasi berdasarkan tanggal dan status.

## Teknologi yang Digunakan
- PHP Native
- MySQL / MariaDB
- HTML
- CSS
- JavaScript
- Bootstrap
- XAMPP / phpMyAdmin

## Struktur Project
```text
Project PBW/
├── admin/
│   ├── admin.php
│   └── laporan.php
│
├── assets/
│   ├── css/
│   ├── js/
│   ├── dashboard.css
│   ├── dashboard.js
│   ├── laporan.css
│   ├── laporan.js
│   ├── reservasi.css
│   ├── reservasi.js
│   └── style.css
│
├── config/
│   └── koneksi.php
│
├── data/
│   ├── akun.php
│   ├── pembayaran.php
│   ├── reservasi.php
│   ├── resi.php
│   └── riwayat.php
│
├── db/
│   └── db_cafe.sql
│
├── public/
│   └── file gambar website
│
├── src/
│   ├── dataReservasi.php
│   ├── deleteReservasi.php
│   ├── prosesPelunasan.php
│   └── uploads/
│
├── dashboard.php
├── login.php
├── register.php
└── README.md
```

## Penjelasan Folder dan File Penting
- `login.php` (Halaman login untuk pengguna dan admin.)
- `register.php` (Halaman registrasi akun pembeli.)
- `dashboard.php` (Dashboard utama untuk pengguna setelah login.)
- `admin/admin.php` (Dashboard admin untuk melihat dan mengelola reservasi.)
- `admin/laporan.php` (Halaman laporan keuangan admin.)
- `config/koneksi.php` (File konfigurasi koneksi database MySQL.)
- `data/reservasi.php` (Halaman form reservasi pelanggan.)
- `data/pembayaran.php` (Halaman pembayaran dan unggah bukti transfer.)
- `data/riwayat.php` (Halaman riwayat reservasi pelanggan.)
- `data/resi.php` (Halaman resi reservasi.)
- `db/db_cafe.sql` (File database yang perlu di-import ke phpMyAdmin.)
- `public/` (Berisi gambar yang digunakan pada website.)
- `src/uploads/` (Folder penyimpanan bukti transfer yang diunggah pengguna.)

## Cara Menjalankan Aplikasi

### 1. Clone Repository
```bash
git clone https://github.com/ryzaarvn/cafe-pawie.git
```

Atau download ZIP project dari GitHub.

### 2. Pindahkan Project ke Folder XAMPP
Pindahkan folder project ke direktori:

```text
C:/xampp/htdocs/
```

### 3. Jalankan Apache dan MySQL
Buka XAMPP Control Panel, lalu aktifkan:
- Apache
- MySQL

### 4. Import Database
1. Buka browser.
2. Akses:
```text
http://localhost/phpmyadmin
```

3. Buat database baru dengan nama:
```text
db_cafe
```

4. Pilih database `db_cafe`.
5. Klik menu **Import**.
6. Pilih file:
```text
db/db_cafe.sql
```

7. Klik **Go**.

### 5. Cek Konfigurasi Database
Pastikan file `config/koneksi.php` berisi konfigurasi berikut:
```php
<?php
$conn = mysqli_connect("localhost", "root", "", "db_cafe", "3306");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
```

Jika username, password, atau port MySQL berbeda, sesuaikan dengan konfigurasi perangkat masing-masing.

### 6. Jalankan Website
Buka browser dan akses:
```text
http://localhost/cafe-pawie/login.php
```

## Akun Admin Default
Gunakan akun berikut untuk login sebagai admin:
```text
Email    : admin@cafe.com
Password : admin12
```

## Akun Pengguna
Pengguna dapat membuat akun baru melalui halaman:
```text
register.php
```

Setelah registrasi berhasil, pengguna dapat login melalui halaman login.

## Link Video Presentasi Project
Link video presentasi:
```text
https://drive.google.com/drive/folders/1AtIni8Z1CRMlkEO1StEkGd2LYf4ZaRBH?usp=sharing
```