<?php
include('includes/db.php');
include('includes/header.php');

$pid = intval($_GET['id'] ?? 0);
if($pid <= 0){ echo "<p>Invalid package.</p>"; include('includes/footer.php'); exit; }

// fetch package
$stmt = $conn->prepare("SELECT * FROM packages WHERE package_id = ?");
$stmt->bind_param('i', $pid);
$stmt->execute();
$pkg = $stmt->get_result()->fetch_assoc();
$stmt->close();
if(!$pkg){ echo "<p>Package not found.</p>"; include('includes/footer.php'); exit; }

// get guides available (for simplicity: all guides are available; you can add mapping later)
$gstmt = $conn->prepare("SELECT g.guide_id,g.name,g.expertise,
    (SELECT IFNULL(AVG(r.rating),0) FROM reviews r WHERE r.guide_id = g.guide_id) AS avg_rating,
    (SELECT COUNT(*) FROM reviews r WHERE r.guide_id = g.guide_id) AS review_count
  FROM guides g");
$gstmt->execute(); $guides = $gstmt->get_result();

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book'])){
  if (!isset($_SESSION['user'])) {
    echo "<p>Please <a href='login.php'>login</a> to book.</p>";
  } else {
    $uid = $_SESSION['user']['user_id'];
    $date = $_POST['booking_date'];
    $guide_id = !empty($_POST['guide_id']) ? intval($_POST['guide_id']) : null;
    $q = $conn->prepare("INSERT INTO bookings (user_id, package_id, guide_id, booking_date) VALUES (?,?,?,?)");
    $q->bind_param('iiis', $uid, $pid, $guide_id, $date);
    if($q->execute()){
      echo "<p>Booking successful! We'll contact you soon.</p>";
    } else {
      echo "<p>Error: ".htmlspecialchars($conn->error)."</p>";
    }
    $q->close();
  }
}
?>

<article class="package-detail">
  <img src="assets/images/<?= htmlspecialchars($pkg['image'] ?: 'placeholder.jpg') ?>" alt="">
  <h2><?= htmlspecialchars($pkg['title']) ?></h2>
  <p><strong>Location:</strong> <?= htmlspecialchars($pkg['location']) ?></p>
  <p><strong>Duration:</strong> <?= htmlspecialchars($pkg['duration']) ?></p>
  <p><strong>Price:</strong> ₹<?= number_format($pkg['price'],2) ?></p>
  <p><?= nl2br(htmlspecialchars($pkg['description'])) ?></p>

  <h3>Available Guides</h3>
  <div class="guides">
    <?php while($g = $guides->fetch_assoc()): ?>
      <div class="guide-card">
        <h4><?= htmlspecialchars($g['name']) ?></h4>
        <p><?= htmlspecialchars($g['expertise']) ?></p>
        <p>Rating: <?= number_format($g['avg_rating'],1) ?> / 5 (<?= $g['review_count'] ?> reviews)</p>
      </div>
    <?php endwhile; ?>
  </div>

  <h3>Book this package</h3>
  <form method="POST">
    <label>Booking Date: <input type="date" name="booking_date" required></label><br>
    <label>Select Guide:
      <select name="guide_id">
        <option value="">-- No Preference --</option>
        <?php
        // re-run guides query to populate select (or reset result pointer)
        $g2 = $conn->query("SELECT guide_id,name FROM guides");
        while($gg = $g2->fetch_assoc()){
          echo "<option value='".intval($gg['guide_id'])."'>".htmlspecialchars($gg['name'])."</option>";
        }
        ?>
      </select>
    </label><br>
    <button type="submit" name="book">Book Now</button>
  </form>
</article>

<?php include('includes/footer.php'); ?>
