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
            $sql = "SELECT * FROM eoi";
            $result = mysqli_query($conn, $sql);
            $output = displayResults($result);
        }

        // List by reference code
        elseif ($action === "list_by_job" && !empty($_POST["JobReferenceNumber"])) {
            $JobReferenceNumber = $_POST["JobReferenceNumber"];
            $sql = "SELECT * FROM eoi WHERE JobReferenceNumber=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $JobReferenceNumber);
            $stmt->execute();
            $result = $stmt->get_result();
            $output = displayResults($result);
        }

        // List by name
        elseif ($action === "list_by_name") {
            $fname = !empty($_POST["FirstName"]) ? $_POST["FirstName"] : null;
            $lname = !empty($_POST["LastName"]) ? $_POST["LastName"] : null;

            if ($fname || $lname) {
                $sql = "SELECT * FROM eoi WHERE 1=1";
                $params = [];
                $types = "";

                if ($fname) {
                    $sql .= " AND FirstName LIKE ?";
                    $params[] = "%$fname%";
                    $types .= "s";
                }

                if ($lname) {
                    $sql .= " AND LastName LIKE ?";
                    $params[] = "%$lname%";
                    $types .= "s";
                }

                $stmt = $conn->prepare($sql);
                $stmt->bind_param($types, ...$params);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($lname) {
                    // Validate if the last name exists in the database
                    $validation_sql = "SELECT COUNT(*) AS count FROM eoi WHERE LastName LIKE ?";
                    $validation_stmt = $conn->prepare($validation_sql);
                    $validation_stmt->bind_param("s", $params[array_search("%$lname%", $params)]);
                    $validation_stmt->execute();
                    $validation_result = $validation_stmt->get_result();
                    $validation_row = $validation_result->fetch_assoc();

                    if ($validation_row['count'] == 0) {
                        $output = "⚠️ Invalid input: Last name does not match any records.";
                    } else {
                        $output = displayResults($result);
                    }
                } else {
                    $output = displayResults($result);
                }
            } else {
                $output = "⚠️ Please enter at least a first name or last name to search.";
            }
        }

        // Delete EOIs by JobReferenceNumber
        elseif ($action === "delete_by_job" && !empty($_POST["JobReferenceNumber"])) {
            $JobReferenceNumber = $_POST["JobReferenceNumber"];

            // First, fetch the EOInumbers related to the JobReferenceNumber
            $stmt = $conn->prepare("SELECT EOInumber FROM eoi WHERE JobReferenceNumber=?");
            $stmt->bind_param("s", $JobReferenceNumber);
            $stmt->execute();
            $result = $stmt->get_result();

            $eoi_ids = [];
            while ($row = $result->fetch_assoc()) {
                $eoi_ids[] = $row['EOInumber'];
            }

            if (count($eoi_ids) > 0) {
                // Delete from eoi_skills first (child table)
                $in_clause = implode(',', array_fill(0, count($eoi_ids), '?'));
                $types = str_repeat('i', count($eoi_ids)); // assuming EOInumber is int
                $stmt_skills = $conn->prepare("DELETE FROM eoi_skills WHERE EOInumber IN ($in_clause)");
                $stmt_skills->bind_param($types, ...$eoi_ids);
                $stmt_skills->execute();

                // Then delete from eoi table (parent table)
                $stmt_eoi = $conn->prepare("DELETE FROM eoi WHERE JobReferenceNumber=?");
                $stmt_eoi->bind_param("s", $JobReferenceNumber);
                $stmt_eoi->execute();

                $output = "✅ Successfully deleted EOIs and related skills for job reference: <strong>$JobReferenceNumber</strong>.";
            } else {
                $output = "⚠️ No EOIs found with job reference: <strong>$JobReferenceNumber</strong>.";
            }
        }

        // Update EOI status
        elseif ($action === "update_status" && !empty($_POST["eoi_id"]) && isset($_POST["status"])) {
            $eoi_id = $_POST["eoi_id"];
            $new_status = $_POST["status"];

            // Validate if the EOI ID exists in the database
            $validation_sql = "SELECT COUNT(*) AS count FROM eoi WHERE EOInumber = ?";
            $validation_stmt = $conn->prepare($validation_sql);
            $validation_stmt->bind_param("i", $eoi_id);
            $validation_stmt->execute();
            $validation_result = $validation_stmt->get_result();
            $validation_row = $validation_result->fetch_assoc();

            if ($validation_row['count'] > 0) {
                // Update the status in the database
                $sql = "UPDATE eoi SET Status=? WHERE EOInumber=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $new_status, $eoi_id);
                $stmt->execute();
                $output = "✅ Updated status of EOI ID $eoi_id to '$new_status'.";
            } else {
                $output = "⚠️ Invalid input: EOI ID does not exist.";
            }
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
                        <select name="JobReferenceNumber" required>
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
                    <label>First Name: <input type="text" name="FirstName" placeholder="Enter First Name"></label>
                    <label>Last Name: <input type="text" name="LastName" placeholder="Enter Last Name"></label>
                    <button name="action" value="list_by_name">List EOIs by Name</button>
                </form>

                <hr>

                <form method="post">
                    <label>EOI ID: <input type="number" name="eoi_id" placeholder="Enter EOI ID" required></label>
                    <label>Status:
                        <select name="status" required>
                            <option value="">Select Status</option>
                            <option value="New">New</option>
                            <option value="Current">Current</option>
                            <option value="Final">Final</option>
                        </select>
                    </label>
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