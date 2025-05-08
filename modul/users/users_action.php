<?php
session_start();
require_once __DIR__ . '_func/controlWeb.php';
// require_once __DIR__ . '/../../includes/auth_validate.php';

// Only admin can access this page
if ($_SESSION['level'] != 1) {
  header('HTTP/1.1 401 Unauthorized');
  exit();
}

// Get action and ID from URL
$action = $_GET['action'] ?? '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle actions
switch ($action) {
  case 'add':
    handleAddUser();
    break;
  case 'edit':
    handleEditUser($id);
    break;
  case 'delete':
    handleDeleteUser($id);
    break;
  default:
    header("HTTP/1.0 404 Not Found");
    exit('Invalid action');
}
