<?php
require 'koneksi.php';
$judul_halaman = "Selamat Datang di Perpustakaan Digital";
require 'template/header_public.php';

// Ambil 4 buku terbaru untuk ditampilkan
$buku_terbaru = mysqli_query($koneksi, "SELECT * FROM buku WHERE stok > 0 ORDER BY id_buku DESC LIMIT 4");
$query_populer = mysqli_query($koneksi, "SELECT 
                                            buku.*, 
                                            COUNT(transaksi.id_buku) AS jumlah_dipinjam
                                        FROM transaksi
                                        JOIN buku ON transaksi.id_buku = buku.id_buku
                                        GROUP BY transaksi.id_buku
                                        ORDER BY jumlah_dipinjam DESC
                                        LIMIT 3");

$buku_populer_data = [];
while ($data = mysqli_fetch_assoc($query_populer)) {
    $buku_populer_data[] = $data;
}
?>

<div class="hero-section position-relative">
    <video playsinline autoplay muted loop class="hero-video">
        <source src="assets/video/hero-video.mp4" type="video/mp4">
        Browser Anda tidak mendukung tag video.
    </video>

    <div class="hero-content text-center text-white d-flex align-items-center justify-content-center">
        <div class="py-5">
            <h1 class="display-5 fw-bold">Jelajahi Dunia Melalui Kata</h1>
            <p class="fs-4">Perpustakaan Digital kami menyediakan akses tak terbatas ke lautan pengetahuan dan imajinasi.</p>
            <a href="katalog.php" class="btn btn-primary btn-lg">Mulai Menjelajah <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row text-center">
        <div class="col-md-4">
            <div class="p-3">
                <i class="fas fa-book-reader fa-3x text-primary mb-3"></i>
                <h4 class="fw-bold">Koleksi Beragam</h4>
                <p class="text-muted">Ribuan judul buku dari berbagai genre siap untuk Anda jelajahi.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3">
                <i class="fas fa-search-plus fa-3x text-primary mb-3"></i>
                <h4 class="fw-bold">Pencarian Mudah</h4>
                <p class="text-muted">Temukan buku yang Anda inginkan dengan cepat melalui fitur pencarian kami.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3">
                <i class="fas fa-users fa-3x text-primary mb-3"></i>
                <h4 class="fw-bold">Komunitas Pembaca</h4>
                <p class="text-muted">Bergabunglah dengan komunitas untuk berdiskusi dan berbagi ulasan.</p>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-4">Koleksi Terpopuler</h2>

        <?php if (!empty($buku_populer_data)): ?>
        <div id="carouselBukuPopuler" class="carousel slide carousel-dark" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <?php foreach ($buku_populer_data as $index => $buku): ?>
                <button type="button" data-bs-target="#carouselBukuPopuler" data-bs-slide-to="<?php echo $index; ?>" class="<?php echo ($index == 0) ? 'active' : ''; ?>"></button>
                <?php endforeach; ?>
            </div>

            <div class="carousel-inner">
                <?php foreach ($buku_populer_data as $index => $buku): ?>
                <div class="carousel-item <?php echo ($index == 0) ? 'active' : ''; ?>">
                    <div class="row align-items-center justify-content-center p-5">
                        <div class="col-md-3 text-center">
                            <img src="uploads/<?php echo $buku['gambar']; ?>" class="d-block w-100 rounded shadow-lg" alt="Cover <?php echo htmlspecialchars($buku['judul']); ?>">
                        </div>
                        <div class="col-md-7">
                            <h3 class="fw-bold"><?php echo htmlspecialchars($buku['judul']); ?></h3>
                            <p class="text-muted">oleh <?php echo htmlspecialchars($buku['pengarang']); ?></p>
                            <p class="deskripsi-carousel">
                                <?php
                                    // Potong deskripsi agar tidak terlalu panjang
                                    $deskripsi = $buku['deskripsi'];
                                    if (strlen($deskripsi) > 250) {
                                        echo substr($deskripsi, 0, 250) . "...";
                                    } else {
                                        echo $deskripsi;
                                    }
                                ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="detail_buku.php?id=<?php echo $buku['id_buku']; ?>" class="btn btn-primary">Baca Selengkapnya</a>
                                <span class="text-danger fw-bold"><i class="fas fa-fire"></i> Dipinjam <?php echo $buku['jumlah_dipinjam']; ?>x</span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#carouselBukuPopuler" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselBukuPopuler" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <?php else: ?>
            <p class="text-center text-muted">Belum ada data peminjaman untuk menampilkan buku populer.</p>
        <?php endif; ?>
    </div>
</div>

<div class="container mt-5">
    <h2 class="text-center mb-4">Koleksi Terbaru Kami</h2>
    <div class="row row-cols-1 row-cols-md-4 g-4">
        <?php while($buku = mysqli_fetch_assoc($buku_terbaru)): ?>
        <div class="col">
            <div class="card h-100 text-center shadow-sm">
                 <?php if ($buku['stok'] == 0): ?><span class="badge bg-danger">Stok Habis</span><?php endif; ?>
                <img src="uploads/<?php echo $buku['gambar']; ?>" class="card-img-top" alt="Cover Buku" style="height: 300px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($buku['judul']); ?></h5>
                    <p class="card-text text-muted"><?php echo htmlspecialchars($buku['pengarang']); ?></p>
                </div>
                <div class="card-footer bg-white border-top-0 pb-3">
                    <a href="detail_buku.php?id=<?php echo $buku['id_buku']; ?>" class="btn btn-primary w-100">Lihat Detail</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php
require 'template/footer_public.php';
?>