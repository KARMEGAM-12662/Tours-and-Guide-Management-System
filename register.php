<?php
include('includes/db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
  $role = $_POST['role'];
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $phone = trim($_POST['phone']);
  $pass = $_POST['password'];

  // ✅ Server-side validation
  if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $pass)) {
    echo "<script>alert('Password must be at least 8 characters long and include uppercase, lowercase, number, and special symbol.');</script>";
  } elseif (!preg_match('/^\d{10}$/', $phone)) {
    echo "<script>alert('Phone number must contain exactly 10 digits.');</script>";
  } else {
    $hashed = password_hash($pass, PASSWORD_DEFAULT);

    // ✅ Insert into users table
    $stmt = $conn->prepare("INSERT INTO users (role, name, email, password, phone) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('sssss', $role, $name, $email, $hashed, $phone);

    if ($stmt->execute()) {
      $user_id = $conn->insert_id; // Get the newly created user ID

      // ✅ If the registered user is a guide, insert into guides table
      if ($role === 'guide') {
        $experience = isset($_POST['experience']) ? intval($_POST['experience']) : 0;
        $location = isset($_POST['location']) ? trim($_POST['location']) : '';
        $default_pic = 'default.jpg';
        $rating = 0.0;

        $gstmt = $conn->prepare("INSERT INTO guides (name, email, phone, experience, location, rating, profile_pic, user_id)
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $gstmt->bind_param("sssisdsi", $name, $email, $phone, $experience, $location, $rating, $default_pic, $user_id);
        $gstmt->execute();
        $gstmt->close();

        // ✅ Redirect to guide dashboard
        echo "<script>alert('Guide Registered Successfully! Redirecting to dashboard...'); window.location='guide_dashboard.php';</script>";
      } else {
        // ✅ For normal users, redirect to login
        echo "<script>alert('User Registered Successfully! Please login now.'); window.location='login.php';</script>";
      }
    } else {
      echo "<script>alert('Error: Could not register. Try again later.');</script>";
    }

    $stmt->close();
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - Tour and Guide Management System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <style>
    /* ===== Reset ===== */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: url('banner1.jpeg') no-repeat center center/cover;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* ===== HEADER ===== */
    header {
      background: linear-gradient(90deg, #0b4661, #1a7ca5);
      padding: 15px 50px;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1000;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }

    nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo {
      color: #fff;
      font-size: 22px;
      font-weight: bold;
    }

    .nav-links {
      list-style: none;
      display: flex;
      gap: 20px;
    }

    .nav-links a {
      color: #fff;
      text-decoration: none;
      padding: 8px 14px;
      border-radius: 8px;
      transition: 0.3s;
    }

    .nav-links a:hover {
      background: #fff;
      color: #0b4661;
    }

    /* ===== FORM CONTAINER ===== */
    .register-container {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding-top: 80px;
      padding-bottom: 80px;
    }

    .register-box {
      background: rgba(255, 255, 255, 0.9);
      padding: 40px;
      border-radius: 15px;
      width: 350px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
      text-align: center;
      animation: fadeIn 0.8s ease;
    }

    .register-box h2 {
      margin-bottom: 20px;
      color: #0b4661;
      font-size: 26px;
    }

    .input-group {
      text-align: left;
      margin-bottom: 12px;
    }

    .input-group label {
      display: block;
      font-weight: 600;
      font-size: 14px;
      margin-bottom: 5px;
      color: #333;
    }

    .input-group input, .input-group select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
    }

    .btn {
      width: 100%;
      padding: 10px;
      background: #0b4661;
      border: none;
      color: #fff;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s;
    }

    .btn:hover {
      background: #1a7ca5;
    }

    .login-link {
      margin-top: 15px;
      font-size: 14px;
    }

    .login-link a {
      color: #0b4661;
      text-decoration: none;
      font-weight: 600;
    }

    /* ===== FOOTER ===== */
    .site-footer {
      background: #0b4661;
      color: #fff;
      text-align: center;
      padding: 15px;
      position: fixed;
      bottom: 0;
      width: 100%;
      font-size: 14px;
    }

    /* ===== Animation ===== */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* ===== Error Message ===== */
    .error {
      color: red;
      font-size: 13px;
      display: none;
    }
  </style>

  <script>
    // Client-side validation
    function validatePhone(input) {
      input.value = input.value.replace(/\D/g, '');
      if (input.value.length > 10) {
        input.value = input.value.slice(0, 10);
      }
    }

    function validatePasswordField() {
      const passwordField = document.getElementById('password');
      const error = document.getElementById('passwordError');
      const value = passwordField.value;

      const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

      if (value === '') {
        error.textContent = 'Password must be at least 8 characters long and include uppercase, lowercase, number, and special symbol.';
        error.style.display = 'block';
      } else if (!pattern.test(value)) {
        error.textContent = 'Invalid password format.';
        error.style.display = 'block';
      } else {
        error.style.display = 'none';
      }
    }
  </script>

</head>
<body>

  <!-- ===== HEADER ===== -->
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
          <li><a href="register.php" style="background:#fff; color:#0b4661;">Register</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>

  <!-- ===== REGISTER SECTION ===== -->
  <div class="register-container">
    <div class="register-box">
      <h2>Create Your Account</h2>
      <form method="POST">

        <div class="input-group">
          <label for="role">Role</label>
          <select name="role" id="role" required>
            <option value="user" selected>User</option>
            <option value="guide">Guide</option>
          </select>
        </div>

        <div class="input-group">
          <label for="name">Full Name</label>
          <input name="name" id="name" required placeholder="Enter your full name">
        </div>

        <div class="input-group">
          <label for="email">Email</label>
          <input name="email" id="email" type="email" required placeholder="Enter your email">
        </div>

        <div class="input-group">
          <label for="password">Password</label>
          <input name="password" id="password" type="password" required placeholder="Create a password" onblur="validatePasswordField()">
          <span id="passwordError" class="error"></span>
        </div>

        <div class="input-group">
          <label for="phone">Phone</label>
          <input name="phone" id="phone" required placeholder="Enter 10-digit phone number" oninput="validatePhone(this)">
        </div>

        <!-- Guide-specific fields -->
        <div class="input-group guide-field" style="display:none;">
         <label for="experience">Experience (in years)</label>
         <input type="number" name="experience" id="experience" min="0" placeholder="Enter years of experience">
        </div>

        <div class="input-group guide-field" style="display:none;">
          <label for="location">Location / State</label>
          <input type="text" name="location" id="location" placeholder="Enter your work location">
        </div>

        <button name="register" class="btn">Register</button>
        <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
      </form>
    </div>
  </div>

  <!-- ===== FOOTER ===== -->
  <footer class="site-footer">
    <p>&copy; <?= date('Y') ?> Tour and Guide | Contact: +91 98765 43210 | Email: tagms@gmail.com</p>
  </footer>

</body>
<script>
document.getElementById('role').addEventListener('change', function() {
  const guideFields = document.querySelectorAll('.guide-field');
  if (this.value === 'guide') {
    guideFields.forEach(f => f.style.display = 'block');
  } else {
    guideFields.forEach(f => f.style.display = 'none');
  }
});
</script>
</html>
