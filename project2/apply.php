<?php include("includes/header.inc"); ?>
<link rel="stylesheet" href="styles/applystyle.css">
<?php include("includes/menu.inc"); ?>

<!-- Navigation bar -->
<nav class="navbar">
    <div>
        <a href="index.php">
            <h1>
                <img src="styles/images/logo.png" alt="Logo" onerror="this.onerror=null; this.src='../../images/default-logo.png';">
                Home
            </h1>
        </a>
        <a href="jobs.php">
            <span>Back to Jobs</span>
        </a>
    </div>
</nav>

<main>
    <div>
        <h1>Apply for a Job</h1>
        <form id="applicationForm" action="process_eoi.php" method="post" enctype="multipart/form-data" novalidate="novalidate">

            <!-- Job title -->
            <div>
                <label for="jobTitle">Job Title *</label>
                <select id="jobTitle" name="jobref" required>
                    <option value="" disabled selected>Select a job</option>
                    <option value="DEV01">Software Developer</option>
                    <option value="SEC02">Cyber Security Analyst</option>
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
                    <label><input type="checkbox" name="skills[]" value="Skill1"> Programming</label>
                    <label><input type="checkbox" name="skills[]" value="Skill2"> Networking</label>
                    <label><input type="checkbox" name="skills[]" value="Skill3"> Database Management</label>
                    <label><input type="checkbox" name="skills[]" value="Skill4"> Web Development</label>
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

<?php include("includes/footer.inc"); ?>
