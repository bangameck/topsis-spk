<!-- /**
 * @author [RadevankaProject]
 * @email radevankaproject@mail.com]
 * @create date 2025-05-12 04:47:54
 * @modify date 2025-05-12 04:47:54
 * @desc [description]
 */ -->

<!-- Page Title Area -->
<title>Dashboard</title>
<div class="row page-title clearfix">
  <div class="page-title-left">
    <h6 class="page-title-heading mr-0 mr-r-5">Dashboard</h6>
    <p class="page-title-description mr-0 d-none d-md-inline-block">statistics, charts and events</p>
  </div>
  <!-- /.page-title-left -->
  <div class="page-title-right d-none d-sm-inline-flex">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Home</li>
    </ol>
  </div>
  <!-- /.page-title-right -->
</div>

<!-- /.page-title -->
<!-- =================================== -->
<!-- Different data widgets ============ -->
<!-- =================================== -->
<div class="widget-list row">
  <div class="widget-holder widget-full-height widget-flex col-md-6">
    <div class="widget-bg">
      <div class="widget-heading">
        <h4 class="widget-title"><span class="color-color-scheme fw-600">
            <?php
            $c_count = $db->query("SELECT * FROM criteria")->num_rows;
            echo $c_count;
            ?>
          </span> <small class="h5 ml-1 my-0 fw-500">Kriteria</small></h4>

        <!-- /.widget-graph-info -->
      </div>
      <!-- /.widget-heading -->
      <div class="widget-body">
        <div class="mr-t-10 flex-1">
          <div class="h-100" style="max-height: 270px">
            <div class="widget-body clearfix">
              <table class="table table-striped table-responsive" data-toggle="datatables" data-plugin-options='{"searching": true}'>
                <thead class="thead-dark">
                  <tr>
                    <th>#</th>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Jenis</th>
                    <th>Value</th>
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
      <!-- /.widget-body -->
    </div>
    <!-- /.widget-bg -->
  </div>
  <!-- /.widget-holder -->
  <div class="widget-holder widget-full-height widget-flex col-md-6">
    <div class="widget-bg">
      <div class="widget-heading">
        <h4 class="widget-title"><span class="color-color-scheme fw-600">876</span> <small class="h5 ml-1 my-0 fw-500">New Users</small></h4>
        <div class="widget-graph-info"><i class="feather feather-chevron-up arrow-icon color-success"></i> <span class="color-success ml-2">+34%</span> <span class="text-muted ml-1">more than last week</span>
        </div>
        <!-- /.widget-graph-info -->
      </div>
      <!-- /.widget-heading -->
      <div class="widget-body">
        <div class="mr-t-10 flex-1">
          <div class="h-100" style="max-height: 270px">
            <canvas id="chartJsNewUsers" style="height:100%"></canvas>
          </div>
        </div>
      </div>
      <!-- /.widget-body -->
    </div>
    <!-- /.widget-bg -->
  </div>
  <!-- /.widget-holder -->
  <div class="widget-holder widget-sm widget-border-radius col-md-3">
    <div class="widget-bg">
      <div class="widget-heading bg-purple"><span class="widget-title my-0 color-white fs-12 fw-600">AVG Conversion Time</span> <i class="widget-heading-icon feather feather-box"></i>
      </div>
      <!-- /.widget-heading -->
      <div class="widget-body">
        <div class="counter-w-info">
          <div class="counter-title color-color-scheme"><span class="counter">2.5</span>hrs</div>
          <!-- /.counter-title -->
          <div class="counter-info"><span class="badge bg-success-contrast"><i class="feather feather-arrow-up"></i> 23% increase </span>in conversion</div>
          <!-- /.counter-info -->
        </div>
        <!-- /.counter-w-info -->
      </div>
      <!-- /.widget-body -->
    </div>
    <!-- /.widget-bg -->
  </div>
  <!-- /.widget-holder -->
  <div class="widget-holder widget-sm widget-border-radius col-md-3">
    <div class="widget-bg">
      <div class="widget-heading bg-purple"><span class="widget-title my-0 color-white fs-12 fw-600">Daily Earnings</span> <i class="widget-heading-icon feather feather-briefcase"></i>
      </div>
      <!-- /.widget-heading -->
      <div class="widget-body">
        <div class="counter-w-info">
          <div class="counter-title color-purple">&dollar;<span class="counter">846</span>
          </div>
          <!-- /.counter-title -->
          <div class="counter-info"><span class="badge bg-danger-contrast"><i class="feather feather-arrow-down"></i> 6% decrease </span>in earnings</div>
          <!-- /.counter-info -->
        </div>
        <!-- /.counter-w-info -->
      </div>
      <!-- /.widget-body -->
    </div>
    <!-- /.widget-bg -->
  </div>
  <!-- /.widget-holder -->
  <div class="widget-holder widget-sm widget-border-radius col-md-3">
    <div class="widget-bg">
      <div class="widget-heading"><span class="widget-title my-0 fs-12 fw-600">Completed Tasks</span> <i class="widget-heading-icon feather feather-anchor"></i>
      </div>
      <!-- /.widget-heading -->
      <div class="widget-body">
        <div class="counter-w-info">
          <div class="counter-title">
            <div class="d-flex justify-content-center align-items-end">
              <div data-toggle="circle-progress" data-start-angle="30" data-thickness="6" data-size="40" data-value="0.58" data-line-cap="round" data-empty-fill="#E2E2E2" data-fill='{"gradient": ["#40E4C2", "#0087FF"], "gradientAngle": -90}'></div><span class="counter ml-3">432</span>
            </div>
            <!-- /.d-flex -->
          </div>
          <!-- /.counter-title -->
          <div class="counter-info"><span class="badge bg-success-contrast"><i class="feather feather-arrow-up"></i> 5% increase</span>
          </div>
          <!-- /.counter-info -->
        </div>
        <!-- /.counter-w-info -->
      </div>
      <!-- /.widget-body -->
    </div>
    <!-- /.widget-bg -->
  </div>
  <!-- /.widget-holder -->
  <div class="widget-holder widget-sm widget-border-radius col-md-3">
    <div class="widget-bg">
      <div class="widget-heading"><span class="widget-title my-0 fs-12 fw-600">Advertising Credits</span> <i class="widget-heading-icon feather feather-zap"></i>
      </div>
      <!-- /.widget-heading -->
      <div class="widget-body">
        <div class="counter-w-info">
          <div class="counter-title">
            <div class="d-flex justify-content-center align-items-center"><span data-toggle="sparklines" sparkheight="25" sparktype="bar" sparkchartrangemin="0" sparkbarspacing="3" sparkbarcolor="#947AE8" sparkbarcolor="red"><!-- 2,4,5,3,2,3,5 --> </span><span class="align-bottom ml-2"><span class="counter">670</span></span>
            </div>
            <!-- /.d-flex -->
          </div>
          <!-- /.counter-title -->
          <div class="counter-info"><span class="badge bg-success-contrast"><i class="feather feather-arrow-up"></i> 5% increase </span>in advertising</div>
          <!-- /.counter-info -->
        </div>
        <!-- /.counter-w-info -->
      </div>
      <!-- /.widget-body -->
    </div>
    <!-- /.widget-bg -->
  </div>
  <!-- /.widget-holder -->
</div>
<!-- /.widget-list -->
<hr>