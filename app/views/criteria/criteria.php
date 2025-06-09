<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once __DIR__ . '/../../../config/controlWeb.php';
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
              <a href="<?= base_url(); ?>criteria/add" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i> Kriteria
              </a>
            <?php endif ?>
          </div>
        </div>

        <!-- Criteria Table -->
        <div class="widget-body clearfix">
          <table id="tableCriteria" class="hover">
            <thead class="thead-dark">
              <tr>
                <th>#</th>
                <th>ID</th>
                <th>Nama</th>
                <th>Jenis</th>
                <th style="width: 10%;">Bobot (%)</th>
                <th>Keterangan</th>
                <?php if ($_SESSION['level'] == 1) : ?>
                  <th style="width: 20%;">Actions</th>
                <?php endif ?>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              $sql = "SELECT * FROM criteria";
              $result = $db->query($sql);
              if ($result->num_rows > 0): ?>
                <?php while ($criteria = $result->fetch_assoc()): ?>
                  <tr>
                    <td><?php echo $no++ ?></td>
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
                    <td><b><?= htmlspecialchars($criteria['criteria_value']) ?></b></td>
                    <td><?= htmlspecialchars($criteria['criteria_information']); ?></td>
                    <?php if ($_SESSION['level'] == 1) : ?>
                      <td>
                        <a class="btn btn-sm btn-warning edit-user" href="<?= base_url(); ?>criteria/edit/<?= $criteria['criteria_id']; ?>">
                          <i class="fa fa-edit"></i> Edit
                        </a>
                        <a href="<?= base_url(); ?>criteria/delete/<?= $criteria['criteria_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah kamu yakin menghapus kriteria ini?')">
                          <i class="fa fa-trash"></i> Delete
                        </a>
                      </td>
                    <?php endif ?>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7" class="text-center">No criterias found</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include_once __DIR__ . '/../../../config/jstable.php'; ?>

<!-- JS -->
<script src="<?= base_url(); ?>app/views/criteria/js/criteria.js"></script>