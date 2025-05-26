<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "jobs";

$conn = new mysqli($host, $user, $pass, $dbname); // This connects to the 'jobs' database
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
