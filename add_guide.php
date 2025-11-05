<?php
include('../includes/db.php'); session_start();
if(empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){ header('Location: /tour_guide_system/login.php'); exit; }
include('../includes/header.php');

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_guide'])){
  $name = $_POST['name']; $expertise = $_POST['expertise']; $contact = $_POST['contact'];
  $stmt = $conn->prepare("INSERT INTO guides (name,expertise,contact) VALUES (?,?,?)");
  $stmt->bind_param('sss',$name,$expertise,$contact);
  if($stmt->execute()) echo "<p>Guide added.</p>"; else echo "<p>Error: ".htmlspecialchars($stmt->error)."</p>";
  $stmt->close();
}
?>
<h2>Admin - Add Guide</h2>
<form method="POST">
  <input name="name" placeholder="Guide Name" required><br>
  <input name="expertise" placeholder="Expertise / Languages"><br>
  <input name="contact" placeholder="Contact"><br>
  <button name="add_guide">Add Guide</button>
</form>
<?php include('../includes/footer.php'); ?>
