<?php
$conn = mysqli_connect("localhost", "root", "", "db_cafe", "3306");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>