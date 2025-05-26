<?php

// Displays errors that occur
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$user = "root";       // change if needed
$pass = "";           // change if needed
$dbname = "jobs";     // change to your DB name

$conn = mysqli_connect($host, $user, $pass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form actions
$output = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["action"])) {
        $action = $_POST["action"];

        // List all EOIs
        if ($action === "list_all") {
            $sql = "SELECT * FROM eoi";
            $result = mysqli_query($conn, $sql);
            $output = displayResults($result);
        }

        // List by job reference
        elseif ($action === "list_by_job") {
            $job_ref = $_POST["job_ref"];
            $sql = "SELECT * FROM eoi WHERE job_ref=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $job_ref);
            $stmt->execute();
            $result = $stmt->get_result();
            $output = displayResults($result);
        }

        // List by name
        elseif ($action === "list_by_name") {
            $fname = $_POST["first_name"];
            $lname = $_POST["last_name"];
            $sql = "SELECT * FROM eoi WHERE first_name LIKE ? OR last_name LIKE ?";
            $stmt = $conn->prepare($sql);
            $fname = "%$fname%";
            $lname = "%$lname%";
            $stmt->bind_param("ss", $fname, $lname);
            $stmt->execute();
            $result = $stmt->get_result();
            $output = displayResults($result);
        }

        // Delete by job ref
        elseif ($action === "delete_by_job") {
            $job_ref = $_POST["job_ref"];
            $sql = "DELETE FROM eoi WHERE job_ref=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $job_ref);
            $stmt->execute();
            $output = "Deleted all EOIs for job reference: $job_ref";
        }

        // Update EOI status
        elseif ($action === "update_status") {
            $eoi_id = $_POST["eoi_id"];
            $new_status = $_POST["status"];
            $sql = "UPDATE eoi SET status=? WHERE eoi_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $new_status, $eoi_id);
            $stmt->execute();
            $output = "Updated status of EOI ID $eoi_id to '$new_status'";
        }
    }
}

// Function to display query results
function displayResults($result) {
    if ($result && $result->num_rows > 0) {
        $html = "<table border='1'><tr>";
        while ($field = $result->fetch_field()) {
            $html .= "<th>{$field->name}</th>";
        }
        $html .= "</tr>";
        while ($row = $result->fetch_assoc()) {
            $html .= "<tr>";
            foreach ($row as $val) {
                $html .= "<td>$val</td>";
            }
            $html .= "</tr>";
        }
        $html .= "</table>";
    } else {
        $html = "No results found.";
    }
    return $html;
}
?>

<?php
session_start();
if (empty($_SESSION['manager_logged_in'])) {
    header("Location: auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manager Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['manager_username']); ?></h1>
    <p>This is the manager-only area.</p>
    <p><a href="auth/logout.php">Logout</a></p>

    <h1>Manage EOIs</h1>

    <form method="post">
        <button name="action" value="list_all">List All EOIs</button>
    </form>

    <hr>

    <form method="post">
        <label>Job Reference:
            <select name="job_ref">
                <option value="J001">J001</option>
                <option value="J002">J002</option>
                <option value="J003">J003</option>
            </select>
        </label>
        <button name="action" value="list_by_job">List EOIs by Job</button>
        <button name="action" value="delete_by_job">Delete EOIs by Job</button>
    </form>

    <hr>

    <form method="post">
        <label>First Name: <input type="text" name="first_name" maxlength="20"></label>
        <label>Last Name: <input type="text" name="last_name" maxlength="20"></label>
        <button name="action" value="list_by_name">List EOIs by Name</button>
    </form>

    <hr>

    <form method="post">
        <label>EOI ID: <input type="number" name="eoi_id" required></label>
        <label>Status: <input type="text" name="status" required></label>
        <button name="action" value="update_status">Update Status</button>
    </form>

    <hr>

    <form action="/Applications/XAMPP/xamppfiles/htdocs/Huzztml-project/auth/logout.php" method="post">
        <button type="submit">Logout</button>
    </form>

    <hr>

    <h2>Results:</h2>
    <div>
        <?php echo $output; ?>
    </div>
</body>
</html>
