<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("Location: ../login.php");
    exit;
}

require '../koneksi.php';

// Cek apakah form sudah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $pengarang = mysqli_real_escape_string($koneksi, $_POST['pengarang']);
    $penerbit = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
    $tahun_terbit = (int)$_POST['tahun_terbit'];
    $isbn = mysqli_real_escape_string($koneksi, $_POST['isbn']);
    $stok = (int)$_POST['stok'];
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    // --- Proses Upload Gambar ---
    $gambar = $_FILES['gambar']['name'];
    $lokasi_gambar = $_FILES['gambar']['tmp_name'];
    $nama_gambar_unik = uniqid() . '_' . $gambar; // Membuat nama file unik
    $target_dir = "../uploads/";

    // Pindahkan file yang diupload ke folder uploads
    if (move_uploaded_file($lokasi_gambar, $target_dir . $nama_gambar_unik)) {
        // --- Proses Insert ke Database ---
        $sql = "INSERT INTO buku (judul, pengarang, penerbit, tahun_terbit, isbn, deskripsi, stok, gambar) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "sssssiss", $judul, $pengarang, $penerbit, $tahun_terbit, $isbn, $deskripsi, $stok, $nama_gambar_unik);

        // Eksekusi statement
        if (mysqli_stmt_execute($stmt)) {
            // Jika berhasil, redirect ke halaman utama manajemen buku
            header("Location: buku.php?status=sukses_tambah_buku");
            exit;
        } else {
            $error = "Gagal menyimpan data ke database: " . mysqli_error($koneksi);
        }
    } else {
        // Jika gambar gagal diupload
        $error = "Gagal mengupload gambar.";
    }
}
?>

<?php require 'template/header.php'; ?>

<div class="container mt-4">
    <h3><i class="fas fa-book-medical"></i> Tambah Buku Baru</h3>
    <hr>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form action="tambah_buku.php" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Buku</label>
                    <input type="text" class="form-control" id="judul" name="judul" required>
                </div>
                <div class="mb-3">
                    <label for="pengarang" class="form-label">Pengarang</label>
                    <input type="text" class="form-control" id="pengarang" name="pengarang" required>
                </div>
                <div class="mb-3">
                    <label for="penerbit" class="form-label">Penerbit</label>
                    <input type="text" class="form-control" id="penerbit" name="penerbit" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                    <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" required>
                </div>
                <div class="mb-3">
                    <label for="stok" class="form-label">Stok</label>
                    <input type="number" class="form-control" id="stok" name="stok" required>
                </div>
                <div class="mb-3">
                    <label for="isbn" class="form-label">ISBN (Opsional)</label>
                    <input type="text" class="form-control" id="isbn" name="isbn">
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi / Sinopsis</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"></textarea>
        </div>
        <div class="mb-3">
            <label for="gambar" class="form-label">Gambar Sampul</label>
            <input class="form-control" type="file" id="gambar" name="gambar" accept="image/*">
            <div class="form-text">Pilih file gambar (format: jpg, jpeg, png).</div>
        </div>

        <hr>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan Buku
        </button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

</body>

</html>