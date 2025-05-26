<?php
session_start();
require_once("../db/db_connect.php"); // Assumes you have a db_connect.php with $conn

$errors = [];
$locked = false;
$lockoutTime = 300; // 5 minutes

if (!isset($_SESSION['login_attempts'])) $_SESSION['login_attempts'] = 0;
if (!isset($_SESSION['lockout_start'])) $_SESSION['lockout_start'] = null;

if ($_SESSION['login_attempts'] >= 3) {
    if ($_SESSION['lockout_start'] === null) {
        $_SESSION['lockout_start'] = time();
    }
    $elapsed = time() - $_SESSION['lockout_start'];
    if ($elapsed < $lockoutTime) {
        $locked = true;
        $remaining = $lockoutTime - $elapsed;
    } else {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['lockout_start'] = null;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$locked) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT password_hash FROM managers WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hash);
        $stmt->fetch();
        if (password_verify($password, $hash)) {
            $_SESSION['manager_logged_in'] = true;
            $_SESSION['manager_username'] = $username;
            $_SESSION['login_attempts'] = 0;
            $_SESSION['lockout_start'] = null;
            header("Location: ../manage.php");
            exit;
        }
    }
    $_SESSION['login_attempts']++;
    $errors[] = "Invalid username or password.";
    if ($_SESSION['login_attempts'] >= 3) {
        $_SESSION['lockout_start'] = time();
        $locked = true;
        $remaining = $lockoutTime;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manager Login</title>
</head>
<body>
    <h1>Manager Login</h1>
    <?php
    foreach ($errors as $e) echo "<p style='color:red;'>$e</p>";
    if ($locked) {
        echo "<p style='color:red;'>Too many failed attempts. Please try again in " . ceil($remaining/60) . " minute(s).</p>";
    }
    ?>
    <?php if (!$locked): ?>
    <form method="post">
        <label>Username: <input type="text" name="username" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <button type="submit">Login</button>
    </form>
    <?php endif; ?>
    <p><a href="register.php">Register as manager</a></p>
</body>
</html>
