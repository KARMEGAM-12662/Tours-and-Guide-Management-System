<?php
session_start();
include('includes/db.php');
include('includes/header.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  echo "<div class='container mt-5'><div class='alert alert-danger'>Please login to book a guide.</div></div>";
  include('includes/footer.php');
  exit();
}

// Check if guide_id is provided
if (!isset($_GET['guide_id'])) {
  echo "<div class='container mt-5'><div class='alert alert-warning'>No guide selected.</div></div>";
  include('includes/footer.php');
  exit();
}

$guide_id = intval($_GET['guide_id']);

// Fetch guide details
$stmt = $conn->prepare("SELECT * FROM guides WHERE guide_id = ?");
$stmt->bind_param("i", $guide_id);
$stmt->execute();
$guide = $stmt->get_result()->fetch_assoc();

if (!$guide) {
  echo "<div class='container mt-5'><div class='alert alert-warning'>Guide not found.</div></div>";
  include('includes/footer.php');
  exit();
}
?>

<div class="container mt-5">
  <h2 class="text-center mb-4">Book Your Guide</h2>
  
  <div class="card p-4">
    <h4>Guide: <?php echo htmlspecialchars($guide['name']); ?></h4>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($guide['location']); ?></p>
    <p><strong>Rating:</strong> ⭐ <?php echo htmlspecialchars($guide['rating']); ?>/5</p>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Select Date</label>
        <input type="date" name="booking_date" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Message (optional)</label>
        <textarea name="message" class="form-control" rows="3"></textarea>
      </div>

      <button type="submit" name="book" class="btn btn-primary w-100">Confirm Booking</button>
    </form>
  </div>
</div>

<?php
if (isset($_POST['book'])) {
  $user_id = $_SESSION['user_id'];
  $booking_date = $_POST['booking_date'];
  $message = $_POST['message'];

  // Insert booking
  $insert = $conn->prepare("INSERT INTO bookings (user_id, guide_id, booking_date, message, status) VALUES (?, ?, ?, ?, 'Pending')");
  $insert->bind_param("iiss", $user_id, $guide_id, $booking_date, $message);
  
  if ($insert->execute()) {
    echo "<script>alert('Booking request sent to guide successfully!'); window.location='choose_guide.php';</script>";
    $notify = $conn->prepare("INSERT INTO notifications (guide_id, message) VALUES (?, ?)");
$msg = "New booking request from User ID $user_id for date $booking_date.";
$notify->bind_param("is", $guide_id, $msg);
$notify->execute();
  } else {
    echo "<div class='alert alert-danger mt-3'>Error: Could not send booking.</div>";
  }
}

include('includes/footer.php');
?>
