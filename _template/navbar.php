<nav class="navbar">
  <!-- Logo Area -->
  <?php include 'logo.php' ?>
  <!-- /.navbar-header -->
  <!-- Left Menu & Sidebar Toggle -->
  <ul class="nav navbar-nav">
    <li class="sidebar-toggle"><a href="javascript:void(0)" class="ripple"><i class="feather feather-menu list-icon fs-20"></i></a>
    </li>
  </ul>
  <!-- /.navbar-left -->
  <!-- Search Form -->
  <!-- <form class="navbar-search d-none d-sm-block" role="search"><i class="feather feather-search list-icon"></i>
    <input type="search" class="search-query" placeholder="Search anything..."> <a href="javascript:void(0);" class="remove-focus"><i class="feather feather-x"></i></a>
  </form> -->
  <!-- /.navbar-search -->
  <div class="spacer"></div>
  <!-- Button: Create New -->
  <?php
  if (!empty($_SESSION['username'])) {
  ?>
    <div class="btn-list dropdown d-none d-md-flex mr-4 mr-0-rtl ml-4-rtl"><a href="javascript:void(0);" class="btn btn-primary dropdown-toggle ripple" data-toggle="dropdown"><i class="feather feather-plus list-icon"></i> Create New</a>
      <div class="dropdown-menu dropdown-left animated flipInY">
        <span class="dropdown-header">Create new ...</span>
        <a class="dropdown-item" href="#">Users</a>
        <a class="dropdown-item" href="#">Kriteria</a>
        <a class="dropdown-item" href="#">
          <span class="d-flex align-items-end">
            <span class="flex-1">To-do Item</span>
            <span class="badge badge-pill bg-primary-contrast">7</span>
          </span>
        </a>
        <a
          class="dropdown-item" href="#"><span class="d-flex align-items-end"><span class="flex-1">Mail</span> <span class="badge badge-pill bg-color-scheme-contrast">23</span></span>
        </a>
      </div>
    </div>
  <?php
  } else {
  ?>
    <div class="btn-list dropdown d-none d-md-flex mr-4 mr-0-rtl ml-4-rtl"><a href="<?= base_url(); ?>login" class="btn btn-primary"> Login</a>
    </div>
  <?php
  }
  ?>

  <!-- /.btn-list -->
  <!-- User Image with Dropdown -->
  <?php
  if (empty($_SESSION['img'])) {
    $img = 'default.png';
  } else {
    $img = $_SESSION['img'];
  }

  if (!empty($_SESSION['username'])) {
  ?>
    <ul class="nav navbar-nav">
      <li class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle ripple" data-toggle="dropdown"><span class="avatar thumb-xs2"><img src="assets/img/<?= $img; ?>" class="rounded-circle" alt=""> <i class="feather feather-chevron-down list-icon"></i></span></a>
        <div
          class="dropdown-menu dropdown-left dropdown-card dropdown-card-profile animated flipInY">
          <div class="card">
            <header class="card-header d-flex mb-0"><a href="javascript:void(0);" class="col-md-4 text-center"><i class="feather feather-user-plus align-middle"></i> </a><a href="javascript:void(0);" class="col-md-4 text-center"><i class="feather feather-settings align-middle"></i> </a>
              <a
                href="javascript:void(0);" class="col-md-4 text-center"><i class="feather feather-power align-middle"></i>
              </a>
            </header>
            <ul class="list-unstyled card-body">
              <li><a href="#"><span><span class="align-middle">Manage Accounts</span></span></a>
              </li>
              <li><a href="#"><span><span class="align-middle">Change Password</span></span></a>
              </li>
              <li><a href="#"><span><span class="align-middle">Check Inbox</span></span></a>
              </li>
              <li><a onclick="confirmLogout()"><span><span class="align-middle">Sign Out</span></span></a>
              </li>
            </ul>
          </div>
        </div>
      </li>
    </ul>
  <?php
  }
  ?>

  <!-- /.navbar-right -->
</nav>