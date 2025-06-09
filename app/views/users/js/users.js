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