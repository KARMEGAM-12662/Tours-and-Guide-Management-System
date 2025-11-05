<?php
session_start();
include('includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $whatsapp = $_POST['whatsapp'];
  $destination = $_POST['destination'];
  $travel_date = $_POST['travel_date'];
  $message = $_POST['message'];
  $captcha = $_POST['captcha'];

  if ($captcha != $_SESSION['captcha_answer']) {
    echo "<script>alert('Incorrect captcha. Please try again.'); window.history.back();</script>";
    exit();
  }

  $stmt = $conn->prepare("INSERT INTO enquiries (name, email, phone, whatsapp, destination, travel_date, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("sssssss", $name, $email, $phone, $whatsapp, $destination, $travel_date, $message);
  $stmt->execute();

  // Redirect to guide selection page
  header("Location: choose_guide.php");
  exit();
}
?>
