<?php
include('includes/db.php');
include('includes/header.php');
if(empty($_SESSION['user'])) { echo "<p>Please <a href='login.php'>login</a></p>"; include('includes/footer.php'); exit; }
$uid = $_SESSION['user']['user_id'];

$stmt = $conn->prepare("SELECT b.booking_id,b.booking_date,b.status,p.title,p.price,g.name AS guide_name,g.guide_id
  FROM bookings b
  LEFT JOIN packages p ON b.package_id = p.package_id
  LEFT JOIN guides g ON b.guide_id = g.guide_id
  WHERE b.user_id = ? ORDER BY b.created_at DESC");
$stmt->bind_param('i',$uid);
$stmt->execute();
$res = $stmt->get_result();
?>
<h2>My Bookings</h2>
<table border="1" cellpadding="8">
<tr><th>Package</th><th>Date</th><th>Guide</th><th>Price</th><th>Status</th><th>Actions</th></tr>
<?php while($r = $res->fetch_assoc()): ?>
<tr>
  <td><?= htmlspecialchars($r['title']) ?></td>
  <td><?= htmlspecialchars($r['booking_date']) ?></td>
  <td><?= htmlspecialchars($r['guide_name'] ?: 'Not assigned') ?></td>
  <td>₹<?= number_format($r['price'],2) ?></td>
  <td><?= htmlspecialchars($r['status']) ?></td>
  <td>
    <?php
      // check if user already reviewed this guide for this booking (simple approach: check any review by user for this guide)
      $canRate = false;
      if(!empty($r['guide_id'])){
        $chk = $conn->prepare("SELECT COUNT(*) AS cnt FROM reviews WHERE guide_id = ? AND user_id = ?");
        $chk->bind_param('ii',$r['guide_id'],$uid);
        $chk->execute();
        $c = $chk->get_result()->fetch_assoc();
        $chk->close();
        if($c['cnt'] == 0) $canRate = true;
      }
      if($canRate){
        echo "<a href='rate_guide.php?guide_id=".$r['guide_id']."&booking_id=".$r['booking_id']."'>Rate Guide</a>";
      } else {
        echo "-";
      }
    ?>
  </td>
</tr>
<?php endwhile; $stmt->close(); ?>
</table>
<?php include('includes/footer.php'); ?>
