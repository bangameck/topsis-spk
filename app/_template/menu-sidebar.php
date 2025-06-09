<ul class="nav in side-menu">
  <li><a href="<?= base_url(); ?>"><i class="list-icon feather feather-command"></i> Dashboard</a>
  </li>
  <?php if ($_SESSION['level'] == 1) : ?>
    <li class="menu-item-has-children"><a href="javascript:void(0);"><i class="list-icon feather feather-layers"></i> <span class="hide-menu">Data Master</span></a>
      <ul class="list-unstyled sub-menu">
        <li><a href="<?= base_url(); ?>users">Users</a>
        </li>
        <li><a href="<?= base_url(); ?>criteria">Kriteria</a>
        </li>
        <li><a href="<?= base_url(); ?>alternative">Kriteria Alternatif</a>
        </li>
      </ul>
    </li>
  <?php endif ?>
  <?php if ($_SESSION['level'] == 2) : ?>
    <li><a href="<?= base_url(); ?>alternative/add"><i class="list-icon feather feather-edit"></i> Input Nilai</a>
    <?php endif ?>
    <li><a href="<?= base_url(); ?>topsis/results"><i class="list-icon feather feather-fast-forward"></i> Perhitungan Topsis</a>
      <?php if (!empty($_SESSION['username'])) : ?>
    <li><a onclick="confirmLogout()"><i class="list-icon feather feather-power"></i> Logout</a>
    <?php endif ?>
    </li>
</ul>