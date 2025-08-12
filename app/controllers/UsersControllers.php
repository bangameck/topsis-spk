<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// session_start();
require_once __DIR__ . '/../../config/controlWeb.php';
// require_once __DIR__ . '/../../includes/auth_validate.php';

// Only admin can access this page
if ($_SESSION['level'] != 1) {
    header('HTTP/1.1 401 Unauthorized');
    exit();
}

// Path profile image dinamis
define('PROFILE_IMG_PATH', rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'topsis-skripsi' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'profile' . DIRECTORY_SEPARATOR);
define('PROFILE_KTP_PATH', rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'topsis-skripsi' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'ktp' . DIRECTORY_SEPARATOR);

// Get action and ID from URL
$action = $_GET['action'] ?? '';
$id     = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Handle actions
switch ($action) {
    case 'add':
        handleAddUser();
        break;
    case 'edit':
        handleEditUser($id);
        break;
    case 'editProfile':
        handleEditProfile();
        break;
    case 'delete':
        handleDeleteUser($id);
        break;
    default:
        header("HTTP/1.0 404 Not Found");
        exit('Invalid action');
}

#Tambah Users
function handleAddUser()
{
    global $db;

    //cek method (tidak diizinkan selain POST)
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        toastNotif('error', 'Method tidak sesuai!');
        header("Location: " . base_url('users'));
        exit();
    }

    //cek CSRF token
    if (! check_csrf_token($_POST['csrf_token'] ?? '')) {
        toastNotif('error', 'Token CSRF tidak valid.');
        header("Location: " . base_url('users'));
        exit();
    }

    //handleInput
    $username = $db->real_escape_string($_POST['username']);
    $name     = $db->real_escape_string($_POST['name']);
    $level    = (int) $_POST['level'];
    $password = $_POST['password'];

    //cek jika username sudah ada didatabase
    $stmt = $db->prepare("SELECT user_id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    if ($stmt->get_result()->num_rows > 0) {
        toastNotif('error', 'Username sudah terdaftar');
        header("Location: " . base_url('users'));

        exit();
    }
    $stmt->close();

    //handle img Profil
    $imgFilename = handleFileUpload();
    // print_r($imgFilename);

    //password Hash
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    // var_dump($imgFilename);
    // die();
    //insert user baru
    $stmt = $db->prepare("INSERT INTO users (username, password, name, level, img, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("sssis", $username, $passwordHash, $name, $level, $imgFilename);
    if ($stmt->execute()) {
        toastNotif('success', 'Username berhasil ditambahkan!');
    } else {
        toastNotif('error', 'Gagal menambahkan username' . $db->error);
    }
    $stmt->close();
    header("Location: " . base_url('users'));
    exit();
}

#Edit User
function handleEditUser($id)
{
    global $db;

    //cek method (tidak diizinkan selain POST)
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        toastNotif('error', 'Method tidak sesuai!');
        header("Location: " . base_url('users'));
        exit();
    }

    //cek CSRF token
    if (! check_csrf_token($_POST['csrf_token'] ?? '')) {
        toastNotif('error', 'Token CSRF tidak valid.');
        header("Location: " . base_url('users'));
        exit();
    }

    $username     = $db->real_escape_string($_POST['username']);
    $name         = $db->real_escape_string($_POST['name']);
    $level        = (int) $_POST['level'];
    $password     = $_POST['password'] ?? '';
    $currentImage = $db->real_escape_string($_POST['current_image'] ?? '');

    //cek jika username sudah ada
    $stmt = $db->prepare("SELECT user_id FROM users WHERE username = ? AND user_id != ?");
    $stmt->bind_param("si", $username, $id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        toastNotif('error', 'Username sudah ada!');
        header("Location: " . base_url('users'));
        exit();
    }
    $stmt->close();

    //handel upload image
    $imgFilename = handleFileUpload();
    if ($imgFilename && $currentImage) {
        //hapus image lama
        deleteProfileImage($currentImage);
    }
    $finalImage = $imgFilename ?: $currentImage;

    //prepare password
    $passwordUpdate = '';
    if (! empty($password)) {
        $passwordHash   = password_hash($password, PASSWORD_DEFAULT);
        $passwordUpdate = ", password  = ?";
    }

    //update user
    $query = "UPDATE users SET
            username = ?,
            name = ?,
            level = ?,
            img = ?,
            updated_at = NOW()
            {$passwordUpdate}
            WHERE user_id = ?";
    $stmt = $db->prepare($query);

    if (! empty($passwordUpdate)) {
        $stmt->bind_param("ssissi", $username, $name, $level, $finalImage, $passwordHash, $id);
    } else {
        $stmt->bind_param("ssisi", $username, $name, $level, $finalImage, $id);
    }

    if ($stmt->execute()) {
        toastNotif('success', 'Data berhasil di update.');
    } else {
        toastNotif('error', 'Data gagal di update' . $db->error);
    }

    $stmt->close();

    header("Location: " . base_url('users'));
    exit();
}

function handleEditProfile()
{
    global $db;

    // 4. Proses update data jika form disubmit
    // 4. Proses update data jika form disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (! check_csrf_token($_POST['csrf_token'] ?? '')) {
            toastNotif('error', 'Token CSRF tidak valid.');
        } else {
            $user_id = $_POST['user_id'];
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
                        $upload_path = PROFILE_IMG_PATH;

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
                            $upload_path_ktp = PROFILE_KTP_PATH;

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
                            $upload_path_ktp = PROFILE_KTP_PATH;

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
                header("Location: " . base_url() . "users/editProfile");
                exit();

            } catch (Exception $e) {
                $db->rollback();
                toastNotif('error', $e->getMessage());
            }
        }
    }
}

#hapus User
function handleDeleteUser($id)
{
    global $db;

    // Get user data first
    $stmt = $db->prepare("SELECT img FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user   = $result->fetch_assoc();
    $stmt->close();

    if (! $user) {
        toastNotif('error', 'User tidak ditemukan.');
        header("Location: " . base_url('users'));
        exit();
    }

    // Hapus data dari tabel anak (ranking, masyarakat, dst)
    $stmt = $db->prepare("DELETE FROM ranking WHERE user_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    $stmt = $db->prepare("DELETE FROM masyarakat WHERE user_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Baru hapus user
    $stmt = $db->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Delete profile image if exists
        if (! empty($user['img'])) {
            deleteProfileImage($user['img']);
        }
        toastNotif('success', 'User berhasil dihapus');
    } else {
        toastNotif('error', 'User tidak berhasil dihapus. ' . $db->error);
    }
    $stmt->close();

    header("Location: " . base_url('users'));
    exit();
}

#Upload Image
function handleFileUpload()
{
    $imgFilename = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['image']['tmp_name'];
        $name_tmp  = $_FILES['image']['name'];
        $ext_valid = ['png', 'jpg', 'jpeg', 'gif'];
        $x         = explode('.', $name_tmp);
        $extend    = strtolower(end($x));
        $foto      = uniqid() . '.' . $extend;
        $path      = PROFILE_IMG_PATH;

        if (in_array($extend, $ext_valid)) {
            $hasil = fotoCompressResize($foto, $file_tmp, $path);
            if ($hasil !== false) {
                $imgFilename = $foto; // hanya nama file saja
            } else {
                toastNotif('error', 'Gagal memproses gambar.');
            }
        } else {
            toastNotif('error', 'Jenis file tidak didukung. Hanya JPG, PNG, GIF.');
        }
    }

    return $imgFilename;
}

function deleteProfileImage($filename)
{
    $filepath = PROFILE_IMG_PATH . $filename;
    if (file_exists($filepath)) {
        unlink($filepath);
        toastNotif('success', 'Image berhasil terhapus.');
    }
}
