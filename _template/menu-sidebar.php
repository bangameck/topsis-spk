<ul class="nav in side-menu">
  <li><a href="<?= base_url(); ?>"><i class="list-icon feather feather-command"></i> Dashboard</a>
  </li>
  <li class="menu-item-has-children"><a href="javascript:void(0);"><i class="list-icon feather feather-layers"></i> <span class="hide-menu">Data Master</span></a>
    <ul class="list-unstyled sub-menu">
      <li><a href="<?= base_url(); ?>users">Users</a>
      </li>
      <li><a href="<?= base_url(); ?>criteria">Kriteria</a>
      </li>
      <li><a href="page-profile.html">Kriteria Alternatif</a>
      </li>
    </ul>
  </li>
  <?php
  if (!empty($_SESSION['username'])) {
    echo '<li><a onclick="confirmLogout()"><i class="list-icon feather feather-power"></i> Logout</a>';
  }
  ?>

  </li>
</ul>