<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("Location: ../login.php");
    exit;
}

require '../koneksi.php';

// Query untuk mengambil data transaksi dengan JOIN ke tabel buku dan anggota
$sql = "SELECT 
            transaksi.id_transaksi,
            anggota.nama AS nama_anggota,
            buku.judul AS judul_buku,
            transaksi.tanggal_pinjam,
            transaksi.tanggal_kembali,
            transaksi.status
        FROM transaksi
        JOIN anggota ON transaksi.id_anggota = anggota.id_anggota
        JOIN buku ON transaksi.id_buku = buku.id_buku
        ORDER BY transaksi.tanggal_pinjam DESC";

$query = mysqli_query($koneksi, $sql);
?>
<?php require 'template/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Data Peminjaman & Pengembalian</h3>
        <a href="tambah_transaksi.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Catat Peminjaman Baru
        </a>
    </div>
    <hr>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>Nama Peminjam</th>
                    <th>Judul Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
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
                            <td><?php echo htmlspecialchars($data['nama_anggota']); ?></td>
                            <td><?php echo htmlspecialchars($data['judul_buku']); ?></td>
                            <td><?php echo date('d-m-Y', strtotime($data['tanggal_pinjam'])); ?></td>
                            <td><?php echo ($data['tanggal_kembali']) ? date('d-m-Y', strtotime($data['tanggal_kembali'])) : 'Belum Kembali'; ?></td>
                            <td>
                                <?php
                                if ($data['status'] == 'Dipinjam') {
                                    echo '<span class="badge bg-warning text-dark">Dipinjam</span>';
                                } else {
                                    echo '<span class="badge bg-success">Selesai</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <?php if ($data['status'] == 'Dipinjam'): ?>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-success" onclick="konfirmasiKembalikan('kembalikan_buku.php?id=<?php echo $data['id_transaksi']; ?>')">
                                        <i class="fas fa-check"></i> Kembalikan
                                    </a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data transaksi.</td>
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