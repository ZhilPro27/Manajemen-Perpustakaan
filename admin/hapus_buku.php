<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("Location: ../login.php");
    exit;
}

require '../koneksi.php';

// Cek apakah ada ID yang dikirim
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_buku = $_GET['id'];

// --- Langkah 1: Ambil nama file gambar dari database ---
$sql_gambar = "SELECT gambar FROM buku WHERE id_buku = ?";
$stmt_gambar = mysqli_prepare($koneksi, $sql_gambar);
mysqli_stmt_bind_param($stmt_gambar, "i", $id_buku);
mysqli_stmt_execute($stmt_gambar);
$result_gambar = mysqli_stmt_get_result($stmt_gambar);
$data_gambar = mysqli_fetch_assoc($result_gambar);

if ($data_gambar) {
    // --- Langkah 2: Hapus file gambar dari folder 'uploads' ---
    $file_gambar = "../uploads/" . $data_gambar['gambar'];
    if (file_exists($file_gambar) && $data_gambar['gambar'] != 'default.jpg') {
        unlink($file_gambar);
    }
}

// --- Langkah 3: Hapus data buku dari database ---
$sql_hapus = "DELETE FROM buku WHERE id_buku = ?";
$stmt_hapus = mysqli_prepare($koneksi, $sql_hapus);
mysqli_stmt_bind_param($stmt_hapus, "i", $id_buku);

if (mysqli_stmt_execute($stmt_hapus)) {
    // Jika berhasil, redirect kembali ke halaman utama
    header("Location: buku.php?status=sukses_hapus_buku");
    exit;
} else {
    // Jika gagal, bisa ditambahkan notifikasi error
    header("Location: buku.php?status=gagal_hapus");
    exit;
}
?>