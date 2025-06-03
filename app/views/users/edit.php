<?php
$id = $_GET['id'];
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>
<title>Halaman Users</title>
<div class="row page-title clearfix">
  <div class="page-title-left">
    <h6 class="page-title-heading mr-0 mr-r-5">Users</h6>
    <p class="page-title-description mr-0 d-none d-md-inline-block">Input Users</p>
  </div>
  <!-- /.page-title-left -->
  <div class="page-title-right d-none d-sm-inline-flex">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>users">Users</a>
      </li>
      <li class="breadcrumb-item active">input</li>
    </ol>

  </div>
  <!-- /.page-title-right -->
</div>
<div class="widget-list">
  <div class="row">
    <div class="col-md-12 widget-holder">
      <div class="widget-bg">
        <div class="widget-body clearfix">
          <form id="editUserForm" action="<?= base_url(); ?>users/actionEdit/<?= $user['user_id']; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="username">Username</label>
                  <input type="text" class="form-control" id="username" name="username" value="<?= $user['username']; ?>" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="kosongkan jika tidak diubah">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="name">Full Name</label>
                  <input type="text" class="form-control" id="name" name="name" value="<?= $user['name']; ?>" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="level">Level</label>
                  <?php $level = ['1' => 'Admin', '2' => 'Masyarakat']; ?>
                  <select class="form-control" id="level" name="level" required>
                    <option value="<?= $user['level']; ?>"><?= $level[$user['level']]; ?></option>
                    <option value="1">Admin</option>
                    <option value="2">Masyarakat</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Profile Image</label>

              <div class="drag-drop-area" id="imageDragDropArea">
                <input type="file" name="image" id="imageUploadInput" accept="image/*" class="d-none">
                <p class="text-muted mb-2 initial-text">Seret dan lepas gambar di sini, atau</p>
                <button type="button" class="btn btn-primary btn-sm" id="browseImageButton">Pilih Gambar</button>
                <img id="imagePreview" class="preview-image mt-3" src="#" alt="Image Preview" style="display: none;">

                <div id="fileInfo" class="mt-2 text-center" style="display: none;">
                  <p class="mb-0 text-muted" id="fileName"></p>
                  <p class="mb-0 text-muted" id="fileSize"></p>
                </div>

                <i class="feather feather-x-circle clear-image-icon" style="display: none;" title="Hapus Gambar"></i>
                <small class="text-muted mt-2 max-size-text">Ukuran maks: 2MB</small>
              </div>

              <?php if (isset($user['img']) && !empty($user['img'])) : ?>
                <input type="hidden" name="current_image" value="<?= htmlspecialchars($user['img']); ?>">
                <img src="<?= base_url('assets/img/profile/' . $user['img']); ?>" alt="Current Profile Image" class="current-profile-image mt-2" style="max-width: 150px; display: block;">
                <small class="text-muted current-image-label">Gambar saat ini.</small>
              <?php endif; ?>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Save User</button>
            </div>
          </form>
        </div>
        <!-- /.widget-body -->
      </div>
    </div>
  </div>
</div>

<script src="<?= base_url(); ?>app/views/users/js/users.js"></script>
<script src="<?= base_url(); ?>app/views/users/js/imgUploads.js"></script>