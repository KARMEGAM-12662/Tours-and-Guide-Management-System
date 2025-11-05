<?php
session_start();
include('includes/db.php');
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header('Location: login.php');
  exit;
}
$bookings = $conn->query("SELECT * FROM bookings ORDER BY booking_id DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Bookings</title>
  <style>
    body {font-family: Arial; background:#f4f4f4; padding:20px;}
    h2 {color:#003366;}
    table {width:100%; border-collapse:collapse; background:white;}
    th,td {padding:10px; border-bottom:1px solid #ddd;}
    th {background:#003366; color:white;}
  </style>
</head>
<body>
<button onclick="history.back()" 
style="
  background-color:#3498db;
  color:white;
  border:none;
  padding:10px 20px;
  border-radius:5px;
  cursor:pointer;
  margin-bottom:15px;
">
⬅️ Back
</button>

  <h2>📅 All Bookings</h2>
  <table>
    <tr><th>ID</th><th>User ID</th><th>Package ID</th><th>Status</th><th>Action</th></tr>
    <?php while($b=$bookings->fetch_assoc()): ?>
      <tr>
        <td><?= $b['booking_id'] ?></td>
        <td><?= $b['user_id'] ?></td>
        <td><?= $b['package_id'] ?></td>
        <td><?= htmlspecialchars($b['status']) ?></td>
        <td>
          <a href="update_booking.php?id=<?= $b['booking_id'] ?>&status=confirmed">✅ Confirm</a> |
          <a href="update_booking.php?id=<?= $b['booking_id'] ?>&status=cancelled">❌ Cancel</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
