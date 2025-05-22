<?php
// Define $pdo in the global scope
$host = 'localhost';
$dbname = 'jobs';
$username = 'root';
$password = ''; // Default password for XAMPP is blank

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
