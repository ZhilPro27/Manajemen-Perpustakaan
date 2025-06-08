<?php
// koneksi.php

// Variabel untuk koneksi database
$host = "localhost";    // Nama host database
$user = "root";         // Username database
$pass = "";             // Password database (kosongkan jika tidak ada)
$db   = "db_perpustakaan"; // Nama database yang sudah kita buat

// Membuat koneksi ke database
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Memeriksa apakah koneksi berhasil atau gagal
if (!$koneksi) {
    // Jika gagal, tampilkan pesan error dan hentikan skrip
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Jika Anda ingin memastikan koneksi berhasil saat membuka file ini,
// hapus tanda komentar dari baris di bawah ini untuk sementara.
// echo "Koneksi ke database berhasil!";

?>