<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
require 'db/settings.php'; // This brings in the $pdo variable

// Query the job_listings table to get all job openings
$stmt = $pdo->query("SELECT * FROM job_listings"); // Use the correct table name
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
    <!-- Link to your CSS file -->
    <link rel="stylesheet" href="../project2/styles/jobs.css"> 
</head>
<body>

<?php include("includes/header.inc"); ?>
<?php include("includes/menu.inc"); ?>

<main class="container">

    <!-- Current openings section - contains job listings -->
    <section id="current-openings">
        <h2>Current Job Openings</h2>
        <p>Explore our exciting opportunities and find your next career move with us.</p>

        <!-- Dynamically output job listings -->
        <?php foreach ($jobs as $job): ?>
            <section class="job-card">
                <h3><?php echo htmlspecialchars($job['title']); ?></h3> <!-- Job title -->

                <!-- Job metadata section with reference, reporting line, and salary -->
                <div class="job-meta">
                    <span><strong>Reference:</strong> <?php echo htmlspecialchars($job['reference_code']); ?></span>
                    <span><strong>Reports To:</strong> <?php echo htmlspecialchars($job['reports_to']); ?></span>
                    <span><strong>Salary Range:</strong> <?php echo htmlspecialchars($job['salary_range']); ?></span>
                </div>

                <!-- Job overview section -->
                <h4>Position Overview</h4>
                <p><?php echo nl2br(htmlspecialchars($job['overview'])); ?></p> <!-- Position overview -->

                <!-- Key responsibilities section -->
                <h4>Key Responsibilities</h4>
                <ol>
                    <?php 
                    // Convert the responsibilities into an array and output each item
                    $responsibilities = explode("\n", $job['responsibilities']);
                    foreach ($responsibilities as $responsibility):
                    ?>
                        <li><?php echo htmlspecialchars($responsibility); ?></li>
                    <?php endforeach; ?>
                </ol>

                <!-- Qualifications section -->
                <h4>Qualifications & Skills</h4>
                <div class="requirements">
                    <div>
                        <h5>Essential:</h5>
                        <ul>
                            <?php 
                            // Convert essential qualifications into an array and output each item
                            $essential_qualifications = explode("\n", $job['essential_qualifications']);
                            foreach ($essential_qualifications as $qualification):
                            ?>
                                <li><?php echo htmlspecialchars($qualification); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div>
                        <h5>Preferable:</h5>
                        <ul>
                            <?php 
                            // Convert preferable qualifications into an array and output each item
                            $preferable_qualifications = explode("\n", $job['preferable_qualifications']);
                            foreach ($preferable_qualifications as $qualification):
                            ?>
                                <li><?php echo htmlspecialchars($qualification); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </section>
        <?php endforeach; ?>
    </section>

    <!-- Application process section - explains the hiring process -->
    <section id="application-process">
        <h2>Application Process</h2>
        <p>Our hiring process is designed to find the best talent while providing you with a great candidate experience:</p>
        <!-- Ordered list (numbered steps) - required by specifications -->
        <ol>
            <li><strong>Application Review:</strong> Our hiring team reviews all applications</li>
            <li><strong>Initial Screening:</strong> Phone call to discuss your experience and expectations</li>
            <li><strong>Technical Assessment:</strong> A task related to the position you're applying for</li>
            <li><strong>Team Interviews:</strong> Meet with potential team members and managers</li>
            <li><strong>Final Decision:</strong> We make an offer to the successful candidate</li>
        </ol>
        <p>We aim to complete the entire process within 2-3 weeks to respect your time.</p>
    </section>

    <div class="floating-apply">
    <input type="checkbox" id="toggle-apply" class="toggle-apply-checkbox">
    <label for="toggle-apply" class="toggle-apply-header">
        💼 Why Join Us? <span class="toggle-icon">+</span>
    </label>

    <div class="apply-content">
        <h3>Why Join Us?</h3>
        <p>At Huzztml, we believe in:</p>
        <ul>
            <li>Innovative work environment</li>
            <li>Competitive compensation</li>
            <li>Flexible work arrangements</li>
            <li>Continuous learning opportunities</li>
            <li>Career advancement</li>
        </ul>
        <p>We're always looking for talented individuals who are passionate about technology and innovation.</p>
        <a href="apply.php" class="cta-button">Apply Now</a>
    </div>
</div>
</main>

<?php include("includes/footer.inc"); ?>
</body>
</html>
