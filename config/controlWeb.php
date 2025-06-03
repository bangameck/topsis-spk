<!-- /**
 * @author [RadevankaProject]
 * @email radevankaproject@mail.com]
 * @create date 2025-05-07 18:46:36
 * @modify date 2025-05-07 18:46:36
 * @desc [description]
 */ -->


<?php
#startSession and Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
// error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
// error_reporting(0);
session_start();
ini_set('max_execution_time', 3600);
date_default_timezone_set('Asia/Jakarta');

#Control Database
$srvName = "localhost";
$dbUser = "root";
$dbPass = "12345678";
$dbName = "topsis_spk";
$db = new mysqli($srvName, $dbUser, $dbPass, $dbName);
if ($db->connect_error) {
  die("Koneksi gagal: " . $db->connect_error);
}

#URL Settings
if (!function_exists('base_url')) {
  function base_url($path = '')
  {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];

    // Ambil nama folder root proyek (misalnya: /topsis-skripsi/)
    $script_name = $_SERVER['SCRIPT_NAME']; // e.g. /topsis-skripsi/modul/login.php
    $script_dir  = dirname($script_name);   // e.g. /topsis-skripsi/modul
    $base_folder = explode('/', trim($script_dir, '/'))[0]; // e.g. topsis-skripsi

    // Susun base_url dari host + folder root
    $base = $protocol . '://' . $host . '/' . $base_folder . '/';

    // Gabungkan base + path
    return rtrim($base, '/') . '/' . ltrim($path, '/');
  }
}
$base_url = base_url();

#Error Message
function displaySessionMessages()
{
  if (!empty($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
    unset($_SESSION['error']);
  }

  if (!empty($_SESSION['success'])) {
    echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
    unset($_SESSION['success']);
  }
}

#notifToastr
function toastNotif($type, $message)
{
  $_SESSION['toast_notifications'][] = [
    'type' => $type,
    'message' => $message
  ];
}

#Template Website
function template($nameFile)
{
  if ($nameFile == 'head') {
    include 'app/_template/head.php';
  } elseif ($nameFile == 'navbar') {
    include 'app/_template/navbar.php';
  } elseif ($nameFile == 'user-details') {
    include 'app/_template/user-details-sidebar.php';
  } elseif ($nameFile == 'menu') {
    include 'app/_template/menu-sidebar.php';
  } elseif ($nameFile == 'footer') {
    include 'app/_template/footer.php';
  } elseif ($nameFile == 'js') {
    include 'app/_template/js.php';
  }
}

#CSRF Token
function generate_csrf_token()
{
  if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['csrf_token'];
}

function check_csrf_token($token)
{
  return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function fotoCompressResize($img_name, $source, $upload)
{
  // Pastikan direktori tujuan ada
  if (!is_dir($upload)) {
    if (!mkdir($upload, 0755, true)) {
      return false; // Gagal membuat folder
    }
  }

  // Ambil info gambar
  $imgInfo = getimagesize($source);
  if (!$imgInfo) {
    return false; // Bukan file gambar valid
  }

  $mime = $imgInfo['mime'];

  // Buat resource gambar dari file asli
  switch ($mime) {
    case 'image/jpeg':
      $image = imagecreatefromjpeg($source);
      break;
    case 'image/png':
      $image = imagecreatefrompng($source);
      break;
    case 'image/gif':
      $image = imagecreatefromgif($source);
      break;
    default:
      return false; // MIME tidak didukung
  }

  // Ukuran asli
  $src_width = imagesx($image);
  $src_height = imagesy($image);

  // Resize ukuran lebar tetap 500px
  $dst_width = 500;
  $dst_height = (int) round(($dst_width / $src_width) * $src_height);

  // Buat kanvas baru untuk ukuran yang dikecilkan
  $im = imagecreatetruecolor($dst_width, $dst_height);

  // Salin dan ubah ukurannya
  imagecopyresampled($im, $image, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);

  // Simpan gambar hasil ke path akhir
  $outputPath = rtrim($upload, '/') . '/' . $img_name;
  if (imagejpeg($im, $outputPath, 85)) {
    return $outputPath; // Jika sukses, return path file
  }

  return false; // Jika gagal menyimpan
}
