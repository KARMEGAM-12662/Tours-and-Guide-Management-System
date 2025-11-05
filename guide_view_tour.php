<?php
session_start();
include('includes/db.php');

if (!isset($_GET['id'])) {
  die("Tour ID missing.");
}

$tour_id = $_GET['id'];
$sql = "SELECT * FROM tours WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tour_id);
$stmt->execute();
$result = $stmt->get_result();
$tour = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title>Tour Details</title>
<style>
body {
  font-family: Arial, sans-serif;
  background: #f2f2f2;
  padding: 20px;
}
.card {
  background: white;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  width: 60%;
  margin: auto;
}
h2 { color: #333; text-align: center; }
.details { margin-top: 20px; }
.details p { font-size: 18px; margin: 10px 0; }
.back-btn {
  display: inline-block;
  margin-top: 20px;
  background-color: #007bff;
  color: white;
  padding: 8px 16px;
  border-radius: 8px;
  text-decoration: none;
}
.back-btn:hover {
  background-color: #0056b3;
}
</style>
</head>
<body>
<div class="card">
  <h2><?php echo $tour['title']; ?></h2>
  <div class="details">
    <p><strong>State:</strong> <?php echo $tour['state']; ?></p>
    <p><strong>Start Date:</strong> <?php echo $tour['start_date']; ?></p>
    <p><strong>End Date:</strong> <?php echo $tour['end_date']; ?></p>
    <p><strong>Price:</strong> ₹<?php echo $tour['price']; ?></p>
  </div>
  <a href="guide_dashboard.php" class="back-btn">← Back to Dashboard</a>
</div>
</body>
</html>
