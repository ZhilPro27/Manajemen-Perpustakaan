--
-- Struktur tabel untuk `admin`
-- Untuk menyimpan data login pustakawan
--
CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL, -- Menggunakan varchar(255) untuk menampung password yang di-hash
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Isi data admin awal untuk login pertama kali
-- Passwordnya adalah "admin123", kita akan hash nanti dengan PHP
INSERT INTO `admin` (`id_admin`, `nama_lengkap`, `username`, `password`) VALUES
(1, 'Administrator', 'admin', '$2y$10$U.iwvqu.g59TU7gLpP2hHeG.D8mYr./2o0I2qSTqjPYM1G8B26A3S'); -- password: admin123

--
-- Struktur tabel untuk `buku`
--
CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `pengarang` varchar(100) NOT NULL,
  `penerbit` varchar(100) NOT NULL,
  `tahun_terbit` int(4) NOT NULL,
  `isbn` varchar(25) DEFAULT NULL,
  `stok` int(5) NOT NULL,
  `gambar` varchar(255) DEFAULT 'default.jpg', -- Kolom untuk nama file gambar sampul
  PRIMARY KEY (`id_buku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Struktur tabel untuk `anggota`
--
CREATE TABLE `anggota` (
  `id_anggota` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `alamat` text DEFAULT NULL,
  `tanggal_daftar` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_anggota`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Struktur tabel untuk `transaksi`
--
CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL AUTO_INCREMENT,
  `id_anggota` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `tanggal_pinjam` date NOT NULL DEFAULT current_timestamp(),
  `tanggal_kembali` date DEFAULT NULL, -- Diisi saat buku dikembalikan
  `status` enum('Dipinjam','Selesai','Terlambat') NOT NULL DEFAULT 'Dipinjam',
  PRIMARY KEY (`id_transaksi`),
  KEY `id_anggota` (`id_anggota`),
  KEY `id_buku` (`id_buku`),
  CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;