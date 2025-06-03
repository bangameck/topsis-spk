<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once __DIR__ . '/../../_func/controlWeb.php';
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
            <?php if ($_SESSION['level'] == 1) : ?>
              <button class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
                <i class="fas fa-user-plus mr-1"></i> Kriteria
              </button>
            <?php endif ?>
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
                <?php if ($_SESSION['level'] == 1) : ?>
                  <th>Actions</th>
                <?php endif ?>
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
                    <?php if ($_SESSION['level'] == 1) : ?>
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
                    <?php endif ?>
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