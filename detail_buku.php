<?php
require 'koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: katalog.php");
    exit;
}

$id_buku = (int)$_GET['id'];
$result = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku = $id_buku");
$buku = mysqli_fetch_assoc($result);

if (!$buku) {
    echo "Buku tidak ditemukan.";
    exit;
}

$judul_halaman = $buku['judul']; // Set judul halaman sesuai judul buku
require 'template/header_public.php';
?>

<div class="row">
    <div class="col-md-4 text-center">
        <img src="uploads/<?php echo $buku['gambar']; ?>" class="img-fluid rounded shadow-sm" alt="Cover Buku">
    </div>

    <div class="col-md-8">
        <h2><?php echo htmlspecialchars($buku['judul']); ?></h2>
        <p class="text-muted">oleh <?php echo htmlspecialchars($buku['pengarang']); ?></p>
        <hr>

        <table class="table table-striped">
            <tr>
                <th width="200px">Penerbit</th>
                <td><?php echo htmlspecialchars($buku['penerbit']); ?></td>
            </tr>
            <tr>
                <th>Tahun Terbit</th>
                <td><?php echo $buku['tahun_terbit']; ?></td>
            </tr>
            <tr>
                <th>ISBN</th>
                <td><?php echo htmlspecialchars($buku['isbn']); ?></td>
            </tr>
            <tr>
                <th>Status Ketersediaan</th>
                <td>
                    <?php if ($buku['stok'] > 0): ?>
                        <span class="badge bg-success">Tersedia (<?php echo $buku['stok']; ?> buah)</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Stok Habis</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <hr>
        <h4>Deskripsi</h4>
        <p style="text-align: justify;">
            <?php
            // nl2br() berfungsi untuk mengubah baris baru (enter) menjadi tag <br>
            // sehingga format paragraf tetap terjaga.
            echo nl2br(htmlspecialchars($buku['deskripsi']));
            ?>
        </p>
        <a href="katalog.php" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i> Kembali ke Katalog</a>
    </div>
</div>


<?php
require 'template/footer_public.php';
?>