<?php
require_once __DIR__ . '/../../_func/controlWeb.php';

if ($_SESSION['level'] != 1) {
  toastNotif('error', 'Anda tidak memiliki akses dihalaman users!');
  echo "<script>
				window.location='" . base_url() . "home'
        </script>";
  exit();
}

// Handle delete user
if (isset($_GET['del_id'])) {
  $id = filter_var($_GET['del_id'], FILTER_SANITIZE_NUMBER_INT);

  // Get user data to delete image file
  $stmt = $db->prepare("SELECT * FROM users WHERE user_id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $user = $stmt->get_result()->fetch_assoc();
  $stmt->close();

  if ($user) {
    // Delete user
    $stmt = $db->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Delete image file if exists
    if ($user['img'] && file_exists('../../assets/img/profil/' . $user['img'])) {
      unlink('../../assets/img/profil/' . $user['img']);
    }

    $_SESSION['success'] = "User deleted successfully!";
  } else {
    $_SESSION['failure'] = "User not found!";
  }

  header('location: users.php');
  exit();
}
?>



<title>Halaman Users</title>
<div class="row page-title clearfix">
  <div class="page-title-left">
    <h6 class="page-title-heading mr-0 mr-r-5">Users</h6>
    <p class="page-title-description mr-0 d-none d-md-inline-block">Halaman Users</p>
  </div>
  <!-- /.page-title-left -->
  <div class="page-title-right d-none d-sm-inline-flex">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">users</li>
    </ol>

  </div>
  <!-- /.page-title-right -->
</div>
<div class="widget-list">
  <div class="row">
    <div class="col-md-12 widget-holder">
      <div class="widget-bg">
        <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h5 class="mb-0"></h5>
          <div>
            <button class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
              <i class="fas fa-user-plus mr-1"></i> Add User
            </button>
          </div>
        </div>

        <!-- Users Table -->
        <div class="widget-body clearfix">
          <table class="table table-striped table-responsive" data-toggle="datatables" data-plugin-options='{"searching": true}'>
            <thead class="thead-dark">
              <tr>
                <th>#</th>
                <th>Username</th>
                <th>Name</th>
                <th>Level</th>
                <th>Profile Image</th>
                <th>Created At</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $offset = 0;
              $query = $db->query("SELECT * FROM users");
              $users = $query->fetch_all(MYSQLI_ASSOC);
              if (count($users) > 0): ?>
                <?php foreach ($users as $index => $user): ?>
                  <tr>
                    <td><?php echo $offset + $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td>
                      <?php
                      if ($user['level'] == 1) {
                        echo '<span class="badge badge-primary">Admin</span>';
                      } else {
                        echo '<span class="badge badge-secondary">Masyarakat</span>';
                      }
                      ?>
                    </td>
                    <td>
                      <?php if ($user['img']): ?>
                        <img src="../../assets/img/profil/<?php echo htmlspecialchars($user['img']); ?>" alt="Profile" style="max-width: 50px; max-height: 50px;">
                      <?php else: ?>
                        <span class="text-muted">No image</span>
                      <?php endif; ?>
                    </td>
                    <td><?php echo date('d M Y H:i', strtotime($user['created_at'])); ?></td>
                    <td>
                      <button class="btn btn-sm btn-warning edit-user"
                        data-bs-toggle="modal"
                        data-bs-target="#editUserModal"
                        data-id="<?php echo $user['user_id']; ?>"
                        data-username="<?php echo htmlspecialchars($user['username']); ?>"
                        data-name="<?php echo htmlspecialchars($user['name']); ?>"
                        data-level="<?php echo $user['level']; ?>"
                        data-img="<?php echo htmlspecialchars($user['img']); ?>">
                        <i class="fa fa-edit"></i> Edit
                      </button>
                      <a href="users.php?del_id=<?php echo $user['user_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                        <i class="fa fa-trash"></i> Delete
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7" class="text-center">No users found</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="addUserForm" action="<?= base_url(); ?>users/add" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add">

        <div class="modal-header">
          <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
          </div>
          <div class="form-group">
            <label for="level">Level</label>
            <select class="form-control" id="level" name="level" required>
              <option value="1">Admin</option>
              <option value="2">Masyarakat</option>
            </select>
          </div>
          <div class="form-group">
            <label>Profile Image</label>
            <div id="dragDropArea" class="drag-drop-area">
              <p>Drag & drop your image here or click to browse</p>
              <input type="file" id="imageInput" name="image" accept="image/*" style="display: none;">
              <div id="imagePreviewContainer" style="display: none;">
                <img id="imagePreview" class="preview-image" src="#" alt="Preview">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save User</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="editUserForm" action="user_action.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" id="editId" name="id">

        <div class="modal-header">
          <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="editUsername">Username</label>
            <input type="text" class="form-control" id="editUsername" name="username" required>
          </div>
          <div class="form-group">
            <label for="editPassword">Password (Leave blank to keep current)</label>
            <input type="password" class="form-control" id="editPassword" name="password">
          </div>
          <div class="form-group">
            <label for="editName">Full Name</label>
            <input type="text" class="form-control" id="editName" name="name" required>
          </div>
          <div class="form-group">
            <label for="editLevel">Level</label>
            <select class="form-control" id="editLevel" name="level" required>
              <option value="1">Admin</option>
              <option value="2">Masyarakat</option>
            </select>
          </div>
          <div class="form-group">
            <label>Profile Image</label>
            <div id="editDragDropArea" class="drag-drop-area">
              <p>Drag & drop your image here or click to browse</p>
              <input type="file" id="editImageInput" name="image" accept="image/*" style="display: none;">
              <div id="editImagePreviewContainer">
                <img id="editImagePreview" class="preview-image" src="#" alt="Preview" style="display: none;">
              </div>
              <input type="hidden" id="currentImage" name="current_image">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update User</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- jQuery, Bootstrap JS -->
<script src="assets/js/jquery-3.5.1.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS -->
<script>
  $(document).ready(function() {
    // Handle edit button click
    $('.edit-user').click(function() {
      var id = $(this).data('id');
      var username = $(this).data('username');
      var name = $(this).data('name');
      var level = $(this).data('level');
      var img = $(this).data('img');

      $('#editId').val(id);
      $('#editUsername').val(username);
      $('#editName').val(name);
      $('#editLevel').val(level);
      $('#currentImage').val(img);

      if (img) {
        $('#editImagePreview').attr('src', '../../assets/img/profil/' + img).show();
        $('#editImagePreviewContainer').show();
      } else {
        $('#editImagePreview').hide();
      }

      $(document).ready(function() {
        $(document).on('click', '.edit-user', function() {
          var id = $(this).data('id');
          var username = $(this).data('username');
          var name = $(this).data('name');

          $('#editId').val(id);
          $('#editUsername').val(username);
          $('#editName').val(name);

          // Jika menggunakan Bootstrap 5
          var editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
          editModal.show();
        });
      });
    });

    // Drag and drop functionality for add form
    var dragDropArea = document.getElementById('dragDropArea');
    var imageInput = document.getElementById('imageInput');
    var imagePreview = document.getElementById('imagePreview');
    var imagePreviewContainer = document.getElementById('imagePreviewContainer');

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
      dragDropArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
      e.preventDefault();
      e.stopPropagation();
    }

    // Highlight drop area when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
      dragDropArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
      dragDropArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
      dragDropArea.classList.add('active');
    }

    function unhighlight() {
      dragDropArea.classList.remove('active');
    }

    // Handle dropped files
    dragDropArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
      var dt = e.dataTransfer;
      var files = dt.files;
      handleFiles(files);
    }

    // Handle click to browse
    dragDropArea.addEventListener('click', function() {
      imageInput.click();
    });

    imageInput.addEventListener('change', function() {
      handleFiles(this.files);
    });

    function handleFiles(files) {
      if (files.length > 0) {
        var file = files[0];
        if (file.type.match('image.*')) {
          var reader = new FileReader();
          reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreviewContainer.style.display = 'block';
          };
          reader.readAsDataURL(file);
        }
      }
    }

    // Drag and drop functionality for edit form
    var editDragDropArea = document.getElementById('editDragDropArea');
    var editImageInput = document.getElementById('editImageInput');
    var editImagePreview = document.getElementById('editImagePreview');
    var editImagePreviewContainer = document.getElementById('editImagePreviewContainer');

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
      editDragDropArea.addEventListener(eventName, preventDefaults, false);
    });

    // Highlight drop area when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
      editDragDropArea.addEventListener(eventName, highlightEdit, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
      editDragDropArea.addEventListener(eventName, unhighlightEdit, false);
    });

    function highlightEdit() {
      editDragDropArea.classList.add('active');
    }

    function unhighlightEdit() {
      editDragDropArea.classList.remove('active');
    }

    // Handle dropped files
    editDragDropArea.addEventListener('drop', handleEditDrop, false);

    function handleEditDrop(e) {
      var dt = e.dataTransfer;
      var files = dt.files;
      handleEditFiles(files);
    }

    // Handle click to browse
    editDragDropArea.addEventListener('click', function() {
      editImageInput.click();
    });

    editImageInput.addEventListener('change', function() {
      handleEditFiles(this.files);
    });

    function handleEditFiles(files) {
      if (files.length > 0) {
        var file = files[0];
        if (file.type.match('image.*')) {
          var reader = new FileReader();
          reader.onload = function(e) {
            editImagePreview.src = e.target.result;
            editImagePreview.style.display = 'block';
          };
          reader.readAsDataURL(file);
        }
      }
    }

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
  });
</script>