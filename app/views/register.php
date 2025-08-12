<?php
    include_once __DIR__ . '/../../config/controlWeb.php';

    // Proses registrasi
    if (isset($_POST['register'])) {
        if (! check_csrf_token($_POST['csrf_token'] ?? '')) {
            toastNotif('error', 'Token CSRF tidak valid.');
        } elseif (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['nama']) || empty($_POST['nik']) || empty($_POST['alamat']) || empty($_POST['tempat_lahir']) || empty($_POST['tanggal_lahir']) || empty($_POST['jenis_kelamin'])) {
            toastNotif('error', 'Semua field wajib diisi.');
        } elseif (strlen($_POST['nik']) !== 16 || ! is_numeric($_POST['nik'])) {
            toastNotif('error', 'NIK harus 16 digit angka.');
        } elseif (strlen($_POST['password']) < 6) {
            toastNotif('error', 'Password minimal 6 karakter.');
        } else {
            $username      = $db->real_escape_string(trim($_POST['username']));
            $password      = $db->real_escape_string(trim($_POST['password']));
            $nama          = $db->real_escape_string(trim($_POST['nama']));
            $nik           = $db->real_escape_string(trim($_POST['nik']));
            $alamat        = $db->real_escape_string(trim($_POST['alamat']));
            $tempat_lahir  = $db->real_escape_string(trim($_POST['tempat_lahir']));
            $tanggal_lahir = $db->real_escape_string(trim($_POST['tanggal_lahir']));
            $jenis_kelamin = $db->real_escape_string(trim($_POST['jenis_kelamin']));
            $level         = '2'; // Default level untuk masyarakat

            // Cek apakah username sudah ada
            $check_user = $db->query("SELECT username FROM users WHERE username='$username'");

            // Cek apakah NIK sudah ada
            $check_nik = $db->query("SELECT nik FROM masyarakat WHERE nik='$nik'");

            if ($check_user && $check_user->num_rows > 0) {
                toastNotif('error', 'Username sudah digunakan!');
            } elseif ($check_nik && $check_nik->num_rows > 0) {
                toastNotif('error', 'NIK sudah terdaftar!');
            } else {
                $db->begin_transaction();
                try {
                    // Handle upload foto profil
                    $profile_pic = 'default.png';
                    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
                        $upload_dir     = __DIR__ . '/../../assets/img/profile/';
                        $file_extension = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
                        $profile_pic    = uniqid() . '.' . $file_extension;

                        if (! move_uploaded_file($_FILES['profile_pic']['tmp_name'], $upload_dir . $profile_pic)) {
                            $profile_pic = 'default.png';
                        }
                    }

                    // Handle upload foto KTP
                    $ktp_pic = '';
                    if (isset($_FILES['ktp_pic']) && $_FILES['ktp_pic']['error'] == 0) {
                        $upload_dir_ktp = __DIR__ . '/../../assets/img/ktp/';
                        if (! is_dir($upload_dir_ktp)) {
                            mkdir($upload_dir_ktp, 0755, true);
                        }
                        $file_extension_ktp = pathinfo($_FILES['ktp_pic']['name'], PATHINFO_EXTENSION);
                        $ktp_pic            = 'ktp_' . uniqid() . '.' . $file_extension_ktp;

                        if (! move_uploaded_file($_FILES['ktp_pic']['tmp_name'], $upload_dir_ktp . $ktp_pic)) {
                            $ktp_pic = '';
                        }
                    }

                    // Hash password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Insert user baru ke tabel users
                    $insert_user_query = "INSERT INTO users (username, password, name, level, img, created_at, updated_at) VALUES ('$username', '$hashed_password', '$nama', '$level', '$profile_pic', NOW(), NULL)";

                    if (! $db->query($insert_user_query)) {
                        throw new Exception("Gagal menyimpan data akun: " . $db->error);
                    }

                    // Ambil user_id yang baru saja dimasukkan
                    $user_id = $db->insert_id;

                    // Insert data ke tabel masyarakat
                    $insert_masyarakat_query = "INSERT INTO masyarakat (user_id, nik, alamat, tempat_lahir, tanggal_lahir, jenis_kelamin, ktp_img, created_at, updated_at) VALUES ('$user_id', '$nik', '$alamat', '$tempat_lahir', '$tanggal_lahir', '$jenis_kelamin', '$ktp_pic', NOW(), NULL)";

                    if (! $db->query($insert_masyarakat_query)) {
                        throw new Exception("Gagal menyimpan data pribadi: " . $db->error);
                    }

                    // Jika semua query berhasil, commit transaksi
                    $db->commit();

                    toastNotif('success', 'Registrasi berhasil! Silahkan login.');
                    header("Location: " . base_url() . "login");
                    exit;

                } catch (Exception $e) {
                    // Jika terjadi error, rollback transaksi
                    $db->rollback();
                    toastNotif('error', 'Terjadi kesalahan saat registrasi: ' . $e->getMessage());
                }
            }
        }
    }

    // Redirect jika sudah login
    if (! empty($_SESSION['username'])) {
        header("Location: " . base_url() . "home");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once __DIR__ . '/../_template/head.php'; ?>
  <title>Halaman Registrasi - TOPSIS App</title>
</head>

<body class="body-bg-full profile-page">
  <div id="wrapper" class="wrapper">
    <div class="row container-min-full-height">
      <div class="col-lg-12 p-4 login-left">
        <div class="w-75 mx-auto">
          <div class="text-center mb-4">
            <img class="rounded-circle" width="100px" src="<?php echo base_url() ?>assets/img/koperasi.png" alt="Logo of Koperasi Indonesia featuring a green banyan tree with roots and branches, a yellow gear and chain border, a balance scale with a star above, and a yellow ribbon at the bottom with the text KOPERASI INDONESIA. The design conveys unity, cooperation, and stability in a formal and official tone.">
            <h2 class="mb-2 mt-3">KOPERASI BUDI KARYA JAYA</h2>
            <h5 class="mb-2 mt-3">Daftar Akun Baru</h5>
            <p class="text-muted">Silahkan lengkapi data di bawah ini</p>
          </div>

          <form method="POST" action="" enctype="multipart/form-data" id="registerForm">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">
            <input type="hidden" name="register" value="1">

            <!-- Data Akun -->
            <div class="card mb-4">
              <div class="card-header">
                <h5 class="mb-0"><i class="material-icons mr-2">account_circle</i>Data Akun</h5>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label class="text-muted" for="username">Username</label>
                  <input type="text" class="form-control form-control-line" name="username" id="username" placeholder="Masukkan username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                </div>

                <div class="form-group">
                  <label class="text-muted" for="password">Password</label>
                  <input type="password" class="form-control form-control-line" name="password" id="password" placeholder="Masukkan password (min. 6 karakter)" required>
                </div>

                <div class="form-group">
                  <label class="text-muted" for="profile_pic">Upload Foto Profil (Opsional)</label>
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="profile_pic" name="profile_pic" accept="image/*">
                    <label class="custom-file-label" for="profile_pic">Pilih file...</label>
                  </div>
                  <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                </div>
              </div>
            </div>

            <!-- Data Pribadi -->
            <div class="card mb-4">
              <div class="card-header">
                <h5 class="mb-0"><i class="material-icons mr-2">person</i>Data Pribadi</h5>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label class="text-muted" for="nik">NIK (Nomor Induk Kependudukan)</label>
                  <input type="text" class="form-control form-control-line" name="nik" id="nik" placeholder="Masukkan 16 digit NIK" maxlength="16" value="<?php echo isset($_POST['nik']) ? htmlspecialchars($_POST['nik']) : ''; ?>" required>
                  <div class="invalid-feedback"></div>
                </div>

                <div class="form-group">
                  <label class="text-muted" for="nama">Nama Lengkap</label>
                  <input type="text" class="form-control form-control-line" name="nama" id="nama" placeholder="Masukkan nama lengkap" value="<?php echo isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>" required>
                </div>

                <div class="form-group">
                  <label class="text-muted" for="alamat">Alamat</label>
                  <textarea class="form-control form-control-line" name="alamat" id="alamat" rows="3" placeholder="Masukkan alamat lengkap" required><?php echo isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : ''; ?></textarea>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="text-muted" for="tempat_lahir">Tempat Lahir</label>
                      <input type="text" class="form-control form-control-line" name="tempat_lahir" id="tempat_lahir" placeholder="Masukkan tempat lahir" value="<?php echo isset($_POST['tempat_lahir']) ? htmlspecialchars($_POST['tempat_lahir']) : ''; ?>" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="text-muted" for="tanggal_lahir">Tanggal Lahir</label>
                      <input type="date" class="form-control form-control-line" name="tanggal_lahir" id="tanggal_lahir" value="<?php echo isset($_POST['tanggal_lahir']) ? htmlspecialchars($_POST['tanggal_lahir']) : ''; ?>" required>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label class="text-muted">Jenis Kelamin</label>
                  <div class="mt-2">
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="jenis_kelamin" id="laki_laki" value="Laki-laki"                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         <?php echo(isset($_POST['jenis_kelamin']) && $_POST['jenis_kelamin'] == 'Laki-laki') ? 'checked' : ''; ?> required>
                      <label class="form-check-label" for="laki_laki">Laki-laki</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="jenis_kelamin" id="perempuan" value="Perempuan"                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         <?php echo(isset($_POST['jenis_kelamin']) && $_POST['jenis_kelamin'] == 'Perempuan') ? 'checked' : ''; ?> required>
                      <label class="form-check-label" for="perempuan">Perempuan</label>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label class="text-muted" for="ktp_pic">Upload Foto KTP (Opsional)</label>
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="ktp_pic" name="ktp_pic" accept="image/*">
                    <label class="custom-file-label" for="ktp_pic">Pilih file...</label>
                  </div>
                  <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                </div>
              </div>
            </div>

            <div class="form-group mr-b-20">
              <button class="btn btn-block btn-rounded btn-md btn-color-scheme text-uppercase fw-600 ripple" type="submit">
                <i class="material-icons mr-2">person_add</i>Daftar
              </button>
            </div>
          </form>

          <div class="text-center mb-3">
            <p class="text-muted">Sudah punya akun? <a href="<?php echo base_url(); ?>login" class="text-primary fw-600">Login di sini</a></p>
          </div>

          <a href="<?php echo base_url(); ?>home">
            <button type="button" class="btn btn-block btn-rounded btn-outline-facebook ripple">
              <i class="material-icons mr-2">home</i>Kembali ke <span class="fw-700">Dashboard</span>
            </button>
          </a>
        </div>
      </div>
    </div>
  </div>

  <?php include_once __DIR__ . '/../_template/js.php'; ?>

  <script>
    $(document).ready(function() {
      // Konfigurasi Toastr
      toastr.options = {
        "closeButton": true, "progressBar": true, "positionClass": "toast-top-right", "timeOut": "5000"
      };

      // Tampilkan notifikasi dari session
      <?php if (isset($_SESSION['toast_notifications'])): ?>
<?php foreach ($_SESSION['toast_notifications'] as $notification): ?>
          toastr.<?php echo $notification['type'] ?>('<?php echo addslashes($notification['message']) ?>');
        <?php endforeach; ?>
<?php unset($_SESSION['toast_notifications']); ?>
<?php endif; ?>

      // Update label file input
      $('.custom-file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass('selected').html(fileName);

        if (this.files[0] && this.files[0].size > 2097152) { // 2MB
          toastr.error('Ukuran file terlalu besar! Maksimal 2MB');
          $(this).val('');
          $(this).siblings('.custom-file-label').removeClass('selected').html('Pilih file...');
        }
      });

      // Validasi NIK
      $('#nik').on('input', function() {
        this.value = this.value.replace(/\D/g, '');
        if (this.value.length > 0 && this.value.length !== 16) {
          $(this).addClass('is-invalid').siblings('.invalid-feedback').text('NIK harus 16 digit angka');
        } else {
          $(this).removeClass('is-invalid').siblings('.invalid-feedback').text('');
        }
      });

      // Validasi password
      $('#password').on('input', function() {
        if (this.value.length > 0 && this.value.length < 6) {
          $(this).addClass('is-invalid').siblings('.invalid-feedback').text('Password minimal 6 karakter');
        } else {
          $(this).removeClass('is-invalid').siblings('.invalid-feedback').text('');
        }
      });

      // Validasi username
      $('#username').on('input', function() {
        if (this.value.includes(' ')) {
          $(this).addClass('is-invalid').siblings('.invalid-feedback').text('Username tidak boleh mengandung spasi');
        } else {
          $(this).removeClass('is-invalid').siblings('.invalid-feedback').text('');
        }
      });

      // Validasi tanggal lahir
      $('#tanggal_lahir').on('change', function() {
        if (new Date(this.value) > new Date()) {
          $(this).addClass('is-invalid').siblings('.invalid-feedback').text('Tanggal lahir tidak bisa di masa depan');
        } else {
          $(this).removeClass('is-invalid').siblings('.invalid-feedback').text('');
        }
      });

      // Validasi form sebelum submit
      $('#registerForm').on('submit', function(e) {
        let isValid = true;
        let errorMessages = [];

        // Clear previous invalid states
        $('.is-invalid').removeClass('is-invalid');

        if ($('#nik').val().length !== 16) {
          isValid = false;
          $('#nik').addClass('is-invalid').siblings('.invalid-feedback').text('NIK harus 16 digit angka.');
        }

        if ($('#password').val().length < 6) {
          isValid = false;
          $('#password').addClass('is-invalid').siblings('.invalid-feedback').text('Password minimal 6 karakter.');
        }

        if ($('#username').val().includes(' ')) {
          isValid = false;
          $('#username').addClass('is-invalid').siblings('.invalid-feedback').text('Username tidak boleh mengandung spasi.');
        }

        if (new Date($('#tanggal_lahir').val()) > new Date()) {
          isValid = false;
          $('#tanggal_lahir').addClass('is-invalid').siblings('.invalid-feedback').text('Tanggal lahir tidak bisa di masa depan.');
        }

        // Cek radio button jenis kelamin
        if (!$('input[name="jenis_kelamin"]:checked').val()) {
            isValid = false;
            errorMessages.push('Silakan pilih jenis kelamin.');
        }


        if (!isValid) {
          e.preventDefault();
          toastr.error('Harap perbaiki error pada form sebelum mendaftar.');
          if(errorMessages.length > 0) {
              toastr.error(errorMessages.join('<br>'));
          }
        } else {
          $(this).find('button[type="submit"]').html('<i class="material-icons mr-2">hourglass_empty</i>Mendaftar...').prop('disabled', true);
        }
      });

      // Auto-formatting
      $('#nama, #tempat_lahir').on('blur', function() {
        let str = $(this).val();
        let formatted = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
          return letter.toUpperCase();
        });
        $(this).val(formatted);
      });
    });
  </script>
</body>

</html>

