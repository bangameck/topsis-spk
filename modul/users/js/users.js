document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi modal edit
    const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));

    // Event handler untuk tombol edit
    document.querySelectorAll('.edit-user').forEach(button => {
        button.addEventListener('click', function() {
            // Ambil data dari atribut
            const id = this.getAttribute('data-id');
            const username = this.getAttribute('data-username');
            const name = this.getAttribute('data-name');
            const level = this.getAttribute('data-level');
            const img = this.getAttribute('data-img');

            // Isi form modal
            document.getElementById('editId').value = id;
            document.getElementById('editUsername').value = username;
            document.getElementById('editName').value = name;
            document.getElementById('editLevel').value = level;
            document.getElementById('currentImage').value = img;

            // Handle gambar preview
            const editImagePreview = document.getElementById('editImagePreview');
            const editImagePreviewContainer = document.getElementById('editImagePreviewContainer');

            if (img && img !== 'default.png') {
                editImagePreview.src = 'assets/img/profile/' + img;
                editImagePreview.style.display = 'block';
                editImagePreviewContainer.style.display = 'block';
            } else {
                editImagePreview.style.display = 'none';
                editImagePreviewContainer.style.display = 'none';
            }

            // Tampilkan modal
            editModal.show();
        });
    });

    const dragDropArea = document.getElementById('dragDropArea');
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const removeImageBtn = document.getElementById('removeImageBtn');

    // Pastikan input file bisa diakses
    imageInput.style.display = 'block';
    imageInput.style.visibility = 'visible';
    imageInput.style.position = 'absolute';
    imageInput.style.width = '100%';
    imageInput.style.height = '100%';
    imageInput.style.top = '0';
    imageInput.style.left = '0';
    imageInput.style.opacity = '0';
    imageInput.style.cursor = 'pointer';

    // Click handler untuk area drop
    dragDropArea.addEventListener('click', function(e) {
        // Pastikan kita tidak mengganggu elemen anak
        if (e.target === dragDropArea || e.target.tagName === 'P' || e.target.tagName === 'SPAN') {
            imageInput.click();
        }
    });

    // File selection handler
    imageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];

            // Validasi file
            const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Please select a valid image (JPEG, PNG, GIF)');
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('Image size must be less than 2MB');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreviewContainer.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        }
    });

    // Remove image handler
    removeImageBtn.addEventListener('click', function() {
        imageInput.value = '';
        imagePreview.src = '#';
        imagePreviewContainer.classList.add('d-none');
    });

    // Drag and drop handlers
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dragDropArea.addEventListener(eventName, preventDefaults, false);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dragDropArea.addEventListener(eventName, highlightArea, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dragDropArea.addEventListener(eventName, unhighlightArea, false);
    });

    dragDropArea.addEventListener('drop', handleDrop, false);

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlightArea() {
        dragDropArea.classList.add('bg-light');
    }

    function unhighlightArea() {
        dragDropArea.classList.remove('bg-light');
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files.length) {
            imageInput.files = files;
            const event = new Event('change');
            imageInput.dispatchEvent(event);
        }
    }
});

// Fungsi umum untuk inisialisasi drag & drop
function initDragDrop(dragDropId, inputId, previewId, containerId, removeBtnId) {
    const dragDropArea = document.getElementById(dragDropId);
    const imageInput = document.getElementById(inputId);
    const imagePreview = document.getElementById(previewId);
    const previewContainer = document.getElementById(containerId);
    const removeBtn = document.getElementById(removeBtnId);

    // Click handler untuk area drop
    dragDropArea.addEventListener('click', function(e) {
        if (e.target === dragDropArea || e.target.tagName === 'P' || e.target.tagName === 'SPAN') {
            imageInput.click();
        }
    });

    // File selection handler
    imageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];

            // Validasi file
            const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
            const maxSize = 2 * 1024 * 1024; // 2MB

            if (!validTypes.includes(file.type)) {
                alert('Please select a valid image (JPEG, PNG, GIF)');
                return;
            }

            if (file.size > maxSize) {
                alert('Image size must be less than 2MB');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        }
    });

    // Remove image handler
    removeBtn.addEventListener('click', function() {
        imageInput.value = '';
        imagePreview.src = '#';
        previewContainer.classList.add('d-none');
    });

    // Drag and drop handlers
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dragDropArea.addEventListener(eventName, preventDefaults, false);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dragDropArea.addEventListener(eventName, highlightArea, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dragDropArea.addEventListener(eventName, unhighlightArea, false);
    });

    dragDropArea.addEventListener('drop', handleDrop, false);

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlightArea() {
        dragDropArea.classList.add('bg-light');
    }

    function unhighlightArea() {
        dragDropArea.classList.remove('bg-light');
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files.length) {
            imageInput.files = files;
            const event = new Event('change');
            imageInput.dispatchEvent(event);
        }
    }
}

// Inisialisasi untuk Add User
initDragDrop(
    'addDragDropArea',
    'addImageInput',
    'addImagePreview',
    'addImagePreviewContainer',
    'addRemoveImageBtn'
);

// Inisialisasi untuk Edit User
initDragDrop(
    'editDragDropArea',
    'editImageInput',
    'editImagePreview',
    'editImagePreviewContainer',
    'editRemoveImageBtn'
);

// Fungsi untuk menampilkan gambar yang sudah ada saat edit
$(document).on('click', '.edit-user', function() {
    const img = $(this).data('img');
    if (img && img !== 'default.png') {
        $('#editImagePreview').attr('src', 'assets/img/profile/' + img);
        $('#editImagePreviewContainer').removeClass('d-none');
    } else {
        $('#editImagePreviewContainer').addClass('d-none');
    }
});

// Reset add form when modal is closed
$('#addUserModal').on('hidden.bs.modal', function() {
    $('#addUserForm')[0].reset();
    imagePreviewContainer.style.display = 'none';
});

// Reset edit form when modal is closed
$('#editUserModal').on('hidden.bs.modal', function() {
    $('#editUserForm')[0].reset();
    editImagePreview.style.display = 'none';
});