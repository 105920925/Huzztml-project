<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
// Include the database connection
require 'db/settings.php';

// Query the job_listings table to get all job titles or reference codes
$stmt = $pdo->query("SELECT title, reference_code FROM job_listings");
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get skills
$skill_stmt = $pdo->query("SELECT name FROM technical_skills");
$skills = $skill_stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize error array for validation
$errors = [];

// Collect and validate form data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Job reference
    $jobRef = $_POST['jobref'] ?? '';
    $firstName = $_POST['fname'] ?? '';
    $lastName = $_POST['lname'] ?? '';
    $street = $_POST['street'] ?? '';
    $suburb = $_POST['suburb'] ?? '';
    $state = $_POST['state'] ?? '';
    $postcode = $_POST['postcode'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $skills = $_POST['skills'] ?? [];
    $otherSkills = $_POST['otherskills'] ?? '';

    // Validate fields
    if (empty($jobRef)) $errors[] = "Job Reference is required.";
    if (!preg_match("/^[A-Za-z]{1,20}$/", $firstName)) $errors[] = "Invalid First Name";
    if (!preg_match("/^[A-Za-z]{1,20}$/", $lastName)) $errors[] = "Invalid Last Name";
    if (!preg_match("/^[\w\s]{1,40}$/", $street)) $errors[] = "Invalid Street Address";
    if (!preg_match("/^[\w\s]{1,40}$/", $suburb)) $errors[] = "Invalid Suburb/Town";
    if (!preg_match("/^\d{4}$/", $postcode)) $errors[] = "Postcode must be 4 digits";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid Email";
    if (!preg_match("/^[\d\s]{8,12}$/", $phone)) $errors[] = "Phone must be 8–12 digits";

    // Skills Validation
    if (empty($skills)) $errors[] = "At least one skill is required.";

    // Postcode and state matching
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
    if (!$validPrefix) $errors[] = "Postcode does not match selected state.";


    // Check if resume file is uploaded and handle it
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $fileType = mime_content_type($_FILES['resume']['tmp_name']);

        // Check if file type is allowed
        if (!in_array($fileType, $allowedTypes)) {
            $errors[] = "Invalid resume format. Only PDF and Word documents are allowed.";
        }

        // Check file size (10MB limit)
        $maxFileSize = 10 * 1024 * 1024; // 10MB
        if ($_FILES['resume']['size'] > $maxFileSize) {
            $errors[] = "Resume file size exceeds 10MB.";
        }

        if (empty($errors)) {
            // Set upload directory path
            $upload_dir = "uploads/";

            // Check if the directory exists and is writable
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true); // Create directory if it doesn't exist
            }

            // Ensure directory is writable
            if (!is_writable($upload_dir)) {
                $errors[] = "The upload directory is not writable.";
            } else {
                // Prepare file name and move the uploaded file
                $fileTmp = $_FILES['resume']['tmp_name'];
                $fileName = basename($_FILES['resume']['name']);
                $resumePath = $upload_dir . time() . "_" . $fileName;

                // Move the uploaded file to the specified directory
                if (move_uploaded_file($fileTmp, $resumePath)) {
                    // Successfully uploaded, do nothing
                } else {
                    $errors[] = "Failed to upload resume. Please try again.";
                }
            }
        }
    } else {
        // Handle the case where no file is uploaded or there is an error
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] != UPLOAD_ERR_OK) {
            $errors[] = "Error in file upload. Error code: " . $_FILES['resume']['error'];
        } else {
            $errors[] = "Resume is required.";
        }
    }

    // If there are no validation errors, proceed with saving to the database
    if (empty($errors)) {
        $insert_query = "
            INSERT INTO eoi (
                JobReferenceNumber, FirstName, LastName, StreetAddress, Suburb,
                State, Postcode, Email, Phone,
                Skills, OtherSkills, ResumePath
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt = $pdo->prepare($insert_query);
        $stmt->execute([
            $jobRef,
            $firstName,
            $lastName,
            $street,
            $suburb,
            $state,
            $postcode,
            $email,
            $phone,
            implode(",", $skills),
            $otherSkills,
            $resumePath
        ]);

        $eoi_id = $pdo->lastInsertId();  // Get the last inserted EOI ID
        echo "<h2>Application Submitted</h2>";
        echo "<p>Your EOI number is: <strong>$eoi_id</strong></p>";
        echo "<p><a href='index.php'>Back to Home</a></p>";
        exit();
    }
}
?>

<?php include("inclusions/header.inc"); ?>
<link rel="stylesheet" href="styles/applystyle.css">
<?php include("inclusions/menu.inc"); ?>

<main>
    <div>
        <h1>Apply for a Job</h1>
        <?php
        // Show errors if any
        if (!empty($errors)) {
            echo "<h2>Submission Error</h2><ul>";
            foreach ($errors as $error) {
                echo "<li>$error</li>";
            }
            echo "</ul>";
            echo "<p><a href='apply.php'>Go back</a></p>";
        } else {
            // If no errors, show the form (not needed if the form is displayed after each error)
        }
        ?>
        <form id="applicationForm" action="process_eoi.php" method="post" enctype="multipart/form-data" novalidate="novalidate">
            <!-- Job title -->
            <div>
                <label for="jobTitle">Job Title *</label>
                <select id="jobTitle" name="jobref" required>
                    <option value="" disabled selected>Select a job</option>
                    <?php
                    // Loop through the jobs fetched from the database
                    foreach ($jobs as $job) {
                        echo "<option value='" . htmlspecialchars($job['reference_code']) . "'>" . htmlspecialchars($job['title']) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Personal Info -->
            <div>
                <label for="firstName">First Name *</label>
                <input type="text" id="firstName" name="fname" maxlength="20" pattern="[A-Za-z]+" required>
            </div>

            <div>
                <label for="lastName">Last Name *</label>
                <input type="text" id="lastName" name="lname" maxlength="20" pattern="[A-Za-z]+" required>
            </div>

            <div>
                <label for="streetAddress">Street Address *</label>
                <input type="text" id="streetAddress" name="street" maxlength="40" required>
            </div>

            <div>
                <label for="suburb">Suburb/Town *</label>
                <input type="text" id="suburb" name="suburb" maxlength="40" required>
            </div>

            <div>
                <label for="state">State *</label>
                <select id="state" name="state" required>
                    <option value="" disabled selected>Select a state</option>
                    <option value="VIC">VIC</option>
                    <option value="NSW">NSW</option>
                    <option value="QLD">QLD</option>
                    <option value="NT">NT</option>
                    <option value="WA">WA</option>
                    <option value="SA">SA</option>
                    <option value="TAS">TAS</option>
                    <option value="ACT">ACT</option>
                </select>
            </div>

            <div>
                <label for="postcode">Postcode *</label>
                <input type="text" id="postcode" name="postcode" pattern="\d{4}" required>
            </div>

            <div>
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div>
                <label for="phone">Phone *</label>
                <input type="text" id="phone" name="phone" pattern="[0-9 ]{8,12}" required>
            </div>

            <!-- Skills -->
            <fieldset>
                <legend>Required Technical Skills *</legend>
                <div id="skillsContainer">
                    <?php
                    foreach ($skills as $skill) {
                        echo "<label><input type='checkbox' name='skills[]' value='" . htmlspecialchars($skill['name']) . "'> " . htmlspecialchars($skill['name']) . "</label><br>";
                    }
                    ?>
                </div>
            </fieldset>

            <!-- Other Skills -->
            <div>
                <label for="otherskills">Other Skills</label>
                <textarea id="otherskills" name="otherskills" rows="4" cols="50"></textarea>
            </div>

            <!-- Resume upload -->
            <div>
                <label for="resume">Upload Resume *</label>
                <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx" required>
            </div>

            <!-- Submit -->
            <div>
                <button type="submit">Submit Application</button>
            </div>
        </form>
    </div>
</main>

<?php include("inclusions/footer.inc"); ?>