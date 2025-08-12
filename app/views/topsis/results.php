<title>Hasil Perhitungan TOPSIS</title>
<div class="row page-title clearfix">
  <div class="page-title-left">
    <h6 class="page-title-heading mr-0 mr-r-5">TOPSIS</h6>
    <p class="page-title-description mr-0 d-none d-md-inline-block">Detail & Hasil Perhitungan SPK</p>
  </div>
  <div class="page-title-right d-none d-sm-inline-flex">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url();?>home">Home</a></li>
      <li class="breadcrumb-item active">Hasil TOPSIS</li>
    </ol>
  </div>
</div>

<div class="widget-list">
  <div class="row">
    <div class="col-md-12 widget-holder">
      <div class="widget-bg">
        <div class="widget-body clearfix">
          <?php if (isset($topsisData['error'])): ?>
            <div class="alert alert-danger" role="alert">
              Error: <?php echo htmlspecialchars($topsisData['error']);?>
            </div>
          <?php elseif (empty($rankingResults)): ?>
            <div class="alert alert-info" role="alert">
              Belum ada data alternatif untuk dihitung atau tidak ada kriteria.
            </div>
          <?php else: ?>
            <h4>Peringkat Alternatif (Masyarakat yang Paling Layak Dibantu)</h4>
            <table id="tableResults" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th style="width: 10%;">Peringkat</th>
                  <th>Nama Masyarakat</th>
                  <th style="text-align: left;">Nilai Preferensi (V)</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($rankingResults as $result): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($result['peringkat']);?></td>
                    <td><?php echo htmlspecialchars($result['user_name']);?></td>
                    <td style="text-align: left;"><?php echo number_format($result['score'], 4);?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>

            <!-- <hr>

            <h3>Detail Proses Perhitungan TOPSIS</h3>

            <?php
                $criteriaHeaders = [];
                if (! empty($criteriaInfo)) {
                    foreach ($criteriaInfo as $criteriaId => $info) {
                        $criteriaHeaders[$criteriaId] = $info['criteria_name'];
                    }
                } elseif (! empty($idealPositive)) {
                    foreach ($idealPositive as $criteriaId => $value) {
                        $criteriaHeaders[$criteriaId] = $criteriaId;
                    }
                }
            ?>

            <h5>1. Matriks Keputusan (X)</h5>
            <p>Nilai input asli dari setiap alternatif pada setiap kriteria.</p>
            <<table class="table table-bordered table-sm">
              <thead>
                <tr>
                  <th>User</th>
                  <?php foreach ($criteriaHeaders as $cid => $cname): ?>
                    <th><?php echo htmlspecialchars($cname);?></th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($allUsersData as $userData): ?>
<?php $userId = $userData['user_id']; ?>
                  <tr>
                    <td><?php echo htmlspecialchars($userData['user_name']);?></td>
                    <?php foreach ($criteriaHeaders as $cid => $cname): ?>
                      <td><?php echo number_format($topsisData['detailMatrices']['matrix'][$userId][$cid] ?? 0);?></td>
                    <?php endforeach; ?>
                  </tr>
                <?php endforeach; ?>
              </tbody>
              </table>
              <br>

              <h5>2. Matriks Normalisasi (R)</h5>
              <p>Normalisasi dilakukan untuk menyamakan skala nilai antar kriteria. Menggunakan rumus:</p> -->
              <!-- <div class="formula">
                $$r_{ij} = \frac{x_{ij}}{\sqrt{\sum_{i=1}^{m} x_{ij}^2}}$$
              </div> -->
              <!-- <table class="table table-bordered table-sm">
                <thead>
                  <tr>
                    <th>User</th>
                    <?php foreach ($criteriaHeaders as $cid => $cname): ?>
                      <th><?php echo htmlspecialchars($cname);?></th>
                    <?php endforeach; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($allUsersData as $userData): ?>
<?php $userId = $userData['user_id']; ?>
                    <tr>
                      <td><?php echo htmlspecialchars($userData['user_name']);?></td>
                      <?php foreach ($criteriaHeaders as $cid => $cname): ?>
                        <td><?php echo number_format($detailMatrices['normalizedMatrix'][$userId][$cid] ?? 0, 4);?></td>
                      <?php endforeach; ?>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
              <br>

              <h5>3. Matriks Normalisasi Terbobot (Y)</h5>
              <p>Matriks normalisasi dikalikan dengan bobot kriteria. Menggunakan rumus:</p> -->
              <!-- <div class="formula">
                $$y_{ij} = w_j \cdot r_{ij}$$
              </div> -->
              <!-- <table class="table table-bordered table-sm">
                <thead>
                  <tr>
                    <th>User</th>
                    <?php foreach ($criteriaHeaders as $cid => $cname): ?>
                      <th><?php echo htmlspecialchars($cname);?> (Bobot: <?php echo number_format($criteriaInfo[$cid]['weight'] ?? 0, 2);?>)</th>
                    <?php endforeach; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($allUsersData as $userData): ?>
<?php $userId = $userData['user_id']; ?>
                    <tr>
                      <td><?php echo htmlspecialchars($userData['user_name']);?></td>
                      <?php foreach ($criteriaHeaders as $cid => $cname): ?>
                        <td><?php echo number_format($detailMatrices['weightedNormalizedMatrix'][$userId][$cid] ?? 0, 4);?></td>
                      <?php endforeach; ?>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
              <br>

              <h5>4. Solusi Ideal Positif (A+) dan Solusi Ideal Negatif (A-)</h5>
              <p>A+ adalah nilai terbaik untuk setiap kriteria, A- adalah nilai terburuk untuk setiap kriteria. Ditentukan dari matriks normalisasi terbobot.</p>
              <table class="table table-bordered table-sm">
                <thead>
                  <tr>
                    <th></th>
                    <?php foreach ($criteriaHeaders as $cid => $cname): ?>
                      <th><?php echo htmlspecialchars($cname);?> (Tipe: <?php echo htmlspecialchars($criteriaInfo[$cid]['type'] ?? '');?>)</th>
                    <?php endforeach; ?>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>A+</td>
                    <?php foreach ($criteriaHeaders as $cid => $cname): ?>
                      <td><?php echo number_format($idealPositive[$cid] ?? 0, 4);?></td>
                    <?php endforeach; ?>
                  </tr>
                  <tr>
                    <td>A-</td>
                    <?php foreach ($criteriaHeaders as $cid => $cname): ?>
                      <td><?php echo number_format($idealNegative[$cid] ?? 0, 4);?></td>
                    <?php endforeach; ?>
                  </tr>
                </tbody>
              </table>
              <br>

              <h5>5. Jarak ke Solusi Ideal Positif (D+) dan Negatif (D-)</h5>
              <p>D+ adalah jarak setiap alternatif ke solusi ideal positif. D- adalah jarak setiap alternatif ke solusi ideal negatif. Menggunakan rumus:</p> -->
              <!-- <div class="formula">
                $$D_i^+ = \sqrt{\sum_{j=1}^{n} (A_j^+ - y_{ij})^2}$$
              </div>
              <div class="formula">
                $$D_i^- = \sqrt{\sum_{j=1}^{n} (A_j^- - y_{ij})^2}$$
              </div> -->
              <!-- <table class="table table-bordered table-sm">
                <thead>
                  <tr>
                    <th>User</th>
                    <th>D+</th>
                    <th>D-</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($allUsersData as $userData): ?>
<?php $userId = $userData['user_id']; ?>
                    <tr>
                      <td><?php echo htmlspecialchars($userData['user_name']);?></td>
                      <td><?php echo number_format($detailMatrices['distancePositive'][$userId] ?? 0, 4);?></td>
                      <td><?php echo number_format($detailMatrices['distanceNegative'][$userId] ?? 0, 4);?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
              <br>

              <h5>6. Nilai Preferensi (V)</h5>
              <p>Nilai preferensi menunjukkan seberapa dekat alternatif dengan solusi ideal positif dan seberapa jauh dari solusi ideal negatif. Menggunakan rumus:</p> -->
              <!-- <div class="formula">
                $$V_i = \frac{D_i^-}{D_i^- + D_i^+}$$
              </div> -->
              <!-- <p>Alternatif dengan nilai V tertinggi adalah yang terbaik.</p>
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>User</th>
                    <th>Nilai Preferensi (V)</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($rankingResults as $result): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($result['user_name']);?></td>
                      <td><?php echo number_format($result['score'], 4);?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>

            <?php endif; ?> -->
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  /* Styling untuk formula container */
  .formula {
    font-family: serif;
    /* Font serif untuk tampilan matematis */
    font-size: 1.1em;
    margin-left: 20px;
    background-color: #f9f9f9;
    padding: 8px 12px;
    border-left: 3px solid #007bff;
    margin-bottom: 15px;
    overflow-x: auto;
    /* Untuk rumus yang terlalu panjang */
  }
</style>

<script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
<script>
  // Konfigurasi MathJax (opsional, tapi bagus untuk kontrol)
  window.MathJax = {
    tex: {
      inlineMath: [
        ['$', '$'],
        ['\\(', '\\)']
      ] // Mengaktifkan rumus inline dengan $...$
    },
    svg: {
      fontCache: 'global' // Mempercepat rendering jika MathJax digunakan berulang kali
    }
  };
</script>
<?php include_once __DIR__ . '/../../../config/jstable.php'?>