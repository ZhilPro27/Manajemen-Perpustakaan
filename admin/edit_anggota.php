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

// Ambil data lama anggota
$sql_lama = "SELECT * FROM anggota WHERE id_anggota = ?";
$stmt_lama = mysqli_prepare($koneksi, $sql_lama);
mysqli_stmt_bind_param($stmt_lama, "i", $id_anggota);
mysqli_stmt_execute($stmt_lama);
$result = mysqli_stmt_get_result($stmt_lama);
$data_lama = mysqli_fetch_assoc($result);

if (!$data_lama) {
    header("Location: anggota.php");
    exit;
}

// Logika untuk UPDATE data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $telepon = mysqli_real_escape_string($koneksi, $_POST['telepon']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    $sql_update = "UPDATE anggota SET nama=?, email=?, telepon=?, alamat=? WHERE id_anggota=?";
    $stmt_update = mysqli_prepare($koneksi, $sql_update);
    mysqli_stmt_bind_param($stmt_update, "ssssi", $nama, $email, $telepon, $alamat, $id_anggota);

    if (mysqli_stmt_execute($stmt_update)) {
        header("Location: anggota.php?status=sukses_edit");
        exit;
    } else {
        $error = "Gagal memperbarui data: " . mysqli_error($koneksi);
    }
}
?>
<?php require 'template/header.php'; ?>

<div class="container mt-4">
    <h3><i class="fas fa-user-edit"></i> Edit Data Anggota</h3>
    <hr>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="edit_anggota.php?id=<?php echo $id_anggota; ?>" method="POST">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($data_lama['nama']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Alamat Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($data_lama['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="telepon" class="form-label">Nomor Telepon</label>
            <input type="text" class="form-control" id="telepon" name="telepon" value="<?php echo htmlspecialchars($data_lama['telepon']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3"><?php echo htmlspecialchars($data_lama['alamat']); ?></textarea>
        </div>
        
        <hr>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan Perubahan
        </button>
        <a href="anggota.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

</body>
</html>