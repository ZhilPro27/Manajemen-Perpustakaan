<?php
require 'koneksi.php';
$judul_halaman = "Katalog Buku";
require 'template/header_public.php';

// --- Logika Pagination ---
$limit = 8; // Jumlah buku per halaman
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$offset = ($halaman - 1) * $limit;

// --- Logika Pencarian ---
$search_query = "";
$search_term = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = mysqli_real_escape_string($koneksi, $_GET['search']);
    $search_query = " WHERE judul LIKE '%$search_term%' OR pengarang LIKE '%$search_term%'";
}

// Query untuk menghitung total data
$total_result = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM buku" . $search_query);
$total_data = mysqli_fetch_assoc($total_result)['total'];
$jumlah_halaman = ceil($total_data / $limit);

// Query untuk mengambil data buku dengan limit dan offset
$sql = "SELECT * FROM buku" . $search_query . " ORDER BY judul ASC LIMIT $limit OFFSET $offset";
$query_buku = mysqli_query($koneksi, $sql);

?>

<h2 class="mb-4">Katalog Buku</h2>

<div class="row mb-4">
    <div class="col-md-6 offset-md-3">
        <form action="katalog.php" method="GET">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Cari judul atau pengarang..." name="search" value="<?php echo htmlspecialchars($search_term); ?>">
                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Cari</button>
            </div>
        </form>
    </div>
</div>

<div class="row row-cols-1 row-cols-md-4 g-4">
    <?php if (mysqli_num_rows($query_buku) > 0): ?>
        <?php while ($buku = mysqli_fetch_assoc($query_buku)): ?>
            <div class="col">
                <div class="card h-100 text-center">
                    <?php if ($buku['stok'] == 0): ?>
                        <span class="badge bg-danger">Stok Habis</span>
                    <?php endif; ?>
                    <img src="uploads/<?php echo $buku['gambar']; ?>" class="card-img-top" alt="Cover Buku" style="height: 300px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($buku['judul']); ?></h5>
                        <p class="card-text text-muted"><?php echo htmlspecialchars($buku['pengarang']); ?></p>
                    </div>
                    <div class="card-footer">
                        <a href="detail_buku.php?id=<?php echo $buku['id_buku']; ?>" class="btn btn-primary w-100">Lihat Detail</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-center">Buku tidak ditemukan.</p>
    <?php endif; ?>
</div>

<nav class="mt-4">
    <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $jumlah_halaman; $i++): ?>
            <li class="page-item <?php if ($i == $halaman) echo 'active'; ?>">
                <a class="page-link" href="?halaman=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search_term); ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>


<?php
require 'template/footer_public.php';
?>