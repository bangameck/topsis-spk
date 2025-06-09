<?php
$id = $_GET['id'];
$query = "SELECT * FROM criteria WHERE criteria_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $id);
$stmt->execute();
$result =  $stmt->get_result();

$criteria = $result->fetch_assoc();

$stmt->close();
?>
<title>Halaman Update Kriteria</title>
<div class="row page-title clearfix">
  <div class="page-title-left">
    <h6 class="page-title-heading mr-0 mr-r-5">Kriteria</h6>
    <p class="page-title-description mr-0 d-none d-md-inline-block">Update Kriteria</p>
  </div>
  <!-- /.page-title-left -->
  <div class="page-title-right d-none d-sm-inline-flex">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>criteria">Kriteria</a>
      </li>
      <li class="breadcrumb-item active">update</li>
    </ol>

  </div>
  <!-- /.page-title-right -->
</div>
<div class="widget-list">
  <div class="row">
    <div class="col-md-12 widget-holder">
      <div class="widget-bg">
        <div class="widget-body clearfix">
          <form action="<?= base_url(); ?>criteria/actionEdit/<?= $criteria['criteria_id']; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="criteriaId">ID</label>
                  <input type="text" id="criteriaId" value="<?= $criteria['criteria_id']; ?>" class="form-control" readonly>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="name">Nama</label>
                  <input type="text" class="form-control" value="<?= $criteria['criteria_name']; ?>" name="name" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="value">Bobot</label>
                  <input type="number" step="0.1" value="<?= $criteria['criteria_value']; ?>" class="form-control" name="value" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="type">Jenis</label>
                  <select class="form-control" name="type" required>
                    <?php $type = ['cost' => 'Cost', 'benefit' => 'Benefit']; ?>
                    <option value="<?= $criteria['criteria_type']; ?>"><?= $type[$criteria['criteria_type']]; ?></option>
                    <option value="cost">Cost</option>
                    <option value="benefit">Benefit</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="information">Information</label>
              <textarea class="form-control" name="information" rows="3"><?= $criteria['criteria_information']; ?></textarea>

            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Update Kriteria</button>
            </div>
          </form>
        </div>
        <!-- /.widget-body -->
      </div>
    </div>
  </div>
</div>
<!-- JS -->
<script src="<?= base_url(); ?>app/views/criteria/js/criteria.js"></script>