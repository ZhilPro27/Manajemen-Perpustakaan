<?php
// admin/index.php
require 'template/header.php';
require '../koneksi.php';

// Statistik
$total_buku = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM buku"))['total'];
$total_anggota = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM anggota"))['total'];
$buku_dipinjam = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM transaksi WHERE status = 'Dipinjam'"))['total'];

// Query untuk mengambil 5 aktivitas transaksi terakhir
$sql_aktivitas = "SELECT 
                    transaksi.status,
                    anggota.nama AS nama_anggota,
                    buku.judul AS judul_buku,
                    transaksi.tanggal_pinjam,
                    transaksi.tanggal_kembali
                  FROM transaksi
                  JOIN anggota ON transaksi.id_anggota = anggota.id_anggota
                  JOIN buku ON transaksi.id_buku = buku.id_buku
                  ORDER BY GREATEST(transaksi.tanggal_pinjam, IFNULL(transaksi.tanggal_kembali, '1970-01-01')) DESC, transaksi.id_transaksi DESC
                  LIMIT 5";
$query_aktivitas = mysqli_query($koneksi, $sql_aktivitas);
?>

<h3><i class="fas fa-tachometer-alt"></i> Dashboard</h3>
<hr>
<div class="row g-4">
    <div class="col-md-4">
        <div class="card bg-info text-white shadow">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title">Total Judul Buku</h5>
                    <p class="card-text fs-2 fw-bold"><?php echo $total_buku; ?></p>
                </div>
                <i class="fas fa-book fa-3x"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white shadow">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title">Total Anggota</h5>
                    <p class="card-text fs-2 fw-bold"><?php echo $total_anggota; ?></p>
                </div>
                <i class="fas fa-users fa-3x"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-dark shadow">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title">Buku Sedang Dipinjam</h5>
                    <p class="card-text fs-2 fw-bold"><?php echo $buku_dipinjam; ?></p>
                </div>
                <i class="fas fa-hand-holding-heart fa-3x"></i>
            </div>
        </div>
    </div>
</div>

<div class="card mt-5 shadow-sm">
    <div class="card-header">
        <h4 class="mb-0"><i class="fas fa-history"></i> Aktivitas Terbaru</h4>
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            <?php if (mysqli_num_rows($query_aktivitas) > 0): ?>
                <?php while($aktivitas = mysqli_fetch_assoc($query_aktivitas)): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <?php if ($aktivitas['status'] == 'Selesai'): ?>
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong><?php echo htmlspecialchars($aktivitas['nama_anggota']); ?></strong>
                                telah mengembalikan buku
                                <em>"<?php echo htmlspecialchars($aktivitas['judul_buku']); ?>"</em>.
                                <small class="text-muted d-block">
                                    Pada: <?php echo date('d M Y', strtotime($aktivitas['tanggal_kembali'])); ?>
                                </small>
                            <?php else: ?>
                                <i class="fas fa-arrow-circle-right text-warning me-2"></i>
                                <strong><?php echo htmlspecialchars($aktivitas['nama_anggota']); ?></strong>
                                baru saja meminjam buku
                                <em>"<?php echo htmlspecialchars($aktivitas['judul_buku']); ?>"</em>.
                                <small class="text-muted d-block">
                                    Pada: <?php echo date('d M Y', strtotime($aktivitas['tanggal_pinjam'])); ?>
                                </small>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <li class="list-group-item text-center">Belum ada aktivitas.</li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<?php
require 'template/footer.php';
?>