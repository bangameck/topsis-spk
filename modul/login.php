<?php
include '../_func/controlWeb.php';

$error_message = ""; // variabel untuk menampung pesan error

if (isset($_POST['login'])) {
  if (!check_csrf_token($_POST['csrf_token'] ?? '')) {
    $error_message = '<div class="alert alert-danger mt-3">Token CSRF tidak valid.</div>';
  } elseif (empty($_POST['username']) || empty($_POST['password'])) {
    $error_message = '<div class="alert alert-danger mt-3">Username dan Password wajib diisi.</div>';
  } else {
    $u = $db->real_escape_string(trim($_POST['username']));
    $p = $db->real_escape_string(trim($_POST['password']));
    $q = $db->query("SELECT * FROM users WHERE username='$u'");

    if ($q && $q->num_rows >= 1) {
      $r = $q->fetch_assoc();
      $ph = $r['password'];

      if (password_verify($p, $ph)) {
        $_SESSION['username'] = $r['username'];
        $_SESSION['password'] = $r['password'];
        $_SESSION['name']     = $r['name'];
        $_SESSION['level']    = $r['level'];
        $_SESSION['img']      = $r['img'];

        header("Location: " . base_url() . "home");
        exit;
      } else {
        $error_message = '<div class="alert alert-danger mt-3">Password salah.</div>';
      }
    } else {
      $error_message = '<div class="alert alert-danger mt-3">Username tidak ditemukan.</div>';
    }
  }
}

if (!empty($_SESSION['username'])) {
  header("Location: " . base_url() . "home");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
  <link rel="icon" type="image/png" sizes="16x16" href="assets/demo/favicon.png">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>Halaman Login </title>
  <!-- CSS -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:200,300,400,500,600|Roboto:400" rel="stylesheet" type="text/css">
  <link href="assets/vendors/material-icons/material-icons.css" rel="stylesheet" type="text/css">
  <link href="assets/vendors/mono-social-icons/monosocialiconsfont.css" rel="stylesheet" type="text/css">
  <link href="assets/vendors/feather-icons/feather.css" rel="stylesheet" type="text/css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.7.0/css/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
  <link href="assets/css/style.css" rel="stylesheet" type="text/css">
  <!-- Head Libs -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
</head>

<body class="body-bg-full profile-page">
  <div id="wrapper" class="wrapper">
    <div class="row container-min-full-height">
      <div class="col-lg-12 p-4 login-left">
        <div class="w-50">
          <h2 class="mb-4 text-center">Silahkan Login!</h2>
          <form class="text-center" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
            <div class="form-group">
              <label class="text-muted" for="username">Username</label>
              <input type="text" placeholder="elonmusk" class="form-control form-control-line" name="username" value="">
            </div>
            <div class="form-group">
              <label class="text-muted" for="password">Password</label>
              <input type="password" placeholder="password" class="form-control form-control-line" name="password" value="">
            </div>
            <!-- <div class="form-group no-gutters mb-5 text-center"><a href="page-forgot-pwd.html" id="to-recover" class="text-muted fw-700 text-uppercase heading-font-family fs-12">Forgot Password?</a>
            </div> -->
            <!-- /.form-group -->
            <div class="form-group mr-b-20">
              <button class="btn btn-block btn-rounded btn-md btn-color-scheme text-uppercase fw-600 ripple" type="submit" name="login">Sign In</button>
            </div>
          </form>
          <?php echo $error_message; ?>
          <!-- /form -->
          <!-- <button type="button" class="btn btn-block btn-rounded btn-outline-facebook ripple" title="Login with Facebook">Connect using <span class="fw-700">facebook</span>
          </button> -->
        </div>
        <!-- /.w-75 -->
      </div>
      <!-- /.login-right -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.wrapper -->
  <!-- Scripts -->
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="assets/js/material-design.js"></script>
</body>

</html>