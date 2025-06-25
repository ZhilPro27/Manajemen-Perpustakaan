</div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function konfirmasiHapus(url) {
    Swal.fire({
        title: 'Apakah Anda Yakin?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33', // Merah untuk tombol hapus
        cancelButtonColor: '#3085d6', // Biru untuk tombol batal
        confirmButtonText: 'Ya, Hapus Saja!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        // Jika pengguna mengklik tombol "Ya, Hapus Saja!"
        if (result.isConfirmed) {
            // Arahkan browser ke URL penghapusan
            window.location.href = url;
        }
    })
}
function konfirmasiKembalikan(url) {
    Swal.fire({
        title: 'Konfirmasi Pengembalian?',
        text: "Anda akan mengubah status buku ini menjadi 'Selesai'.",
        icon: 'question', // Ikonnya pertanyaan, bukan peringatan
        showCancelButton: true,
        confirmButtonColor: '#28a745', // Warna hijau untuk konfirmasi
        cancelButtonColor: '#6c757d', // Warna abu-abu untuk batal
        confirmButtonText: 'Ya, Sudah Kembali!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        // Jika pengguna mengklik tombol "Ya, Sudah Kembali!"
        if (result.isConfirmed) {
            // Arahkan browser ke URL pengembalian
            window.location.href = url;
        }
    })
}

// Fungsi untuk membuat Toast dengan SweetAlert2
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end', // Posisi di pojok kanan atas
  showConfirmButton: false,
  timer: 3000, // Hilang setelah 3 detik
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
});

// Ambil status dari URL menggunakan PHP
const status = <?php echo json_encode($_GET['status'] ?? null); ?>;

// Tampilkan toast berdasarkan status
if (status) {
  let title = '';
  switch (status) {
    // Notifikasi untuk Buku
    case 'sukses_tambah_buku':
      title = 'Buku baru berhasil ditambahkan.';
      break;
    case 'sukses_edit_buku':
      title = 'Data buku berhasil diperbarui.';
      break;
    case 'sukses_hapus_buku':
      title = 'Data buku telah dihapus.';
      break;

    // Notifikasi untuk Anggota
    case 'sukses_tambah_anggota':
      title = 'Anggota baru berhasil ditambahkan.';
      break;
    case 'sukses_edit_anggota':
      title = 'Data anggota telah diperbarui.';
      break;
    case 'sukses_hapus_anggota':
      title = 'Data anggota telah dihapus.';
      break;

    // Notifikasi untuk Transaksi
    case 'sukses_pinjam':
      title = 'Peminjaman berhasil dicatat.';
      break;
    case 'sukses_kembali':
      title = 'Buku telah berhasil dikembalikan.';
      break;
  }

  if (title) {
    Toast.fire({
      icon: 'success',
      title: title
    });
  }

  history.replaceState(null, null, window.location.pathname);
}
</script>
</body>
</html>