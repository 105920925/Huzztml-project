<?php
session_start();
$errors = [];
$success = false;

// Database connection
require_once("../db/settings.php"); // Loads $host, $user, $pass, $dbname

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Password rule: at least 8 chars, 1 uppercase, 1 lowercase, 1 digit
    $passwordRule = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/';

    if ($username === '' || $password === '') {
        $errors[] = "Username and password are required.";
    } elseif (!preg_match($passwordRule, $password)) {
        $errors[] = "Password must be at least 8 characters, include an uppercase letter, a lowercase letter, and a digit.";
    } else {
        // Check if username exists
        $stmt = $conn->prepare("SELECT id FROM managers WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "Username already exists.";
        } else {
            // Insert new manager
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO managers (username, password_hash) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hash);
            if ($stmt->execute()) {
                $success = true;
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manager Registration</title>
</head>
<body>
    <h1>Register Manager Account</h1>
    <?php if ($success): ?>
        <p>Registration successful. <a href="login.php">Login here</a>.</p>
    <?php else: ?>
        <?php foreach ($errors as $e) echo "<p style='color:red;'>$e</p>"; ?>
        <form method="post">
            <label>Username: <input type="text" name="username" required></label><br>
            <label>Password: <input type="password" name="password" required></label><br>
            <small>Password must be at least 8 characters, include an uppercase letter, a lowercase letter, and a digit.</small><br>
            <button type="submit">Register</button>
        </form>
    <?php endif; ?>
</body>
</html>
