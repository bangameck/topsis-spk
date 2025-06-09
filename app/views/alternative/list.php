<?php
// topsis-skripsi/app/views/alternative/list.php

// Pastikan $alternativesList sudah tersedia di sini dari route/web.php
// Pastikan $criteriaList juga tersedia untuk mapping criteria_id ke criteria_name
?>

<title>Daftar Nilai Alternatif</title>
<div class="row page-title clearfix">
  <div class="page-title-left">
    <h6 class="page-title-heading mr-0 mr-r-5">Alternatif</h6>
    <p class="page-title-description mr-0 d-none d-md-inline-block">Daftar Nilai Alternatif Masyarakat</p>
  </div>
  <div class="page-title-right d-none d-sm-inline-flex">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>home">Home</a></li>
      <li class="breadcrumb-item active">Daftar Alternatif</li>
    </ol>
  </div>
</div>

<div class="widget-list">
  <div class="row">
    <div class="col-md-12 widget-holder">
      <div class="widget-bg">
        <div class="widget-body clearfix">

          <?php if (empty($alternativesList)): ?>
            <div class="alert alert-info" role="alert">
              Belum ada data alternatif yang diinput.
            </div>
          <?php else: ?>
            <h4>Data Nilai Alternatif Masyarakat</h4>
            <table class="table table-bordered table-striped data-table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nama Masyarakat</th>
                  <?php foreach ($criteriaList as $criteria): ?>
                    <th><?= htmlspecialchars($criteria['criteria_name']); ?></th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody>
                <?php $i = 1; ?>
                <?php foreach ($alternativesList as $userId => $data): ?>
                  <tr>
                    <td><?= $i++; ?></td>
                    <td><?= htmlspecialchars($data['user_name']); ?></td>
                    <?php foreach ($criteriaList as $criteria): ?>
                      <td>
                        <?= htmlspecialchars($data['values'][$criteria['criteria_id']] ?? '-'); ?>
                      </td>
                    <?php endforeach; ?>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" />
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {
    $('.data-table').DataTable();
  });
</script>