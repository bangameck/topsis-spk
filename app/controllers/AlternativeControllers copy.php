<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// --- PENTING: Tambahkan ini di awal file AlternativeControllers.php ---
// Include controlWeb.php untuk akses $db, base_url(), toastNotif(), dan yang paling penting, session_start()
require_once __DIR__ . '/../../config/controlWeb.php';

// Pastikan sesi sudah aktif
if (session_status() == PHP_SESSION_NONE) {
  session_start(); // Ini akan dieksekusi jika controlWeb.php tidak berhasil memulai sesi (jarang terjadi)
}
// --- Akhir tambahan penting ---


// HANYA user level 2 (Masyarakat) yang bisa mengakses halaman ini
// Pengecekan ini harus ada di sini karena file ini bisa diakses langsung
if (!isset($_SESSION['level']) || $_SESSION['level'] != 2) {
  // Jika tidak ada sesi atau level tidak 2, lakukan tindakan yang sesuai
  // Untuk permintaan AJAX/POST, mungkin lebih baik mengirim header 401
  // Untuk permintaan GET, redirect ke login
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('HTTP/1.1 401 Unauthorized');
    exit();
  } else {
    header('Location: ' . base_url('login'));
    exit();
  }
}

// Global variable $criterias hanya relevan jika controller ini di-include oleh route/web.php
// untuk menampilkan form. Untuk aksi 'save', ini tidak relevan.
global $criterias; // Deklarasikan di sini juga untuk showAlternativeForm()

// Fungsi untuk menyiapkan data kriteria untuk view
function showAlternativeForm()
{
  global $db;
  global $criterias; // Ini harus ada di sini

  // --- Hapus semua debugging echo/print_r/die yang Anda tambahkan sebelumnya di fungsi ini ---

  $criterias = []; // Inisialisasi array kosong
  $stmt = $db->prepare("SELECT criteria_id, criteria_name, criteria_information FROM criteria ORDER BY criteria_id ASC");
  if ($stmt === false) {
    error_log("Prepare statement failed in AlternativeControllers.php (showAlternativeForm): " . $db->error);
    die('Database error: Unable to prepare criteria query. Check logs.');
  }
  $stmt->execute();
  $result = $stmt->get_result();

  while ($row = $result->fetch_assoc()) {
    $criterias[] = $row;
  }
  $stmt->close();
}


// Fungsi untuk menangani penyimpanan data alternatif
function handleAddAlternative()
{
  global $db;

  // --- Hapus semua debugging echo/print_r/die yang Anda tambahkan sebelumnya di fungsi ini ---

  // Pastikan user level 2 yang memproses aksi ini
  // Cek ini akan berjalan setelah session_start() di awal file
  if (!isset($_SESSION['level']) || $_SESSION['level'] != 2) {
    header('HTTP/1.1 401 Unauthorized'); // <--- Ini yang memicu error 401
    exit();
  }

  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    toastNotif('error', 'Method tidak sesuai!');
    header("Location: " . base_url('alternative/add'));
    exit();
  }

  if (!check_csrf_token($_POST['csrf_token'] ?? '')) {
    toastNotif('error', 'Token CSRF tidak valid.');
    header("Location: " . base_url('alternative/add'));
    exit();
  }

  // $_SESSION['user_id'] SEHARUSNYA SUDAH ADA SETELAH LOGIN YANG BERHASIL
  $user_id = $_SESSION['user_id'] ?? null;

  if (!$user_id) {
    toastNotif('error', 'User ID tidak ditemukan. Harap login kembali.');
    header("Location: " . base_url('login')); // Redirect ke login jika user_id tidak ada
    exit();
  }

  // ... (sisa logika penyimpanan data) ...
  $criteriaIds = [];
  $stmt = $db->prepare("SELECT criteria_id FROM criteria");
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $criteriaIds[] = $row['criteria_id'];
  }
  $stmt->close();

  $allDataProcessed = true;
  foreach ($_POST as $key => $value) {
    if (in_array($key, $criteriaIds)) {
      $criteria_id = $key;
      $alternative_value = $db->real_escape_string($value);

      $check_stmt = $db->prepare("SELECT * FROM alternative WHERE user_id = ? AND criteria_id = ?");
      $check_stmt->bind_param("is", $user_id, $criteria_id);
      $check_stmt->execute();
      $check_result = $check_stmt->get_result();

      if ($check_result->num_rows > 0) {
        $update_stmt = $db->prepare("UPDATE alternative SET alternative_value = ?, updated_at = NOW() WHERE user_id = ? AND criteria_id = ?");
        $update_stmt->bind_param("sis", $alternative_value, $user_id, $criteria_id);
        if (!$update_stmt->execute()) {
          $allDataProcessed = false;
          error_log("Failed to update alternative for user_id: $user_id, criteria_id: $criteria_id - " . $db->error);
        }
        $update_stmt->close();
      } else {
        $insert_stmt = $db->prepare("INSERT INTO alternative (user_id, criteria_id, alternative_value, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
        $insert_stmt->bind_param("iss", $user_id, $criteria_id, $alternative_value);
        if (!$insert_stmt->execute()) {
          $allDataProcessed = false;
          error_log("Failed to insert alternative for user_id: $user_id, criteria_id: $criteria_id - " . $db->error);
        }
        $insert_stmt->close();
      }
      $check_stmt->close();
    }
  }

  if ($allDataProcessed) {
    toastNotif('success', 'Nilai alternatif berhasil disimpan!');
  } else {
    toastNotif('error', 'Terdapat masalah saat menyimpan nilai alternatif. Silakan periksa log.');
  }

  header("Location: " . base_url('alternative/add'));
  exit();
}

// Logika eksekusi langsung (hanya untuk aksi 'save' dari .htaccess)
if (isset($_GET['action']) && $_GET['action'] === 'save') {
  handleAddAlternative();
  // handleAddAlternative() sudah melakukan exit() melalui redirect
}
