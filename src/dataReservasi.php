<?php
session_start();
include '../config/koneksi.php';

// Cek admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Ambil semua data reservasi
$reservasi = mysqli_query($conn, "SELECT * FROM reservations ORDER BY id_reservasi DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Reservasi</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #FFFBE9;
            color: #333;
        }

        .sidebar {
            width: 230px;
            height: 100vh;
            background-color: white;
            position: fixed;
            padding: 25px 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        }

        .sidebar h2 {
            color: #AD8B73;
            text-align: center;
            margin-bottom: 35px;
        }

        .sidebar a {
            display: block;
            padding: 13px 15px;
            margin-bottom: 12px;
            text-decoration: none;
            color: #AD8B73;
            border-radius: 10px;
            font-weight: 500;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #AD8B73;
            color: white;
        }

        .content {
            margin-left: 260px;
            padding: 35px;
        }

        h1 {
            color: #AD8B73;
            margin-bottom: 5px;
        }

        .subtitle {
            color: #777;
            margin-bottom: 25px;
        }

        .list-box {
            background-color: white;
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
        }

        .list-header,
        .list-row {
            display: grid;
            grid-template-columns: 60px 1.5fr 1.2fr 1fr 0.8fr 0.8fr 1.2fr 1.2fr 1fr;
            gap: 12px;
            align-items: center;
        }

        .list-header {
            color: #AD8B73;
            font-weight: bold;
            padding: 12px 15px;
            font-size: 14px;
        }

        .list-row {
            background-color: #FFFBE9;
            margin-bottom: 12px;
            padding: 15px;
            border-radius: 14px;
            transition: 0.3s;
        }

        .list-row:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 14px rgba(0,0,0,0.08);
        }

        .nama {
            font-weight: bold;
            color: #AD8B73;
        }

        .small {
            font-size: 13px;
            color: #666;
        }

        .badge {
            display: inline-block;
            background-color: white;
            color: #AD8B73;
            padding: 6px 9px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .btn {
            display: inline-block;
            padding: 8px 10px;
            background-color: #AD8B73;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 13px;
            text-align: center;
        }

        .btn:hover {
            background-color: #8c6f5c;
        }

        .btn-light {
            background-color: #CEAB93;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #777;
        }

        @media (max-width: 1100px) {
            .list-header {
                display: none;
            }

            .list-row {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .list-row div::before {
                font-weight: bold;
                color: #AD8B73;
            }

            .content {
                margin-left: 260px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .content {
                margin-left: 0;
                padding: 25px;
            }
        }
    </style>
</head>

<body>

<div class="sidebar">
    <h2>Cafe Pawie</h2>
    <a href="../admin/admin.php">Dashboard</a>
    <a href="dataReservasi.php" class="active">Data Reservasi</a>
    <a href="../login.php">Keluar</a>
</div>

<div class="content">
    <h1>Data Reservasi</h1>
    <p class="subtitle">Daftar reservasi yang dibuat oleh pembeli.</p>

    <div class="list-box">

        <?php if (mysqli_num_rows($reservasi) > 0) { ?>

            <div class="list-header">
                <div>ID</div>
                <div>Nama</div>
                <div>No Telepon</div>
                <div>Tanggal</div>
                <div>Jam</div>
                <div>Orang</div>
                <div>Status Bayar</div>
                <div>Status Order</div>
                <div>Aksi</div>
            </div>

            <?php while ($data = mysqli_fetch_assoc($reservasi)) { ?>

                <div class="list-row">
                    <div>#<?php echo $data['id_reservasi']; ?></div>

                    <div>
                        <div class="nama"><?php echo $data['nama_pemesan']; ?></div>
                    </div>

                    <div><?php echo $data['no_telp_reservasi']; ?></div>

                    <div><?php echo $data['tgl_kunjungan']; ?></div>

                    <div><?php echo $data['jam_kunjungan']; ?></div>

                    <div><?php echo $data['jml_orang']; ?> orang</div>

                    <div>
                        <span class="badge">
                            <?php echo $data['status_bayar']; ?>
                        </span>
                    </div>

                    <div>
                        <form method="POST">
    <input type="hidden" name="id_reservasi" value="<?php echo $data['id_reservasi']; ?>">

    <select name="status_order" onchange="this.form.submit()" class="status-select">
        <option value="Booking" <?php if ($data['status_order'] == 'Booking') echo 'selected'; ?>>
            BOOKING
        </option>

        <option value="Selesai" <?php if ($data['status_order'] == 'Selesai') echo 'selected'; ?>>
            SELESAI
        </option>

        <option value="Gagal" <?php if ($data['status_order'] == 'Gagal') echo 'selected'; ?>>
            GAGAL
        </option>
    </select>

    <input type="hidden" name="update_status" value="1">
</form>
                    </div>

                    <div>
                        <?php if ($data['bukti_transfer'] != '') { ?>
                            <a class="btn btn-light"
                               href="uploads/buktiTransfer/<?php echo $data['bukti_transfer']; ?>"
                               target="_blank">
                                Bukti
                            </a>
                        <?php } ?>

                        <a class="btn"
                           href="../data/resi.php?id_reservasi=<?php echo $data['id_reservasi']; ?>"
                           target="_blank">
                            Resi
                        </a>
                    </div>
                </div>

            <?php } ?>

        <?php } else { ?>

            <div class="empty">
                Belum ada data reservasi.
            </div>

        <?php } ?>

    </div>
</div>

</body>
</html>