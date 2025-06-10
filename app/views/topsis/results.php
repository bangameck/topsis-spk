<title>Hasil Perhitungan TOPSIS</title>
<div class="row page-title clearfix">
  <div class="page-title-left">
    <h6 class="page-title-heading mr-0 mr-r-5">TOPSIS</h6>
    <p class="page-title-description mr-0 d-none d-md-inline-block">Detail & Hasil Perhitungan SPK</p>
  </div>
  <div class="page-title-right d-none d-sm-inline-flex">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>home">Home</a></li>
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
              Error: <?= htmlspecialchars($topsisData['error']); ?>
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
                    <td><?= htmlspecialchars($result['rank']); ?></td>
                    <td><?= htmlspecialchars($result['user_name']); ?></td>
                    <td style="text-align: left;"><?= number_format($result['score'], 4); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>

            <hr>

            <h3>Detail Proses Perhitungan TOPSIS</h3>

            <?php
            // Dapatkan daftar Criteria ID dan Nama dari $topsisData['criterias'] jika ada,
            // atau dari $criteriaList yang bisa dimuat di route/web.php
            // Untuk contoh ini, kita akan ambil dari $topsisData['idealPositive'] keys
            // dan asumsi kita bisa mendapatkan nama kriterianya.
            $criteriaHeaders = [];
            // Ambil kriteria dari data yang dikembalikan oleh TopsisCalculator (jika ada di output)
            // atau, cara terbaik, muat daftar kriteria di route/web.php dan kirimkan ke view
            // Contoh: $criteriaList = $topsis->getAllCriterias(); // Tambahkan method ini di TopsisCalculator
            // Di route/web.php: $criteriaList = $topsis->getAllCriterias();
            // Lalu di view, gunakan $criteriaList

            // Untuk demonstrasi ini, kita akan membuat array dummy atau perlu modifikasi TopsisCalculator
            // untuk mengembalikan semua data kriteria (ID, Nama, Bobot, Tipe)
            // Sementara, kita gunakan ID sebagai header jika nama tidak tersedia.
            // Anggap $topsisData['criteriaInfo'] ada (akan dibahas di TopsisCalculator update)
            if (!empty($topsisData['criteriaInfo'])) {
              foreach ($topsisData['criteriaInfo'] as $criteriaId => $info) {
                $criteriaHeaders[$criteriaId] = $info['criteria_name'];
              }
            } elseif (!empty($idealPositive)) {
              // Fallback jika criteriaInfo belum ada, gunakan ID sebagai header
              foreach ($idealPositive as $criteriaId => $value) {
                $criteriaHeaders[$criteriaId] = $criteriaId; // Atau tampilkan nama jika sudah di-fetch di route/web.php
              }
            }
            ?>

            <h5>1. Matriks Keputusan (X)</h5>
            <p>Nilai input asli dari setiap alternatif pada setiap kriteria.</p>
            <table class="table table-bordered table-sm">
              <thead>
                <tr>
                  <th>User</th>
                  <?php foreach ($criteriaHeaders as $cid => $cname): ?>
                    <th><?= htmlspecialchars($cname); ?></th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($allUsersData as $userData): ?>
                  <?php $userId = $userData['user_id']; ?>
                  <tr>
                    <td><?= htmlspecialchars($userData['user_name']); ?></td>
                    <?php foreach ($criteriaHeaders as $cid => $cname): ?>
                      <td><?= number_format($topsisData['detailMatrices']['matrix'][$userId][$cid] ?? 0, 2); ?></td>
                    <?php endforeach; ?>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <br>

            <h5>2. Matriks Normalisasi (R)</h5>
            <p>Normalisasi dilakukan untuk menyamakan skala nilai antar kriteria. Menggunakan rumus:</p>
            <p class='formula'>$r_{ij} = \frac{x_{ij}}{\sqrt{\sum_{i=1}^{m} x_{ij}^2}}$</p>
            <table class="table table-bordered table-sm">
              <thead>
                <tr>
                  <th>User</th>
                  <?php foreach ($criteriaHeaders as $cid => $cname): ?>
                    <th><?= htmlspecialchars($cname); ?></th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($allUsersData as $userData): ?>
                  <?php $userId = $userData['user_id']; ?>
                  <tr>
                    <td><?= htmlspecialchars($userData['user_name']); ?></td>
                    <?php foreach ($criteriaHeaders as $cid => $cname): ?>
                      <td><?= number_format($topsisData['detailMatrices']['normalizedMatrix'][$userId][$cid] ?? 0, 4); ?></td>
                    <?php endforeach; ?>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <br>

            <h5>3. Matriks Normalisasi Terbobot (Y)</h5>
            <p>Matriks normalisasi dikalikan dengan bobot kriteria. Menggunakan rumus:</p>
            <p class='formula'>$y_{ij} = w_j \cdot r_{ij}$</p>
            <table class="table table-bordered table-sm">
              <thead>
                <tr>
                  <th>User</th>
                  <?php foreach ($criteriaHeaders as $cid => $cname): ?>
                    <th><?= htmlspecialchars($cname); ?> (Bobot: <?= number_format($topsisData['criteriaInfo'][$cid]['weight'] ?? 0, 2); ?>)</th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($allUsersData as $userData): ?>
                  <?php $userId = $userData['user_id']; ?>
                  <tr>
                    <td><?= htmlspecialchars($userData['user_name']); ?></td>
                    <?php foreach ($criteriaHeaders as $cid => $cname): ?>
                      <td><?= number_format($topsisData['detailMatrices']['weightedNormalizedMatrix'][$userId][$cid] ?? 0, 4); ?></td>
                    <?php endforeach; ?>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <br>

            <h5>4. Solusi Ideal Positif (A+) dan Negusi Ideal Negatif (A-)</h5>
            <p>A+ adalah nilai terbaik untuk setiap kriteria, A- adalah nilai terburuk untuk setiap kriteria. Ditentukan dari matriks normalisasi terbobot.</p>
            <table class="table table-bordered table-sm">
              <thead>
                <tr>
                  <th></th>
                  <?php foreach ($criteriaHeaders as $cid => $cname): ?>
                    <th><?= htmlspecialchars($cname); ?> (Tipe: <?= htmlspecialchars($topsisData['criteriaInfo'][$cid]['type'] ?? ''); ?>)</th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>A+</td>
                  <?php foreach ($criteriaHeaders as $cid => $cname): ?>
                    <td><?= number_format($idealPositive[$cid] ?? 0, 4); ?></td>
                  <?php endforeach; ?>
                </tr>
                <tr>
                  <td>A-</td>
                  <?php foreach ($criteriaHeaders as $cid => $cname): ?>
                    <td><?= number_format($idealNegative[$cid] ?? 0, 4); ?></td>
                  <?php endforeach; ?>
                </tr>
              </tbody>
            </table>
            <br>

            <h5>5. Jarak ke Solusi Ideal Positif (D+) dan Negatif (D-)</h5>
            <p>D+ adalah jarak setiap alternatif ke solusi ideal positif. D- adalah jarak setiap alternatif ke solusi ideal negatif. Menggunakan rumus:</p>
            <p class='formula'>$D_i^+ = \sqrt{\sum_{j=1}^{n} (A_j^+ - y_{ij})^2}$</p>
            <p class='formula'>$D_i^- = \sqrt{\sum_{j=1}^{n} (A_j^- - y_{ij})^2}$</p>
            <table class="table table-bordered table-sm">
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
                    <td><?= htmlspecialchars($userData['user_name']); ?></td>
                    <td><?= number_format($topsisData['detailMatrices']['distancePositive'][$userId] ?? 0, 4); ?></td>
                    <td><?= number_format($topsisData['detailMatrices']['distanceNegative'][$userId] ?? 0, 4); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <br>

            <h5>6. Nilai Preferensi (V)</h5>
            <p>Nilai preferensi menunjukkan seberapa dekat alternatif dengan solusi ideal positif dan seberapa jauh dari solusi ideal negatif. Menggunakan rumus:</p>
            <p class='formula'>$V_i = \frac{D_i^-}{D_i^- + D_i^+}$</p>
            <p>Alternatif dengan nilai V tertinggi adalah yang terbaik.</p>
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
                    <td><?= htmlspecialchars($result['user_name']); ?></td>
                    <td><?= number_format($result['score'], 4); ?></td>
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

<style>
  .formula {
    font-family: serif;
    /* Math formulas often look better in serif font */
    font-size: 1.1em;
    margin-left: 20px;
    /* Indent formulas */
    background-color: #f9f9f9;
    padding: 8px 12px;
    border-left: 3px solid #007bff;
    margin-bottom: 15px;
  }
</style>
<?php include_once __DIR__ . '/../../../config/jstable.php'; ?>