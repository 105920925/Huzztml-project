<?php include("header.inc"); ?>
<?php include("menu.inc"); ?>

<?php include("settings.php"); ?>

<!-- Create the connection to the database -->
<?php
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);

// Check for connection errors
if (!$conn) {
    echo "<p>Database connection failure: " . mysqli_connect_error() . "</p>";
    exit();
}

// SQL query to fetch all job listings
$query = "SELECT * FROM jobs";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if (!$result) {
    echo "<p>Failed to retrieve job listings.</p>";
} else {
    echo "<main class='container'>";
    echo "<section id='current-openings'>";
    echo "<h2>Current Job Openings</h2>";
    echo "<p>Explore our exciting opportunities and find your next career move with us.</p>";

    // Loop through each job listing and display them
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<section class='job-card'>";
        echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";  // Display job title

        // Display job metadata: reference, reports to, and salary range
        echo "<div class='job-meta'>";
        echo "<span><strong>Reference:</strong> " . htmlspecialchars($row['job_ref']) . "</span>";  // Job reference
        echo "<span><strong>Reports To:</strong> " . htmlspecialchars($row['reports_to']) . "</span>";  // Reporting manager
        echo "<span><strong>Salary Range:</strong> " . htmlspecialchars($row['salary_range']) . "</span>";  // Salary range
        echo "</div>";

        // Job position overview
        echo "<h4>Position Overview</h4>";
        echo "<p>" . nl2br(htmlspecialchars($row['description'])) . "</p>";  // Job description

        // Key Responsibilities (assuming newline-separated values in database)
        echo "<h4>Key Responsibilities</h4>";
        echo "<ol>";
        $responsibilities = explode("\n", $row['key_responsibilities']);  // Split responsibilities by new line
        foreach ($responsibilities as $responsibility) {
            echo "<li>" . htmlspecialchars($responsibility) . "</li>";
        }
        echo "</ol>";

        // Qualifications & Skills Section
        echo "<h4>Qualifications & Skills</h4>";
        echo "<div class='requirements'>";

        // Essential Skills
        echo "<div><h5>Essential:</h5><ul>";
        $essential_skills = explode("\n", $row['essential_skills']);  // Split essential skills by new line
        foreach ($essential_skills as $skill) {
            echo "<li>" . htmlspecialchars($skill) . "</li>";
        }
        echo "</ul></div>";

        // Preferable Skills
        echo "<div><h5>Preferable:</h5><ul>";
        $preferable_skills = explode("\n", $row['preferable_skills']);  // Split preferable skills by new line
        foreach ($preferable_skills as $skill) {
            echo "<li>" . htmlspecialchars($skill) . "</li>";
        }
        echo "</ul></div>";

        echo "</div>";  // Close requirements div
        echo "</section>";  // Close job-card section
    }

    echo "</section>"; // Close current-openings section
    echo "</main>"; // Close container div
}

// Close the database connection
mysqli_close($conn);

include("footer.inc");
?>

<!-- Navigation bar - appears at the top of all pages -->
<nav>
    <ul>
        <!-- Navigation links - connect to other pages -->
        <li><a href="index.php">Home</a></li>
        <li><a href="apply.php">Apply Now</a></li>
        <li><a href="about.php">About Us</a></li>
    </ul>
</nav>

<!-- Header section - contains page title and introductory text -->
<header>
    <div class="container header-container">
        <div class="header-image">
            <img src="../images/header-image.jpg" alt="Technology image">
        </div>
        <div class="header-text">
            <img src="../images/logo.png" alt="Huzztml Logo" style="width: 150px; display: block; margin: 0 auto; opacity: 0.7; border-radius: 50%;">

            <h1>Career Opportunities at Huzztml</h1>
            <p>Join our team of passionate tech professionals and help shape the future of technology</p>
        </div>
    </div>
</header>

<!-- Main content area -->
<main class="container">
    <!-- Aside (sidebar) - contains supplementary information -->
    <aside>
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
        <a href="apply.html" class="cta-button">Apply Now</a>
    </aside>

    <!-- Current openings section - contains job listings -->
    <section id="current-openings">
        <h2>Current Job Openings</h2>
        <p>Explore our exciting opportunities and find your next career move with us.</p>

        <!-- Job listings will be inserted dynamically here -->
    </section>
</main>

<?php include 'footer.inc'; ?>

