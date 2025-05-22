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

function sanitise($data)
{
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

$errors = [];

// Validation
if (empty($jobRef)) $errors[] = "Job reference is required.";

if (empty($firstName) || !preg_match("/^[A-Za-z]{1,20}$/", $firstName)) {
    $errors[] = "Invalid first name.";
}

if (empty($lastName) || !preg_match("/^[A-Za-z]{1,20}$/", $lastName)) {
    $errors[] = "Invalid last name.";
}

if (empty($street) || strlen($street) > 40) {
    $errors[] = "Street address is required and must be less than 40 characters.";
}

if (empty($suburb) || strlen($suburb) > 40) {
    $errors[] = "Suburb is required and must be less than 40 characters.";
}

$validStates = ['VIC', 'NSW', 'QLD', 'NT', 'WA', 'SA', 'TAS', 'ACT'];
if (empty($state) || !in_array($state, $validStates)) {
    $errors[] = "Please select a valid state.";
}

if (empty($postcode) || !preg_match("/^\d{4}$/", $postcode)) {
    $errors[] = "Postcode must be exactly 4 digits.";
} else {
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
    if (!$validPrefix) {
        $errors[] = "Postcode does not match the selected state.";
    }
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email address.";
}

if (empty($phone) || !preg_match("/^[0-9 ]{8,12}$/", $phone)) {
    $errors[] = "Phone number must be 8–12 digits (numbers and spaces only).";
}

if (empty($skills)) {
    $errors[] = "Please select at least one technical skill.";
}

// Resume upload
$resumePath = "";
if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = "db/uploads/";
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status</title>
    <link rel="stylesheet" href="styles/process_eoi.css">
</head>
<body>
<main class="container">
<?php
if (!empty($errors)) {
    echo "<div class='error-box'>";
    echo "<h2>Submission Error</h2><ul>";
    foreach ($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>";
    echo "</ul><p><a href='apply.php'>Go back to form</a></p></div>";
} else {
    $insert_query = "
    INSERT INTO eoi (
        JobReferenceNumber, FirstName, LastName, StreetAddress, Suburb,
        State, Postcode, Email, Phone, OtherSkills, ResumePath
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param(
        $stmt,
        "sssssssssss",
        $jobRef,
        $firstName,
        $lastName,
        $street,
        $suburb,
        $state,
        $postcode,
        $email,
        $phone,
        $otherSkills,
        $resumePath
    );

    if (mysqli_stmt_execute($stmt)) {
        $eoi_id = mysqli_insert_id($conn);

        if (!empty($skills)) {
            $skill_insert_query = "INSERT INTO eoi_skills (eoi_id, skill_name) VALUES (?, ?)";
            $skill_stmt = mysqli_prepare($conn, $skill_insert_query);

            foreach ($skills as $skill) {
                mysqli_stmt_bind_param($skill_stmt, "is", $eoi_id, $skill);
                mysqli_stmt_execute($skill_stmt);
            }

            mysqli_stmt_close($skill_stmt);
        }

        echo "<div class='success-box'>";
        echo "<h2>Application Submitted</h2>";
        echo "<p>Your EOInumber is: <strong>" . htmlspecialchars($eoi_id) . "</strong></p>";
        echo "<p><a href='index.php'>Back to Home</a></p></div>";
    } else {
        echo "<div class='error-box'><h2>Submission failed. Please try again.</h2></div>";
    }

    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
?>
</main>
</body>
</html>
