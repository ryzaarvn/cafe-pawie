<?php
include '../config/koneksi.php';
$id = $_GET['id'];

$update = mysqli_query($conn, "UPDATE reservations SET status_bayar = 'lunas', status_order = 'selesai', status_kedatangan = 'sudah' WHERE id_reservasi = '$id'");

if ($update) {
    header("Location: ../dashboard.php");
} else {
    echo "Gagal mengupdate data.";
}
?>