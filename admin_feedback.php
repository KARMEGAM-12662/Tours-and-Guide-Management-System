<?php
session_start();
include('includes/db.php');

// Check if admin is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header('Location: login.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - View Customer Feedback</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
      margin: 0;
      padding: 0;
    }
    header {
      background: #007bff;
      color: white;
      padding: 15px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    header h1 {
      margin: 0;
      font-size: 22px;
    }
    header a {
      color: white;
      text-decoration: none;
      background: #0056b3;
      padding: 8px 12px;
      border-radius: 5px;
      transition: 0.3s;
    }
    header a:hover {
      background: #004099;
    }
    table {
      width: 90%;
      margin: 20px auto;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 0 8px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: left;
    }
    th {
      background: #007bff;
      color: white;
    }
    tr:nth-child(even) {
      background: #f2f2f2;
    }
  </style>
</head>
<body>

<header>
  <a href="admin_dashboard.php">🏠 Home</a>
  <h1>Customer Feedback</h1>
  <div>Welcome, <b><?php echo htmlspecialchars($_SESSION['user']['name']); ?></b></div>
</header>
<br>
<button onclick="history.back()" 
style="
  background-color:#3498db;
  color:white;
  border:none;
  padding:10px 20px;
  border-radius:5px;
  cursor:pointer;
  margin-bottom:5px;
">
⬅️ Back
</button>


<table>
  <tr>
    <th>Feedback ID</th>
    <th>User Name</th>
    <th>Feedback</th>
    <th>Rating</th>
    <th>Date</th>
  </tr>

  <?php
  $sql = "
    SELECT f.feedback_id, u.name AS user_name, f.feedback_text, f.rating, f.created_at
    FROM feedback f
    JOIN users u ON f.user_id = u.user_id
    ORDER BY f.created_at DESC
  ";

  $result = $conn->query($sql);

  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo "<tr>
              <td>{$row['feedback_id']}</td>
              <td>" . htmlspecialchars($row['user_name']) . "</td>
              <td>" . htmlspecialchars($row['feedback_text']) . "</td>
              <td>{$row['rating']} ⭐</td>
              <td>{$row['created_at']}</td>
            </tr>";
    }
  } else {
    echo "<tr><td colspan='5' style='text-align:center;'>No feedback available</td></tr>";
  }
  ?>
</table>

</body>
</html>
