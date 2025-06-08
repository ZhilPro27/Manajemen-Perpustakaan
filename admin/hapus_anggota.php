<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("Location: ../login.php");
    exit;
}

require '../koneksi.php';

// Cek ID dari URL
if (!isset($_GET['id'])) {
    header("Location: anggota.php");
    exit;
}

$id_anggota = $_GET['id'];

// Hapus data anggota dari database
$sql_hapus = "DELETE FROM anggota WHERE id_anggota = ?";
$stmt_hapus = mysqli_prepare($koneksi, $sql_hapus);
mysqli_stmt_bind_param($stmt_hapus, "i", $id_anggota);

if (mysqli_stmt_execute($stmt_hapus)) {
    header("Location: anggota.php?status=sukses_hapus");
    exit;
} else {
    // Jika gagal, mungkin karena anggota masih punya tanggungan buku
    $error_message = urlencode(mysqli_error($koneksi));
    header("Location: anggota.php?status=gagal_hapus&error=" . $error_message);
    exit;
}
?>