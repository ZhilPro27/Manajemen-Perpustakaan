<?php
require '../koneksi.php'; // Koneksi sudah cukup, tidak perlu session check untuk laporan cetak

$sql = "SELECT * FROM buku ORDER BY judul ASC";
$result = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Daftar Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>@media print {.no-print {display: none !important;}} body {font-size: 12pt;}</style>
</head>
<body>
<div class="container mt-4">
    <div class="text-center"><h3>Laporan Daftar Semua Buku</h3><h5>Perpustakaan Digital</h5></div><hr>
    <button onclick="window.print()" class="btn btn-success no-print mb-3"><i class="fas fa-print"></i> Cetak</button>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>No.</th><th>Judul</th><th>Pengarang</th><th>Penerbit</th><th>Tahun</th><th>Stok</th><th>ISBN</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): $no = 1; ?>
                <?php while($data = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($data['judul']); ?></td>
                    <td><?php echo htmlspecialchars($data['pengarang']); ?></td>
                    <td><?php echo htmlspecialchars($data['penerbit']); ?></td>
                    <td><?php echo $data['tahun_terbit']; ?></td>
                    <td><?php echo $data['stok']; ?></td>
                    <td><?php echo htmlspecialchars($data['isbn']); ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center">Tidak ada data.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>