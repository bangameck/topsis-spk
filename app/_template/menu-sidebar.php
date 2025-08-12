<ul class="nav in side-menu">
  <li>
    <a href="<?php echo base_url(); ?>">
      <i class="list-icon feather feather-command"></i> Dashboard
    </a>
  </li>
  <?php if (isset($_SESSION['level']) && $_SESSION['level'] == 1): ?>
    <li class="menu-item-has-children">
      <a href="javascript:void(0);">
        <i class="list-icon feather feather-layers"></i>
        <span class="hide-menu">Data Master</span>
      </a>
      <ul class="list-unstyled sub-menu">
        <li>
          <a href="<?php echo base_url(); ?>users">Anggota</a>
        </li>
        <li>
          <a href="<?php echo base_url(); ?>criteria">Kriteria</a>
        </li>
        <li>
          <a href="<?php echo base_url(); ?>alternative">Kriteria Alternatif</a>
        </li>
      </ul>
    </li>
  <?php endif; ?>

  <?php if (isset($_SESSION['level']) && $_SESSION['level'] == 2): ?>
    <li>
      <a href="<?php echo base_url(); ?>alternative/add">
        <i class="list-icon feather feather-edit"></i> Input
      </a>
    </li>
  <?php endif; ?>

  <li>
    <a href="<?php echo base_url(); ?>topsis/results">
      <i class="list-icon feather feather-fast-forward"></i> Perhitungan Topsis
    </a>
  </li>
  <?php if (isset($_SESSION['username']) && ! empty($_SESSION['username'])): ?>
    <li>
      <a href="<?php echo base_url(); ?>users/editProfile">
        <i class="list-icon feather feather-settings"></i> Edit Profil
      </a>
    </li>
    <li>
      <a onclick="confirmLogout()">
        <i class="list-icon feather feather-power"></i> Logout
      </a>
    </li>
  <?php endif; ?>
</ul>