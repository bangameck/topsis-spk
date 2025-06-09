<?php
// topsis-skripsi/app/controllers/TopsisCalculator.php

require_once __DIR__ . '/../../config/controlWeb.php';

class TopsisCalculator
{
  private $db;
  private $criterias = []; // Data kriteria (id, weight, type)
  private $alternatives = []; // Data alternatif (user_id, criteria_id, value)
  private $users = []; // Data user yang punya alternatif
  private $criteriaInfo = []; // Menyimpan semua info kriteria: id, name, weight, type

  public function __construct(mysqli $db)
  {
    $this->db = $db;
    $this->loadData();
  }

  private function loadData()
  {
    // 1. Load Kriteria (dengan bobot dan tipe, dan nama)
    $stmt = $this->db->prepare("SELECT criteria_id, criteria_name, criteria_value, criteria_type FROM criteria ORDER BY criteria_id ASC");
    if ($stmt === false) {
      error_log("Failed to prepare criteria query: " . $this->db->error);
      return;
    }
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      $this->criterias[$row['criteria_id']] = [
        'weight' => (float)$row['criteria_value'],
        'type' => $row['criteria_type']
      ];
      // Simpan info lengkap kriteria untuk ditampilkan
      $this->criteriaInfo[$row['criteria_id']] = [
        'criteria_name' => $row['criteria_name'],
        'weight' => (float)$row['criteria_value'],
        'type' => $row['criteria_type']
      ];
    }
    $stmt->close();

    // 2. Load Alternatif (nilai input user)
    $stmt = $this->db->prepare("SELECT user_id, criteria_id, alternative_value FROM alternative ORDER BY user_id, criteria_id");
    if ($stmt === false) {
      error_log("Failed to prepare alternative query: " . $this->db->error);
      return;
    }
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      if (!in_array($row['user_id'], $this->users)) {
        $this->users[] = $row['user_id'];
      }
      $this->alternatives[$row['user_id']][$row['criteria_id']] = (float)$row['alternative_value'];
    }
    $stmt->close();

    sort($this->users);
  }

  public function calculate()
  {
    if (empty($this->criterias) || empty($this->alternatives)) {
      return ['error' => 'Data kriteria atau alternatif tidak lengkap.'];
    }

    $matrix = [];
    $normalizedMatrix = [];
    $weightedNormalizedMatrix = [];
    $idealPositive = [];
    $idealNegative = [];
    $distancePositive = [];
    $distanceNegative = [];
    $preferenceValues = [];

    // Populate the decision matrix
    foreach ($this->users as $userId) {
      foreach ($this->criterias as $criteriaId => $data) {
        $matrix[$userId][$criteriaId] = $this->alternatives[$userId][$criteriaId] ?? 0.0;
      }
    }

    // --- Step 1: Normalisasi Matriks Keputusan (Rij) ---
    foreach ($this->criterias as $criteriaId => $data) {
      $sumOfSquares = 0;
      foreach ($this->users as $userId) {
        $sumOfSquares += pow($matrix[$userId][$criteriaId], 2);
      }
      $sqrtSumOfSquares = sqrt($sumOfSquares);

      foreach ($this->users as $userId) {
        if ($sqrtSumOfSquares != 0) {
          $normalizedMatrix[$userId][$criteriaId] = $matrix[$userId][$criteriaId] / $sqrtSumOfSquares;
        } else {
          $normalizedMatrix[$userId][$criteriaId] = 0;
        }
      }
    }

    // --- Step 2: Normalisasi Terbobot (Yij) ---
    foreach ($this->criterias as $criteriaId => $data) {
      foreach ($this->users as $userId) {
        $weightedNormalizedMatrix[$userId][$criteriaId] = $normalizedMatrix[$userId][$criteriaId] * $data['weight'];
      }
    }

    // --- Step 3: Menentukan Solusi Ideal Positif (A+) dan Solusi Ideal Negatif (A-) ---
    foreach ($this->criterias as $criteriaId => $data) {
      $valuesForCriteria = array_column($weightedNormalizedMatrix, $criteriaId);

      if ($data['type'] == 'benefit') {
        $idealPositive[$criteriaId] = !empty($valuesForCriteria) ? max($valuesForCriteria) : 0;
        $idealNegative[$criteriaId] = !empty($valuesForCriteria) ? min($valuesForCriteria) : 0;
      } else { // cost
        $idealPositive[$criteriaId] = !empty($valuesForCriteria) ? min($valuesForCriteria) : 0;
        $idealNegative[$criteriaId] = !empty($valuesForCriteria) ? max($valuesForCriteria) : 0;
      }
    }

    // --- Step 4: Menghitung Jarak ke Solusi Ideal Positif (D+) dan Solusi Ideal Negatif (D-) ---
    foreach ($this->users as $userId) {
      $sumDPlus = 0;
      $sumDMinus = 0;
      foreach ($this->criterias as $criteriaId => $data) {
        $sumDPlus += pow(($idealPositive[$criteriaId] - $weightedNormalizedMatrix[$userId][$criteriaId]), 2);
        $sumDMinus += pow(($idealNegative[$criteriaId] - $weightedNormalizedMatrix[$userId][$criteriaId]), 2);
      }
      $distancePositive[$userId] = sqrt($sumDPlus);
      $distanceNegative[$userId] = sqrt($sumDMinus);
    }

    // --- Step 5: Menghitung Nilai Preferensi (Vi) dan Merangking ---
    $results = [];
    foreach ($this->users as $userId) {
      if (($distancePositive[$userId] + $distanceNegative[$userId]) != 0) {
        $preferenceValues[$userId] = $distanceNegative[$userId] / ($distancePositive[$userId] + $distanceNegative[$userId]);
      } else {
        $preferenceValues[$userId] = 0;
      }

      $userName = $this->getUserName($userId);

      $results[] = [
        'user_id' => $userId,
        'user_name' => $userName,
        'score' => $preferenceValues[$userId],
        // Detail perhitungan per user dihapus dari sini karena akan diakses via detailMatrices
        // 'detail' => [ ... ]
      ];
    }

    usort($results, function ($a, $b) {
      return $b['score'] <=> $a['score'];
    });

    foreach ($results as $key => &$row) {
      $row['rank'] = $key + 1;
    }

    // --- PENTING: Kembalikan semua matriks detail dalam satu array 'detailMatrices' ---
    return [
      'rankingResults' => $results,
      'idealPositive' => $idealPositive,
      'idealNegative' => $idealNegative,
      'allUsers' => array_map(function ($id) {
        return ['user_id' => $id, 'user_name' => $this->getUserName($id)];
      }, $this->users),
      'criteriaInfo' => $this->criteriaInfo, // Tambahkan informasi kriteria lengkap
      'detailMatrices' => [ // Kumpulan semua matriks detail
        'matrix' => $matrix,
        'normalizedMatrix' => $normalizedMatrix,
        'weightedNormalizedMatrix' => $weightedNormalizedMatrix,
        'distancePositive' => $distancePositive,
        'distanceNegative' => $distanceNegative
      ]
    ];
  }

  private function getUserName($userId)
  {
    $stmt = $this->db->prepare("SELECT name FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['name'] ?? 'Unknown User';
  }

  public function saveRankingResults($results)
  {
    if (empty($results)) {
      return false;
    }
    $this->db->query("TRUNCATE TABLE ranking");
    $stmt = $this->db->prepare("INSERT INTO ranking (user_id, score, rank, calculated_at) VALUES (?, ?, ?, NOW())");
    if ($stmt === false) {
      error_log("Failed to prepare ranking insert: " . $this->db->error);
      return false;
    }
    foreach ($results as $row) {
      $stmt->bind_param("idi", $row['user_id'], $row['score'], $row['rank']);
      if (!$stmt->execute()) {
        error_log("Failed to insert ranking for user_id " . $row['user_id'] . ": " . $stmt->error);
      }
    }
    $stmt->close();
    return true;
  }
}
