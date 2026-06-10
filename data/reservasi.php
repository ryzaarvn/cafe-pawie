<?php
session_start();
include '../config/koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id_user'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Reservasi - Cafe Pawie</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #FFFBE9;
            color: #333;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            min-width: 250px;
            max-width: 250px;
            flex: 0 0 250px;
            background-color: white;
            padding: 25px 20px;
            border-right: 1px solid #E3CAA5;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        }

        .sidebar h2 {
            color: #AD8B73;
            text-align: center;
            margin-bottom: 30px;
        }

        .menu a {
            display: block;
            padding: 12px 15px;
            margin-bottom: 10px;
            color: #AD8B73;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s;
        }

        .menu a:hover,
        .menu a.active {
            background-color: #AD8B73;
            color: white;
        }

        .logout {
            margin-top: 30px;
        }

        .logout a {
            display: block;
            text-align: center;
            padding: 12px;
            background-color: #AD8B73;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s;
        }

        .logout a:hover {
            background-color: #8c6f5c;
        }

        .content {
            flex: 1;
            padding: 40px;
        }

        .content h1 {
            color: #AD8B73;
            margin-bottom: 25px;
        }

        .card {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            border: 1px solid #E3CAA5;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            max-width: 650px;
        }

        .info {
            background-color: #FFFBE9;
            padding: 15px;
            border-left: 5px solid #AD8B73;
            border-radius: 8px;
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #AD8B73;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #E3CAA5;
            border-radius: 8px;
            font-size: 15px;
            background-color: #FFFBE9;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #AD8B73;
            box-shadow: 0 0 5px rgba(173, 139, 115, 0.4);
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #AD8B73;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.3s;
        }

        button:hover {
            background-color: #8c6f5c;
        }

        @media (max-width: 768px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                min-width: 100%;
                max-width: 100%;
                flex: none;
                border-right: none;
                border-bottom: 1px solid #E3CAA5;
            }

            .content {
                padding: 25px;
            }
        }
    </style>
</head>

<body>

<div class="layout">

    <div class="sidebar">
        <h2>Cafe Pawie</h2>

        <div class="menu">
            <a href="../dashboard.php">Dashboard Cafe</a>
            <a href="akun.php">Akun Saya</a>
            <a href="reservasi.php" class="active">Form Reservasi</a>
            <a href="riwayat.php">Riwayat</a>
        </div>

        <div class="logout">
            <a href="../login.php">Keluar</a>
        </div>
    </div>

    <div class="content">
        <h1>Form Reservasi</h1>

        <div class="card">
            <div class="info">
                Silakan isi data reservasi kunjungan kamu ke Cafe Pawie. Pilih pembayaran DP atau Lunas, lalu lanjutkan ke halaman pembayaran.
            </div>

            <form action="pembayaran.php" method="POST">

                <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">

                <div class="form-group">
                    <label>Nama Pemesan</label>
                    <input type="text" name="nama_pemesan" placeholder="Masukkan nama pemesan" required>
                </div>

                <div class="form-group">
                    <label>No Telepon</label>
                    <input type="text" name="no_telp_reservasi" placeholder="Contoh: 08123456789" required>
                </div>

                <div class="form-group">
                    <label>Tanggal Kunjungan</label>
                    <input type="date" name="tgl_kunjungan" required>
                </div>

                <div class="form-group">
                    <label>Jam Kunjungan</label>
                    <input type="time" name="jam_kunjungan" required>
                </div>

                <div class="form-group">
                    <label>Jumlah Orang</label>
                    <input type="number" name="jml_orang" min="1" max="10" placeholder="Masukkan jumlah orang" required>
                </div>

                <div class="form-group">
                    <label>Pilihan Pembayaran</label>
                    <select name="status_bayar" required>
                        <option value="">-- Pilih Pembayaran --</option>
                        <option value="DP">DP</option>
                        <option value="Lunas">Lunas</option>
                    </select>
                </div>

                <button type="submit" name="buat_reservasi">
                    Reservasi Sekarang
                </button>

            </form>
        </div>
    </div>

</div>

</body>
</html>