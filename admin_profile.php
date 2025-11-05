<?php
session_start();
include('includes/db.php');
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header('Location: login.php');
  exit;
}
$admin_id = $_SESSION['user']['user_id'];
$stmt = $conn->prepare("SELECT name, email FROM users WHERE user_id=?");
$stmt->bind_param('i', $admin_id);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Profile</title>
  <style>
    body {font-family: Arial; background:#f4f4f4; padding:20px;}
    h2 {color:#003366;}
    form {background:white; padding:20px; border-radius:10px; width:400px;}
    input {width:100%; padding:10px; margin:8px 0;}
    button {background:#00509e; color:white; padding:10px; border:none; border-radius:6px;}
  </style>
</head>
<body>
  <h2>⚙️ Admin Profile</h2>
  <form method="post" action="">
    <label>Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($admin['name']) ?>" required>
    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" required>
    <button type="submit" name="update">Update Profile</button>
  </form>
</body>
</html>
