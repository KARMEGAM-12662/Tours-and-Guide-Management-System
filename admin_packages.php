<?php
session_start();
include('includes/db.php');
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header('Location: login.php');
  exit;
}

// Fetch all packages
$packages = $conn->query("SELECT * FROM packages ORDER BY package_id DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Packages</title>
  <style>
    body {font-family: Arial; background: #f4f4f4; padding: 20px;}
    h2 {color:#003366;}
    a.add {background: #00509e; color:white; padding:8px 12px; border-radius:6px; text-decoration:none;}
    table {width:100%; border-collapse: collapse; margin-top: 20px; background: white;}
    th, td {padding: 10px; border-bottom: 1px solid #ddd;}
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

  <h2>🧳 Manage Packages</h2>
  <a href="add_package.php" class="add">➕ Add Package</a>

  <table>
    <tr>
      <th>ID</th><th>Name</th><th>State</th><th>Price</th><th>Duration</th><th>Action</th>
    </tr>
    <?php while ($p = $packages->fetch_assoc()): ?>
      <tr>
        <td><?= $p['package_id'] ?></td>
        <td><?= htmlspecialchars($p['title']) ?></td>
        <td><?= htmlspecialchars($p['location']) ?></td>
        <td><?= htmlspecialchars($p['price']) ?></td>
        <td><?= htmlspecialchars($p['duration']) ?></td>
        <td>
          <a href="edit_package.php?id=<?= $p['package_id'] ?>">✏️ Edit</a> |
          <a href="delete_package.php?id=<?= $p['package_id'] ?>" onclick="return confirm('Delete this package?')">🗑️ Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</body>
</html>
