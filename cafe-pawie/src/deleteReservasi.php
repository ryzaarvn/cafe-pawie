<?php
include '../config/koneksi.php';
$id = $_GET['id'];

$query = mysqli_query($conn, "DELETE FROM reservations WHERE id_reservasi = '$id'");

if ($query) {
    echo "<script>alert('Data berhasil dihapus'); window.location='../data/reservasi.php';</script>";
} else {
    echo "Gagal menghapus data.";
}
?>