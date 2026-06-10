<?php
include 'config/koneksi.php';

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Email sudah terdaftar!');</script>";
    } else {
        $query = "INSERT INTO users (username, email, no_telp_akun, password, role) 
                  VALUES ('$username', '$email', '$no_telp', '$password', 'pembeli')";

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Registrasi berhasil!'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Registrasi gagal');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>REGISTER</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #E3CAA5, #FFFB E9);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            width: 320px;
            text-align: center;
        }

        .card h2 {
            color: #AD8B73;
            margin-bottom: 5px;
        }

        .card p {
            color: #777;
            margin-bottom: 20px;
        }

        .card input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .card input:focus {
            outline: none;
            border-color: #AD8B73;
        }

        .card button {
            width: 100%;
            padding: 10px;
            background: #AD8B73;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        .card button:hover {
            background: #8c6f5c;
        }

        .card a {
            display: block;
            margin-top: 10px;
            color: #AD8B73;
            text-decoration: none;
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
            color: #AD8B73;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

<div class="card">
    <div class="logo">Cafe Pawie 🐾</div>
    <h2>Register</h2>
    <p>Buat akun baru</p>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="no_telp" placeholder="No Telepon" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="register">REGISTER</button>
    </form>

    <a href="login.php">Sudah punya akun? Login</a>
</div>

</body>
</html>