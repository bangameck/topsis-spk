<?php
// topsis-skripsi/app/controllers/AlternativeControllers.php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once __DIR__ . '/../../config/controlWeb.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Global variable $criterias hanya relevan jika controller ini di-include oleh route/web.php
// untuk menampilkan form.
global $criterias;


// Fungsi untuk menyiapkan data kriteria untuk form input alternatif
function showAlternativeForm()
{
  global $db;
  global $criterias;

  $criterias = [];
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

// --- FUNGSI BARU: Untuk mengambil dan memformat data alternatif ---
function getAlternativeDataForList()
{
  global $db;
  global $alternativesList; // Variabel ini akan di-pass ke view
  global $criteriaList;    // Variabel ini akan di-pass ke view

  $alternativesList = [];
  $criteriaList = [];

  // 1. Ambil daftar semua kriteria
  $stmtCriteria = $db->prepare("SELECT criteria_id, criteria_name FROM criteria ORDER BY criteria_id ASC");
  if ($stmtCriteria === false) {
    error_log("Failed to prepare criteria list query: " . $db->error);
    return;
  }
  $stmtCriteria->execute();
  $resultCriteria = $stmtCriteria->get_result();
  while ($row = $resultCriteria->fetch_assoc()) {
    $criteriaList[] = $row;
  }
  $stmtCriteria->close();

  // Buat array map untuk kriteria agar mudah diakses
  $criteriaMap = [];
  foreach ($criteriaList as $c) {
    $criteriaMap[$c['criteria_id']] = $c['criteria_name'];
  }

  // 2. Ambil semua data alternatif dari tabel 'alternative'
  // Gabungkan dengan tabel 'users' untuk mendapatkan nama user
  $stmtAlternatives = $db->prepare("
        SELECT
            a.user_id,
            u.name AS user_name,
            a.criteria_id,
            a.alternative_value
        FROM
            alternative a
        JOIN
            users u ON a.user_id = u.user_id
        ORDER BY
            u.name, a.criteria_id
    ");
  if ($stmtAlternatives === false) {
    error_log("Failed to prepare alternatives list query: " . $db->error);
    return;
  }
  $stmtAlternatives->execute();
  $resultAlternatives = $stmtAlternatives->get_result();

  // Format data ke dalam struktur yang mudah diakses di view
  // $alternativesList akan menjadi:
  // [
  //    user_id_1 => ['user_name' => 'Nama User 1', 'values' => ['C1' => val, 'C2' => val]],
  //    user_id_2 => ['user_name' => 'Nama User 2', 'values' => ['C1' => val, 'C2' => val]],
  // ]
  while ($row = $resultAlternatives->fetch_assoc()) {
    $userId = $row['user_id'];
    $criteriaId = $row['criteria_id'];
    $alternativeValue = $row['alternative_value'];
    $userName = $row['user_name'];

    if (!isset($alternativesList[$userId])) {
      $alternativesList[$userId] = [
        'user_name' => $userName,
        'values' => []
      ];
    }
    $alternativesList[$userId]['values'][$criteriaId] = $alternativeValue;
  }
  $stmtAlternatives->close();

  // Mengisi kriteria yang mungkin kosong (jika ada user yang belum menginput semua kriteria)
  // Ini memastikan setiap baris memiliki semua kolom kriteria, meskipun nilainya kosong
  foreach ($alternativesList as $userId => &$userData) {
    foreach ($criteriaList as $criteria) {
      $cid = $criteria['criteria_id'];
      if (!isset($userData['values'][$cid])) {
        $userData['values'][$cid] = null; // Atau '0' atau '-' sesuai preferensi Anda
      }
    }
  }
}


// Fungsi untuk menangani penyimpanan data alternatif (dipanggil langsung oleh .htaccess)
function handleAddAlternative()
{
  global $db;

  if (!isset($_SESSION['level']) || $_SESSION['level'] != 2) {
    header('HTTP/1.1 401 Unauthorized');
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

  $user_id = $_SESSION['user_id'] ?? null;

  if (!$user_id) {
    toastNotif('error', 'User ID tidak ditemukan. Harap login kembali.');
    header("Location: " . base_url('login'));
    exit();
  }

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

// Logika eksekusi langsung
if (isset($_GET['action']) && $_GET['action'] === 'save') {
  handleAddAlternative();
}

// Catatan: Variabel global $criterias yang dideklarasikan di awal file AlternativeControllers.php
// hanya relevan untuk fungsi showAlternativeForm().
// Untuk fungsi getAlternativeDataForList(), kita mendeklarasikan $alternativesList
// dan $criteriaList sebagai global juga agar bisa diakses oleh view.
