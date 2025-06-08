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
</script>
</body>
</html>