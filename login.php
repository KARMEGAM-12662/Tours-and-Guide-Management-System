<?php
session_start();
include('includes/db.php');

$error = "";
$role_selected = "";
$email_value = "";

// ✅ Match roles exactly as in your database
$allowed_roles = ['user', 'guide', 'admin'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
  $role = isset($_POST['role']) ? trim($_POST['role']) : '';
  $email = isset($_POST['email']) ? trim($_POST['email']) : '';
  $pass = isset($_POST['password']) ? $_POST['password'] : '';

  $role_selected = htmlspecialchars($role);
  $email_value = htmlspecialchars($email);

  if (empty($role) || !in_array($role, $allowed_roles)) {
    $error = "Please select a valid role.";
  } elseif (empty($email)) {
    $error = "Please enter your email address.";
  } elseif (empty($pass)) {
    $error = "Please enter your password.";
  } else {
    // ✅ Fetch user record
    $stmt = $conn->prepare("SELECT user_id, name, email, password, role FROM users WHERE email = ? AND role = ? LIMIT 1");
    if ($stmt) {
      $stmt->bind_param('ss', $email, $role);
      $stmt->execute();
      $result = $stmt->get_result();
      $user = $result ? $result->fetch_assoc() : null;
      $stmt->close();

      // ✅ Verify password using password_verify
      if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user'] = [
          'user_id' => $user['user_id'],
          'name' => $user['name'],
          'email' => $user['email'],
          'role' => $user['role']
        ];

        // ✅ Redirect based on role
        $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';

        if ($user['role'] === 'admin') {
          header('Location: admin_dashboard.php');
        } elseif ($user['role'] === 'guide') {
          header('Location: guide_dashboard.php');
        } else {
          header('Location: ' . $redirect);
        }
        exit;
      } else {
        $error = "Invalid email or password.";
      }
    } else {
      $error = "Server error. Please try again later.";
    }
  }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Login - Tour and Guide Management System</title>
  <style>
    body { margin:0; font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; background: url('banner3.jpeg') no-repeat center center/cover; color:#222; }
    header {
      background: linear-gradient(90deg, #0b4661, #1a7ca5);
      padding: 15px 30px;
    }
    nav { display:flex; align-items:center; justify-content:space-between; gap:20px; flex-wrap:wrap; }
    .logo { font-size:22px; font-weight:700; color:#fff; text-align:center; flex:1; }
    .nav-links { display:flex; gap:12px; list-style:none; padding:0; margin:0; }
    .nav-links li a {
      color:#fff; text-decoration:none; background: rgba(255,255,255,0.12);
      padding:8px 12px; border-radius:8px; transition: all .22s;
    }
    .nav-links li a:hover { background:#fff; color:#0b4661; transform:translateY(-2px); }

    .login-page {
      min-height: calc(100vh - 90px);
      display:flex; align-items:center; justify-content:center;
      background: url('photo-1526772662000-3f88f10405ff.jpeg') no-repeat center center/cover;
      padding:40px 20px;
    }
    .login-overlay { width:100%; display:flex; align-items:center; justify-content:center; }

    .login-box {
      background: rgba(255,255,255,0.97);
      padding: 36px 40px; border-radius:16px;
      width: 380px; max-width:95%;
      box-shadow: 0 12px 30px rgba(0,0,0,0.25);
      text-align:left;
      animation: fadeIn .6s ease;
    }
    .login-box h2 { color:#0b4661; margin:0 0 6px; font-size:22px; }
    .subtitle { font-size:13px; color:#666; margin-bottom:18px; }

    .input-group { margin-bottom:14px; position:relative; }
    .input-group label {
      display:block; margin-bottom:6px; color:#333; font-weight:600; font-size:13px;
    }
    .input-group input, .input-group select {
      width:100%; padding:10px 12px; font-size:15px;
      border:1px solid #d0d6dc; border-radius:8px;
      transition: box-shadow .18s ease, border-color .18s ease, transform .12s ease;
      background:#fff;
    }
    .input-group input:hover, .input-group select:hover {
      transform: translateY(-3px);
      border-color: #80bfff;
      box-shadow: 0 6px 20px rgba(0,123,255,0.12);
    }
    .input-group input:focus, .input-group select:focus {
      outline: none;
      border-color: #007bff;
      box-shadow: 0 6px 24px rgba(0,123,255,0.16);
    }

    .btn-login {
      width:100%; padding:12px; border-radius:10px; border:none; cursor:pointer;
      background: linear-gradient(90deg,#007bff,#00c6ff); color:#fff; font-weight:700; font-size:15px;
      transition: transform .12s ease, box-shadow .12s ease;
    }
    .btn-login:hover { transform: translateY(-3px); box-shadow: 0 8px 26px rgba(0,0,0,0.16); }

    .register-link { margin-top:12px; font-size:13px; color:#444; }
    .register-link a { color:#007bff; text-decoration:none; font-weight:600; }

    .error-box {
      background: #ffefef; color:#8b0000; border:1px solid #f1c0c0;
      border-radius:8px; padding:10px 12px; margin-top:12px; font-size:14px;
    }

    .site-footer { background:#0b4661; color:#fff; text-align:center; padding:12px 8px; margin-top:20px; font-size:14px; }
    @keyframes fadeIn { from { opacity:0; transform:scale(.98); } to { opacity:1; transform:scale(1); } }
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
        <li><a href="login.php" style="background:#fff;color:#0b4661;">Login</a></li>
        <li><a href="register.php">Register</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>

<main class="login-page">
  <div class="login-overlay">
    <div class="login-box">
      <center><h2>Welcome Back 👋</h2></center>
      <form method="POST" class="login-form" novalidate>
        <div class="input-group">
          <label for="role">Login As</label>
          <select id="role" name="role" required>
            <option value="">-- Select Role --</option>
            <option value="user" <?= $role_selected === 'user' ? 'selected' : '' ?>>User</option>
            <option value="guide" <?= $role_selected === 'guide' ? 'selected' : '' ?>>Guide</option>
            <option value="admin" <?= $role_selected === 'admin' ? 'selected' : '' ?>>Admin</option>
          </select>
        </div>

        <div class="input-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" placeholder="Enter your email" required value="<?= $email_value ?>">
        </div>

        <div class="input-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>

        <button name="login" type="submit" class="btn-login">Login</button>

        <p class="register-link">
          Don't have an account? <a href="register.php">Register here</a>
        </p>

        <?php if (!empty($error)): ?>
          <div class="error-box"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
      </form>
    </div>
  </div>
</main>

<footer class="site-footer">
  <p>&copy; <?= date('Y') ?> Tour and Guide | Contact: +91 98765 43210 | Email: tagms@gmail.com</p>
</footer>

</body>
</html>
