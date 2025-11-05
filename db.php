<?php
$DB_HOST = "localhost:3307";
$DB_USER = "root";
$DB_PASS = "";         // empty for XAMPP
$DB_NAME = "tourdb";

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
