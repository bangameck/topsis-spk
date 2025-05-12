<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once __DIR__ . '/../../_func/controlWeb.php';

if ($_SESSION['level'] != 1) {
  toastNotif('error', 'Anda tidak memiliki akses dihalaman users!');
  echo "<script>
				window.location='" . base_url() . "home'
        </script>";
  exit();
}
?>

<title>Halaman Kriteria</title>
<div class="row page-title clearfix">
  <div class="page-title-left">
    <h6 class="page-title-heading mr-0 mr-r-5">Kriteria</h6>
    <p class="page-title-description mr-0 d-none d-md-inline-block">Halaman Utama Kriteria</p>
  </div>
  <!-- /.page-title-left -->
  <div class="page-title-right d-none d-sm-inline-flex">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">kriteria</li>
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
              <i class="fas fa-user-plus mr-1"></i> Kriteria
            </button>
          </div>
        </div>

        <!-- Users Table -->
        <div class="widget-body clearfix">
          <table class="table table-striped table-responsive" data-toggle="datatables" data-plugin-options='{"searching": true}'>
            <thead class="thead-dark">
              <tr>
                <th>#</th>
                <th>ID</th>
                <th>Nama</th>
                <th>Jenis</th>
                <th>Value</th>
                <th>Keterangan</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $offset = 0;
              $query = $db->query("SELECT * FROM criteria");
              $criterias = $query->fetch_all(MYSQLI_ASSOC);
              if (count($criterias) > 0): ?>
                <?php foreach ($criterias as $index => $criteria): ?>
                  <tr>
                    <td><?php echo $offset + $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($criteria['criteria_id']); ?></td>
                    <td><?php echo htmlspecialchars($criteria['criteria_name']); ?></td>
                    <td>
                      <?php
                      if ($criteria['criteria_type'] == 'cost') {
                        echo '<span class="badge badge-primary">Cost</span>';
                      } else {
                        echo '<span class="badge badge-success">Benefit</span>';
                      }
                      ?>
                    </td>
                    <td><?= htmlspecialchars($criteria['criteria_value']) ?></td>
                    <td><?= htmlspecialchars($criteria['criteria_information']); ?></td>
                    <td>
                      <button class="btn btn-sm btn-warning edit-user"
                        data-toggle="modal"
                        data-target="#editUserModal"
                        data-id="<?= $user['user_id']; ?>"
                        data-username="<?= htmlspecialchars($user['username']); ?>"
                        data-name="<?= htmlspecialchars($user['name']); ?>"
                        data-level="<?= $user['level']; ?>"
                        data-img="<?= $user['img'] ? htmlspecialchars($user['img']) : 'default.png'; ?>">
                        <i class="fa fa-edit"></i> Edit
                      </button>
                      <a href="<?= base_url(); ?>users/delete/<?= $user['user_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
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

  <!-- Add User Modal -->
  <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="addUserForm" action="<?= base_url(); ?>users/add" method="post" enctype="multipart/form-data">
          <input type="hidden" name="action" value="add">
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
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
              <label class="form-label">Profile Image</label>
              <div id="addDragDropArea" class="drag-drop-area border-2 border-dashed rounded p-4 text-center position-relative">
                <p class="mb-2">Drag & drop your image here or <span class="text-primary fw-bold">click to browse</span></p>
                <input type="file" id="addImageInput" name="image" accept="image/*" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer">
                <div id="addImagePreviewContainer" class="mt-3 text-center d-none">
                  <img id="addImagePreview" class="preview-image img-thumbnail" src="#" alt="Preview" style="max-height: 150px;">
                  <button type="button" class="btn btn-sm btn-danger mt-2" id="addRemoveImageBtn">
                    <i class="fas fa-times me-1"></i>Remove
                  </button>
                </div>
                <small class="text-muted d-block mt-2">Supported formats: JPG, PNG, GIF (Max 2MB)</small>
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

  <!-- Edit User Modal -->
  <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="editUserForm" action="<?= base_url(); ?>users/edit/<?= $user['user_id']; ?>" method="post" enctype="multipart/form-data">
          <input type="hidden" name="action" value="edit">
          <input type="hidden" id="editId" name="id">
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">

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
              <label class="form-label">Profile Image</label>
              <div id="editDragDropArea" class="drag-drop-area border-2 border-dashed rounded p-4 text-center position-relative">
                <p class="mb-2">Drag & drop your image here or <span class="text-primary fw-bold">click to browse</span></p>
                <input type="file" id="editImageInput" name="image" accept="image/*" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer">
                <div id="editImagePreviewContainer" class="mt-3 text-center d-none">
                  <img id="editImagePreview" class="preview-image img-thumbnail" src="" alt="Preview" style="max-height: 150px;">
                  <button type="button" class="btn btn-sm btn-danger mt-2" id="editRemoveImageBtn">
                    <i class="fas fa-times me-1"></i>Remove
                  </button>
                </div>
                <small class="text-muted d-block mt-2">Supported formats: JPG, PNG, GIF (Max 2MB)</small>
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