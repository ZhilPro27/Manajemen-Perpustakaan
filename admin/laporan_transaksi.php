<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("Location: ../login.php");
    exit;
}

require '../koneksi.php';

// Cek apakah tanggal sudah diset
if (!isset($_GET['tgl_mulai']) || !isset($_GET['tgl_selesai'])) {
    die("Error: Harap tentukan rentang tanggal.");
}

$tgl_mulai = $_GET['tgl_mulai'];
$tgl_selesai = $_GET['tgl_selesai'];

// Query dengan filter tanggal
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
        WHERE transaksi.tanggal_pinjam BETWEEN ? AND ?
        ORDER BY transaksi.tanggal_pinjam ASC";

$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "ss", $tgl_mulai, $tgl_selesai);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* CSS untuk menyembunyikan elemen saat mencetak */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                font-size: 12pt;
            }
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="text-center">
        <h3>Laporan Transaksi Peminjaman</h3>
        <h5>Perpustakaan Digital</h5>
        <p>Periode: <?php echo date('d M Y', strtotime($tgl_mulai)) . " - " . date('d M Y', strtotime($tgl_selesai)); ?></p>
    </div>
    
    <hr>
    
    <button onclick="window.print()" class="btn btn-success no-print mb-3">
        <i class="fas fa-print"></i> Cetak Laporan
    </button>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>No.</th>
                <th>Peminjam</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                $no = 1;
                while($data = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($data['nama_anggota']); ?></td>
                    <td><?php echo htmlspecialchars($data['judul_buku']); ?></td>
                    <td><?php echo date('d-m-Y', strtotime($data['tanggal_pinjam'])); ?></td>
                    <td><?php echo ($data['tanggal_kembali']) ? date('d-m-Y', strtotime($data['tanggal_kembali'])) : '-'; ?></td>
                    <td><?php echo htmlspecialchars($data['status']); ?></td>
                </tr>
            <?php
                }
            } else {
            ?>
                <tr>
                    <td colspan="6" class="text-center">Tidak ada transaksi pada periode ini.</td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <div class="text-end mt-4">
        <p>Dicetak pada: <?php echo date('d M Y, H:i:s'); ?></p>
    </div>

</div>

</body>
</html>