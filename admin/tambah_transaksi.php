<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("Location: ../login.php");
    exit;
}

require '../koneksi.php';

// Ambil data anggota dan buku untuk dropdown
$anggota = mysqli_query($koneksi, "SELECT * FROM anggota ORDER BY nama ASC");
$buku = mysqli_query($koneksi, "SELECT * FROM buku WHERE stok > 0 ORDER BY judul ASC");


// Logika simpan data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_anggota = (int)$_POST['id_anggota'];
    $id_buku = (int)$_POST['id_buku'];
    $tanggal_pinjam = date('Y-m-d'); // Tanggal hari ini

    // Mulai transaksi database untuk menjaga integritas data
    mysqli_begin_transaction($koneksi);

    try {
        // 1. Masukkan data ke tabel transaksi
        $sql_pinjam = "INSERT INTO transaksi (id_anggota, id_buku, tanggal_pinjam, status) VALUES (?, ?, ?, 'Dipinjam')";
        $stmt_pinjam = mysqli_prepare($koneksi, $sql_pinjam);
        mysqli_stmt_bind_param($stmt_pinjam, "iis", $id_anggota, $id_buku, $tanggal_pinjam);
        mysqli_stmt_execute($stmt_pinjam);

        // 2. Kurangi stok buku
        $sql_stok = "UPDATE buku SET stok = stok - 1 WHERE id_buku = ?";
        $stmt_stok = mysqli_prepare($koneksi, $sql_stok);
        mysqli_stmt_bind_param($stmt_stok, "i", $id_buku);
        mysqli_stmt_execute($stmt_stok);

        // Jika semua query berhasil, commit transaksi
        mysqli_commit($koneksi);
        header("Location: transaksi.php?status=sukses_pinjam");
        exit;

    } catch (mysqli_sql_exception $exception) {
        // Jika ada error, batalkan semua perubahan (rollback)
        mysqli_rollback($koneksi);
        $error = "Gagal memproses transaksi: " . $exception->getMessage();
    }
}
?>
<?php require 'template/header.php'; ?>

<div class="container mt-4">
    <h3><i class="fas fa-hand-holding-heart"></i> Catat Peminjaman Baru</h3>
    <hr>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="tambah_transaksi.php" method="POST">
        <div class="mb-3">
            <label for="id_anggota" class="form-label">Pilih Anggota</label>
            <select class="form-select" id="id_anggota" name="id_anggota" required>
                <option value="" disabled selected>-- Pilih Nama Anggota --</option>
                <?php while($data_anggota = mysqli_fetch_assoc($anggota)): ?>
                    <option value="<?php echo $data_anggota['id_anggota']; ?>">
                        <?php echo htmlspecialchars($data_anggota['nama']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="id_buku" class="form-label">Pilih Buku yang Dipinjam</label>
            <select class="form-select" id="id_buku" name="id_buku" required>
                <option value="" disabled selected>-- Pilih Judul Buku --</option>
                <?php while($data_buku = mysqli_fetch_assoc($buku)): ?>
                    <option value="<?php echo $data_buku['id_buku']; ?>">
                        <?php echo htmlspecialchars($data_buku['judul']) . " (Stok: " . $data_buku['stok'] . ")"; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <hr>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Transaksi</button>
        <a href="transaksi.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

</body>
</html>