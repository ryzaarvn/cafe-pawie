<?php
session_start();
include '../config/koneksi.php';

// ======================
// PROTEKSI ADMIN
// ======================

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// ======================
// UPDATE STATUS BAYAR
// ======================


if (isset($_POST['update_status_bayar'])) {

    $id_reservasi = $_POST['id_reservasi'];
    $status_bayar = $_POST['status_bayar'];

    $status_bayar_valid = ['dp', 'lunas'];

    if (in_array($status_bayar, $status_bayar_valid)) {

        $update_bayar = mysqli_query($conn,
            "UPDATE reservations 
             SET status_bayar = '$status_bayar'
             WHERE id_reservasi = '$id_reservasi'"
        );

        if (!$update_bayar) {
            die("Update status bayar gagal: " . mysqli_error($conn));
        }

        header("Location: admin.php");
        exit();

    } else {
        die("Status bayar tidak valid.");
    }
}

// ======================
// UPDATE STATUS ORDER
// ======================

if (isset($_POST['update_status_order'])) {

    $id_reservasi = $_POST['id_reservasi'];
    $status_order = $_POST['status_order'];

    $status_order_valid = [
        'booking',
        'disetujui',
        'selesai',
        'gagal'
    ];

    if (in_array($status_order, $status_order_valid)) {

        $update_order = mysqli_query($conn,
            "UPDATE reservations
             SET status_order = '$status_order'
             WHERE id_reservasi = '$id_reservasi'"
        );

        if (!$update_order) {
            die("Update status order gagal: " . mysqli_error($conn));
        }

        header("Location: admin.php");
        exit();

    } else {
        die("Status order tidak valid.");
    }
}


// ======================
// SETTING DASAR
// ======================

$hari_ini = date('Y-m-d');
$harga_per_orang = 25000;

// ======================
// TOTAL BOOKING HARI INI
// Data batal/gagal tidak dihitung
// ======================

$q_booking = mysqli_query($conn,
    "SELECT COUNT(*) AS total
     FROM reservations
     WHERE DATE(tgl_kunjungan) = '$hari_ini'
     AND status_order != 'gagal'"
);

if (!$q_booking) {
    die("Query total booking gagal: " . mysqli_error($conn));
}

$data_booking = mysqli_fetch_assoc($q_booking);
$total_booking = $data_booking['total'] ?? 0;

// ======================
// ESTIMASI PENDAPATAN HARI INI
// Data batal/gagal tidak dihitung
// ======================

$q_duit = mysqli_query($conn,
    "SELECT SUM(jml_orang * $harga_per_orang) AS total
     FROM reservations
     WHERE DATE(tgl_kunjungan) = '$hari_ini'
     AND status_order != 'gagal'"
);

if (!$q_duit) {
    die("Query pendapatan gagal: " . mysqli_error($conn));
}

$data_duit = mysqli_fetch_assoc($q_duit);
$pendapatan = $data_duit['total'] ?? 0;

// ======================
// DATA RESERVASI HARI INI
// ======================

$tabel_reservasi = mysqli_query($conn,
    "SELECT 
        id_reservasi,
        nama_pemesan,
        no_telp_reservasi,
        tgl_kunjungan,
        jam_kunjungan,
        jml_orang,
        status_bayar,
        status_order,
        bukti_transfer
     FROM reservations
     WHERE DATE(tgl_kunjungan) = '$hari_ini'
     ORDER BY jam_kunjungan ASC"
);

if (!$tabel_reservasi) {
    die("Query reservasi gagal: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Dashboard - Cafe Pawie</title>

    <link rel="stylesheet" href="../assets/reservasi.css">
    <link rel="stylesheet" href="../assets/dashboard.css">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
    <style>
        .status-select {
            border: none;
            padding: 6px 10px;
            border-radius: 20px;
            font-weight: bold;
            cursor: pointer;
            color: white;
            outline: none;
        }

        .bg-lunas {
            background-color: #28a745;
        }

        .bg-dp {
            background-color: #f0ad4e;
        }

        .bg-booking {
            background-color: #007bff;
        }

        .bg-selesai {
            background-color: #28a745;
        }

        .bg-gagal {
            background-color: #dc3545;
        }

        .bg-disetujui {
            background-color: #17a2b8;
        }
    </style>
</head>

<body>

<!-- ======================
     SIDEBAR
====================== -->

<div class="sidebar">

    <h2>Cafe Pawie</h2>

    <a href="admin.php" class="active">
        <i class="fa fa-home"></i>
        Dashboard 
    </a>

    <a href="laporan.php">
        <i class="fa fa-file-invoice"></i>
        Laporan
    </a>

    <a href="../login.php" class="logout">
        <i class="fa fa-sign-out-alt"></i>
        Keluar
    </a>

</div>

<!-- ======================
     MAIN CONTENT
====================== -->

<div class="main-content">

    <!-- ======================
         TOP STATISTICS
    ====================== -->

    <div class="top-stats">

        <div class="stat-card krem">
            <h3>Total Booking Hari Ini</h3>
            <p><?= $total_booking; ?></p>
        </div>

        <div class="stat-card krem">
            <h3>Estimasi Pendapatan Hari Ini</h3>
            <p>Rp <?= number_format($pendapatan, 0, ',', '.'); ?></p>
        </div>

    </div>

    <!-- ======================
         DATA RESERVASI HARI INI
    ====================== -->

    <div class="data-box full-table">

        <div class="table-header">

            <h3>Semua Data Reservasi Hari Ini</h3>

            <input
                type="text"
                id="searchInput"
                placeholder="Cari nama pemesan..."
                onkeyup="searchTable()"
            >

        </div>

        <table id="reservasiTable">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Pemesan</th>
                    <th>No. WA</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Jumlah</th>
                    <th>Status Bayar</th>
                    <th>Status Order</th>
                    <th>Bukti Transfer</th>
                </tr>
            </thead>

            <tbody>

            <?php if (mysqli_num_rows($tabel_reservasi) > 0): ?>

                <?php while ($row = mysqli_fetch_assoc($tabel_reservasi)): ?>

                    <tr>

                        <td>
                            #<?= htmlspecialchars($row['id_reservasi']); ?>
                        </td>

                        <td>
                            <b><?= htmlspecialchars($row['nama_pemesan']); ?></b>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['no_telp_reservasi']); ?>
                        </td>

                        <td>
                            <?= date('d/m/Y', strtotime($row['tgl_kunjungan'])); ?>
                        </td>

                        <td>
                            <i class="fa fa-clock"></i>
                            <?= htmlspecialchars($row['jam_kunjungan']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['jml_orang']); ?> Orang
                        </td>

                        <td>
                            <form method="POST" action="" style="margin: 0;">
                                <input 
                                    type="hidden" 
                                    name="id_reservasi" 
                                    value="<?= htmlspecialchars($row['id_reservasi']); ?>"
                                >

                                <select 
                                    name="status_bayar" 
                                    onchange="this.form.submit()"
                                    class="status-select <?= ($row['status_bayar'] == 'lunas') ? 'bg-lunas' : 'bg-dp'; ?>"
                                >
                                    <option 
                                        value="dp"
                                        <?= ($row['status_bayar'] == 'dp') ? 'selected' : ''; ?>
                                    >
                                        DP
                                    </option>

                                    <option 
                                        value="lunas"
                                        <?= ($row['status_bayar'] == 'lunas') ? 'selected' : ''; ?>
                                    >
                                        LUNAS
                                    </option>
                                </select>

                                <input 
                                    type="hidden" 
                                    name="update_status_bayar" 
                                    value="1"
                                >
                            </form>
                        </td>

                        <td>
                            <form method="POST" action="" style="margin: 0;">
                                <input 
                                    type="hidden" 
                                    name="id_reservasi" 
                                    value="<?= htmlspecialchars($row['id_reservasi']); ?>"
                                >

                                <select 
                                    name="status_order" 
                                    onchange="this.form.submit()"
                                    class="status-select 
                                        <?php 
                                            if ($row['status_order'] == 'booking') {
                                                echo 'bg-booking';
                                            } elseif ($row['status_order'] == 'disetujui') {
                                                echo 'bg-disetujui';
                                            } elseif ($row['status_order'] == 'selesai') {
                                                echo 'bg-selesai';
                                            } else {
                                                echo 'bg-gagal';
                                            }
                                        ?>"
                                >
                                    <option value="booking"
                                    <?= ($row['status_order'] == 'booking') ? 'selected' : ''; ?>>
                                    BOOKING
                                    </option>

                                    <option value="disetujui"
                                    <?= ($row['status_order'] == 'disetujui') ? 'selected' : ''; ?>>
                                    DISETUJUI
                                    </option>

                                    <option value="selesai"
                                    <?= ($row['status_order'] == 'selesai') ? 'selected' : ''; ?>>
                                    SELESAI
                                    </option>

                                    <option value="gagal"
                                    <?= ($row['status_order'] == 'gagal') ? 'selected' : ''; ?>>
                                    GAGAL
                                    </option>
                                </select>

                                <input 
                                    type="hidden" 
                                    name="update_status_order" 
                                    value="1"
                                >
                            </form>
                        </td>

                        <td>
                            <?php if (!empty($row['bukti_transfer'])) : ?>
                                <a href="../src/uploads/buktiTransfer/<?= htmlspecialchars($row['bukti_transfer']); ?>"
                                target="_blank">
                                Lihat Bukti
                                </a>
                            <?php else : ?>
                                Belum Upload
                            <?php endif; ?>
                        </td>

                        <td></td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="9" style="text-align: center; padding: 20px;">
                        Belum ada reservasi untuk hari ini.
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<!-- ======================
     JAVASCRIPT
====================== -->

<script src="../assets/dashboard.js"></script>

</body>
</html>