/**
 * Menunggu hingga seluruh konten halaman dimuat sebelum menjalankan skrip.
 * Ini adalah praktik terbaik untuk memastikan semua elemen HTML sudah ada.
 */
document.addEventListener('DOMContentLoaded', function() {

    /**
     * Fungsi untuk menampilkan atau menyembunyikan
     * dropdown guru pengganti berdasarkan status kehadiran.
     */
    function toggleGuruPengganti() {
        // Ambil elemen-elemen yang dibutuhkan
        const div = document.getElementById('guru_pengganti_div');
        const selectPengganti = document.getElementById('id_guru_pengganti');

        // Cek apakah elemen 'div' dan 'selectPengganti' ada di halaman ini
        if (!div || !selectPengganti) {
            return; // Hentikan fungsi jika elemen tidak ditemukan
        }

        // 'this.value' merujuk pada nilai dari dropdown status yang sedang dipilih
        if (this.value === 'Hadir') {
            div.style.display = 'none'; // Sembunyikan jika status 'Hadir'
            selectPengganti.value = ''; // Kosongkan pilihan guru pengganti
        } else {
            div.style.display = 'block'; // Tampilkan jika status BUKAN 'Hadir'
        }
    }

    // Cari elemen dropdown status kehadiran
    const statusSelect = document.getElementById('status');

    // Jika elemen dropdown status ada di halaman ini,
    // tambahkan event listener 'change' padanya.
    if (statusSelect) {
        statusSelect.addEventListener('change', toggleGuruPengganti);
    }

});