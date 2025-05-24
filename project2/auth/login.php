<?php
session_start();
$managersFile = __DIR__ . '/managers.json';
if (!file_exists($managersFile)) file_put_contents($managersFile, json_encode([]));
$managers = json_decode(file_get_contents($managersFile), true);

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

    if (isset($managers[$username]) && password_verify($password, $managers[$username])) {
        $_SESSION['manager_logged_in'] = true;
        $_SESSION['manager_username'] = $username;
        $_SESSION['login_attempts'] = 0;
        $_SESSION['lockout_start'] = null;
        header("Location: ../manage.php");
        exit;
    } else {
        $_SESSION['login_attempts']++;
        $errors[] = "Invalid username or password.";
        if ($_SESSION['login_attempts'] >= 3) {
            $_SESSION['lockout_start'] = time();
            $locked = true;
            $remaining = $lockoutTime;
        }
    }
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
