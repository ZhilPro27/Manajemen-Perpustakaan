<?php
require '../koneksi.php';

// Query untuk menghitung buku yang paling sering dipinjam
$sql = "SELECT 
            buku.judul, 
            buku.pengarang,
            COUNT(transaksi.id_buku) AS jumlah_dipinjam
        FROM transaksi
        JOIN buku ON transaksi.id_buku = buku.id_buku
        GROUP BY transaksi.id_buku
        ORDER BY jumlah_dipinjam DESC
        LIMIT 10"; // Kita batasi hanya 10 buku teratas

$result = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Buku Terpopuler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>@media print {.no-print {display: none !important;}} body {font-size: 12pt;}</style>
</head>
<body>
<div class="container mt-4">
    <div class="text-center"><h3>Laporan 10 Buku Terpopuler</h3><h5>Perpustakaan Digital</h5></div><hr>
    <button onclick="window.print()" class="btn btn-success no-print mb-3"><i class="fas fa-print"></i> Cetak</button>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Peringkat</th><th>Judul Buku</th><th>Pengarang</th><th>Jumlah Peminjaman</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): $no = 1; ?>
                <?php while($data = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($data['judul']); ?></td>
                    <td><?php echo htmlspecialchars($data['pengarang']); ?></td>
                    <td><?php echo $data['jumlah_dipinjam']; ?> kali</td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4" class="text-center">Belum ada data transaksi untuk membuat laporan.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>