<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("Location: ../login.php");
    exit;
}

require '../koneksi.php';

// --- Bagian 1: Mengambil ID dan data lama ---
// Cek apakah ada ID yang dikirim dari halaman index.php
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_buku = $_GET['id'];

// Query untuk mengambil data buku berdasarkan ID
$sql_lama = "SELECT * FROM buku WHERE id_buku = ?";
$stmt_lama = mysqli_prepare($koneksi, $sql_lama);
mysqli_stmt_bind_param($stmt_lama, "i", $id_buku);
mysqli_stmt_execute($stmt_lama);
$result = mysqli_stmt_get_result($stmt_lama);
$data_lama = mysqli_fetch_assoc($result);

// Jika data dengan ID tersebut tidak ditemukan, kembalikan ke index
if (!$data_lama) {
    header("Location: index.php");
    exit;
}


// --- Bagian 2: Logika untuk menyimpan data yang diperbarui ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $pengarang = mysqli_real_escape_string($koneksi, $_POST['pengarang']);
    $penerbit = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
    $tahun_terbit = (int)$_POST['tahun_terbit'];
    $isbn = mysqli_real_escape_string($koneksi, $_POST['isbn']);
    $stok = (int)$_POST['stok'];
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    // Default nama gambar adalah nama gambar yang lama
    $nama_gambar_baru = $data_lama['gambar'];

    // Cek apakah user mengupload gambar baru
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        // Hapus gambar lama jika ada
        $file_gambar_lama = "../uploads/" . $data_lama['gambar'];
        if (file_exists($file_gambar_lama) && $data_lama['gambar'] != 'default.jpg') {
            unlink($file_gambar_lama);
        }

        // Proses upload gambar baru
        $gambar = $_FILES['gambar']['name'];
        $lokasi_gambar = $_FILES['gambar']['tmp_name'];
        $nama_gambar_baru = uniqid() . '_' . $gambar;
        $target_dir = "../uploads/";
        move_uploaded_file($lokasi_gambar, $target_dir . $nama_gambar_baru);
    }

    // Query UPDATE
    $sql_update = "UPDATE buku SET judul=?, pengarang=?, penerbit=?, tahun_terbit=?, isbn=?, deskripsi=?, stok=?, gambar=? WHERE id_buku=?";
    $stmt_update = mysqli_prepare($koneksi, $sql_update);
    mysqli_stmt_bind_param($stmt_update, "sssissisi", $judul, $pengarang, $penerbit, $tahun_terbit, $isbn, $deskripsi, $stok, $nama_gambar_baru, $id_buku);

    if (mysqli_stmt_execute($stmt_update)) {
        header("Location: buku.php?status=sukses_edit_buku");
        exit;
    } else {
        $error = "Gagal memperbarui data: " . mysqli_error($koneksi);
    }
}
?>
<?php require 'template/header.php'; ?>

<div class="container mt-4">
    <h3><i class="fas fa-edit"></i> Edit Data Buku</h3>
    <hr>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="edit_buku.php?id=<?php echo $id_buku; ?>" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Buku</label>
                    <input type="text" class="form-control" id="judul" name="judul" value="<?php echo htmlspecialchars($data_lama['judul']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="pengarang" class="form-label">Pengarang</label>
                    <input type="text" class="form-control" id="pengarang" name="pengarang" value="<?php echo htmlspecialchars($data_lama['pengarang']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="penerbit" class="form-label">Penerbit</label>
                    <input type="text" class="form-control" id="penerbit" name="penerbit" value="<?php echo htmlspecialchars($data_lama['penerbit']); ?>" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                    <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" value="<?php echo $data_lama['tahun_terbit']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="stok" class="form-label">Stok</label>
                    <input type="number" class="form-control" id="stok" name="stok" value="<?php echo $data_lama['stok']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="isbn" class="form-label">ISBN (Opsional)</label>
                    <input type="text" class="form-control" id="isbn" name="isbn" value="<?php echo htmlspecialchars($data_lama['isbn']); ?>">
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi / Sinopsis</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"><?php echo htmlspecialchars($data_lama['deskripsi']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="gambar" class="form-label">Ganti Gambar Sampul (Opsional)</label>
            <br>
            <img src="../uploads/<?php echo $data_lama['gambar']; ?>" width="100" class="mb-2 img-thumbnail">
            <input class="form-control" type="file" id="gambar" name="gambar" accept="image/*">
            <div class="form-text">Kosongkan jika tidak ingin mengganti gambar.</div>
        </div>

        <hr>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan Perubahan
        </button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

</body>

</html>