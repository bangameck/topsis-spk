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

#Modul Control
function modul($m)
{
  global $db;
  global $base_url;
  if (empty($m)) {
    include "modul/home/home.php";
  } else {
    return include "modul/$m/$m.php";
  }
}

#Template Website
function template($nameFile)
{
  if ($nameFile == 'head') {
    include '_template/head.php';
  } elseif ($nameFile == 'navbar') {
    include '_template/navbar.php';
  } elseif ($nameFile == 'user-details') {
    include '_template/user-details-sidebar.php';
  } elseif ($nameFile == 'menu') {
    include '_template/menu-sidebar.php';
  } elseif ($nameFile == 'footer') {
    include '_template/footer.php';
  } elseif ($nameFile == 'js') {
    include '_template/js.php';
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
