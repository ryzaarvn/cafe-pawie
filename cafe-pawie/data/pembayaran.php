<?php
session_start();
include '../config/koneksi.php';

// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// ===============================
// JIKA DATANG DARI FORM RESERVASI
// ===============================
if (isset($_POST['buat_reservasi'])) {

    $nama_pemesan = $_POST['nama_pemesan'];
    $no_telp_reservasi = $_POST['no_telp_reservasi'];
    $tgl_kunjungan = $_POST['tgl_kunjungan'];
    $jam_kunjungan = $_POST['jam_kunjungan'];
    $jml_orang = $_POST['jml_orang'];
    $status_bayar = $_POST['status_bayar']; // DP atau Lunas

    $insert = mysqli_query($conn, "INSERT INTO reservations 
        (id_user, nama_pemesan, no_telp_reservasi, tgl_kunjungan, jam_kunjungan, jml_orang, status_bayar, bukti_transfer, status_order)
        VALUES
        ('$id_user', '$nama_pemesan', '$no_telp_reservasi', '$tgl_kunjungan', '$jam_kunjungan', '$jml_orang', '$status_bayar', '', 'Booking')
    ");

    if ($insert) {
        $id_reservasi = mysqli_insert_id($conn);
    } else {
        die("Gagal membuat reservasi: " . mysqli_error($conn));
    }

// ===============================
// JIKA USER UPLOAD BUKTI TRANSFER
// ===============================
} elseif (isset($_POST['upload_bukti'])) {

    $id_reservasi = $_POST['id_reservasi'];

    if (isset($_FILES['bukti_transfer']) && $_FILES['bukti_transfer']['error'] == 0) {

        $nama_file = $_FILES['bukti_transfer']['name'];
        $tmp_file = $_FILES['bukti_transfer']['tmp_name'];

        $folder = "../src/uploads/buktiTransfer/";

        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $ekstensi_diizinkan = ['jpg', 'jpeg', 'png'];
        $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

        if (!in_array($ekstensi, $ekstensi_diizinkan)) {
            die("File harus berupa JPG, JPEG, atau PNG.");
        }

        $nama_baru = "bukti_" . time() . "_" . rand(100, 999) . "." . $ekstensi;
        $lokasi_simpan = $folder . $nama_baru;

        if (move_uploaded_file($tmp_file, $lokasi_simpan)) {

            // Status order tetap Booking.
            // Nanti admin yang ubah jadi Selesai atau Gagal dari dataReservasi.php.
            $update = mysqli_query($conn, "UPDATE reservations SET 
                bukti_transfer = '$nama_baru'
                WHERE id_reservasi = '$id_reservasi'
                AND id_user = '$id_user'
            ");

            if ($update) {
                header("Location: resi.php?id_reservasi=$id_reservasi");
                exit;
            } else {
                die("Gagal update pembayaran: " . mysqli_error($conn));
            }

        } else {
            die("Gagal mengupload bukti transfer.");
        }

    } else {
        die("Bukti transfer belum dipilih.");
    }

// ===============================
// JIKA HALAMAN DIBUKA DARI URL
// ===============================
} elseif (isset($_GET['id_reservasi'])) {

    $id_reservasi = $_GET['id_reservasi'];

} else {
    die("ID reservasi tidak ditemukan!");
}

// ===============================
// AMBIL DATA RESERVASI
// ===============================
$query_reservasi = mysqli_query($conn, "SELECT * FROM reservations 
    WHERE id_reservasi = '$id_reservasi'
    AND id_user = '$id_user'
");

$data_reservasi = mysqli_fetch_assoc($query_reservasi);

if (!$data_reservasi) {
    die("Data reservasi tidak ditemukan!");
}

// ===============================
// HITUNG TOTAL BAYAR
// ===============================
$harga_per_orang = 25000;
$total_lunas = $data_reservasi['jml_orang'] * $harga_per_orang;
$total_dp = $total_lunas * 0.5;

if ($data_reservasi['status_bayar'] == 'DP') {
    $total_bayar = $total_dp;
} else {
    $total_bayar = $total_lunas;
}

// Untuk warna status order
$status_class = strtolower($data_reservasi['status_order']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran - Cafe Pawie</title>

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
        }

        .logout a:hover {
            background-color: #8c6f5c;
        }

        .content {
            flex: 1;
            padding: 40px;
        }

        h1, h2 {
            color: #AD8B73;
        }

        .card {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            border: 1px solid #E3CAA5;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            max-width: 750px;
            margin-bottom: 25px;
        }

        .detail-row {
            margin-bottom: 13px;
        }

        .detail-row b {
            color: #AD8B73;
        }

        .payment-box {
            background-color: #FFFBE9;
            border-left: 5px solid #AD8B73;
            padding: 18px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #E3CAA5;
            border-radius: 8px;
            background-color: #FFFBE9;
            margin: 10px 0 15px;
        }

        button, .btn {
            display: inline-block;
            width: 100%;
            padding: 12px;
            background-color: #AD8B73;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }

        button:hover, .btn:hover {
            background-color: #8c6f5c;
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

        .bukti-img {
            max-width: 250px;
            border-radius: 10px;
            margin-top: 10px;
            border: 1px solid #E3CAA5;
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
            <a href="#" class="active">Pembayaran</a>
            <a href="riwayat.php">Riwayat</a>
        </div>

        <div class="logout">
            <a href="../login.php">Keluar</a>
        </div>
    </div>

    <div class="content">
        <h1>Pembayaran Reservasi</h1>

        <div class="card">
            <h2>Detail Reservasi</h2>

            <div class="detail-row">
                <b>ID Reservasi:</b>
                <?php echo $data_reservasi['id_reservasi']; ?>
            </div>

            <div class="detail-row">
                <b>Nama Pemesan:</b>
                <?php echo $data_reservasi['nama_pemesan']; ?>
            </div>

            <div class="detail-row">
                <b>No Telepon:</b>
                <?php echo $data_reservasi['no_telp_reservasi']; ?>
            </div>

            <div class="detail-row">
                <b>Tanggal Kunjungan:</b>
                <?php echo $data_reservasi['tgl_kunjungan']; ?>
            </div>

            <div class="detail-row">
                <b>Jam Kunjungan:</b>
                <?php echo $data_reservasi['jam_kunjungan']; ?>
            </div>

            <div class="detail-row">
                <b>Jumlah Orang:</b>
                <?php echo $data_reservasi['jml_orang']; ?> orang
            </div>

            <div class="detail-row">
                <b>Harga per Orang:</b>
                Rp <?php echo number_format($harga_per_orang, 0, ',', '.'); ?>
            </div>

            <div class="detail-row">
                <b>Total Jika Lunas:</b>
                Rp <?php echo number_format($total_lunas, 0, ',', '.'); ?>
            </div>

            <div class="detail-row">
                <b>Pilihan Pembayaran:</b>
                <span class="status">
                    <?php echo $data_reservasi['status_bayar']; ?>
                </span>
            </div>

            <div class="detail-row">
                <b>Total yang Harus Dibayar:</b>
                Rp <?php echo number_format($total_bayar, 0, ',', '.'); ?>
            </div>

            <div class="detail-row">
                <b>Status Order:</b>
                <span class="status status-<?php echo $status_class; ?>">
                    <?php echo strtoupper($data_reservasi['status_order']); ?>
                </span>
            </div>
        </div>

        <div class="card">
            <h2>Informasi Pembayaran</h2>

            <div class="payment-box">
                <p><b>Silakan transfer ke rekening berikut:</b></p>
                <p>Bank BCA: <b>1234567890</b></p>
                <p>Atas Nama: <b>Cafe Pawie</b></p>

                <?php if ($data_reservasi['status_bayar'] == 'DP') { ?>
                    <p>Pembayaran DP sebesar 50% dari total.</p>
                <?php } else { ?>
                    <p>Pembayaran Lunas sebesar 100% dari total.</p>
                <?php } ?>

                <p>
                    Total Bayar:
                    <b>Rp <?php echo number_format($total_bayar, 0, ',', '.'); ?></b>
                </p>
            </div>

            <?php if ($data_reservasi['bukti_transfer'] == '') { ?>

                <form action="pembayaran.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_reservasi" value="<?php echo $data_reservasi['id_reservasi']; ?>">

                    <label><b>Upload Bukti Transfer</b></label>
                    <input type="file" name="bukti_transfer" accept="image/*" required>

                    <button type="submit" name="upload_bukti">
                        Upload Bukti Transfer
                    </button>
                </form>

            <?php } else { ?>

                <p><b>Bukti transfer sudah diupload:</b></p>

                <img class="bukti-img"
                     src="../src/uploads/buktiTransfer/<?php echo $data_reservasi['bukti_transfer']; ?>"
                     alt="Bukti Transfer">

                <br><br>

                <a class="btn" href="resi.php?id_reservasi=<?php echo $data_reservasi['id_reservasi']; ?>">
                    Lihat Resi
                </a>

            <?php } ?>
        </div>
    </div>

</div>

</body>
</html>