<title>Halaman Tambah Kriteria</title>
<div class="row page-title clearfix">
  <div class="page-title-left">
    <h6 class="page-title-heading mr-0 mr-r-5">Kriteria</h6>
    <p class="page-title-description mr-0 d-none d-md-inline-block">Input Kriteria</p>
  </div>
  <!-- /.page-title-left -->
  <div class="page-title-right d-none d-sm-inline-flex">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>criteria">Kriteria</a>
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
          <form action="<?= base_url(); ?>criteria/actionAdd" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="criteriaId">ID</label>
                  <input type="text" id="criteriaId" class="form-control" name="id" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="name">Nama</label>
                  <input type="text" class="form-control" name="name" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="value">Bobot</label>
                  <input type="number" step="0.1" value="0.0" class="form-control" name="value" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="type">Jenis</label>
                  <select class="form-control" name="type" required>
                    <option value="cost">Cost</option>
                    <option value="benefit">Benefit</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="information">Information</label>
              <textarea class="form-control" name="information" rows="3"></textarea>

            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Save Kriteria</button>
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