<title>Halaman Input Nilai Alternatif</title>
<div class="row page-title clearfix">
  <div class="page-title-left">
    <h6 class="page-title-heading mr-0 mr-r-5">Alternatif</h6>
    <p class="page-title-description mr-0 d-none d-md-inline-block">Input Nilai Alternatif</p>
  </div>
  <div class="page-title-right d-none d-sm-inline-flex">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>home">Home</a></li>
      <li class="breadcrumb-item active">Input Alternatif</li>
    </ol>
  </div>
</div>

<div class="widget-list">
  <div class="row">
    <div class="col-md-12 widget-holder">
      <div class="widget-bg">
        <div class="widget-body clearfix">
          <form action="<?= base_url(); ?>alternative/save" method="post">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">

            <?php if (empty($criterias)): ?>
              <div class="alert alert-warning" role="alert">
                Belum ada kriteria yang ditambahkan oleh admin. Silakan hubungi admin untuk menambahkan kriteria.
              </div>
            <?php else: ?>
              <?php
              // Mengambil nilai alternatif yang sudah ada untuk user_id saat ini
              // Pastikan $db sudah tersedia dari controlWeb.php
              $user_id = $_SESSION['user_id'] ?? null;
              $existing_alternative_values = [];
              if ($user_id) {
                $stmt_alt = $db->prepare("SELECT criteria_id, alternative_value FROM alternative WHERE user_id = ?");
                $stmt_alt->bind_param("i", $user_id);
                $stmt_alt->execute();
                $result_alt = $stmt_alt->get_result();
                while ($row_alt = $result_alt->fetch_assoc()) {
                  $existing_alternative_values[$row_alt['criteria_id']] = $row_alt['alternative_value'];
                }
                $stmt_alt->close();
              }
              ?>

              <?php foreach ($criterias as $criteria): ?>
                <div class="form-group">
                  <label for="<?= htmlspecialchars($criteria['criteria_id']); ?>">
                    <?= htmlspecialchars($criteria['criteria_name']); ?>
                    <small class="text-muted">(<?= htmlspecialchars($criteria['criteria_information']); ?>)</small>
                  </label>
                  <input type="text" class="form-control"
                    id="<?= htmlspecialchars($criteria['criteria_id']); ?>"
                    name="<?= htmlspecialchars($criteria['criteria_id']); ?>"
                    value="<?= htmlspecialchars($existing_alternative_values[$criteria['criteria_id']] ?? ''); ?>"
                    required>
                </div>
              <?php endforeach; ?>

              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Simpan Nilai Alternatif</button>
              </div>
            <?php endif; ?>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Anda bisa menambahkan JS khusus untuk halaman alternatif di sini jika diperlukan
</script>