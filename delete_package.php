<?php
session_start();
include('includes/db.php');

// Check admin login
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header('Location: login.php');
  exit;
}

if (isset($_GET['id'])) {
  $id = intval($_GET['id']);

  // Delete the package
  $stmt = $conn->prepare("DELETE FROM packages WHERE package_id = ?");
  $stmt->bind_param("i", $id);
  if ($stmt->execute()) {
    header("Location: admin_packages.php?msg=deleted");
  } else {
    header("Location: admin_packages.php?msg=error");
  }
  $stmt->close();
} else {
  header("Location: admin_packages.php");
}
exit;
?>
