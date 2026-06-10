<?php
session_start();
include '../config/koneksi.php';

// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id_reservasi'])) {
    die("ID reservasi tidak ditemukan!");
}

$id_user = $_SESSION['id_user'];
$role = $_SESSION['role'];
$id_reservasi = $_GET['id_reservasi'];

// Kalau admin, boleh lihat semua resi
if ($role == 'admin') {
    $query = mysqli_query($conn, "SELECT * FROM reservations 
        WHERE id_reservasi = '$id_reservasi'
    ");
} else {
    // Kalau pembeli, hanya boleh lihat resi miliknya sendiri
    $query = mysqli_query($conn, "SELECT * FROM reservations 
        WHERE id_reservasi = '$id_reservasi'
        AND id_user = '$id_user'
    ");
}

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data resi tidak ditemukan atau kamu tidak punya akses.");
}

$harga_per_orang = 25000;
$total_bayar = $data['jml_orang'] * $harga_per_orang;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Resi Reservasi - Cafe Pawie</title>

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

        .menu a:hover {
            background-color: #FFFBE9;
        }

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
        }

        .content {
            flex: 1;
            padding: 40px;
        }

        h1 {
            color: #AD8B73;
            margin-bottom: 25px;
        }

        .resi-card {
            max-width: 750px;
            background-color: white;
            border: 1px solid #E3CAA5;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .resi-header {
            background-color: #AD8B73;
            color: white;
            padding: 25px;
            text-align: center;
        }

        .resi-header h2 {
            margin: 0;
            font-size: 28px;
        }

        .resi-body {
            padding: 30px;
        }

        .row-detail {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px dashed #E3CAA5;
            padding: 12px 0;
            gap: 20px;
        }

        .row-detail span:first-child {
            color: #AD8B73;
            font-weight: bold;
        }

        .status {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 8px;
            background-color: #FFFBE9;
            color: #AD8B73;
            font-weight: bold;
            border: 1px solid #E3CAA5;
        }

        .total {
            background-color: #FFFBE9;
            border-radius: 10px;
            padding: 18px;
            margin-top: 20px;
            text-align: right;
            font-size: 20px;
            color: #AD8B73;
            font-weight: bold;
        }

        .bukti-img {
            max-width: 250px;
            border-radius: 10px;
            margin-top: 15px;
            border: 1px solid #E3CAA5;
        }

        .actions {
            margin-top: 25px;
            display: flex;
            gap: 10px;
        }

        .btn {
            display: inline-block;
            padding: 12px 18px;
            background-color: #AD8B73;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            text-align: center;
        }

        .btn:hover {
            background-color: #8c6f5c;
        }

        .btn-light {
            background-color: #CEAB93;
        }

        @media print {
            .sidebar,
            .actions {
                display: none;
            }

            .layout {
                display: block;
            }

            .content {
                padding: 0;
            }

            body {
                background-color: white;
            }

            .resi-card {
                box-shadow: none;
                border: 1px solid #ccc;
                max-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #E3CAA5;
            }

            .content {
                padding: 25px;
            }

            .row-detail {
                flex-direction: column;
                gap: 5px;
            }

            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

<div class="layout">

    <div class="content">
        <h1>Resi Reservasi</h1>

        <div class="resi-card" id="area-resi">

            <div class="resi-header">
                <h2>Cafe Pawie</h2>
                <p>Resi Reservasi Kunjungan</p>
            </div>

            <div class="resi-body">

                <div class="row-detail">
                    <span>ID Reservasi</span>
                    <span><?php echo $data['id_reservasi']; ?></span>
                </div>

                <div class="row-detail">
                    <span>Nama Pemesan</span>
                    <span><?php echo $data['nama_pemesan']; ?></span>
                </div>

                <div class="row-detail">
                    <span>No Telepon</span>
                    <span><?php echo $data['no_telp_reservasi']; ?></span>
                </div>

                <div class="row-detail">
                    <span>Tanggal Kunjungan</span>
                    <span><?php echo $data['tgl_kunjungan']; ?></span>
                </div>

                <div class="row-detail">
                    <span>Jam Kunjungan</span>
                    <span><?php echo $data['jam_kunjungan']; ?></span>
                </div>

                <div class="row-detail">
                    <span>Jumlah Orang</span>
                    <span><?php echo $data['jml_orang']; ?> orang</span>
                </div>

                <div class="row-detail">
                    <span>Harga per Orang</span>
                    <span>Rp <?php echo number_format($harga_per_orang, 0, ',', '.'); ?></span>
                </div>

                <div class="row-detail">
                    <span>Status Bayar</span>
                    <span class="status"><?php echo $data['status_bayar']; ?></span>
                </div>

                <div class="row-detail">
                    <span>Status Order</span>
                    <span class="status"><?php echo $data['status_order']; ?></span>
                </div>

                <?php if ($data['bukti_transfer'] != '') { ?>
                    <div style="margin-top: 20px;">
                        <b style="color:#AD8B73;">Bukti Transfer:</b><br>
                        <img class="bukti-img" src="../src/uploads/buktiTransfer/<?php echo $data['bukti_transfer']; ?>" alt="Bukti Transfer">
                    </div>
                <?php } ?>

                <div class="total">
                    Total Bayar: Rp <?php echo number_format($total_bayar, 0, ',', '.'); ?>
                </div>

                <p style="margin-top: 25px; line-height: 1.6;">
                    Terima kasih telah melakukan reservasi di Cafe Pawie. 
                    Silakan tunjukkan resi ini saat datang ke cafe.
                </p>

            </div>
        </div>

        <div class="actions">
            <button class="btn" onclick="window.print()" target="_blank">Cetak Resi</button>
            <a class="btn btn-light" href="../dashboard.php">Kembali ke Dashboard</a>
            <a class="btn btn-light" href="reservasi.php">Buat Reservasi Lagi</a>
        </div>
    </div>

</div>

</body>
</html>