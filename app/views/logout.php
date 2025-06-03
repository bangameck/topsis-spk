<?php
include_once __DIR__ . '/../../config/controlWeb.php';
session_destroy();
session_start();
toastNotif('warning', 'Anda telah keluar dari sistem, Silahkan Login kembali untuk mengakses Sistem.!');
header("Location: " . base_url('login'));
exit;
