<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register - Koperasi Budi Karya Jaya</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet">
    <link href="<?= base_url('assets/css/lite-purple.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/custom.css'); ?>" rel="stylesheet"> <!-- Untuk styling custom -->
</head>

<body>
    <div class="auth-layout-wrap" style="background-image: url(<?= base_url('assets/img/gallery/login-bg.jpeg'); ?>)">
        <div class="auth-content">
            <div class="card o-hidden">
                <div class="row">
                    <div class="col-md-12">
                        <div class="p-4">
                            <div class="auth-logo text-center mb-4">
                                <img src="<?= base_url('assets/img/logo-koperasi.png'); ?>" alt="Logo Koperasi">
                                <!-- Judul di bawah logo -->
                                <h1 class="mb-0 mt-3 text-22 font-weight-bold">KOPERASI BUDI KARYA JAYA</h1>
                                <h2 class="mb-3 text-16">Halaman Registrasi Anggota</h2>
                            </div>
                            <form method="POST" action="<?= base_url('auth/process-register'); ?>" enctype="multipart/form-data">
                                <input type="hidden" name="csrf_token" value="<?= generate_csrf_token(); ?>">

                                <!-- Data Akun -->
                                <h5 class="mb-3">Data Akun</h5>
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input class="form-control form-control-rounded" id="username" name="username" type="text" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input class="form-control form-control-rounded" id="password" name="password" type="password" required>
                                </div>
                                <div class="form-group">
                                    <label for="profile_pic">Upload Foto Profil</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="profile_pic" name="profile_pic" accept="image/*">
                                        <label class="custom-file-label" for="profile_pic">Pilih file...</label>
                                    </div>
                                </div>

                                <hr>

                                <!-- Data Pribadi -->
                                <h5 class="mb-3 mt-4">Data Pribadi</h5>
                                <div class="form-group">
                                    <label for="nik">NIK (Nomor Induk Kependudukan)</label>
                                    <input class="form-control form-control-rounded" id="nik" name="nik" type="text" pattern="\d{16}" title="NIK harus 16 digit angka" required>
                                </div>
                                <div class="form-group">
                                    <label for="nama">Nama Lengkap</label>
                                    <input class="form-control form-control-rounded" id="nama" name="nama" type="text" required>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <textarea class="form-control form-control-rounded" id="alamat" name="alamat" required></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="tempat_lahir">Tempat Lahir</label>
                                        <input class="form-control form-control-rounded" id="tempat_lahir" name="tempat_lahir" type="text" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="tanggal_lahir">Tanggal Lahir</label>
                                        <input class="form-control form-control-rounded" id="tanggal_lahir" name="tanggal_lahir" type="date" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Jenis Kelamin</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="laki_laki" value="Laki-laki" required>
                                            <label class="form-check-label" for="laki_laki">Laki-laki</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="perempuan" value="Perempuan" required>
                                            <label class="form-check-label" for="perempuan">Perempuan</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ktp_pic">Upload Foto KTP</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="ktp_pic" name="ktp_pic" accept="image/*">
                                        <label class="custom-file-label" for="ktp_pic">Pilih file...</label>
                                    </div>
                                </div>

                                <button class="btn btn-rounded btn-primary btn-block mt-4" type="submit">Daftar</button>
                            </form>
                            <div class="mt-3 text-center">
                                <a class="text-muted" href="<?= base_url('login.php'); ?>">
                                    <u>Sudah punya akun? Login di sini</u>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url('assets/js/common-bundle-script.js'); ?>"></script>
    <script src="<?= base_url('assets/js/script.js'); ?>"></script>
    <script>
        // Script untuk menampilkan nama file di input file bootstrap
        document.querySelectorAll('.custom-file-input').forEach(function (input) {
            input.addEventListener('change', function (e) {
                var fileName = e.target.files[0].name;
                var nextSibling = e.target.nextElementSibling;
                nextSibling.innerText = fileName;
            });
        });
    </script>
</body>

</html>