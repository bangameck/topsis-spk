// Fungsi real-time untuk memfilter username input
function filterUsernameInput(input) {
    // Hilangkan spasi dan karakter tidak valid, ubah jadi huruf kecil
    input.value = input.value.toLowerCase().replace(/[^a-z0-9_-]/g, '');
}

// Fungsi untuk mencegah spasi pada password
function filterPasswordInput(input) {
    input.value = input.value.replace(/\s/g, '');
}

// Terapkan ke input username dan password (add + edit)
document.getElementById('username').addEventListener('input', function() {
    filterUsernameInput(this);
});

document.getElementById('editUsername').addEventListener('input', function() {
    filterUsernameInput(this);
});

document.getElementById('password').addEventListener('input', function() {
    filterPasswordInput(this);
});

document.getElementById('editPassword').addEventListener('input', function() {
    filterPasswordInput(this);
});

$(document).ready(function() {
    // Inisialisasi DataTable untuk tabel dengan ID 'usersTable' (beri ID pada tabel Anda)
    // Atau jika Anda ingin menggunakan class: $('.table[data-toggle="datatables"]').DataTable({ ... });
    $('#usersTable').DataTable({
        // Konfigurasi untuk fitur-fitur DataTables
        "paging": true, // Aktifkan paginasi
        "searching": true, // Aktifkan pencarian/filter
        "ordering": true, // Aktifkan pengurutan kolom
        "info": true, // Tampilkan informasi "Showing X of Y entries"
        "responsive": true, // Aktifkan mode responsif
        "lengthMenu": [10, 25, 50, 100], // Opsi untuk jumlah entri per halaman
        "language": {
            "url": "//cdn.datatables.net/plug-ins/2.0.8/i18n/id.json" // Opsional: Terjemahan ke Bahasa Indonesia
        }
    });
});