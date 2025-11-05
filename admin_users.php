<?php
session_start();
include('includes/db.php');
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header('Location: login.php');
  exit;
}
$result = $conn->query("SELECT user_id, name, email, role FROM users ORDER BY role");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Users</title>
  <style>
    body {font-family: Arial; background: #f4f4f4; padding: 20px;}
    h2 {color:#003366;}
    table {width: 100%; border-collapse: collapse; background: white;}
    th, td {padding: 12px; border-bottom: 1px solid #ddd;}
    th {background: #003366; color: white;}
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

  <h2>👥 Registered Users</h2>
  <table>
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['user_id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['role']) ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
