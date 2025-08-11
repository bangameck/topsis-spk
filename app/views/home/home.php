<title>Dashboard</title>
<div class="row page-title clearfix">
  <div class="page-title-left">
    <h6 class="page-title-heading mr-0 mr-r-5">Dashboard</h6>
    <p class="page-title-description mr-0 d-none d-md-inline-block">Statistik, Grafik dan Informasi Sistem</p>
  </div>
  <div class="page-title-right d-none d-sm-inline-flex">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item active">Home</li>
    </ol>
  </div>
</div>

<div class="widget-list row">

  <div class="widget-holder widget-full-height widget-flex col-md-6">
    <div class="widget-bg">
      <div class="widget-heading">
        <h4 class="widget-title">
          <small class="h5 ml-1 my-0 fw-500">Penerima UMKM</small>
        </h4>
      </div>
      <div class="widget-body">
        <div class="mr-t-10 flex-1">
          <div class="h-100" style="max-height: 270px; overflow-y: auto;">
            <div class="widget-body clearfix">
              <table class="table table-striped table-responsive" data-toggle="datatables" data-plugin-options='{"searching": true}'>
                <thead class="thead-dark">
                  <tr>
                    <th>Rank</th>
                    <th>Nama</th>
                    <th>Nilai</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($rankingResults as $result): ?>
                    <tr>
                      <td><?= htmlspecialchars($result['peringkat']); ?></td>
                      <td><?= htmlspecialchars($result['user_name']); ?></td>
                      <td style="text-align: left;"><?= number_format($result['score'], 4); ?></td>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
              <?php if (!empty($_SESSION['username'])) : ?>
                <span><a href="<?= base_url(); ?>criteria">Klik selengkapnya ></a></span>
              <?php endif ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="widget-holder widget-full-height widget-flex col-md-6">
    <div class="widget-bg">
      <div class="widget-heading">
        <h4 class="widget-title">Peringkat Alternatif Teratas</h4>
      </div>
      <div class="widget-body">
        <canvas id="rankingChart" style="height: 270px;"></canvas>
      </div>
    </div>
  </div>

  <div class="widget-holder widget-full-height widget-flex col-md-6">
    <div class="widget-bg">
      <div class="widget-heading">
        <h4 class="widget-title">Bobot Kriteria</h4>
      </div>
      <div class="widget-body">
        <canvas id="criteriaWeightChart" style="height: 270px;"></canvas>
      </div>
    </div>
  </div>

  <div class="widget-holder widget-full-height widget-flex col-md-6">
    <div class="widget-bg">
      <div class="widget-heading">
        <h4 class="widget-title">Distribusi Tipe Kriteria</h4>
      </div>
      <div class="widget-body">
        <canvas id="criteriaTypeChart" style="height: 270px;"></canvas>
      </div>
    </div>
  </div>

  <div class="widget-holder col-md-12">
    <div class="widget-bg">
      <div class="widget-heading">
        <h4 class="widget-title">Statistik Ringkas</h4>
      </div>
      <div class="widget-body row">
        <div class="col-md-4">
          <div class="card text-center mb-3">
            <div class="card-body">
              <h5 class="card-title">Total Masyarakat</h5>
              <p class="card-text h1 fw-600">
                <?php
                $user_count = $db->query("SELECT COUNT(DISTINCT user_id) FROM alternative")->fetch_row()[0];
                echo $user_count;
                ?>
              </p>
              <p class="card-text text-muted">Telah menginput data alternatif</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center mb-3">
            <div class="card-body">
              <h5 class="card-title">Alternatif Teratas</h5>
              <p class="card-text h1 fw-600">
                <?php
                if (!empty($rankingResults)) {
                  echo htmlspecialchars($rankingResults[0]['user_name']);
                } else {
                  echo '-';
                }
                ?>
              </p>
              <p class="card-text text-muted">Dengan skor:
                <?php
                if (!empty($rankingResults)) {
                  echo '<b>' . number_format($rankingResults[0]['score'], 4) . '</b>';
                } else {
                  echo '-';
                }
                ?>
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-center mb-3">
            <div class="card-body">
              <h5 class="card-title">Terakhir Dihitung</h5>
              <p class="card-text h1 fw-600">
                <?php
                $last_calc = $db->query("SELECT MAX(calculated_at) FROM ranking")->fetch_row()[0];
                echo $last_calc ? date('d M Y', strtotime($last_calc)) : '-';
                ?>
              </p>
              <p class="card-text text-muted">Tanggal perhitungan TOPSIS terakhir</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Pastikan data ini di-pass dari PHP ke JavaScript
    const rankingResults = <?php echo json_encode($rankingResults ?? []); ?>;
    const criteriaInfo = <?php echo json_encode($criteriaInfo ?? []); ?>;
    const criteriaTypes = <?php echo json_encode($criteriaTypes ?? ['benefit_count' => 0, 'cost_count' => 0]); ?>;

    // --- Grafik 1: Peringkat Alternatif (Bar Chart) ---
    if (rankingResults.length > 0) {
      const rankingLabels = rankingResults.slice(0, 5).map(r => r.user_name); // Ambil 5 teratas
      const rankingScores = rankingResults.slice(0, 5).map(r => r.score);

      const rankingCtx = document.getElementById('rankingChart').getContext('2d');
      new Chart(rankingCtx, {
        type: 'bar',
        data: {
          labels: rankingLabels,
          datasets: [{
            label: 'Nilai Preferensi (V)',
            data: rankingScores,
            backgroundColor: [
              'rgba(75, 192, 192, 0.6)',
              'rgba(54, 162, 235, 0.6)',
              'rgba(153, 102, 255, 0.6)',
              'rgba(255, 159, 64, 0.6)',
              'rgba(255, 99, 132, 0.6)'
            ],
            borderColor: [
              'rgba(75, 192, 192, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)',
              'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true,
              max: 1.0 // Nilai V TOPSIS selalu antara 0 dan 1
            }
          },
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  let label = context.dataset.label || '';
                  if (label) {
                    label += ': ';
                  }
                  if (context.parsed.y !== null) {
                    label += context.parsed.y.toFixed(4); // Format ke 4 desimal
                  }
                  return label;
                }
              }
            }
          }
        }
      });
    }

    // --- Grafik 2: Bobot Kriteria (Doughnut Chart) ---
    if (Object.keys(criteriaInfo).length > 0) {
      const weightLabels = Object.values(criteriaInfo).map(c => c.criteria_name);
      const weightData = Object.values(criteriaInfo).map(c => c.weight);

      const weightCtx = document.getElementById('criteriaWeightChart').getContext('2d');
      new Chart(weightCtx, {
        type: 'doughnut',
        data: {
          labels: weightLabels,
          datasets: [{
            label: 'Bobot Kriteria',
            data: weightData,
            backgroundColor: [
              '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9900' // Tambahkan warna sesuai kebutuhan
            ],
            hoverBackgroundColor: [
              '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9900'
            ]
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            tooltip: {
              callbacks: {
                label: function(context) {
                  let label = context.label || '';
                  if (label) {
                    label += ': ';
                  }
                  if (context.parsed !== null) {
                    label += (context.parsed * 100).toFixed(1) + '%'; // Tampilkan dalam persentase
                  }
                  return label;
                }
              }
            }
          }
        }
      });
    }

    // --- Grafik 3: Tipe Kriteria (Doughnut Chart) ---
    if (criteriaTypes.benefit_count > 0 || criteriaTypes.cost_count > 0) {
      const typeLabels = ['Benefit', 'Cost'];
      const typeData = [criteriaTypes.benefit_count, criteriaTypes.cost_count];

      const typeCtx = document.getElementById('criteriaTypeChart').getContext('2d');
      new Chart(typeCtx, {
        type: 'doughnut',
        data: {
          labels: typeLabels,
          datasets: [{
            label: 'Jumlah Kriteria',
            data: typeData,
            backgroundColor: [
              'rgba(75, 192, 192, 0.6)', // Warna untuk Benefit
              'rgba(255, 99, 132, 0.6)' // Warna untuk Cost
            ],
            hoverBackgroundColor: [
              'rgba(75, 192, 192, 0.8)',
              'rgba(255, 99, 132, 0.8)'
            ]
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            tooltip: {
              callbacks: {
                label: function(context) {
                  let label = context.label || '';
                  if (label) {
                    label += ': ';
                  }
                  if (context.parsed !== null) {
                    label += context.parsed + ' Kriteria';
                  }
                  return label;
                }
              }
            }
          }
        }
      });
    }
  });
</script>