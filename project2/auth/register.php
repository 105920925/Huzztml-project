<?php
session_start();
$errors = [];
$success = false;

// Simple file-based storage for demonstration
$managersFile = __DIR__ . '/managers.json';
if (!file_exists($managersFile)) file_put_contents($managersFile, json_encode([]));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Password rule: at least 8 chars, 1 uppercase, 1 lowercase, 1 digit
    $passwordRule = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/';

    $managers = json_decode(file_get_contents($managersFile), true);

    if ($username === '' || $password === '') {
        $errors[] = "Username and password are required.";
    } elseif (isset($managers[$username])) {
        $errors[] = "Username already exists.";
    } elseif (!preg_match($passwordRule, $password)) {
        $errors[] = "Password must be at least 8 characters, include an uppercase letter, a lowercase letter, and a digit.";
    } else {
        // Store hashed password
        $managers[$username] = password_hash($password, PASSWORD_DEFAULT);
        file_put_contents($managersFile, json_encode($managers));
        $success = true;
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
