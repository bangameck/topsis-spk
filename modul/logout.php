<?php
include '../_func/controlWeb.php';
session_destroy();
header("Location: " . base_url('login'));
exit;
