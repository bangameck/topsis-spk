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

// Get action and ID from URL
$action = $_GET['action'] ?? '';
$id = isset($_GET['id']) ? $_GET['id'] : NULL;

// Handle actions
switch ($action) {
  case 'add':
    handleAddCriterias();
    break;
  case 'edit':
    handleEditCriterias($id);
    break;
  case 'delete':
    handleDeleteCriterias($id);
    break;
  default:
    header("HTTP/1.0 404 Not Found");
    exit('Invalid action');
}

#Tambah Users
function handleAddCriterias()
{
  global $db;

  //cek method (tidak diizinkan selain POST)
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    toastNotif('error', 'Method tidak sesuai!');
    header("Location: " . base_url('criteria'));
    exit();
  }

  //cek CSRF token
  if (!check_csrf_token($_POST['csrf_token'] ?? '')) {
    toastNotif('error', 'Token CSRF tidak valid.');
    header("Location: " . base_url('criteria'));
    exit();
  }

  //handleInput
  $id = $db->real_escape_string($_POST['id']);
  $name = $db->real_escape_string($_POST['name']);
  $value = filter_var($_POST['value'], FILTER_VALIDATE_FLOAT);
  $type = $db->real_escape_string($_POST['type']);
  $information = $db->real_escape_string($_POST['information']);

  //cek jika ID kriteria sudah ada didatabase
  $stmt = $db->prepare("SELECT criteria_id FROM criteria WHERE criteria_id = ?");
  $stmt->bind_param("s", $id);
  $stmt->execute();

  if ($stmt->get_result()->num_rows > 0) {
    toastNotif('error', 'ID Kriteria sudah terdaftar');
    header("Location: " . base_url('criteria'));

    exit();
  }
  $stmt->close();

  //insert kriteria baru
  $stmt = $db->prepare("INSERT INTO criteria (criteria_id, criteria_name, criteria_type, criteria_information, criteria_value, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
  $stmt->bind_param("ssssd", $id, $name, $type, $information, $value);
  if ($stmt->execute()) {
    toastNotif('success', 'Kriteria berhasil ditambahkan!');
  } else {
    toastNotif('error', 'Gagal menambahkan kriteria' . $db->error);
  }
  $stmt->close();
  header("Location: " . base_url('criteria'));
  exit();
}

#Edit Kriteria
function handleEditCriterias($id)
{
  global $db;

  //cek method (tidak diizinkan selain POST)
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    toastNotif('error', 'Method tidak sesuai!');
    header("Location: " . base_url('criteria'));
    exit();
  }

  //cek CSRF token
  if (!check_csrf_token($_POST['csrf_token'] ?? '')) {
    toastNotif('error', 'Token CSRF tidak valid.');
    header("Location: " . base_url('criteria'));
    exit();
  }

  // $id = $db->real_escape_string($_POST['id']);
  $name = $db->real_escape_string($_POST['name']);
  $value = filter_var($_POST['value'], FILTER_VALIDATE_FLOAT);
  $type = $db->real_escape_string($_POST['type']);
  $information = $db->real_escape_string($_POST['information']);

  //update user
  $query = "UPDATE criteria SET 
            criteria_name = ?,
            criteria_type = ?,
            criteria_information = ?,
            criteria_value = ?,
            updated_at = NOW()
            WHERE criteria_id = ?";
  $stmt = $db->prepare($query);

  $stmt->bind_param("sssds", $name, $type, $information, $value, $id);

  if ($stmt->execute()) {
    toastNotif('success', 'Data berhasil di update.');
  } else {
    toastNotif('error', 'Data gagal di update' . $db->error);
  }

  $stmt->close();

  header("Location: " . base_url('criteria'));
  exit();
}

#hapus Kriteria
function handleDeleteCriterias($id)
{
  global $db;

  // Get user data first
  $stmt = $db->prepare("SELECT criteria_id FROM criteria WHERE criteria_id = ?");
  $stmt->bind_param("s", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $criteria = $result->fetch_assoc();
  $stmt->close();

  if (!$criteria) {
    toastNotif('error', 'Data kriteria tidak ditemukan.');
    header("Location: " . base_url('criteria'));
    exit();
  }

  // Delete kriteria
  $stmt = $db->prepare("DELETE FROM criteria WHERE criteria_id = ?");
  $stmt->bind_param("s", $id);

  if ($stmt->execute()) {
    toastNotif('success', 'Kriteria berhasil dihapus');
  } else {
    toastNotif('error', 'Kriteria tidak berhasil dihapus' . $db->error);
  }
  $stmt->close();

  header("Location: " . base_url('criteria'));
  exit();
}
