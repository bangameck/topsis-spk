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
          <?php if (isset($rankingResults['error'])): ?>
            <div class="alert alert-danger" role="alert">
              Error: <?= htmlspecialchars($rankingResults['error']); ?>
            </div>
          <?php elseif (empty($rankingResults)): ?>
            <div class="alert alert-info" role="alert">
              Belum ada data alternatif untuk dihitung atau tidak ada kriteria.
            </div>
          <?php else: ?>
            <h4>Peringkat Alternatif (Masyarakat yang Paling Layak Dibantu)</h4>
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Peringkat</th>
                  <th>User (Masyarakat)</th>
                  <th>Nilai Preferensi (V)</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($rankingResults as $result): ?>
                  <tr>
                    <td><?= htmlspecialchars($result['rank']); ?></td>
                    <td><?= htmlspecialchars($result['user_name']); ?></td>
                    <td><?= number_format($result['score'], 4); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>

            <hr>

            <h3>Detail Proses Perhitungan TOPSIS</h3>

            <?php
            // Ambil daftar Criteria ID dan nama untuk header tabel
            $criteriaHeaders = [];
            // Asumsi $topsis (instance TopsisCalculator) tersedia atau Anda bisa memuat ulang kriteria
            // Untuk contoh ini, kita bisa ambil dari $rankingResults[0]['detail']['matrix'] keys
            if (!empty($rankingResults)) {
              foreach ($rankingResults[0]['detail']['matrix'] as $criteriaId => $value) {
                // Ambil nama kriteria dari database jika perlu,
                // atau Anda bisa melewatkannya sebagai bagian dari $topsis->criterias
                // Untuk sementara, kita pakai ID kriteria langsung
                $criteriaHeaders[$criteriaId] = $criteriaId;
              }
              // Opsional: Jika Anda ingin nama kriteria yang lebih informatif:
              // require_once __DIR__ . '/../controllers/TopsisCalculator.php';
              // $tempTopsis = new TopsisCalculator($db); // Hati-hati dengan inisialisasi ulang
              // foreach ($tempTopsis->criterias as $cid => $data) {
              //    $criteriaHeaders[$cid] = $data['criteria_name']; // Asumsi Anda punya criteria_name di data kriteria
              // }
            }
            ?>

            <h5>1. Matriks Keputusan (X)</h5>
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
                <?php foreach ($rankingResults as $result): ?>
                  <tr>
                    <td><?= htmlspecialchars($result['user_name']); ?></td>
                    <?php foreach ($criteriaHeaders as $cid => $cname): ?>
                      <td><?= number_format($result['detail']['matrix'][$cid], 2); ?></td>
                    <?php endforeach; ?>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <br>

            <h5>2. Matriks Normalisasi (R)</h5>
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
                <?php foreach ($rankingResults as $result): ?>
                  <tr>
                    <td><?= htmlspecialchars($result['user_name']); ?></td>
                    <?php foreach ($criteriaHeaders as $cid => $cname): ?>
                      <td><?= number_format($result['detail']['normalized'][$cid], 4); ?></td>
                    <?php endforeach; ?>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <br>

            <h5>3. Matriks Normalisasi Terbobot (Y)</h5>
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
                <?php foreach ($rankingResults as $result): ?>
                  <tr>
                    <td><?= htmlspecialchars($result['user_name']); ?></td>
                    <?php foreach ($criteriaHeaders as $cid => $cname): ?>
                      <td><?= number_format($result['detail']['weighted_normalized'][$cid], 4); ?></td>
                    <?php endforeach; ?>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <br>

            <h5>4. Solusi Ideal Positif (A+) dan Negatif (A-)</h5>
            <?php
            // Untuk A+ dan A-, kita perlu mendapatkan langsung dari TopsisCalculator
            // Ini akan memerlukan penyesuaian di TopsisCalculator untuk menyimpan A+ dan A-
            // atau Anda bisa menghitung ulang di sini jika data sudah ada.
            // Untuk kesederhanaan, kita bisa asumsikan idealPositive dan idealNegative
            // juga dikembalikan sebagai bagian dari rankingResults (misal, di $rankingResults[0]['meta']['idealPositive'])
            // ATAU, cara lebih baik, modifikasi TopsisCalculator untuk mengembalikan A+ dan A- secara terpisah
            // atau buat getter method untuk A+ dan A-
            ?>
            <table class="table table-bordered table-sm">
              <thead>
                <tr>
                  <th>Kriteria</th>
                  <?php foreach ($criteriaHeaders as $cid => $cname): ?>
                    <th><?= htmlspecialchars($cname); ?></th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>A+</td>
                  <?php
                  // Ini membutuhkan A+ dari TopsisCalculator
                  // Untuk sekarang, kita bisa tampilkan dummy atau perlu modifikasi TopsisCalculator
                  // Anggap $idealPositive dan $idealNegative tersedia dari TopsisCalculator
                  // Anda bisa menambahkan getter method di TopsisCalculator untuk ini
                  // Contoh: $topsis->getIdealPositive()
                  // ATAU mengembalikan mereka di $rankingResults['meta']
                  ?>
                  <?php
                  // Untuk contoh ini, kita akan membuat array dummy atau Anda perlu memodifikasi TopsisCalculator
                  // agar `calculate()` mengembalikan A+ dan A- juga, misalnya:
                  // return ['results' => $results, 'idealPositive' => $idealPositive, 'idealNegative' => $idealNegative];
                  // Lalu di route/web.php: $topsisData = $topsis->calculate(); $rankingResults = $topsisData['results']; $idealPositive = $topsisData['idealPositive']; ...
                  // Untuk saat ini, kita tampilkan placeholder atau ambil dari instance $topsis jika memungkinkan

                  // Contoh, jika TopsisCalculator menyimpan ini di properti private, Anda butuh getter:
                  // $idealPositive = $topsis->getIdealPositiveValues(); // Anda perlu menambahkan ini di TopsisCalculator
                  // $idealNegative = $topsis->getIdealNegativeValues(); // Anda perlu menambahkan ini di TopsisCalculator

                  // Alternatif lain, jika Anda mengembalikan mereka sebagai bagian dari `calculate()`:
                  // Misalnya, di TopsisCalculator::calculate() return ['rankingResults' => $results, 'idealPositive' => $idealPositive, 'idealNegative' => $idealNegative];
                  // Lalu di web.php: list($rankingResults, $idealPositive, $idealNegative) = $topsis->calculate();

                  // Untuk demonstrasi di view tanpa modifikasi TopsisCalculator, ini agak sulit
                  // Mari kita tambahkan A+ dan A- ke output `calculate()` di TopsisCalculator agar mudah diakses di sini.
                  // Saya akan update TopsisCalculator di bagian selanjutnya.

                  // Asumsi idealPositive dan idealNegative sudah ada di scope view
                  // (akan dijelaskan bagaimana ini bisa terjadi setelah update TopsisCalculator)
                  foreach ($criteriaHeaders as $cid => $cname): ?>
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

            <h5>5. Jarak ke Solusi Ideal (D+ dan D-)</h5>
            <table class="table table-bordered table-sm">
              <thead>
                <tr>
                  <th>User</th>
                  <th>D+</th>
                  <th>D-</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($rankingResults as $result): ?>
                  <tr>
                    <td><?= htmlspecialchars($result['user_name']); ?></td>
                    <td><?= number_format($result['detail']['d_plus'], 4); ?></td>
                    <td><?= number_format($result['detail']['d_minus'], 4); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <br>

          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>