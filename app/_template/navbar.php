<nav class="navbar">
  <!-- Logo Area -->
  <?php
  include 'logo.php';
  ?>
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
      <li class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle ripple" data-toggle="dropdown"><span class="avatar thumb-xs2"><img src="<?= base_url(); ?>assets/img/profile/<?= $img; ?>" class="rounded-circle" alt=""> <i class="feather feather-chevron-down list-icon"></i></span></a>
        <div
          class="dropdown-menu dropdown-left dropdown-card dropdown-card-profile animated flipInY">
          <div class="card">
            <ul class="list-unstyled card-body">
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