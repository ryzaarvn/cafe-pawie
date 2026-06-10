<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

$user = mysqli_query($conn, "SELECT * FROM users WHERE id_user='$id_user'");
$data_user = mysqli_fetch_assoc($user);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Cafe Pawie</title>

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
            transition: 0.3s;
        }

        .logout a:hover {
            background-color: #8c6f5c;
        }

        .content {
            flex: 1;
            padding: 40px;
        }

        .hero-cafe {
            background-color: #AD8B73;
            color: white;
            padding: 40px;
            border-radius: 15px;
            margin-bottom: 25px;
        }

        .hero-cafe h1 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 32px;
        }

        .hero-cafe p {
            margin-bottom: 0;
            font-size: 18px;
        }

        .info-wrapper {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .card-cafe {
            flex: 1;
            background-color: white;
            padding: 25px;
            border-radius: 15px;
            border: 1px solid #E3CAA5;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .card-cafe h3,
        .judul {
            color: #AD8B73;
        }

        .card-cafe h3 {
            margin-top: 0;
            margin-bottom: 15px;
        }

        .card-cafe ul {
            margin-bottom: 0;
        }

        .card-cafe li {
            margin-bottom: 8px;
        }

        .judul {
            margin-top: 0;
            margin-bottom: 20px;
        }

        .cat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
        }

        .cat-card {
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
            text-align: center;
            border: 1px solid #E3CAA5;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .cat-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            display: block;
        }

        .cat-card h4 {
            color: #AD8B73;
            padding: 12px;
            margin: 0;
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

            .info-wrapper {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

<div class="layout">

    <div class="sidebar">
        <h2>Cafe Pawie</h2>

        <div class="menu">
            <a href="dashboard.php" class="active">Dashboard Cafe</a>
            <a href="data/akun.php">Akun Saya</a>
            <a href="data/reservasi.php">Form Reservasi</a>
            <a href="data/riwayat.php">Riwayat</a>
        </div>

        <div class="logout">
            <a href="login.php">Keluar</a>
        </div>
    </div>

    <div class="content">

        <div class="hero-cafe">
            <h1>Selamat Datang di Cafe Pawie!</h1>
            <p>Tempat terbaik untuk bersantai dan bermain bersama kucing-kucing lucu.</p>
        </div>

        <div class="info-wrapper">
            <div class="card-cafe">
                <h3>Jam Operasional</h3>
                <ul>
                    <li>Senin - Jumat: 10:00 - 21:00</li>
                    <li>Sabtu - Minggu: 09:00 - 22:00</li>
                    <li>Libur Nasional: Tutup</li>
                </ul>
            </div>

            <div class="card-cafe">
                <h3>Peraturan Cafe Pawie</h3>
                <ul>
                    <li>Cuci tangan sebelum masuk ke dalam ruangan.</li>
                    <li>Jangan memberi makan kucing sembarangan.</li>
                    <li>Jangan mengganggu kucing saat sedang tidur.</li>
                </ul>
            </div>
        </div>

        <h2 class="judul">Kenalan Yuk dengan Kucing di Cafe Pawie</h2>

        <div class="cat-grid">
            <?php
            $kucing = [
                ["Birman", "public/birman.jpeg"],
                ["Maine Coon", "public/maine_coon.jpeg"],
                ["Munchkin", "public/munchkin.jpeg"],
                ["Persia", "public/persia.jpeg"],
                ["Ragdoll", "public/ragdoll.jpeg"],
                ["Scottish Fold", "public/scottish_fold.jpeg"],
                ["Siberia", "public/siberia.jpeg"]
            ];

            foreach ($kucing as $data) {
            ?>
                <div class="cat-card">
                    <img src="<?php echo $data[1]; ?>" alt="<?php echo $data[0]; ?>">
                    <h4><?php echo $data[0]; ?></h4>
                </div>
            <?php } ?>
        </div>

    </div>

</div>

</body>
</html>