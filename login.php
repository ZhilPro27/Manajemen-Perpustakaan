<?php
// login.php

// Memulai session
session_start();

// Jika user sudah login, redirect ke halaman admin
if (isset($_SESSION['status']) && $_SESSION['status'] == 'login') {
    header("Location: admin/index.php");
    exit;
}

// Memanggil file koneksi
require 'koneksi.php';

// Cek apakah form sudah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Melakukan query untuk mencari user berdasarkan username
    $sql = "SELECT * FROM admin WHERE username = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Cek apakah username ditemukan
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // Memverifikasi password
        if (password_verify($password, $user['password'])) {
            // Jika password cocok, buat session
            $_SESSION['id_admin'] = $user['id_admin'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['status'] = "login";

            // Redirect ke halaman dashboard admin
            header("Location: admin/index.php");
            exit;
        }
    }

    // Jika username atau password salah
    $error = "Username atau password salah!";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Sistem Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            max-width: 400px;
            width: 100%;
        }
    </style>
</head>

<body class="login-bg">

    <div class="login-container">
        <div class="card shadow-sm login-card">
            <div class="card-body p-5">
                <h3 class="card-title text-center mb-4">Login Admin</h3>

                <?php if (isset($error)) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <a href="index.php" class="text-decoration-none">
                        <i class="fas fa-arrow-left"></i> Kembali ke Halaman Utama
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>