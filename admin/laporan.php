<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("Location: ../login.php");
    exit;
}
?>
<?php require 'template/header.php'; ?>

<div class="container mt-4">
    <h3><i class="fas fa-file-alt"></i> Pusat Laporan</h3>
    <hr>
    <p>Pilih jenis laporan yang ingin Anda lihat atau cetak.</p>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header fw-bold">Laporan Transaksi Peminjaman</div>
                <div class="card-body">
                    <p>Menampilkan semua transaksi peminjaman dalam rentang tanggal tertentu.</p>
                    <form action="laporan_transaksi.php" method="GET" target="_blank">
                        <div class="mb-3">
                            <label for="tgl_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="tgl_mulai" name="tgl_mulai" required>
                        </div>
                        <div class="mb-3">
                            <label for="tgl_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="tgl_selesai" name="tgl_selesai" required>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-eye"></i> Tampilkan Laporan</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header fw-bold">Laporan Buku Terpopuler</div>
                <div class="card-body d-flex flex-column">
                    <p>Menampilkan 10 buku yang paling sering dipinjam oleh anggota.</p>
                    <div class="mt-auto">
                        <a href="laporan_populer.php" class="btn btn-primary" target="_blank"><i class="fas fa-eye"></i> Tampilkan Laporan</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header fw-bold">Laporan Daftar Semua Buku</div>
                <div class="card-body d-flex flex-column">
                    <p>Mencetak daftar lengkap semua buku yang terdaftar di perpustakaan.</p>
                    <div class="mt-auto">
                        <a href="laporan_buku.php" class="btn btn-primary" target="_blank"><i class="fas fa-eye"></i> Tampilkan Laporan</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header fw-bold">Laporan Daftar Semua Anggota</div>
                <div class="card-body d-flex flex-column">
                    <p>Mencetak daftar lengkap semua anggota yang terdaftar di perpustakaan.</p>
                    <div class="mt-auto">
                        <a href="laporan_anggota.php" class="btn btn-primary" target="_blank"><i class="fas fa-eye"></i> Tampilkan Laporan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require 'template/footer.php'; 
?>
</body>
</html>