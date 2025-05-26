<?php

// Displays errors that occur
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$user = "root";       // change if needed
$pass = "";           // change if needed
$dbname = "jobs";  // change to your DB name

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
            $sql = "SELECT * FROM job_listings";
            $result = mysqli_query($conn, $sql);
            $output = displayResults($result);
        }

        // List by reference code
        elseif ($action === "list_by_job" && !empty($_POST["reference_code"])) {
            $reference_code = $_POST["reference_code"];
            $sql = "SELECT * FROM eoi WHERE reference_code=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $reference_code);
            $stmt->execute();
            $result = $stmt->get_result();
            $output = displayResults($result);
        }

        // List by name
        elseif ($action === "list_by_name" && (!empty($_POST["FirstName"]) || !empty($_POST["LastName"]))) {
            $fname = $_POST["FirstName"];
            $lname = $_POST["LastName"];
            $sql = "SELECT * FROM eoi WHERE FirstName LIKE ? OR LastName LIKE ?";
            $stmt = $conn->prepare($sql);
            $fname = "%$fname%";
            $lname = "%$lname%";
            $stmt->bind_param("ss", $fname, $lname);
            $stmt->execute();
            $result = $stmt->get_result();
            $output = displayResults($result);
        }

        // Delete by reference code
        elseif ($action === "delete_by_job" && !empty($_POST["reference_code"])) {
            $reference_code = $_POST["reference_code"];
            $sql = "DELETE FROM eoi WHERE reference_code=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $reference_code);
            $stmt->execute();
            $output = "Deleted all EOIs for reference code: $reference_code";
        }

        // Update EOI status
        elseif ($action === "update_status" && !empty($_POST["eoi_id"]) && !empty($_POST["status"])) {
            $eoi_id = $_POST["eoi_id"];
            $new_status = $_POST["status"];
            $sql = "UPDATE eoi SET status=? WHERE eoi_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $new_status, $eoi_id);
            $stmt->execute();
            $output = "Updated status of EOI ID $eoi_id to '$new_status'";
        } else {
            $output = "Invalid input or missing fields.";
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
    <link rel="stylesheet" type="text/css" href="styles/manage.css">
</head>
<body>
    <?php include 'includes/header.inc'; ?>
    <?php include 'includes/menu.inc'; ?>

    <div class="box manager-area">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['manager_username']); ?></h1>
        <p>This is the manager-only area.</p>
        <a href="index.php" class="logout-button">Logout</a>
    </div>

    <div class="container">
        <div class="left-column">
            <div class="box">
                <h1>Manage EOIs</h1>

                <form method="post">
                    <button name="action" value="list_all">List All EOIs</button>
                </form>

                <hr>

                <form method="post">
                    <label>Reference Code:
                        <select name="reference_code" required>
                            <option value="">Select a Job</option>
                            <?php
                            // Fetch reference codes from the database
                            $query = "SELECT reference_code FROM job_listings";
                            $result = mysqli_query($conn, $query);

                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value=\"" . htmlspecialchars($row['reference_code']) . "\">" . htmlspecialchars($row['reference_code']) . "</option>";
                                }
                            } else {
                                echo "<option value=\"\">No jobs available</option>";
                            }
                            ?>
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
            </div>
        </div>

        <div class="right-column">
            <div class="box">
                <h2>Results:</h2>
                <div>
                    <?php echo $output; ?>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.inc'; ?>
</body>
</html>