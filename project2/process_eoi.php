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

$jobRef = sanitise($_POST["jobref"]);
$firstName = sanitise($_POST["fname"]);
$lastName = sanitise($_POST["lname"]);
$street = sanitise($_POST["street"]);
$suburb = sanitise($_POST["suburb"]);
$state = sanitise($_POST["state"]);
$postcode = sanitise($_POST["postcode"]);
$email = sanitise($_POST["email"]);
$phone = sanitise($_POST["phone"]);
$skills = $_POST["skills"] ?? [];
$otherSkills = isset($_POST["otherskills"]) ? sanitise($_POST["otherskills"]) : "";

$errors = [];

if (!preg_match("/^[A-Za-z]{1,20}$/", $firstName)) $errors[] = "Invalid First Name";
if (!preg_match("/^[A-Za-z]{1,20}$/", $lastName)) $errors[] = "Invalid Last Name";
if (!preg_match("/^[\w\s]{1,40}$/", $street)) $errors[] = "Invalid Street";
if (!preg_match("/^[\w\s]{1,40}$/", $suburb)) $errors[] = "Invalid Suburb";
if (!preg_match("/^\d{4}$/", $postcode)) $errors[] = "Invalid Postcode";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid Email";
if (!preg_match("/^[\d\s]{8,12}$/", $phone)) $errors[] = "Invalid Phone";

// Match postcode and state
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
if (!$validPrefix) $errors[] = "Postcode does not match state";

if (!empty($errors)) {
    echo "<h2>Form Submission Error</h2><ul>";
    foreach ($errors as $e) echo "<li>$e</li>";
    echo "</ul><p><a href='apply.php'>Go Back</a></p>";
    exit();
}

// Skill values
$skill1 = in_array("Skill1", $skills) ? 1 : 0;
$skill2 = in_array("Skill2", $skills) ? 1 : 0;
$skill3 = in_array("Skill3", $skills) ? 1 : 0;
$skill4 = in_array("Skill4", $skills) ? 1 : 0;

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
    Skill1 BOOLEAN DEFAULT FALSE,
    Skill2 BOOLEAN DEFAULT FALSE,
    Skill3 BOOLEAN DEFAULT FALSE,
    Skill4 BOOLEAN DEFAULT FALSE,
    OtherSkills TEXT,
    Status ENUM('New', 'Current', 'Final') DEFAULT 'New'
)";
mysqli_query($conn, $create_table_query);

$insert_query = "
INSERT INTO eoi (
    JobReferenceNumber, FirstName, LastName, StreetAddress, Suburb,
    State, Postcode, Email, Phone, Skill1, Skill2, Skill3, Skill4, OtherSkills
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
";
$stmt = mysqli_prepare($conn, $insert_query);
mysqli_stmt_bind_param(
    $stmt, "sssssssssssiss",
    $jobRef, $firstName, $lastName, $street, $suburb,
    $state, $postcode, $email, $phone, $skill1, $skill2, $skill3, $skill4, $otherSkills
);

if (mysqli_stmt_execute($stmt)) {
    $eoi_id = mysqli_insert_id($conn);
    echo "<h2>Application Received</h2>";
    echo "<p>Your EOInumber is: <strong>$eoi_id</strong></p>";
    echo "<p><a href='index.php'>Back to Home</a></p>";
} else {
    echo "<h2>Error Submitting Application</h2><p>Please try again later.</p>";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
