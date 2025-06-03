document.addEventListener('DOMContentLoaded', function() {
    const dragDropArea = document.getElementById('imageDragDropArea');
    const imageUploadInput = document.getElementById('imageUploadInput');
    const browseImageButton = document.getElementById('browseImageButton');
    const imagePreview = document.getElementById('imagePreview');
    const clearImageIcon = document.querySelector('.clear-image-icon');
    const existingImageInput = document.querySelector('input[name="existing_image"]');
    const currentProfileImage = document.querySelector('.current-profile-image');
    const currentImageLabel = document.querySelector('.current-image-label'); // Tambahkan ini

    // Elemen informasi file baru
    const fileInfoDiv = document.getElementById('fileInfo');
    const fileNameElement = document.getElementById('fileName');
    const fileSizeElement = document.getElementById('fileSize');

    // Batasan ukuran file: 2 MB dalam byte
    const MAX_FILE_SIZE_BYTES = 2 * 1024 * 1024; // 2MB

    // Helper function to format file size
    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';

        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];

        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    // Fungsi untuk menampilkan preview gambar
    function showImagePreview(file) {
        if (file) {
            // --- Pengecekan ukuran file ---
            if (file.size > MAX_FILE_SIZE_BYTES) {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Ukuran gambar tidak boleh melebihi ' + formatBytes(MAX_FILE_SIZE_BYTES) + '.');
                } else {
                    alert('Ukuran gambar tidak boleh melebihi ' + formatBytes(MAX_FILE_SIZE_BYTES) + '.');
                }
                imageUploadInput.value = ''; // Hapus file dari input
                clearImagePreview(); // Pastikan preview kosong jika ada sebelumnya
                return; // Berhenti di sini
            }
            // --- Akhir pengecekan ukuran file ---

            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
                clearImageIcon.style.display = 'block';
                dragDropArea.classList.add('has-image');

                // Tampilkan informasi file
                fileNameElement.textContent = file.name;
                fileSizeElement.textContent = formatBytes(file.size);
                fileInfoDiv.style.display = 'block';

                // Sembunyikan gambar profil yang sudah ada jika ada
                if (currentProfileImage) {
                    currentProfileImage.style.display = 'none';
                }
                if (currentImageLabel) { // Sembunyikan label gambar saat ini
                    currentImageLabel.style.display = 'none';
                }
            };
            reader.readAsDataURL(file);
        }
    }

    // Fungsi untuk menghapus preview gambar
    function clearImagePreview() {
        imagePreview.src = '#';
        imagePreview.style.display = 'none';
        clearImageIcon.style.display = 'none';
        imageUploadInput.value = '';
        dragDropArea.classList.remove('has-image');

        // Sembunyikan informasi file
        fileInfoDiv.style.display = 'none';
        fileNameElement.textContent = '';
        fileSizeElement.textContent = '';

        // Tampilkan kembali gambar profil yang sudah ada jika ada
        if (currentProfileImage) {
            currentProfileImage.style.display = 'block';
        }
        if (currentImageLabel) { // Tampilkan kembali label gambar saat ini
            currentImageLabel.style.display = 'block';
        }
    }

    // --- Inisialisasi tampilan jika ada gambar existing (mode edit) ---
    // Jika Anda ingin gambar existing tampil di dalam drag-drop-area secara default
    // Maka Anda harus menghapus <img class="current-profile-image"> dari HTML.
    // Kode ini akan tetap membiarkannya terpisah, tapi menyembunyikan drag-drop UI
    // jika gambar sudah ada, dan menampilkan drag-drop UI jika user klik hapus.
    if (existingImageInput && existingImageInput.value && currentProfileImage && currentProfileImage.src && currentProfileImage.src !== '#') {
        // Secara default, drag-drop area akan menampilkan teks "Seret dan lepas..."
        // Jika ada gambar existing, kita akan sembunyikan drag-drop UI kecuali ada interaksi
        dragDropArea.classList.remove('has-image'); // Pastikan ini tidak aktif
        // Jika Anda ingin gambar existing juga bisa dihapus dengan icon clear,
        // Anda perlu memuatnya ke imagePreview dan mensimulasikan file selection
        // Ini lebih kompleks. Untuk saat ini, kita biarkan terpisah (sesuai HTML).
    }


    // Klik tombol "Pilih Gambar" memicu input file
    browseImageButton.addEventListener('click', function() {
        imageUploadInput.click();
    });

    // Saat file dipilih melalui dialog (bukan drag-drop)
    imageUploadInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            showImagePreview(this.files[0]);
        } else {
            clearImagePreview();
        }
    });

    // Menangani Drag & Drop:
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dragDropArea.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dragDropArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dragDropArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        dragDropArea.classList.add('highlight');
        // Sembunyikan gambar existing saat drag-over untuk visual yang lebih bersih
        if (currentProfileImage) currentProfileImage.style.display = 'none';
        if (currentImageLabel) currentImageLabel.style.display = 'none';
    }

    function unhighlight() {
        dragDropArea.classList.remove('highlight');
        // Tampilkan kembali gambar existing jika tidak ada file yang di-drop
        if (!imageUploadInput.files.length && currentProfileImage && existingImageInput.value) {
            currentProfileImage.style.display = 'block';
            if (currentImageLabel) currentImageLabel.style.display = 'block';
        }
    }

    dragDropArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            const file = files[0];
            if (file.type.startsWith('image/')) {
                // Set file ke input file asli
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                imageUploadInput.files = dataTransfer.files;

                showImagePreview(file); // Panggil showImagePreview untuk validasi ukuran
            } else {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Hanya file gambar yang diizinkan!');
                } else {
                    alert('Hanya file gambar yang diizinkan!');
                }
            }
        }
    }

    // Menangani klik ikon hapus
    clearImageIcon.addEventListener('click', function(e) {
        e.stopPropagation();
        clearImagePreview();
    });

    // Inisialisasi awal pada halaman load jika ada gambar yang sudah ada
    // Jika ada gambar existing, dan belum ada file baru dipilih, sembunyikan area drag-drop default
    // dan tampilkan gambar existing.
    if (existingImageInput && existingImageInput.value) {
        // Do nothing here, the HTML already displays it.
        // We only interact if user decides to change the image.
    }
});