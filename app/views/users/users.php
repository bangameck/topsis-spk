<?php
require_once __DIR__ . '/../../../config/controlWeb.php';

if ($_SESSION['level'] != 1) {
  toastNotif('error', 'Anda tidak memiliki akses dihalaman users!');
  echo "<script>
				window.location='" . base_url() . "home'
        </script>";
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
        <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3">
          <h5 class="mb-0"></h5>
          <div>
            <a href="<?= base_url(); ?>users/add" class="btn btn-primary">
              <i class="fas fa-user-plus mr-1"></i> Add User
            </a>
          </div>
        </div>

        <!-- Users Table -->
        <div class="widget-body clearfix">
          <table id="tableUsers" class="hover">
            <thead class="text-center">
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
              $no = 1;
              $sql = "SELECT * FROM users";
              $result = $db->query($sql);
              if ($result->num_rows > 0): ?>
                <?php while ($user = $result->fetch_assoc()): ?>
                  <tr>
                    <td><?php echo $no++; ?></td>
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
                    <td class="text-center">
                      <?php if ($user['img']) {
                        $img = $user['img'];
                      } else {
                        $img = 'default.png';
                      } ?>
                      <figure class="thumb-xs2">
                        <img class="rounded-circle" src="assets/img/profile/<?= $img; ?>" alt="">
                      </figure>
                    </td>
                    <td><?php echo date('d M Y H:i', strtotime($user['created_at'])); ?></td>
                    <td>
                      <a class="btn btn-sm btn-warning edit-user" href="<?= base_url(); ?>users/edit/<?= $user['user_id']; ?>">
                        <i class="fa fa-edit"></i> Edit
                      </a>
                      <a href="<?= base_url(); ?>users/delete/<?= $user['user_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah kamu yakin ingin menghapus user ini?')">
                        <i class="fa fa-trash"></i> Delete
                      </a>
                    </td>
                  </tr>
                <?php endwhile; ?>
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
  <?php include_once __DIR__ . "/../../../config/jstable.php" ?>
  <!-- Custom JS -->
  <script src="<?= base_url(); ?>app/views/users/js/users.js"></script>