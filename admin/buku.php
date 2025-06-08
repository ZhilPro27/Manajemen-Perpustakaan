<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("Location: ../login.php");
    exit;
}

// Memanggil file koneksi
require '../koneksi.php';

// Query untuk mengambil semua data buku
$sql = "SELECT * FROM buku ORDER BY judul ASC";
$query = mysqli_query($koneksi, $sql);

?>
<?php require 'template/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Manajemen Data Buku</h3>
        <a href="tambah_buku.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Buku
        </a>
    </div>

    <hr>

    <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] == 'sukses_tambah_buku'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Berhasil!</strong> Data buku baru telah ditambahkan.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif ($_GET['status'] == 'sukses_edit_buku'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Berhasil!</strong> Data buku telah diperbarui.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif ($_GET['status'] == 'sukses_hapus_buku'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Berhasil!</strong> Data buku telah dihapus.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif ($_GET['status'] == 'gagal_hapus'): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Gagal!</strong> Data buku tidak dapat dihapus.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>Gambar</th>
                    <th>Judul Buku</th>
                    <th>Pengarang</th>
                    <th>Penerbit</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Cek apakah ada data buku
                if (mysqli_num_rows($query) > 0) {
                    $no = 1; // Variabel untuk nomor urut
                    // Looping untuk menampilkan setiap data buku
                    while ($data = mysqli_fetch_assoc($query)) {
                ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <img src="../uploads/<?php echo $data['gambar']; ?>" alt="Cover Buku" width="80">
                            </td>
                            <td><?php echo htmlspecialchars($data['judul']); ?></td>
                            <td><?php echo htmlspecialchars($data['pengarang']); ?></td>
                            <td><?php echo htmlspecialchars($data['penerbit']); ?></td>
                            <td><?php echo $data['stok']; ?></td>
                            <td>
                                <a href="edit_buku.php?id=<?php echo $data['id_buku']; ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="konfirmasiHapus('hapus_buku.php?id=<?php echo $data['id_buku']; ?>')">
                                    <i class="fas fa-trash"></i> Hapus
                                </a>
                            </td>
                        </tr>
                    <?php
                    } // Akhir dari while loop
                } else {
                    // Jika tidak ada data, tampilkan pesan
                    ?>
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data buku.</td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php
require 'template/footer.php';
?>
</body>

</html>