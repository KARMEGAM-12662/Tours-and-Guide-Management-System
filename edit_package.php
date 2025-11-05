<?php
session_start();
include('includes/db.php');

// Check admin login
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header('Location: login.php');
  exit;
}

if (!isset($_GET['id'])) {
  header('Location: admin_packages.php');
  exit;
}

$id = intval($_GET['id']);
$message = "";

// Fetch existing package
$stmt = $conn->prepare("SELECT * FROM packages WHERE package_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$package = $result->fetch_assoc();
$stmt->close();

if (!$package) {
  die("Package not found!");
}

// Update package
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_package'])) {
  $title = trim($_POST['title']);
  $location = trim($_POST['location']);
  $duration = trim($_POST['duration']);
  $price = trim($_POST['price']);
  $short_desc = trim($_POST['short_desc']);
  $description = trim($_POST['description']);
  
  $image = $package['image'];
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
    $image = basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image);
  }

  $stmt = $conn->prepare("UPDATE packages SET title=?, location=?, duration=?, price=?, short_desc=?, description=?, image=? WHERE package_id=?");
  $stmt->bind_param("sssisssi", $title, $location, $duration, $price, $short_desc, $description, $image, $id);

  if ($stmt->execute()) {
    $message = "✅ Package updated successfully!";
    header("Location: admin_packages.php");
    exit;
  } else {
    $message = "❌ Error updating package.";
  }
  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Package</title>
<style>
body { font-family: Arial; background: #f7f7f7; margin: 30px; }
form { background: white; padding: 20px; border-radius: 8px; width: 500px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
input, textarea { width: 100%; padding: 10px; margin: 8px 0; border-radius: 5px; border: 1px solid #ccc; }
button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
button:hover { background: #0056b3; }
img { width: 100px; height: auto; margin-top: 10px; border-radius: 5px; }
p { text-align: center; color: #333; }
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

<h2 style="text-align:center;">Edit Package</h2>
<form method="POST" enctype="multipart/form-data">
  <input type="text" name="title" value="<?= htmlspecialchars($package['title']) ?>" required>
  <input type="text" name="location" value="<?= htmlspecialchars($package['location']) ?>" required>
  <input type="text" name="duration" value="<?= htmlspecialchars($package['duration']) ?>" required>
  <input type="number" name="price" value="<?= htmlspecialchars($package['price']) ?>" required>
  <textarea name="short_desc" required><?= htmlspecialchars($package['short_desc']) ?></textarea>
  <textarea name="description" required><?= htmlspecialchars($package['description']) ?></textarea>
  <label>Current Image:</label><br>
  <img src="uploads/<?= htmlspecialchars($package['image']) ?>" alt="Current Image">
  <input type="file" name="image" accept="image/*">
  <button type="submit" name="update_package">Update Package</button>
</form>

<p><?= $message ?></p>
</body>
</html>
