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
define('PROFILE_IMG_PATH', rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'profile' . DIRECTORY_SEPARATOR);

// Get action and ID from URL
$action = $_GET['action'] ?? '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle actions
switch ($action) {
  case 'add':
    handleAddUser();
    break;
  case 'edit':
    handleEditUser($id);
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
  if (!check_csrf_token($_POST['csrf_token'] ?? '')) {
    toastNotif('error', 'Token CSRF tidak valid.');
    header("Location: " . base_url('users'));
    exit();
  }

  //handleInput
  $username = $db->real_escape_string($_POST['username']);
  $name = $db->real_escape_string($_POST['name']);
  $level = (int)$_POST['level'];
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
  if (!check_csrf_token($_POST['csrf_token'] ?? '')) {
    toastNotif('error', 'Token CSRF tidak valid.');
    header("Location: " . base_url('users'));
    exit();
  }

  $username = $db->real_escape_string($_POST['username']);
  $name = $db->real_escape_string($_POST['name']);
  $level = (int)$_POST['level'];
  $password = $_POST['password'] ?? '';
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
  if (!empty($password)) {
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
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

  if (!empty($passwordUpdate)) {
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

#hapus User
function handleDeleteUser($id)
{
  global $db;

  // Get user data first
  $stmt = $db->prepare("SELECT img FROM users WHERE user_id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();
  $stmt->close();

  if (!$user) {
    toastNotif('error', 'User tidak ditemukan.');
    header("Location: " . base_url('users'));
    exit();
  }

  // Delete user
  $stmt = $db->prepare("DELETE FROM users WHERE user_id = ?");
  $stmt->bind_param("i", $id);

  if ($stmt->execute()) {
    // Delete profile image if exists
    if (!empty($user['img'])) {
      deleteProfileImage($user['img']);
    }
    toastNotif('success', 'User berhasil dihapus');
  } else {
    toastNotif('error', 'User tidak berhasil dihapus' . $db->error);
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
    $file_tmp = $_FILES['image']['tmp_name'];
    $name_tmp = $_FILES['image']['name'];
    $ext_valid = ['png', 'jpg', 'jpeg', 'gif'];
    $x = explode('.', $name_tmp);
    $extend = strtolower(end($x));
    $foto = uniqid() . '.' . $extend;
    $path = PROFILE_IMG_PATH;

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