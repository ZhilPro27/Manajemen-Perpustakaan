<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("Location: ../login.php");
    exit;
}

require '../koneksi.php';

// Cek apakah ada ID transaksi yang dikirim
if (!isset($_GET['id'])) {
    header("Location: transaksi.php");
    exit;
}

$id_transaksi = (int)$_GET['id'];

// --- Mulai Transaksi Database ---
mysqli_begin_transaction($koneksi);

try {
    // 1. Ambil ID buku dari transaksi yang akan dikembalikan
    $sql_get_buku = "SELECT id_buku FROM transaksi WHERE id_transaksi = ?";
    $stmt_get_buku = mysqli_prepare($koneksi, $sql_get_buku);
    mysqli_stmt_bind_param($stmt_get_buku, "i", $id_transaksi);
    mysqli_stmt_execute($stmt_get_buku);
    $result_buku = mysqli_stmt_get_result($stmt_get_buku);
    
    if (mysqli_num_rows($result_buku) == 0) {
        throw new Exception("Transaksi tidak ditemukan.");
    }
    
    $data_buku = mysqli_fetch_assoc($result_buku);
    $id_buku = $data_buku['id_buku'];

    // 2. Update status transaksi menjadi 'Selesai' dan isi tanggal kembali
    $tanggal_kembali = date('Y-m-d');
    $sql_update_transaksi = "UPDATE transaksi SET status = 'Selesai', tanggal_kembali = ? WHERE id_transaksi = ?";
    $stmt_update_transaksi = mysqli_prepare($koneksi, $sql_update_transaksi);
    mysqli_stmt_bind_param($stmt_update_transaksi, "si", $tanggal_kembali, $id_transaksi);
    mysqli_stmt_execute($stmt_update_transaksi);

    // 3. Tambah (kembalikan) stok buku
    $sql_stok = "UPDATE buku SET stok = stok + 1 WHERE id_buku = ?";
    $stmt_stok = mysqli_prepare($koneksi, $sql_stok);
    mysqli_stmt_bind_param($stmt_stok, "i", $id_buku);
    mysqli_stmt_execute($stmt_stok);

    // Jika semua query berhasil, commit transaksi
    mysqli_commit($koneksi);
    header("Location: transaksi.php?status=sukses_kembali");
    exit;

} catch (Exception $e) {
    // Jika ada error, batalkan semua perubahan
    mysqli_rollback($koneksi);
    $error_message = urlencode($e->getMessage());
    header("Location: transaksi.php?status=gagal&error=" . $error_message);
    exit;
}
?>