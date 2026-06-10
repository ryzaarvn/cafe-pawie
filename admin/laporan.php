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
// HAPUS DATA LAPORAN
// ======================

if (isset($_POST['hapus_reservasi'])) {

    $id_reservasi = intval($_POST['id_reservasi']);

    $hapus = mysqli_query($conn,
        "DELETE FROM reservations 
         WHERE id_reservasi = '$id_reservasi'"
    );

    if (!$hapus) {
        die("Hapus data gagal: " . mysqli_error($conn));
    }

    header("Location: laporan.php");
    exit();
}

// ======================
// SETTING DASAR
// ======================

$harga_per_orang = 25000;

$bulan_ini = date('m');
$tahun_ini = date('Y');

// ======================
// TOTAL PENDAPATAN BULAN INI
// Hanya status_order selesai yang dihitung
// ======================

$q_total = mysqli_query($conn,
    "SELECT SUM(jml_orang * $harga_per_orang) AS grand_total
     FROM reservations
     WHERE MONTH(tgl_kunjungan) = '$bulan_ini'
     AND YEAR(tgl_kunjungan) = '$tahun_ini'
     AND status_order = 'selesai'"
);

if (!$q_total) {
    die("Query total pendapatan gagal: " . mysqli_error($conn));
}

$res_total = mysqli_fetch_assoc($q_total);
$total_duit = $res_total['grand_total'] ?? 0;

// ======================
// AMBIL DATA LAPORAN
// Yang tampil hanya selesai dan batal/gagal
// ======================

$query = mysqli_query($conn,
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
     WHERE status_order IN ('selesai', 'batal')
     ORDER BY tgl_kunjungan DESC, jam_kunjungan ASC"
);

if (!$query) {
    die("Query data laporan gagal: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Laporan Keuangan - Cafe Pawie</title>

    <link rel="stylesheet" href="../assets/dashboard.css">
    <link rel="stylesheet" href="../assets/laporan.css">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            color: white;
        }

        .bg-lunas {
            background-color: #28a745;
        }

        .bg-dp {
            background-color: #f0ad4e;
        }

        .bg-selesai {
            background-color: #28a745;
        }

        .bg-batal {
            background-color: #dc3545;
        }

        .text-right {
            text-align: right;
        }

        .subtotal-batal {
            color: #dc3545;
            font-weight: bold;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 7px 10px;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-delete:hover {
            background-color: #b52a37;
        }
    </style>
</head>

<body>

<div class="sidebar">

    <h2>Cafe Pawie</h2>

    <a href="admin.php">
        <i class="fa fa-home"></i>
        Dashboard 
    </a>

    <a href="laporan.php" class="active">
        <i class="fa fa-file-invoice"></i>
        Laporan
    </a>

    <a href="../login.php" class="logout">
        <i class="fa fa-sign-out-alt"></i>
        Keluar
    </a>

</div>

<div class="main-content">

    <div class="header-laporan">

        <h1>Laporan Keuangan</h1>

        <div class="total-box">
            <small>Total Pendapatan Bulan Ini:</small>
            <h4>Rp <?= number_format($total_duit, 0, ',', '.'); ?></h4>
        </div>

    </div>

    <div class="card-laporan">

        <div class="card-body">

            <div class="table-wrapper">

                <table class="laporan-table">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Nama Pemesan</th>
                            <th>No. WA</th>
                            <th>Jumlah</th>
                            <th>Status Bayar</th>
                            <th>Status Order</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (mysqli_num_rows($query) > 0): ?>

                        <?php while ($row = mysqli_fetch_assoc($query)): ?>

                            <?php
                            $status_order = $row['status_order'];

                            if ($status_order == 'selesai') {
                                $subtotal = $row['jml_orang'] * $harga_per_orang;
                                $class_order = 'bg-selesai';
                                $text_order = 'SELESAI';
                            } else {
                                $subtotal = 0;
                                $class_order = 'bg-batal';
                                $text_order = 'GAGAL';
                            }

                            $class_bayar = ($row['status_bayar'] == 'lunas') ? 'bg-lunas' : 'bg-dp';
                            ?>

                            <tr>

                                <td>
                                    #<?= htmlspecialchars($row['id_reservasi']); ?>
                                </td>

                                <td>
                                    <?= date('d/m/Y', strtotime($row['tgl_kunjungan'])); ?>
                                </td>

                                <td>
                                    <i class="fa fa-clock"></i>
                                    <?= htmlspecialchars($row['jam_kunjungan']); ?>
                                </td>

                                <td class="nama-pemesan">
                                    <b><?= htmlspecialchars($row['nama_pemesan']); ?></b>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['no_telp_reservasi']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['jml_orang']); ?> Orang
                                </td>

                                <td>
                                    <span class="badge <?= $class_bayar; ?>">
                                        <?= strtoupper(htmlspecialchars($row['status_bayar'])); ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="badge <?= $class_order; ?>">
                                        <?= $text_order; ?>
                                    </span>
                                </td>

                                <td class="text-right <?= ($status_order == 'batal') ? 'subtotal-batal' : 'subtotal'; ?>">
                                    Rp <?= number_format($subtotal, 0, ',', '.'); ?>
                                </td>

                                <td>
                                    <form method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus data ini?');">

                                        <input type="hidden"
                                            name="id_reservasi"
                                            value="<?= $row['id_reservasi']; ?>">

                                        <button type="submit"
                                                name="hapus_reservasi"
                                                class="btn-delete"
                                                title="Hapus Data">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                                
                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="11" class="empty-data">
                                Belum ada data reservasi selesai atau gagal.
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<script src="../assets/laporan.js"></script>

</body>
</html>