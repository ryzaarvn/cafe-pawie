<?php
session_start();
include '../config/koneksi.php';

// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// Ambil data user
$user = mysqli_query($conn, "SELECT * FROM users WHERE id_user='$id_user'");
$data_user = mysqli_fetch_assoc($user);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Saya - Cafe Pawie</title>

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

        .content h1 {
            color: #AD8B73;
            margin-bottom: 25px;
        }

        .card {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            border: 1px solid #E3CAA5;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            max-width: 600px;
        }

        .profile-row {
            margin-bottom: 18px;
        }

        .profile-row label {
            display: block;
            font-weight: bold;
            color: #AD8B73;
            margin-bottom: 6px;
        }

        .profile-row .value {
            background-color: #FFFBE9;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #E3CAA5;
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
            <a href="akun.php" class="active">Akun Saya</a>
            <a href="reservasi.php">Form Reservasi</a>
            <a href="riwayat.php" class="nav-link">Riwayat</a>
        </div>

        <div class="logout">
            <a href="../login.php">Keluar</a>
        </div>
    </div>

    <div class="content">
        <h1>Akun Saya</h1>

        <div class="card">
            <div class="profile-row">
                <label>Username</label>
                <div class="value">
                    <?php echo $data_user['username']; ?>
                </div>
            </div>

            <div class="profile-row">
                <label>Email</label>
                <div class="value">
                    <?php echo $data_user['email']; ?>
                </div>
            </div>

            <div class="profile-row">
                <label>No Telepon</label>
                <div class="value">
                    <?php echo $data_user['no_telp_akun']; ?>
                </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>