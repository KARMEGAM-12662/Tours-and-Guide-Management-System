<?php
$entered = "Ganesh@123"; // your actual password during registration
$hashed = '$2y$10$UusQ/moCp6kUxifBfhs9OeXQEPfIF07v50eDHizO9is'; // paste from database

if (password_verify($entered, $hashed)) {
  echo "✅ Password matches!";
} else {
  echo "❌ Password does not match!";
}
?>
