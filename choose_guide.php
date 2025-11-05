<?php
session_start();
include('includes/db.php');

// Redirect if not logged in
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}

// ✅ Handle booking when user clicks Book Now
if (isset($_GET['guide_id']) && isset($_SESSION['booking_data'])) {
  $guide_id = intval($_GET['guide_id']);
  $user_id = $_SESSION['user']['id'];

  // Retrieve booking data from session (from state_packages.php)
  $data = $_SESSION['booking_data'];
  $package_id = $data['package_id'];
  $travel_date = $data['travel_date'];
  $adults = $data['adults'];
  $children = $data['children'];
  $message = $data['message'];

  // Insert booking into database
  $stmt = $conn->prepare("INSERT INTO bookings 
    (user_id, guide_id, package_id, travel_date, adults, children, message, status) 
    VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')");
  $stmt->bind_param("iiisiss", $user_id, $guide_id, $package_id, $travel_date, $adults, $children, $message);
  $stmt->execute();

  // Optional: Insert notification for guide
  $msg = "You have a new booking request from user ID $user_id.";
  $notify = $conn->prepare("INSERT INTO notifications (guide_id, message, created_at) VALUES (?, ?, NOW())");
  $notify->bind_param("is", $guide_id, $msg);
  $notify->execute();

  echo "<script>alert('✅ Booking sent successfully to the guide!'); window.location='guide_dashboard.php';</script>";
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Choose Guide | Tour & Guide Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
      padding-top: 90px;
      font-family: "Poppins", sans-serif;
    }
    header {
      background: linear-gradient(90deg, #0b4661, #1a7ca5);
      padding: 15px 50px;
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1000;
      box-shadow: 0 3px 10px rgba(0,0,0,0.2);
    }
    .logo {
      color: #fff;
      font-weight: bold;
      font-size: 24px;
    }
    .nav-links { list-style: none; display: flex; gap: 20px; }
    .nav-links a { color: #fff; text-decoration: none; padding: 8px 14px; border-radius: 8px; transition: 0.3s; }
    .nav-links a:hover { background: #fff; color: #0b4661; }
    .site-footer { background: #0b4661; color: #fff; text-align: center; padding: 10px; margin-top: 40px; }
    .card { border-radius: 12px; overflow: hidden; transition: 0.3s; }
    .card:hover { transform: scale(1.03); box-shadow: 0 4px 10px rgba(0,0,0,0.15); }
    .btn-success { background: #007bff; border: none; }
    .btn-success:hover { background: #0056b3; }
  </style>
</head>
<body>
<header>
  <nav class="d-flex justify-content-between align-items-center">
    <div class="logo">Tour and Guide Management System</div>
    <ul class="nav-links m-0">
      <li><a href="index.php">Home</a></li>
      <li><a href="packages.php">Packages</a></li>
      <?php if(isset($_SESSION['user'])): ?>
        <li><a href="#">Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?></a></li>
        <li><a href="logout.php">Logout</a></li>
      <?php else: ?>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>

<div class="container">
  <h2 class="text-center mb-4 fw-bold text-primary">Choose Your Guide</h2>

  <!-- Search -->
  <form method="GET" class="d-flex justify-content-center mb-4">
    <input type="text" name="search" class="form-control w-50 shadow-sm" placeholder="Search guides by name or location"
      value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <button type="submit" class="btn btn-primary ms-2 px-4">Search</button>
  </form>

  <?php
  $search = isset($_GET['search']) ? trim($_GET['search']) : '';
  if ($search) {
    $query = "SELECT * FROM guides WHERE name LIKE ? OR location LIKE ?";
    $stmt = $conn->prepare($query);
    $like = "%$search%";
    $stmt->bind_param("ss", $like, $like);
  } else {
    $stmt = $conn->prepare("SELECT * FROM guides");
  }

  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    echo '<div class="row">';
    while ($guide = $result->fetch_assoc()) {
      $image = !empty($guide['profile_pic']) ? htmlspecialchars($guide['profile_pic']) : "uploads/default.jpg";
  ?>
    <div class="col-md-4 mb-4">
      <div class="card shadow-sm">
        <img src="<?php echo $image; ?>" class="card-img-top" alt="Guide Photo" style="height:250px; object-fit:cover;">
        <div class="card-body">
          <h5 class="card-title text-center text-primary fw-bold"><?php echo htmlspecialchars($guide['name']); ?></h5>
          <p class="card-text small text-muted">
            <strong>📍 Location:</strong> <?php echo htmlspecialchars($guide['location']); ?><br>
            <strong>📧 Email:</strong> <?php echo htmlspecialchars($guide['email']); ?><br>
            <strong>📞 Phone:</strong> <?php echo htmlspecialchars($guide['phone']); ?><br>
            <strong>⭐ Rating:</strong> <?php echo htmlspecialchars($guide['rating']); ?>/5<br>
            <strong>🎓 Experience:</strong> <?php echo htmlspecialchars($guide['experience']); ?> years
          </p>
          <a href="choose_guide.php?id=<?php echo $guide['id']; ?>" class="btn btn-success w-100">Book Now</a>
        </div>
      </div>
    </div>
  <?php
    }
    echo '</div>';
  } else {
    echo "<p class='text-center text-muted'>No guides found.</p>";
  }

  $stmt->close();
  $conn->close();
  ?>
</div>

<footer class="site-footer">
  <p>&copy; <?= date('Y') ?> Tour and Guide | Contact: +91 98765 43210 | Email: tagms@gmail.com</p>
</footer>
</body>
</html>
