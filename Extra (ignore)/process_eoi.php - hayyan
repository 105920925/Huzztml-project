<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: apply.php");
    exit();
}

require_once("settings.php");

$conn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

function sanitise($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Sanitize input
$jobRef      = sanitise($_POST["jobref"]);
$firstName   = sanitise($_POST["fname"]);
$lastName    = sanitise($_POST["lname"]);
$street      = sanitise($_POST["street"]);
$suburb      = sanitise($_POST["suburb"]);
$state       = sanitise($_POST["state"]);
$postcode    = sanitise($_POST["postcode"]);
$email       = sanitise($_POST["email"]);
$phone       = sanitise($_POST["phone"]);
$skills      = $_POST["skills"] ?? [];
$otherSkills = isset($_POST["otherskills"]) ? sanitise($_POST["otherskills"]) : "";

// Validate input
$errors = [];

if (!preg_match("/^[A-Za-z]{1,20}$/", $firstName)) $errors[] = "Invalid First Name";
if (!preg_match("/^[A-Za-z]{1,20}$/", $lastName)) $errors[] = "Invalid Last Name";
if (!preg_match("/^[\w\s]{1,40}$/", $street)) $errors[] = "Invalid Street";
if (!preg_match("/^[\w\s]{1,40}$/", $suburb)) $errors[] = "Invalid Suburb";
if (!preg_match("/^\d{4}$/", $postcode)) $errors[] = "Postcode must be 4 digits";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid Email";
if (!preg_match("/^[\d\s]{8,12}$/", $phone)) $errors[] = "Phone must be 8–12 digits";

// Validate postcode matches state
$statePostcodePrefixes = [
    "VIC" => ["3", "8"],
    "NSW" => ["1", "2"],
    "QLD" => ["4", "9"],
    "NT"  => ["0"],
    "WA"  => ["6"],
    "SA"  => ["5"],
    "TAS" => ["7"],
    "ACT" => ["0"]
];
$validPrefix = false;
foreach ($statePostcodePrefixes[$state] as $prefix) {
    if (str_starts_with($postcode, $prefix)) {
        $validPrefix = true;
        break;
    }
}
if (!$validPrefix) $errors[] = "Postcode does not match selected state";

// Resume upload
$resumePath = "";
if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $fileTmp = $_FILES['resume']['tmp_name'];
    $fileName = basename($_FILES['resume']['name']);
    $resumePath = $upload_dir . time() . "_" . $fileName;

    if (!move_uploaded_file($fileTmp, $resumePath)) {
        $errors[] = "Failed to upload resume.";
    }
} else {
    $errors[] = "Resume is required.";
}

// If errors, stop
if (!empty($errors)) {
    echo "<h2>Submission Error</h2><ul>";
    foreach ($errors as $e) echo "<li>$e</li>";
    echo "</ul><p><a href='apply.php'>Go back</a></p>";
    exit();
}

// Convert selected skills to actual names or NULL
$skill1 = in_array("Skill1", $skills) ? "Programming" : NULL;
$skill2 = in_array("Skill2", $skills) ? "Networking" : NULL;
$skill3 = in_array("Skill3", $skills) ? "Database Management" : NULL;
$skill4 = in_array("Skill4", $skills) ? "Web Development" : NULL;

// Create table if not exists
$create_table_query = "
CREATE TABLE IF NOT EXISTS eoi (
    EOInumber INT AUTO_INCREMENT PRIMARY KEY,
    JobReferenceNumber VARCHAR(5) NOT NULL,
    FirstName VARCHAR(20) NOT NULL,
    LastName VARCHAR(20) NOT NULL,
    StreetAddress VARCHAR(40) NOT NULL,
    Suburb VARCHAR(40) NOT NULL,
    State ENUM('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,
    Postcode CHAR(4) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    Phone VARCHAR(12) NOT NULL,
    Skill1 TEXT NULL,
    Skill2 TEXT NULL,
    Skill3 TEXT NULL,
    Skill4 TEXT NULL,
    OtherSkills TEXT,
    ResumePath VARCHAR(255),
    Status ENUM('New', 'Current', 'Final') DEFAULT 'New'
)";
mysqli_query($conn, $create_table_query);

// Insert EOI record
$insert_query = "
INSERT INTO eoi (
    JobReferenceNumber, FirstName, LastName, StreetAddress, Suburb,
    State, Postcode, Email, Phone,
    Skill1, Skill2, Skill3, Skill4,
    OtherSkills, ResumePath
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
";

$stmt = mysqli_prepare($conn, $insert_query);
mysqli_stmt_bind_param(
    $stmt, "sssssssssssssss",
    $jobRef, $firstName, $lastName, $street, $suburb,
    $state, $postcode, $email, $phone,
    $skill1, $skill2, $skill3, $skill4,
    $otherSkills, $resumePath
);

if (mysqli_stmt_execute($stmt)) {
    $eoi_id = mysqli_insert_id($conn);
    echo "<h2>Application Submitted</h2>";
    echo "<p>Your EOInumber is: <strong>$eoi_id</strong></p>";
    echo "<p><a href='index.php'>Back to Home</a></p>";
} else {
    echo "<h2>Failed to submit application. Please try again.</h2>";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
