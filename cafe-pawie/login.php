<?php
session_start();
include 'config/koneksi.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query cek user berdasarkan email dan password
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");

    // Jika query error
    if (!$query) {
        die("Query Error: " . mysqli_error($conn));
    }

    // Jika data user ditemukan
    if (mysqli_num_rows($query) > 0) {
    $user = mysqli_fetch_assoc($query);

    // simpan session
    $_SESSION['id_user'] = $user['id_user'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];

    // arahkan sesuai role
    if ($user['role'] == 'admin') {
        header("Location: admin/admin.php");
        exit;
    } else {
        header("Location: dashboard.php");
        exit;
    }

} else {
    echo "<script>
            alert('Email atau password salah!');
            window.location.href='Login.php';
          </script>";
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LOGIN</title>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #FFFBE9;
            display: flex;
            height: 100vh;
        }

        .left {
            width: 50%;
            background: linear-gradient(rgba(173, 139, 115, 0.65), rgba(206, 171, 147, 0.65)), url('public/login.jpeg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;

            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            box-sizing: border-box;
            text-align: center;
        }

        .left h1 {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .left p {
            font-size: 18px;
        }

        .right {
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 300px;
        }

        .login-box h2 {
            margin-bottom: 10px;
            color: #AD8B73;
        }

        .login-box p {
            color: #555;
            margin-bottom: 20px;
        }

        .login-box input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }

        .login-box input:focus {
            outline: none;
            border-color: #AD8B73;
        }

        .login-box button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background: #AD8B73;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-box button:hover {
            background: #8c6f5c;
        }

        .login-box a {
            display: block;
            margin-top: 15px;
            text-align: center;
            color: #AD8B73;
            text-decoration: none;
        }

        .login-box a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="left">
        <h1>Cafe Pawie</h1>
        <p>Heal Your Soul with Cat 🐾</p>
    </div>

    <div class="right">
        <div class="login-box">
            <h2>Login</h2>
            <p>Selamat datang kembali!</p>

            <form method="POST" action="">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">LOGIN</button>
            </form>

            <a href="Register.php">Belum punya akun? Daftar</a>
        </div>
    </div>

</body>
</html>