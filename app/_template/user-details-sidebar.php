<div class="side-user">
  <div class="col-sm-12 text-center p-0 clearfix">
    <?php
    if (empty($_SESSION['username'])) {
    ?>
      <div class="d-inline-block pos-relative mr-b-10">
        <!-- <figure class="thumb-sm mr-b-0 user--offline"> -->
        <!-- <button type="button" class="btn btn-pink btn-circle btn-lg ripple"><i class="material-icons list-icon">exit_to_app</i> -->
        <!-- <img src="assets/demo/users/user1.jpg" class="rounded-circle" alt=""> -->
        </figure><a href="<?= base_url(); ?>login" class="text-muted side-user-link"><i class="feather feather-settings list-icon"></i></a>
      </div>
      <!-- /.d-inline-block -->
      <div class="lh-14 mr-t-5"><a href="<?= base_url(); ?>login" class="hide-menu mt-3 mb-0 side-user-heading fw-500">Klik untuk Login</a>
        <br><small class="hide-menu">TOPSIS App</small>
      </div>
    <?php } else {
      if (empty($_SESSION['img'])) {
        $img = 'default.png';
      } else {
        $img = $_SESSION['img'];
      }
    ?>

      <div class="d-inline-block pos-relative mr-b-10">
        <figure class="avatar thumb-sm2 mr-b-0 user--online">
          <img src="<?= base_url(); ?>assets/img/profile/<?= $img; ?>" class="rounded-circle" alt="">
        </figure><a href="page-profile.html" class="text-muted side-user-link"><i class="feather feather-settings list-icon"></i></a>
      </div>
      <!-- /.d-inline-block -->
      <div class="lh-14 mr-t-5"><a href="#" class="hide-menu mt-3 mb-0 side-user-heading fw-500"><?= $_SESSION['name']; ?></a>
        <br><small class="hide-menu">TOPSIS App</small>
      </div>
    <?php } ?>
  </div>
  <!-- /.col-sm-12 -->
</div>