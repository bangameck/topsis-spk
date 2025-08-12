
<?php
    // File: app/views/users/editProfile.php

    // 1. Ambil ID dari session user yang login
    $id = $_SESSION['user_id'];

    // 2. Siapkan query untuk menggabungkan data dari tabel 'users' dan 'masyarakat'
    $sql = "SELECT u.*, m.nik, m.alamat, m.tempat_lahir, m.tanggal_lahir, m.jenis_kelamin, m.ktp_img
        FROM users u
        LEFT JOIN masyarakat m ON u.user_id = m.user_id
        WHERE u.user_id = ?";

    // 3. Eksekusi query dengan prepared statement untuk keamanan
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data   = $result->fetch_assoc();
    $stmt->close();

    // Jika data tidak ditemukan, redirect ke halaman users
    if (! $data) {
        toastNotif('error', 'Data pengguna tidak ditemukan.');
        header("Location: " . base_url('users'));
        exit();
    }

    // 4. Proses update data jika form disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (! check_csrf_token($_POST['csrf_token'] ?? '')) {
            toastNotif('error', 'Token CSRF tidak valid.');
        } else {
            // Sanitasi input
            $username      = $db->real_escape_string(trim($_POST['username']));
            $nama          = $db->real_escape_string(trim($_POST['nama']));
            $password      = trim($_POST['password']);
            $nik           = $db->real_escape_string(trim($_POST['nik']));
            $alamat        = $db->real_escape_string(trim($_POST['alamat']));
            $tempat_lahir  = $db->real_escape_string(trim($_POST['tempat_lahir']));
            $tanggal_lahir = $db->real_escape_string(trim($_POST['tanggal_lahir']));
            $jenis_kelamin = $db->real_escape_string(trim($_POST['jenis_kelamin']));

            // Level hanya bisa diubah oleh admin
            $level = (isset($_SESSION['level']) && $_SESSION['level'] == '1' && isset($_POST['level']))
            ? $db->real_escape_string($_POST['level']) : $data['level'];

            $db->begin_transaction();
            try {
                // Update tabel users
                $sql_user    = "UPDATE users SET username = ?, name = ?, level = ?";
                $params_user = [$username, $nama, $level];
                $types_user  = "sss";

                // Jika password diisi, update password
                if (! empty($password)) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $sql_user .= ", password = ?";
                    $params_user[] = $hashed_password;
                    $types_user .= "s";
                }

                // Handle upload gambar profil
                if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
                    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                    if (in_array($_FILES['img']['type'], $allowed_types) && $_FILES['img']['size'] <= 2097152) {
                        $img_name    = 'profile_' . uniqid() . '.' . pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
                        $upload_path = __DIR__ . '/../../../assets/img/profile/';

                        if (! is_dir($upload_path)) {
                            mkdir($upload_path, 0755, true);
                        }

                        if (move_uploaded_file($_FILES['img']['tmp_name'], $upload_path . $img_name)) {
                            $sql_user .= ", img = ?";
                            $params_user[] = $img_name;
                            $types_user .= "s";

                            // Hapus gambar lama jika ada
                            if (! empty($data['img']) && $data['img'] != 'default.png' && file_exists($upload_path . $data['img'])) {
                                unlink($upload_path . $data['img']);
                            }
                        }
                    }
                }

                $sql_user .= " WHERE user_id = ?";
                $params_user[] = $id;
                $types_user .= "i";

                $stmt_user = $db->prepare($sql_user);
                $stmt_user->bind_param($types_user, ...$params_user);
                if (! $stmt_user->execute()) {
                    throw new Exception("Gagal memperbarui data akun: " . $stmt_user->error);
                }
                $stmt_user->close();

                // Update atau insert tabel masyarakat
                $check_masyarakat = $db->query("SELECT user_id FROM masyarakat WHERE user_id = $id");
                if ($check_masyarakat->num_rows > 0) {
                    // Update data masyarakat
                    $sql_masyarakat    = "UPDATE masyarakat SET nik = ?, alamat = ?, tempat_lahir = ?, tanggal_lahir = ?, jenis_kelamin = ?";
                    $params_masyarakat = [$nik, $alamat, $tempat_lahir, $tanggal_lahir, $jenis_kelamin];
                    $types_masyarakat  = "sssss";

                    // Handle upload gambar KTP
                    if (isset($_FILES['ktp_img']) && $_FILES['ktp_img']['error'] == 0) {
                        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                        if (in_array($_FILES['ktp_img']['type'], $allowed_types) && $_FILES['ktp_img']['size'] <= 2097152) {
                            $ktp_name        = 'ktp_' . uniqid() . '.' . pathinfo($_FILES['ktp_img']['name'], PATHINFO_EXTENSION);
                            $upload_path_ktp = __DIR__ . '/../../../assets/img/ktp/';

                            if (! is_dir($upload_path_ktp)) {
                                mkdir($upload_path_ktp, 0755, true);
                            }

                            if (move_uploaded_file($_FILES['ktp_img']['tmp_name'], $upload_path_ktp . $ktp_name)) {
                                $sql_masyarakat .= ", ktp_img = ?";
                                $params_masyarakat[] = $ktp_name;
                                $types_masyarakat .= "s";

                                // Hapus gambar KTP lama jika ada
                                if (! empty($data['ktp_img']) && file_exists($upload_path_ktp . $data['ktp_img'])) {
                                    unlink($upload_path_ktp . $data['ktp_img']);
                                }
                            }
                        }
                    }

                    $sql_masyarakat .= " WHERE user_id = ?";
                    $params_masyarakat[] = $id;
                    $types_masyarakat .= "i";
                } else {
                    // Insert data masyarakat baru
                    $ktp_name = '';
                    if (isset($_FILES['ktp_img']) && $_FILES['ktp_img']['error'] == 0) {
                        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                        if (in_array($_FILES['ktp_img']['type'], $allowed_types) && $_FILES['ktp_img']['size'] <= 2097152) {
                            $ktp_name        = 'ktp_' . uniqid() . '.' . pathinfo($_FILES['ktp_img']['name'], PATHINFO_EXTENSION);
                            $upload_path_ktp = __DIR__ . '/../../../assets/img/ktp/';

                            if (! is_dir($upload_path_ktp)) {
                                mkdir($upload_path_ktp, 0755, true);
                            }

                            if (! move_uploaded_file($_FILES['ktp_img']['tmp_name'], $upload_path_ktp . $ktp_name)) {
                                $ktp_name = '';
                            }
                        }
                    }

                    $sql_masyarakat    = "INSERT INTO masyarakat (user_id, nik, alamat, tempat_lahir, tanggal_lahir, jenis_kelamin, ktp_img) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $params_masyarakat = [$id, $nik, $alamat, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $ktp_name];
                    $types_masyarakat  = "issssss";
                }

                $stmt_masyarakat = $db->prepare($sql_masyarakat);
                $stmt_masyarakat->bind_param($types_masyarakat, ...$params_masyarakat);
                if (! $stmt_masyarakat->execute()) {
                    throw new Exception("Gagal memperbarui data pribadi: " . $stmt_masyarakat->error);
                }
                $stmt_masyarakat->close();

                $db->commit();
                toastNotif('success', 'Profil berhasil diperbarui.');

                // Refresh data setelah update
                // header("Location: " . base_url() . "users/editProfile");
                // exit();

            } catch (Exception $e) {
                $db->rollback();
                toastNotif('error', $e->getMessage());
            }
        }
    }
?>
<title>Edit Profil Pengguna</title>
<div class="row page-title clearfix">
  <div class="page-title-left">
    <h6 class="page-title-heading mr-0 mr-r-5">Profile</h6>
    <p class="page-title-description mr-0 d-none d-md-inline-block">Edit Profil Pengguna</p>
  </div>
  <div class="page-title-right d-none d-sm-inline-flex">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item active">Edit Profil</li>
    </ol>
  </div>
</div>

<div class="widget-list">
  <div class="row">
    <div class="col-md-12 widget-holder">
      <div class="widget-bg">
        <div class="widget-body clearfix">
          <h5 class="box-title">Edit Data Pengguna</h5>
          <hr>
          <form id="editProfileForm" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generate_csrf_token()); ?>">

            <!-- DATA AKUN (dari tabel users) -->
            <fieldset>
              <legend class="text-primary">Data Akun</legend>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($data['username']); ?>" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika tidak diubah">
                    <small class="form-text text-muted">Minimal 6 karakter jika ingin mengubah password</small>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($data['name']); ?>" required>
                  </div>
                </div>
                <?php if (isset($_SESSION['level']) && $_SESSION['level'] == '1'): ?>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="level">Level</label>
                    <select class="form-control" id="level" name="level" required>
                      <option value="1"                                                                                                                      <?php echo($data['level'] == '1') ? 'selected' : ''; ?>>Admin</option>
                      <option value="2"                                                                                                                      <?php echo($data['level'] == '2') ? 'selected' : ''; ?>>Masyarakat</option>
                    </select>
                  </div>
                </div>
                <?php endif; ?>
              </div>
            </fieldset>

            <hr class="my-4">

            <!-- DATA PRIBADI (dari tabel masyarakat) -->
            <fieldset>
              <legend class="text-primary">Data Pribadi</legend>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="nik">NIK</label>
                    <input type="text" class="form-control" id="nik" name="nik" value="<?php echo htmlspecialchars($data['nik'] ?? ''); ?>" pattern="\d{16}" title="NIK harus 16 digit angka" maxlength="16">
                    <small class="form-text text-muted">16 digit angka</small>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap"><?php echo htmlspecialchars($data['alamat'] ?? ''); ?></textarea>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="tempat_lahir">Tempat Lahir</label>
                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="<?php echo htmlspecialchars($data['tempat_lahir'] ?? ''); ?>" placeholder="Masukkan tempat lahir">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="tanggal_lahir">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo htmlspecialchars($data['tanggal_lahir'] ?? ''); ?>">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Jenis Kelamin</label>
                <div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="laki_laki" value="Laki-laki"                                                                                                                                                                                                                                                                                                                                                                   <?php echo(($data['jenis_kelamin'] ?? '') == 'Laki-laki') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="laki_laki">Laki-laki</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="perempuan" value="Perempuan"                                                                                                                                                                                                                                                                                                                                                                   <?php echo(($data['jenis_kelamin'] ?? '') == 'Perempuan') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="perempuan">Perempuan</label>
                  </div>
                </div>
              </div>
            </fieldset>

            <hr class="my-4">

            <!-- UPLOAD GAMBAR -->
            <fieldset>
              <legend class="text-primary">Upload Gambar</legend>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label">Foto Profil</label>
                    <div class="custom-file mb-3">
                      <input type="file" class="custom-file-input" id="profileImageInput" name="img" accept="image/*">
                      <label class="custom-file-label" for="profileImageInput">Pilih gambar profil...</label>
                    </div>
                    <div class="preview-container">
                      <img id="profilePreview" class="img-thumbnail" style="max-width: 200px; display: none;">
                    </div>
                    <?php if (! empty($data['img']) && file_exists(__DIR__ . '/../../../assets/img/profile/' . $data['img'])): ?>
                      <div class="current-image mt-2">
                        <label class="form-label">Gambar saat ini:</label>
                        <img src="<?php echo base_url('assets/img/profile/' . $data['img']); ?>" alt="Current Profile Image" class="img-thumbnail" style="max-width: 150px;">
                      </div>
                    <?php endif; ?>
                    <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label">Foto KTP</label>
                    <div class="custom-file mb-3">
                      <input type="file" class="custom-file-input" id="ktpImageInput" name="ktp_img" accept="image/*">
                      <label class="custom-file-label" for="ktpImageInput">Pilih gambar KTP...</label>
                    </div>
                    <div class="preview-container">
                      <img id="ktpPreview" class="img-thumbnail" style="max-width: 200px; display: none;">
                    </div>
                    <?php if (! empty($data['ktp_img']) && file_exists(__DIR__ . '/../../../assets/img/ktp/' . $data['ktp_img'])): ?>
                      <div class="current-image mt-2">
                        <label class="form-label">KTP saat ini:</label>
                        <img src="<?php echo base_url('assets/img/ktp/' . $data['ktp_img']); ?>" alt="Current KTP Image" class="img-thumbnail" style="max-width: 150px;">
                      </div>
                    <?php endif; ?>
                    <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                  </div>
                </div>
              </div>
            </fieldset>

            <div class="form-group mt-4">
              <button type="submit" class="btn btn-primary mr-2">
                <i class="material-icons mr-2">save</i>Simpan Perubahan
              </button>
              <a href="<?php echo base_url(); ?>home" class="btn btn-secondary">
                <i class="material-icons mr-2">arrow_back</i>Kembali
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    // Validasi NIK - hanya angka
    $('#nik').on('input', function() {
        this.value = this.value.replace(/\D/g, '');
    });

    // Preview gambar profil
    $('#profileImageInput').on('change', function() {
        const file = this.files[0];
        const label = $(this).next('.custom-file-label');
        const preview = $('#profilePreview');

        if (file) {
            label.text(file.name);

            // Validasi ukuran file (2MB)
            if (file.size > 2097152) {
                alert('Ukuran file terlalu besar! Maksimal 2MB');
                $(this).val('');
                label.text('Pilih gambar profil...');
                preview.hide();
                return;
            }

            // Validasi tipe file
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                alert('Format file tidak didukung! Gunakan JPG, PNG, atau GIF');
                $(this).val('');
                label.text('Pilih gambar profil...');
                preview.hide();
                return;
            }

            // Preview gambar
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
        } else {
            label.text('Pilih gambar profil...');
            preview.hide();
        }
    });

    // Preview gambar KTP
    $('#ktpImageInput').on('change', function() {
        const file = this.files[0];
        const label = $(this).next('.custom-file-label');
        const preview = $('#ktpPreview');

        if (file) {
            label.text(file.name);

            // Validasi ukuran file (2MB)
            if (file.size > 2097152) {
                alert('Ukuran file terlalu besar! Maksimal 2MB');
                $(this).val('');
                label.text('Pilih gambar KTP...');
                preview.hide();
                return;
            }

            // Validasi tipe file
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                alert('Format file tidak didukung! Gunakan JPG, PNG, atau GIF');
                $(this).val('');
                label.text('Pilih gambar KTP...');
                preview.hide();
                return;
            }

            // Preview gambar
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
        } else {
            label.text('Pilih gambar KTP...');
            preview.hide();
        }
    });

    // Validasi form
    $('#editProfileForm').on('submit', function(e) {
        const password = $('#password').val();
        const nik = $('#nik').val();

        if (password && password.length < 6) {
            e.preventDefault();
            alert('Password minimal 6 karakter!');
            return false;
        }

        if (nik && nik.length !== 16) {
            e.preventDefault();
            alert('NIK harus 16 digit angka!');
            return false;
        }

        // Tampilkan loading
        $(this).find('button[type="submit"]').html('<i class="material-icons mr-2">hourglass_empty</i>Menyimpan...').prop('disabled', true);
    });

    // Auto format nama dan tempat lahir
    $('#nama, #tempat_lahir').on('blur', function() {
        const value = $(this).val();
        const formatted = value.toLowerCase().replace(/\b[a-z]/g, function(letter) {
            return letter.toUpperCase();
        });
        $(this).val(formatted);
    });
});
</script>

