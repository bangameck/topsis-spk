<!-- /**
 * @author [RadevankaProject]
 * @email radevankaproject@mail.com]
 * @create date 2025-05-07 18:32:14
 * @modify date 2025-05-07 18:32:14
 * @desc [description]
 */ -->

<?php include '_func/controlWeb.php' ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php template('head'); ?>
</head>

<body class="header-dark sidebar-light sidebar-expand">
  <div id="wrapper" class="wrapper">
    <!-- HEADER & TOP NAVIGATION -->
    <?php template('navbar'); ?>
    <!-- /.navbar -->
    <div class="content-wrapper">
      <!-- SIDEBAR -->
      <aside class="site-sidebar scrollbar-enabled" data-suppress-scroll-x="true">
        <!-- User Details -->
        <?php template('user-details') ?>
        <!-- /.side-user -->
        <!-- Sidebar Menu -->
        <nav class="sidebar-nav">
          <?php template('menu') ?>
          <!-- /.side-menu -->
        </nav>
        <!-- /.sidebar-nav -->

        <!-- /.nav-contact-info -->
      </aside>
      <!-- /.site-sidebar -->
      <main class="main-wrapper clearfix">
        <!-- ContentWeb -->
        <?php modul($_GET['m']) ?>
        <!-- endContentWeb -->
      </main>
    </div>
    <!-- /.content-wrapper -->
    <!-- FOOTER -->
    <?php template('footer') ?>
  </div>
  <!--/ #wrapper -->
  <!-- Scripts -->
  <?php template('js') ?>
</body>

</html>