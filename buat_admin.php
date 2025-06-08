<?php
// buat_admin.php

require 'koneksi.php';

// Data admin yang ingin kita pastikan ada di database
$nama_lengkap = "Administrator Utama";
$username     = "admin";
$password     = "admin123"; // Password yang mudah diingat

// --- Proses Hashing Password ---
// Ini adalah langkah paling penting. Kita tidak menyimpan "admin123" ke database,
// tapi hasil hash-nya.
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// --- Cek apakah username "admin" sudah ada ---
$query_cek = "SELECT * FROM admin WHERE username = ?";
$stmt_cek = mysqli_prepare($koneksi, $query_cek);
mysqli_stmt_bind_param($stmt_cek, "s", $username);
mysqli_stmt_execute($stmt_cek);
$result_cek = mysqli_stmt_get_result($stmt_cek);

if (mysqli_num_rows($result_cek) > 0) {
    // Jika user sudah ada, kita UPDATE passwordnya saja
    $query_update = "UPDATE admin SET password = ?, nama_lengkap = ? WHERE username = ?";
    $stmt_update = mysqli_prepare($koneksi, $query_update);
    mysqli_stmt_bind_param($stmt_update, "sss", $hashed_password, $nama_lengkap, $username);
    
    if (mysqli_stmt_execute($stmt_update)) {
        echo "<h1>Berhasil!</h1>";
        echo "<p>Password untuk user 'admin' telah di-reset/diperbarui.</p>";
        echo "<p>Silakan coba login kembali menggunakan:</p>";
        echo "<ul><li>Username: <strong>admin</strong></li><li>Password: <strong>admin123</strong></li></ul>";
    } else {
        echo "<h1>Gagal!</h1>";
        echo "<p>Gagal memperbarui data admin: " . mysqli_error($koneksi) . "</p>";
    }

} else {
    // Jika user belum ada, kita INSERT data baru
    $query_insert = "INSERT INTO admin (nama_lengkap, username, password) VALUES (?, ?, ?)";
    $stmt_insert = mysqli_prepare($koneksi, $query_insert);
    mysqli_stmt_bind_param($stmt_insert, "sss", $nama_lengkap, $username, $hashed_password);

    if (mysqli_stmt_execute($stmt_insert)) {
        echo "<h1>Berhasil!</h1>";
        echo "<p>User 'admin' baru telah dibuat.</p>";
        echo "<p>Silakan coba login menggunakan:</p>";
        echo "<ul><li>Username: <strong>admin</strong></li><li>Password: <strong>admin123</strong></li></ul>";
    } else {
        echo "<h1>Gagal!</h1>";
        echo "<p>Gagal membuat admin baru: " . mysqli_error($koneksi) . "</p>";
    }
}

echo "<br><a href='login.php'>Kembali ke Halaman Login</a>";
echo "<p style='color:red;'><strong>PENTING: Setelah berhasil, segera hapus file 'buat_admin.php' ini dari server Anda!</strong></p>";

?>