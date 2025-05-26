<?php
$host = "localhost";
$user = "your_db_username";
$pass = "your_db_password";
$dbname = "jobs";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
