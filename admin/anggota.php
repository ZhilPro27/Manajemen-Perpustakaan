<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("Location: ../login.php");
    exit;
}

require '../koneksi.php';

// Query untuk mengambil semua data anggota
$sql = "SELECT * FROM anggota ORDER BY nama ASC";
$query = mysqli_query($koneksi, $sql);
?>
<?php require 'template/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Manajemen Data Anggota</h3>
        <a href="tambah_anggota.php" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Tambah Anggota
        </a>
    </div>

    <hr>


    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>Nama Anggota</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Tanggal Daftar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($query) > 0) {
                    $no = 1;
                    while ($data = mysqli_fetch_assoc($query)) {
                ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($data['nama']); ?></td>
                            <td><?php echo htmlspecialchars($data['email']); ?></td>
                            <td><?php echo htmlspecialchars($data['telepon']); ?></td>
                            <td><?php echo date('d-m-Y', strtotime($data['tanggal_daftar'])); ?></td>
                            <td>
                                <a href="edit_anggota.php?id=<?php echo $data['id_anggota']; ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="konfirmasiHapus('hapus_anggota.php?id=<?php echo $data['id_anggota']; ?>')">
                                    <i class="fas fa-trash"></i> Hapus
                                </a>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data anggota.</td>
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