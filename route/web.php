<?php
// topsis-skripsi/route/web.php

// controlWeb.php sudah di-include di index.php, jadi $db dan base_url() sudah tersedia secara global.
// JANGAN include_once atau require_once controlWeb.php di sini lagi.
// Jika Anda meng-include-nya di sini juga, itu bisa menyebabkan error "Cannot redeclare function"
// atau masalah sesi karena session_start() dipanggil dua kali.

#RoutesWeb
function routes($m, $r)
{
  global $db;
  global $base_url;
  global $criterias;
  global $alternativesList;
  global $criteriaList;

  // --- PENANGANAN HOME/DASHBOARD ---
  if (empty($m) && empty($r)) {
    // Panggil TopsisCalculator untuk mendapatkan data dashboard
    require_once __DIR__ . '/../app/controllers/TopsisCalculator.php';
    $topsis = new TopsisCalculator($db);
    $dashboardData = $topsis->getDashboardData();

    // Pastikan variabel-variabel ini tersedia di home.php
    $rankingResults = $dashboardData['rankingResults'] ?? [];
    $criteriaInfo = $dashboardData['criteriaInfo'] ?? [];
    $criteriaTypes = $dashboardData['criteriaTypes'] ?? [];

    return include "app/views/home/home.php"; // Ini akan memiliki akses ke variabel-variabel di atas
  }


  switch ($m) {
    case 'alternative':
      if ($r == 'add') {
        // ... (kode yang sudah ada) ...
        if (!isset($_SESSION['level']) || $_SESSION['level'] != 2) {
          header('Location: ' . base_url('login'));
          exit();
        }
        require_once __DIR__ . '/../app/controllers/AlternativeControllers.php';
        showAlternativeForm();
        return include "app/views/alternative/$r.php";
      } elseif ($r == 'list') {
        // ... (kode yang sudah ada) ...
        if (!isset($_SESSION['level']) || $_SESSION['level'] != 1) {
          header('Location: ' . base_url('home'));
          exit();
        }
        require_once __DIR__ . '/../app/controllers/AlternativeControllers.php';
        getAlternativeDataForList();
        return include "app/views/alternative/$r.php";
      }
      break;

    case 'topsis':
      if ($r == 'results') {
        // ... (validasi level user jika diperlukan) ...

        require_once __DIR__ . '/../app/controllers/TopsisCalculator.php';
        $topsis = new TopsisCalculator($db);

        // --- PENTING: Tangkap semua hasil dari calculate(), termasuk detailMatrices dan criteriaInfo ---
        $topsisData = $topsis->calculate();

        // Pisahkan data ke variabel terpisah agar mudah diakses di view
        $rankingResults = $topsisData['rankingResults'] ?? [];
        $idealPositive  = $topsisData['idealPositive'] ?? [];
        $idealNegative  = $topsisData['idealNegative'] ?? [];
        $allUsersData   = $topsisData['allUsers'] ?? [];
        // Tambahkan variabel baru untuk detail
        $detailMatrices = $topsisData['detailMatrices'] ?? [];
        $criteriaInfo   = $topsisData['criteriaInfo'] ?? [];


        // Simpan hasil ke database (opsional)
        if (!isset($topsisData['error'])) {
          $topsis->saveRankingResults($rankingResults);
        }

        // View akan memiliki akses ke $rankingResults, $idealPositive, $idealNegative,
        // $allUsersData, $detailMatrices, dan $criteriaInfo
        return include "app/views/topsis/$r.php";
      }
      break;

    case 'users':
    case 'criteria':
    case 'dashboard':
      // ... (kode yang sudah ada) ...
      return include "app/views/$m/$r.php";
      break;

    default:
      header("HTTP/1.0 404 Not Found");
      exit('Page not found');
  }
}

// Tidak perlu mendeklarasikan $criterias di luar fungsi routes(),
// karena sudah ditangani sebagai global di dalam fungsi dan oleh TopsisCalculator.
