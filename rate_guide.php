<?php
include('includes/db.php');
include('includes/header.php');
if(empty($_SESSION['user'])) { echo "<p>Please <a href='login.php'>login</a></p>"; include('includes/footer.php'); exit; }
$uid = $_SESSION['user']['user_id'];
$guide_id = intval($_GET['guide_id'] ?? 0);

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rate'])){
  $g = intval($_POST['guide_id']);
  $rating = intval($_POST['rating']);
  $comments = trim($_POST['comments']);
  if($rating >=1 && $rating <=5){
    $stmt = $conn->prepare("INSERT INTO reviews (guide_id,user_id,rating,comments) VALUES (?,?,?,?)");
    $stmt->bind_param('iiis',$g,$uid,$rating,$comments);
    if($stmt->execute()){
      echo "<p>Thanks for rating!</p>";
      // redirect to my_bookings after rating
      echo "<p><a href='my_bookings.php'>Back to My Bookings</a></p>";
      include('includes/footer.php'); exit;
    } else {
      echo "<p>Error: ".htmlspecialchars($stmt->error)."</p>";
    }
    $stmt->close();
  } else {
    echo "<p>Invalid rating.</p>";
  }
}

$ginfo = $conn->prepare("SELECT guide_id,name,expertise FROM guides WHERE guide_id = ?");
$ginfo->bind_param('i',$guide_id);
$ginfo->execute();
$gi = $ginfo->get_result()->fetch_assoc();
$ginfo->close();
if(!$gi){ echo "<p>Guide not found.</p>"; include('includes/footer.php'); exit; }
?>
<h2>Rate Guide: <?= htmlspecialchars($gi['name']) ?></h2>
<form method="POST">
  <input type="hidden" name="guide_id" value="<?= $gi['guide_id'] ?>">
  <label>Rating (1-5): <select name="rating" required>
    <option value="5">5 - Excellent</option>
    <option value="4">4 - Very good</option>
    <option value="3">3 - Good</option>
    <option value="2">2 - Fair</option>
    <option value="1">1 - Poor</option>
  </select></label><br>
  <label>Comments:<br><textarea name="comments" rows="4" cols="50"></textarea></label><br>
  <button name="rate">Submit Review</button>
</form>
<?php include('includes/footer.php'); ?>
