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

--
-- Data Dummy untuk tabel `anggota`
--
INSERT INTO `anggota` (`id_anggota`, `nama`, `email`, `telepon`, `alamat`, `tanggal_daftar`) VALUES
(1, 'Budi Santoso', 'budi.s@example.com', '081234567890', 'Jl. Merdeka No. 1, Jakarta', '2024-01-15'),
(2, 'Citra Lestari', 'citra.l@example.com', '081234567891', 'Jl. Sudirman No. 2, Bandung', '2024-02-20'),
(3, 'Agus Setiawan', 'agus.s@example.com', '081234567892', 'Jl. Pahlawan No. 3, Surabaya', '2024-03-10'),
(4, 'Dewi Anggraini', 'dewi.a@example.com', '081234567893', 'Jl. Gajah Mada No. 4, Yogyakarta', '2024-04-05'),
(5, 'Eko Prasetyo', 'eko.p@example.com', '081234567894', 'Jl. Diponegoro No. 5, Semarang', '2024-05-12'),
(6, 'Fitri Handayani', 'fitri.h@example.com', '081234567895', 'Jl. Imam Bonjol No. 6, Medan', '2024-06-01'),
(7, 'Gilang Ramadhan', 'gilang.r@example.com', '081234567896', 'Jl. Teuku Umar No. 7, Makassar', '2025-01-18'),
(8, 'Hana Pertiwi', 'hana.p@example.com', '081234567897', 'Jl. Gatot Subroto No. 8, Palembang', '2025-02-25'),
(9, 'Indra Kusuma', 'indra.k@example.com', '081234567898', 'Jl. Ahmad Yani No. 9, Denpasar', '2025-03-30'),
(10, 'Joko Susilo', 'joko.s@example.com', '081234567899', 'Jl. Slamet Riyadi No. 10, Surakarta', '2025-04-15');

--
-- Data Dummy untuk tabel `buku`
--
INSERT INTO `buku` (`id_buku`, `judul`, `pengarang`, `penerbit`, `tahun_terbit`, `isbn`, `stok`, `gambar`) VALUES
(1, 'Dasar-Dasar Pemrograman PHP', 'Andi Sunyoto', 'Gramedia Pustaka Utama', 2023, '978-602-03-7272-1', 5, 'default.jpg'),
(2, 'Atomic Habits', 'James Clear', 'Elex Media Komputindo', 2019, '978-623-00-0616-9', 3, 'default.jpg'),
(3, 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 2005, '979-3062-79-7', 7, 'default.jpg'),
(4, 'Sapiens: Riwayat Singkat Umat Manusia', 'Yuval Noah Harari', 'KPG', 2018, '978-602-424-416-3', 2, 'default.jpg'),
(5, 'Belajar JavaScript untuk Pemula', 'Rahmat Hidayat', 'Informatika', 2024, '978-602-1514-88-2', 8, 'default.jpg'),
(6, 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Lentera Dipantara', 2005, '979-97312-3-2', 4, 'default.jpg'),
(7, 'Filosofi Teras', 'Henry Manampiring', 'Kompas', 2018, '978-602-412-518-9', 0, 'default.jpg'),
(8, 'The Psychology of Money', 'Morgan Housel', 'Baca', 2021, '978-623-7799-23-0', 6, 'default.jpg'),
(9, 'Mahir CSS Modern', 'Dian Pravita', 'Lokomedia', 2025, '978-602-1999-77-1', 10, 'default.jpg'),
(10, 'Gadis Kretek', 'Ratih Kumala', 'Gramedia Pustaka Utama', 2012, '978-979-22-8141-5', 3, 'default.jpg'),
(11, 'Kecerdasan Buatan (AI) 101', 'Prof. Widodo', 'Penerbit ITB', 2024, '978-602-1234-56-7', 5, 'default.jpg'),
(12, 'Pulang', 'Tere Liye', 'Republika Penerbit', 2015, '978-602-0822-12-9', 1, 'default.jpg'),
(13, 'How to Win Friends and Influence People', 'Dale Carnegie', 'Simon & Schuster', 1936, '978-067-10-2703-2', 8, 'default.jpg'),
(14, 'Dunia Sophie', 'Jostein Gaarder', 'Mizan', 1996, '979-433-072-4', 2, 'default.jpg'),
(15, 'Cantik Itu Luka', 'Eka Kurniawan', 'Gramedia Pustaka Utama', 2002, '978-602-03-1258-1', 4, 'default.jpg');

--
-- Data Dummy untuk tabel `transaksi`
--
INSERT INTO `transaksi` (`id_transaksi`, `id_anggota`, `id_buku`, `tanggal_pinjam`, `tanggal_kembali`, `status`) VALUES
(1, 1, 2, '2025-01-10', '2025-01-17', 'Selesai'),
(2, 2, 5, '2025-01-12', NULL, 'Dipinjam'),
(3, 3, 1, '2025-02-01', '2025-02-08', 'Selesai'),
(4, 1, 8, '2025-02-05', NULL, 'Dipinjam'),
(5, 4, 3, '2025-02-15', '2025-02-22', 'Selesai'),
(6, 5, 4, '2025-03-01', NULL, 'Dipinjam'),
(7, 2, 2, '2025-03-05', '2025-03-12', 'Selesai'),
(8, 6, 9, '2025-03-10', NULL, 'Dipinjam'),
(9, 7, 10, '2025-04-02', '2025-04-09', 'Selesai'),
(10, 8, 12, '2025-04-05', NULL, 'Dipinjam'),
(11, 1, 5, '2025-04-20', '2025-04-27', 'Selesai'),
(12, 9, 2, '2025-05-01', NULL, 'Dipinjam'),
(13, 10, 1, '2025-05-03', '2025-05-10', 'Selesai'),
(14, 3, 8, '2025-05-15', NULL, 'Dipinjam'),
(15, 5, 3, '2025-05-20', '2025-05-27', 'Selesai'),
(16, 4, 1, '2025-05-22', NULL, 'Dipinjam'),
(17, 2, 4, '2025-06-01', NULL, 'Dipinjam'),
(18, 6, 2, '2025-06-03', NULL, 'Dipinjam');

ALTER TABLE `buku`
ADD `deskripsi` TEXT NULL DEFAULT NULL
AFTER `isbn`;

-- Buku ID: 1
UPDATE `buku` SET `deskripsi` = 'Buku ini adalah panduan komprehensif untuk pemula yang ingin menguasai bahasa pemrograman PHP dari nol. Dilengkapi dengan contoh-contoh praktis dan studi kasus, pembaca akan diajak untuk memahami variabel, logika, loop, hingga membangun aplikasi web sederhana.' WHERE `id_buku` = 1;

-- Buku ID: 2
UPDATE `buku` SET `deskripsi` = 'Sebuah buku revolusioner yang mengajarkan bagaimana perubahan-perubahan kecil dapat menghasilkan dampak yang luar biasa. James Clear mengungkapkan strategi praktis yang akan mengajarkan Anda cara membentuk kebiasaan baik, menghilangkan kebiasaan buruk, dan menguasai perilaku-perilaku kecil yang membawa hasil besar.' WHERE `id_buku` = 2;

-- Buku ID: 3
UPDATE `buku` SET `deskripsi` = 'Kisah inspiratif tentang sepuluh anak dari keluarga miskin yang bersekolah di sebuah sekolah Muhammadiyah di pulau Belitong. Dengan segala keterbatasan, mereka membuktikan bahwa semangat untuk belajar dan meraih mimpi dapat mengalahkan rintangan apa pun. Sebuah cerita tentang persahabatan, keteguhan, dan keajaiban pendidikan.' WHERE `id_buku` = 3;

-- Buku ID: 4
UPDATE `buku` SET `deskripsi` = 'Menelusuri 70.000 tahun sejarah manusia, Yuval Noah Harari menggabungkan Sejarah dan Sains untuk menceritakan bagaimana Homo Sapiens bisa mendominasi planet ini. Buku ini membahas tiga revolusi besar: Kognitif, Pertanian, dan Sains, serta dampaknya pada peradaban kita saat ini.' WHERE `id_buku` = 4;

-- Buku ID: 5
UPDATE `buku` SET `deskripsi` = 'Panduan esensial bagi siapa saja yang ingin terjun ke dunia pengembangan web modern. Buku ini membahas konsep fundamental JavaScript, mulai dari sintaks dasar, manipulasi DOM, hingga pengenalan event handling untuk membuat halaman web yang interaktif dan dinamis.' WHERE `id_buku` = 5;

-- Buku ID: 6
UPDATE `buku` SET `deskripsi` = 'Bagian pertama dari Tetralogi Buru, novel ini mengisahkan perjalanan Minke, seorang pribumi terpelajar di era Hindia Belanda. Melalui matanya, pembaca akan melihat potret masyarakat kolonial, pergulatan identitas, cinta terlarang, dan tumbuhnya benih-benih perlawanan nasionalisme.' WHERE `id_buku` = 6;

-- Buku ID: 7
UPDATE `buku` SET `deskripsi` = 'Sebuah pengantar praktis untuk filsafat Stoisisme yang relevan dengan kehidupan modern. Buku ini menyajikan cara-cara konkret untuk mengatasi emosi negatif, kekhawatiran, dan stres dengan mengadopsi prinsip-prinsip kuno dari para filsuf Stoa seperti Seneca dan Marcus Aurelius.' WHERE `id_buku` = 7;

-- Buku ID: 8
UPDATE `buku` SET `deskripsi` = 'Morgan Housel mengajarkan bahwa mengelola uang tidak melulu soal apa yang Anda tahu, tetapi tentang bagaimana Anda berperilaku. Melalui 19 cerita pendek, buku ini menjelajahi cara-cara aneh orang berpikir tentang uang dan mengajarkan Anda cara membuat keputusan finansial yang lebih baik.' WHERE `id_buku` = 8;

-- Buku ID: 9
UPDATE `buku` SET `deskripsi` = 'Tingkatkan skill desain web Anda dengan menguasai teknologi CSS modern. Buku ini mencakup pembahasan mendalam tentang Flexbox, Grid Layout, Custom Properties (Variables), dan teknik-teknik responsive design untuk menciptakan tampilan web yang fleksibel dan profesional di berbagai perangkat.' WHERE `id_buku` = 9;

-- Buku ID: 10
UPDATE `buku` SET `deskripsi` = 'Berlatar belakang industri kretek di Indonesia, novel ini mengisahkan pencarian seorang pemuda akan Jeng Yah, sosok misterius yang terikat dengan sejarah bisnis kretek keluarganya. Sebuah kisah epik tentang cinta, persaingan bisnis, dan sejarah pahit bangsa yang terbungkus dalam aroma tembakau dan cengkeh.' WHERE `id_buku` = 10;

-- Buku ID: 11
UPDATE `buku` SET `deskripsi` = 'Sebuah pengenalan yang mudah diakses tentang dunia Kecerdasan Buatan. Prof. Widodo menguraikan konsep-konsep inti seperti Machine Learning, Neural Networks, dan Deep Learning dengan bahasa yang sederhana. Buku ini cocok bagi siapa saja yang ingin memahami teknologi yang membentuk masa depan kita.' WHERE `id_buku` = 11;

-- Buku ID: 12
UPDATE `buku` SET `deskripsi` = 'Novel ini bercerita tentang Bujang, seorang pemuda yang tumbuh di pedalaman Sumatera dan merantau untuk menjadi seorang tukang pukul di keluarga penguasa ekonomi bayangan. Sebuah perjalanan penuh aksi tentang menemukan arti keluarga, kesetiaan, dan jalan untuk kembali ke akar.' WHERE `id_buku` = 12;

-- Buku ID: 13
UPDATE `buku` SET `deskripsi` = 'Buku klasik Dale Carnegie ini telah mengubah jutaan kehidupan dan tetap relevan hingga hari ini. Pelajari tiga teknik fundamental dalam menangani manusia, enam cara untuk membuat orang menyukai Anda, dan dua belas cara untuk memenangkan orang lain ke cara berpikir Anda.' WHERE `id_buku` = 13;

-- Buku ID: 14
UPDATE `buku` SET `deskripsi` = 'Sebuah novel unik yang juga merupakan kursus kilat sejarah filsafat Barat. Melalui surat-surat misterius yang diterima Sophie Amundsen, pembaca diajak dalam sebuah petualangan intelektual dari mitos Yunani kuno hingga eksistensialisme Jean-Paul Sartre.' WHERE `id_buku` = 14;

-- Buku ID: 15
UPDATE `buku` SET `deskripsi` = 'Novel epik yang memadukan sejarah, mitos, dan surealisme. Berpusat pada kehidupan tragis seorang pelacur bernama Dewi Ayu dan anak-anaknya, Eka Kurniawan melukiskan potret Indonesia dari masa kolonial hingga pasca-kemerdekaan dengan gaya penceritaan yang brutal sekaligus indah.' WHERE `id_buku` = 15;