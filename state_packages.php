<?php
session_start();
include('includes/db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>State Packages</title>
<style>
body {
  font-family: Arial, sans-serif;
  background: #f8f9fa;
  margin: 0;
  padding: 0;
}
.container {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  width: 90%;
  margin: 30px auto;
  gap: 25px;
}
.left-section {
  flex: 2;
}
.right-section {
  flex: 1;
  position: sticky;
  top: 20px;
}
.package {
  background: white;
  margin-bottom: 25px;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}
.package img {
  width: 100%;
  max-height: 350px;
  object-fit: cover;
  border-radius: 12px;
}
.package h2 {
  margin-top: 10px;
  color: #007bff;
}
.itinerary-section {
  margin-top: 20px;
}
.itinerary-title {
  font-size: 20px;
  color: #333;
  margin-bottom: 10px;
}
.accordion {
  border-radius: 8px;
  overflow: hidden;
}
.accordion-item {
  border-bottom: 1px solid #ddd;
}
.accordion-header {
  background: #e9f2ff;
  padding: 12px 16px;
  cursor: pointer;
  transition: background 0.3s;
  font-weight: bold;
}
.accordion-header:hover {
  background: #d8e9ff;
}
.accordion-header.active {
  background: #007bff;
  color: white;
}
.accordion-content {
  display: none;
  padding: 15px;
  background: #fff;
  line-height: 1.6;
  border-left: 3px solid #007bff;
}

/* ===== Enquiry Form Styles ===== */
.enquiry-form {
  background: #eef5ff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}
.enquiry-form h3 {
  color: #0b4661;
  margin-bottom: 15px;
  text-align: center;
}
.enquiry-form label {
  display: block;
  margin-top: 10px;
  font-weight: bold;
  color: #333;
}
.enquiry-form input, .enquiry-form textarea, .enquiry-form select {
  width: 100%;
  padding: 10px;
  margin-top: 5px;
  border-radius: 8px;
  border: 1px solid #ccc;
  font-size: 14px;
}
.enquiry-form button {
  margin-top: 15px;
  padding: 10px 15px;
  background: #007bff;
  border: none;
  color: #fff;
  border-radius: 8px;
  cursor: pointer;
  transition: 0.3s;
  width: 100%;
  font-size: 16px;
}
.enquiry-form button:hover {
  background: #0056b3;
}
.captcha-box {
  display: flex;
  align-items: center;
  margin-top: 10px;
}
.captcha-text {
  background: #fff;
  border: 1px solid #ccc;
  padding: 8px 12px;
  font-weight: bold;
  margin-right: 10px;
  user-select: none;
  border-radius: 6px;
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
      <li><a href="logout.php">Logout</a></li>
      <?php else: ?>
      <li><a href="login.php">Login</a></li>
      <li><a href="register.php">Register</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>

<div class="container">
  <div class="left-section">
  <?php
  if (isset($_GET['state'])) {
    $state = $_GET['state'];
    $stmt = $conn->prepare("SELECT * FROM packages WHERE location = ?");
    $stmt->bind_param("s", $state);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      echo "<h2>Available Packages in " . htmlspecialchars($state) . "</h2>";
      while ($row = $result->fetch_assoc()) {
        echo "<div class='package'>";
        echo "<p><strong>Package No:</strong> " . htmlspecialchars($row['package_id']) . "</p>";
        echo "<img src='image/" . htmlspecialchars($row['image']) . "' alt='Package Image'>";
        echo "<h2>" . htmlspecialchars($row['title']) . "</h2>";
        echo "<p><strong>Location:</strong> " . htmlspecialchars($row['location']) . "</p>";
        echo "<p><strong>Duration:</strong> " . htmlspecialchars($row['duration']) . "</p>";
        echo "<p><strong>Price:</strong> ₹" . htmlspecialchars($row['price']) . "</p>";
        echo "<p>" . htmlspecialchars($row['short_desc']) . "</p>";
        echo "<p>" . htmlspecialchars($row['description']) . "</p>";

        // Itinerary Accordion
        $itinerary_stmt = $conn->prepare("SELECT * FROM package_itinerary WHERE package_id = ? ORDER BY day_number ASC");
        $itinerary_stmt->bind_param("i", $row['package_id']);
        $itinerary_stmt->execute();
        $itinerary_result = $itinerary_stmt->get_result();

        if ($itinerary_result->num_rows > 0) {
          echo "<div class='itinerary-section'>";
          echo "<h3 class='itinerary-title'>Itinerary</h3>";
          echo "<div class='accordion'>";
          while ($day = $itinerary_result->fetch_assoc()) {
            echo "<div class='accordion-item'>";
            echo "<div class='accordion-header'>Day " . htmlspecialchars($day['day_number']) . ": " . htmlspecialchars($day['day_title']) . "</div>";
            echo "<div class='accordion-content'>" . nl2br(htmlspecialchars($day['day_description'])) . "</div>";
            echo "</div>";
          }
          echo "</div></div>";
        } else {
          echo "<p><em>No itinerary details available for this package.</em></p>";
        }
        echo "</div>";
      }
    } else {
      echo "<p>No packages found for this state.</p>";
    }
  } else {
    echo "<p>No state selected.</p>";
  }
  ?>
  </div>

  <!-- ===== Enquiry Form Section (Right Side) ===== -->
  <div class="right-section">
    <div class="enquiry-form">
      <h3>Book Now</h3>
      <form id="enquiryForm" method="POST" action="choose_guide.php" onsubmit="return handleSubmit(event)">
        <label>Select Package Number:</label>
        <input type="number" name="package_id" id="package_id" placeholder="Enter package number" required>

        <label>Full Name:</label>
        <input type="text" name="name" required>

        <label>Email Address:</label>
        <input type="email" name="email" required>

        <label>Phone Number:</label>
        <input type="text" name="phone" pattern="\d{10}" maxlength="10" title="Enter exactly 10 digits" required>

        <label>WhatsApp Number:</label>
        <input type="text" name="whatsapp" pattern="\d{10}" maxlength="10" title="Enter exactly 10 digits" required>

        <label>Travel Date:</label>
        <input type="date" name="travel_date" required>

        <label>Number of Adults:</label>
        <select name="adults" required>
          <option value="">Select</option>
          <?php for($i=1;$i<=10;$i++){ echo "<option value='$i'>$i</option>"; } ?>
        </select>

        <label>Number of Children:</label>
        <select name="children" required>
          <option value="">Select</option>
          <?php for($i=0;$i<=10;$i++){ echo "<option value='$i'>$i</option>"; } ?>
        </select>

        <label>Message:</label>
        <textarea name="message" rows="4" placeholder="Enter your message..."></textarea>

        <label>Captcha:</label>
        <div class="captcha-box">
          <span class="captcha-text" id="captcha"></span>
        </div>
        <input type="text" id="captchaInput" placeholder="Enter captcha here" required>
        <input type="hidden" id="captchaHidden">

        <button type="submit">Submit</button>
      </form>
    </div>
  </div>
</div>

<footer class="site-footer">
  <div class="container">
    <p>&copy; <?= date('Y') ?> Tour and Guide | Contact: +91 98765 43210 | Email: tagms@gmail.com</p>
  </div>
</footer>

<script>
// Accordion
document.addEventListener("DOMContentLoaded", () => {
  const headers = document.querySelectorAll(".accordion-header");
  headers.forEach(header => {
    header.addEventListener("click", () => {
      const active = document.querySelector(".accordion-header.active");
      if (active && active !== header) {
        active.classList.remove("active");
        active.nextElementSibling.style.display = "none";
      }
      header.classList.toggle("active");
      const content = header.nextElementSibling;
      content.style.display = content.style.display === "block" ? "none" : "block";
    });
  });
});

// Captcha
function generateCaptcha() {
  const num1 = Math.floor(Math.random() * 10);
  const num2 = Math.floor(Math.random() * 10);
  const operator = ['+', '-', '*'][Math.floor(Math.random() * 3)];
  const question = `${num1} ${operator} ${num2}`;
  document.getElementById("captcha").textContent = question;
  document.getElementById("captchaHidden").value = eval(question);
}
function validateCaptcha() {
  const input = document.getElementById("captchaInput").value.trim();
  const answer = document.getElementById("captchaHidden").value.trim();
  if (input !== answer) {
    alert("❌ Incorrect captcha! Please try again.");
    generateCaptcha();
    return false;
  }
  return true;
}
window.onload = generateCaptcha;

// Handle submit with login check
function handleSubmit(e) {
  e.preventDefault();
  if (!validateCaptcha()) return false;

  const isLoggedIn = <?= isset($_SESSION['user']) ? 'true' : 'false' ?>;
  if (!isLoggedIn) {
    alert("Please login to continue.");
    const currentUrl = window.location.href; 
    window.location.href = "login.php?redirect=" + encodeURIComponent(currentUrl);
    return false;
  } else {
    document.getElementById("enquiryForm").submit();
  }
}
</script>

<style>
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
.site-footer {
  background: #0b4661;
  color: #fff;
  text-align: center;
  padding: 15px;
  margin-top: 40px;
}
</style>

</body>
</html>
