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