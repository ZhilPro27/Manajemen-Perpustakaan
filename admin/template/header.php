<?php
// admin/template/header.php

// Kita tidak perlu session_start() di sini jika sudah ada di file utama,
// tapi tidak masalah jika ada lagi. Untuk keamanan, kita tambahkan pengecekan.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Memeriksa apakah user sudah login
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    // Sesuaikan path ../ tergantung dari mana file ini dipanggil
    // Untuk amannya kita buat path absolut dari root proyek
    header("Location: /perpustakaan/login.php");
    exit;
}

// --- INI BAGIAN PENTINGNYA ---
// Mendeteksi halaman aktif berdasarkan nama file
$halaman_aktif = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="index.php"><i class="fas fa-book-open"></i> Perpustakaan Digital</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
            <a class="nav-link <?php echo ($halaman_aktif == 'index.php') ? 'active' : ''; ?>" href="index.php">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($halaman_aktif == 'buku.php' || $halaman_aktif == 'tambah_buku.php' || $halaman_aktif == 'edit_buku.php') ? 'active' : ''; ?>" href="buku.php">Manajemen Buku</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($halaman_aktif == 'anggota.php' || $halaman_aktif == 'tambah_anggota.php' || $halaman_aktif == 'edit_anggota.php') ? 'active' : ''; ?>" href="anggota.php">Manajemen Anggota</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($halaman_aktif == 'transaksi.php' || $halaman_aktif == 'tambah_transaksi.php') ? 'active' : ''; ?>" href="transaksi.php">Manajemen Transaksi</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo (strpos($halaman_aktif, 'laporan') !== false) ? 'active' : ''; ?>" href="laporan.php">Laporan</a>
        </li>
        <li class="nav-item ms-3">
          <a class="nav-link btn btn-danger text-white px-3" href="logout.php">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">