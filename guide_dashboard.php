<?php
session_start();
include('includes/db.php');

// Redirect if not logged in or not a guide
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'guide') {
  header('Location: login.php');
  exit;
}

// Get guide_id using user_id (since user logged in as guide)
$user_id = $_SESSION['user']['user_id'];

// ✅ Fetch guide details (with all columns)
$sql = "SELECT id AS guide_id, name, email, phone, experience, location, rating, profile_pic 
        FROM guides 
        WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$guide = $result->fetch_assoc();

// ✅ If guide not found, show default placeholder
if (!$guide) {
  $guide = [
    'guide_id' => 0,
    'name' => 'Unknown Guide',
    'email' => 'Not available',
    'phone' => 'Not available',
    'experience' => 'Not updated',
    'location' => 'Unknown',
    'rating' => 'N/A',
    'profile_pic' => 'default.jpg'
  ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Guide Dashboard | Tour & Guide System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(120deg, #c2e9fb 0%, #a1c4fd 100%);
  margin: 0;
  padding: 0;
  color: #333;
}
header {
  background: linear-gradient(90deg, #0b4661, #1a7ca5);
  padding: 15px 50px;
}
nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.logo {
  flex: 1;
  text-align: center;
  font-size: 28px;
  font-weight: bold;
  color: #fff;
  letter-spacing: 1px;
}
.nav-links {
  display: flex;
  justify-content: flex-end;
  gap: 25px;
  list-style: none;
  margin: 0;
  padding: 0;
}
.nav-links li a {
  color: #ffffff;
  text-decoration: none;
  font-weight: 500;
  background: rgba(255, 255, 255, 0.15);
  padding: 8px 14px;
  border-radius: 8px;
  transition: 0.3s;
}
.nav-links li a:hover {
  background: #fff;
  color: #0b4661;
}
.container {
  margin-top: 70px;
  max-width: 1000px;
}
.profile-card {
  background: #fff;
  border-radius: 15px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  padding: 30px 40px;
  margin-bottom: 30px;
  text-align: center;
}
.profile-card img {
  width: 130px;
  height: 130px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #004aad;
  margin-bottom: 15px;
}
.profile-card h2 {
  color: #004aad;
  font-weight: 600;
  margin-bottom: 10px;
}
.profile-card p {
  font-size: 16px;
  margin: 6px 0;
}
.logout-btn {
  background-color: #e63946;
  color: white;
  padding: 8px 18px;
  border: none;
  border-radius: 8px;
  font-weight: 500;
  text-decoration: none;
  transition: 0.3s;
}
.logout-btn:hover {
  background-color: #b71c1c;
}
.section-title {
  color: #004aad;
  text-align: center;
  margin-top: 30px;
  font-weight: 600;
}
.table-container {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  padding: 25px;
  margin-top: 20px;
}
th, td {
  padding: 12px 10px;
  text-align: center;
  border-bottom: 1px solid #ddd;
}
th {
  background-color: #004aad;
  color: white;
}
tr:hover {
  background-color: #f1f1f1;
  transition: 0.2s;
}
.btn {
  background-color: #007bff;
  color: white;
  padding: 6px 12px;
  border-radius: 6px;
  text-decoration: none;
  font-weight: bold;
  transition: 0.3s;
}
.btn:hover {
  background-color: #0056b3;
}
footer {
  background-color: #004aad;
  color: white;
  text-align: center;
  padding: 10px;
  margin-top: 40px;
}
</style>
</head>
<body>

<header>
  <nav>
    <div class="logo">Tour and Guide Management System</div>
    <ul class="nav-links">
      <li><a href="index.php">Home</a></li>
      <li><a href="packages.php">Packages</a></li>
      <?php if(isset($_SESSION['user'])): ?>
      <li><a href="#">Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?></a></li>
      <li><a href="logout.php" class="logout-btn">Logout</a></li>
      <?php else: ?>
      <li><a href="login.php">Login</a></li>
      <li><a href="register.php">Register</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>

<div class="container">
  <!-- ✅ Profile Section -->
  <div class="profile-card">
    <img src="uploads/<?= htmlspecialchars($guide['profile_pic']) ?>" alt="Profile Picture">
    <h2><?= htmlspecialchars($guide['name']) ?></h2>
    <p><strong>Email:</strong> <?= htmlspecialchars($guide['email']) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($guide['phone']) ?></p>
    <p><strong>Experience:</strong> <?= htmlspecialchars($guide['experience']) ?> years</p>
    <p><strong>Location:</strong> <?= htmlspecialchars($guide['location']) ?></p>
    <p><strong>Rating:</strong> ⭐ <?= htmlspecialchars($guide['rating']) ?>/5</p>
  </div>

  <!-- ✅ Assigned Tours Section -->
  <h3 class="section-title">Assigned Tours</h3>
  <div class="table-container">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>Tour ID</th>
          <th>Tour Title</th>
          <th>State</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Price (₹)</th>
          <th>Assigned On</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT t.id AS tour_id, t.title, t.state, t.start_date, t.end_date, t.price, ga.assigned_date
                FROM guide_assignments ga
                INNER JOIN tours t ON ga.tour_id = t.id
                WHERE ga.guide_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $guide['guide_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['tour_id']}</td>
                    <td>{$row['title']}</td>
                    <td>{$row['state']}</td>
                    <td>{$row['start_date']}</td>
                    <td>{$row['end_date']}</td>
                    <td>₹{$row['price']}</td>
                    <td>{$row['assigned_date']}</td>
                  </tr>";
          }
        } else {
          echo "<tr><td colspan='7' class='text-muted text-center'>No tours assigned yet.</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<footer>
  <p>© <?= date('Y') ?> Tour & Guide Management System | All Rights Reserved</p>
</footer>

</body>
</html>
