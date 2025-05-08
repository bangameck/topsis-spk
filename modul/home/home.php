<?php
?>
<!-- Page Title Area -->
<div class="row page-title clearfix">
  <div class="page-title-left">
    <h6 class="page-title-heading mr-0 mr-r-5">Default</h6>
    <p class="page-title-description mr-0 d-none d-md-inline-block">statistics, charts and events</p>
  </div>
  <!-- /.page-title-left -->
  <div class="page-title-right d-none d-sm-inline-flex">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">Default</li>
    </ol>
    <div class="d-none d-md-inline-flex justify-center align-items-center"><a href="javascript: void(0);" class="btn btn-color-scheme btn-sm fs-11 fw-400 mr-l-40 pd-lr-10 mr-l-0-rtl mr-r-40-rtl hidden-xs hidden-sm ripple" target="_blank">Buy Now</a>
    </div>
  </div>
  <!-- /.page-title-right -->
</div>

<!-- /.page-title -->
<!-- =================================== -->
<!-- Different data widgets ============ -->
<!-- =================================== -->
<div class="widget-list row">
  <div class="widget-holder widget-full-content widget-full-height col-md-6">
    <div class="widget-bg">
      <div class="widget-body">
        <div class="counter-gradient">
          <h3 class="fs-60 fw-600 mt-3 pt-1 h1 letter-spacing-minus"><span class="counter">6843</span></h3>
          <h5 class="mb-4 fw-500">New Registered Users</h5>
          <p class="text-muted">Number of all users who have registered
            <br>on your website last week
          </p>
        </div>
        <!-- /.widget-counter -->
        <div class="row columns-border-bw border-top">
          <div class="col-6 d-flex flex-column justify-content-center align-items-center pd-tb-30">
            <label class="d-flex flex-md-row flex-column align-items-center cursor-pointer">
              <input type="checkbox" checked="checked" class="js-switch" data-color="#8253eb" data-size="small"> <span class="text-muted mr-l-20 mr-l-0-rtl mr-r-20-rtl d-inline-block">User Registrations</span>
            </label>
          </div>
          <!-- /.col-6 -->
          <div class="col-6 d-flex flex-column justify-content-center align-items-center pd-tb-30">
            <label class="d-flex flex-md-row flex-column align-items-center cursor-pointer">
              <input type="checkbox" class="js-switch" data-color="#8253eb" data-size="small"> <span class="text-muted mr-l-20 mr-l-0-rtl mr-r-20-rtl d-inline-block">Total Sales</span>
            </label>
            <!-- /.col-6 -->
          </div>
          <!-- /.col-6 -->
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