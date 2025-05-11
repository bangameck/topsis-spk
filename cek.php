<?php

function checkUploadPermissions($uploadPath)
{
  // Path absolut
  $absolutePath = __DIR__ . '/' . $uploadPath;

  // Cek apakah folder ada
  if (!file_exists($absolutePath)) {
    echo "Folder tidak ditemukan: $absolutePath\n";
    return false;
  }

  // Cek apakah folder dapat ditulis
  if (!is_writable($absolutePath)) {
    echo "Folder tidak dapat ditulis: $absolutePath\n";
    return false;
  }

  echo "Folder dapat ditulis: $absolutePath\n";
  return true;
}

// Uji folder upload
$uploadPath = 'assets/img/profile'; // Ganti dengan folder tujuanmu
checkUploadPermissions($uploadPath);
