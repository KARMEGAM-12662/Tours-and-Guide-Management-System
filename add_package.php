<?php
session_start();
include('includes/db.php');

// Check if admin is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header('Location: login.php');
  exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_package'])) {
  $title = trim($_POST['title']);
  $location = trim($_POST['location']);
  $duration = trim($_POST['duration']);
  $price = trim($_POST['price']);
  $short_desc = trim($_POST['short_desc']);
  $description = trim($_POST['description']);
  
  $image = '';
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
      mkdir($target_dir, 0777, true);
    }
    $image = basename($_FILES['image']['name']);
    $target_file = $target_dir . $image;
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
  }

  if ($title && $location && $duration && $price && $short_desc && $description) {
    $stmt = $conn->prepare("INSERT INTO packages (title, location, duration, price, short_desc, description, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssisss", $title, $location, $duration, $price, $short_desc, $description, $image);
    if ($stmt->execute()) {
      $message = "✅ Package added successfully!";
    } else {
      $message = "❌ Error adding package.";
    }
    $stmt->close();
  } else {
    $message = "⚠️ Please fill all fields.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Package</title>
<style>
body { font-family: Arial; background: #f7f7f7; margin: 30px; }
form { background: white; padding: 20px; border-radius: 8px; width: 500px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
input, textarea { width: 100%; padding: 10px; margin: 8px 0; border-radius: 5px; border: 1px solid #ccc; }
button { background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
button:hover { background: #218838; }
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

<h2 style="text-align:center;">Add New Package</h2>
<form method="POST" enctype="multipart/form-data">
  <input type="text" name="title" placeholder="Package Title" required>
  <input type="text" name="location" placeholder="Location" required>
  <input type="text" name="duration" placeholder="Duration (e.g. 3 Days)" required>
  <input type="number" name="price" placeholder="Price (₹)" required>
  <textarea name="short_desc" placeholder="Short Description" required></textarea>
  <textarea name="description" placeholder="Full Description" required></textarea>
  <input type="file" name="image" accept="image/*" required>
  <button type="submit" name="add_package">Add Package</button>
</form>

<p><?= $message ?></p>
</body>
</html>
