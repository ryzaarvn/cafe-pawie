<?php
session_start();
include '../config/koneksi.php';

// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// PROSES HAPUS RIWAYAT
if (isset($_POST['hapus'])) {
    $id_reservasi = $_POST['id_reservasi'];

    // Hapus hanya data milik user yang sedang login
    $hapus = mysqli_query($conn, "DELETE FROM reservations 
        WHERE id_reservasi = '$id_reservasi' 
        AND id_user = '$id_user'
    ");

    if ($hapus) {
        echo "<script>
                alert('Riwayat berhasil dihapus!');
                window.location.href='riwayat.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Gagal menghapus riwayat!');
                window.location.href='riwayat.php';
              </script>";
        exit;
    }
}

// Ambil semua riwayat reservasi user
$query = mysqli_query($conn, "SELECT * FROM reservations 
    WHERE id_user = '$id_user' 
    ORDER BY id_reservasi DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Reservasi - Cafe Pawie</title>

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

        h1 {
            color: #AD8B73;
            margin-bottom: 25px;
        }

        .card {
            background-color: white;
            border-radius: 15px;
            border: 1px solid #E3CAA5;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            padding: 25px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        th {
            background-color: #AD8B73;
            color: white;
            padding: 12px;
            text-align: left;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #E3CAA5;
        }

        tr:hover {
            background-color: #FFFBE9;
        }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 8px;
            background-color: #FFFBE9;
            color: #AD8B73;
            font-weight: bold;
            border: 1px solid #E3CAA5;
            font-size: 13px;
        }

        .status-booking {
            background-color: #e8f5e9;
            color: #1f9d3a;
            border: 1px solid #1f9d3a;
        }

        .status-selesai {
            background-color: #eeeeee;
            color: #555;
            border: 1px solid #999;
        }

        .status-gagal {
            background-color: #fdecea;
            color: #b85c5c;
            border: 1px solid #b85c5c;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            background-color: #AD8B73;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 13px;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #8c6f5c;
        }

        .btn-secondary {
            background-color: #CEAB93;
        }

        .btn-danger {
            background-color: #b85c5c;
        }

        .btn-danger:hover {
            background-color: #934646;
        }

        .aksi {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .aksi form {
            margin: 0;
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: #777;
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
            <a href="reservasi.php">Form Reservasi</a>
            <a href="riwayat.php" class="active">Riwayat</a>
        </div>

        <div class="logout">
            <a href="../login.php">Keluar</a>
        </div>
    </div>

    <div class="content">
        <h1>Riwayat Reservasi</h1>

        <div class="card">

            <?php if (mysqli_num_rows($query) > 0) { ?>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Pemesan</th>
                            <th>No Telepon</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Jumlah Orang</th>
                            <th>Status Bayar</th>
                            <th>Status Order</th>
                            <th>Bukti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($data = mysqli_fetch_assoc($query)) { ?>

                            <?php 
                            $status_class = strtolower($data['status_order']);
                            ?>

                            <tr>
                                <td><?php echo $data['id_reservasi']; ?></td>
                                <td><?php echo $data['nama_pemesan']; ?></td>
                                <td><?php echo $data['no_telp_reservasi']; ?></td>
                                <td><?php echo $data['tgl_kunjungan']; ?></td>
                                <td><?php echo $data['jam_kunjungan']; ?></td>
                                <td><?php echo $data['jml_orang']; ?> orang</td>

                                <td>
                                    <span class="status">
                                        <?php echo $data['status_bayar']; ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="status status-<?php echo $status_class; ?>">
                                        <?php echo strtoupper($data['status_order']); ?>
                                    </span>
                                </td>

                                <td>
                                    <?php if ($data['bukti_transfer'] != '') { ?>
                                        <a class="btn btn-secondary" 
                                           href="../src/uploads/buktiTransfer/<?php echo $data['bukti_transfer']; ?>" 
                                           target="_blank">
                                            Lihat Bukti
                                        </a>
                                    <?php } else { ?>
                                        Belum ada
                                    <?php } ?>
                                </td>

                                <td>
                                    <div class="aksi">

                                        <?php if ($data['bukti_transfer'] != '') { ?>
                                            <a class="btn" 
                                               href="resi.php?id_reservasi=<?php echo $data['id_reservasi']; ?>" 
                                               target="_blank">
                                                Lihat Resi
                                            </a>
                                        <?php } else { ?>
                                            <a class="btn" 
                                               href="pembayaran.php?id_reservasi=<?php echo $data['id_reservasi']; ?>">
                                                Bayar
                                            </a>
                                        <?php } ?>

                                        <form method="POST" onsubmit="return confirm('Yakin ingin menghapus riwayat ini?');">
                                            <input type="hidden" name="id_reservasi" value="<?php echo $data['id_reservasi']; ?>">
                                            <button type="submit" name="hapus" class="btn btn-danger">
                                                Hapus
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            <?php } else { ?>

                <div class="empty">
                    <h3>Belum ada riwayat reservasi</h3>
                    <p>Kamu belum pernah membuat reservasi.</p>
                    <a class="btn" href="reservasi.php">Buat Reservasi</a>
                </div>

            <?php } ?>

        </div>
    </div>

</div>

</body>
</html>