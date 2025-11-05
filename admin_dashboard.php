<?php
session_start();
include('includes/db.php');

// Redirect if not admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header('Location: login.php');
  exit;
}

$admin_name = $_SESSION['user']['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - Tour & Guide System</title>
<style>
body {
  margin: 0;
  font-family: Arial, sans-serif;
  background: #f4f6f8;
}

/* Sidebar */
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  width: 230px;
  height: 100%;
  background: #2c3e50;
  padding-top: 20px;
  color: white;
}

.sidebar h2 {
  text-align: center;
  margin-bottom: 30px;
  font-size: 22px;
}

.sidebar a {
  display: block;
  color: white;
  padding: 12px 20px;
  text-decoration: none;
  transition: background 0.3s;
  font-size: 16px;
}

.sidebar a:hover {
  background: #34495e;
}

/* Home button */
.sidebar .home-btn {
  background: #1abc9c;
  color: white;
  font-weight: bold;
  border-radius: 5px;
  margin: 0 20px 20px 20px;
  text-align: center;
}
.sidebar .home-btn:hover {
  background: #16a085;
}

/* Top bar */
.topbar {
  margin-left: 230px;
  height: 60px;
  background: #fff;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 20px;
}

.topbar h1 {
  font-size: 22px;
  color: #333;
}

.profile {
  position: relative;
  display: inline-block;
}

.profile span {
  font-weight: bold;
  cursor: pointer;
  color: #2c3e50;
}

.profile-dropdown {
  display: none;
  position: absolute;
  right: 0;
  background: white;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  border-radius: 5px;
  overflow: hidden;
}

.profile-dropdown a {
  display: block;
  color: #333;
  text-decoration: none;
  padding: 10px 15px;
  transition: background 0.3s;
}

.profile-dropdown a:hover {
  background: #f1f1f1;
}

.profile:hover .profile-dropdown {
  display: block;
}

/* Main content */
.main-content {
  margin-left: 230px;
  padding: 20px;
}

.card-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
  gap: 20px;
}

.card {
  background: white;
  padding: 20px;
  border-radius: 8px;
  text-align: center;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  transition: transform 0.2s;
}

.card:hover {
  transform: translateY(-5px);
}

.card a {
  text-decoration: none;
  color: #333;
  font-weight: bold;
}

.card i {
  font-size: 35px;
  color: #007bff;
  margin-bottom: 10px;
}
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <a href="index.php" class="home-btn">🏠 Home</a>
  <h2>Admin Panel</h2>
  <a href="packages.php">Packages</a>
  <?php if(isset($_SESSION['user'])): ?>
  <a href="#">Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?></a>
  <a href="🚪logout.php">Logout</a>
  <?php else: ?>
  <a href="login.php">Login</a>
  <a href="register.php">Register</a>
  <?php endif; ?>
  
</div>

<!-- Topbar -->
<div class="topbar">
  <h1>Welcome, Admin Dashboard</h1>
  <div class="profile">
    <span>👤 <?= htmlspecialchars($admin_name) ?></span>
    <div class="profile-dropdown">
      <a href="admin_feedback.php">💬 Feedback</a>
      <a href="logout.php">🚪 Logout</a>
    </div>
  </div>
</div>

<!-- Main Content -->
<div class="main-content">
  <h2>Quick Access</h2>
  <div class="card-container">
    <div class="card">
      <i>🧍</i>
      <a href="admin_users.php">View Registered Users</a>
    </div>
    <div class="card">
      <i>🧳</i>
      <a href="admin_packages.php">Manage Packages</a>
    </div>
    <div class="card">
      <i>📦</i>
      <a href="admin_bookings.php">View All Bookings</a>
    </div>
    <div class="card">
      <i>💬</i>
      <a href="admin_feedback.php">Customer Feedback</a>
    </div>
  </div>
</div>

</body>
</html>
