<?php include("header.inc"); ?>
<?php include("menu.inc"); ?>

<!-- Navigation bar -->
    <nav class="navbar">
        <div>
            <!-- Link to the home page -->
            <a href="index.php">
                <h1>
                    <img src="../images/logo.png" alt="Logo" onerror="this.onerror=null; this.src='../../images/default-logo.png';">
                    Home
                </h1>
            </a>
            <!-- Link to the jobs page -->
            <a href="jobs.php">
                <span>Back to Jobs</span>
            </a>
        </div>
    </nav>

    <main>
        <div>
            <h1>Apply for a Job</h1>
            <!-- Job application form -->
            <form id="applicationForm" action="process_eoi.php" method="post" enctype="multipart/form-data" novalidate="novalidate">
                <div>
                    <!-- Job title selection -->
                    <select id="jobTitle" name="jobref">
                    <input type="text" id="firstName" name="fname">
                    <input type="text" id="lastName" name="lname">
                    <input type="text" id="streetAddress" name="street">
                    <input type="text" id="suburb" name="suburb">
                    <select id="state" name="state">
                    <input type="text" id="postcode" name="postcode">
                    <input type="email" id="email" name="email">
                    <input type="text" id="phone" name="phone">

                
                <!-- Technical skills section -->
                <fieldset>
                    <legend>Required Technical Skills *</legend>
                    <div id="skillsContainer">
                        <label><input type="checkbox" name="skills[]" value="Skill1"> Programming</label>
                    <label><input type="checkbox" name="skills[]" value="Skill2"> Networking</label>
                    <label><input type="checkbox" name="skills[]" value="Skill3"> Database Management</label>
                    <label><input type="checkbox" name="skills[]" value="Skill4"> Web Development</label>
                    </div>
                    <button type="button" id="addSkillButton">+ Add Skill</button>
                </fieldset>
                
                <div>
                <label for="otherskills">Other Skills</label>
                <textarea id="otherskills" name="otherskills" rows="4" cols="50"></textarea>
                </div>


                <!-- Resume upload section -->
                <div>
                    <label for="resume">Upload Resume *</label>
                    <div id="dropZone" style="border: 2px dashed #ccc; padding: 20px; text-align: center;">
                        Drag and drop your file here or click to upload
                        <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx" style="display: none;" required>
                    </div>
                </div>
                
                <!-- Submit button -->
                <div>
                    <button type="submit">
                        Submit Application
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- JavaScript for interactive features -->
    <script>
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('resume');

        dropZone.addEventListener('click', () => fileInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = 'green';
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.style.borderColor = '#ccc';
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#ccc';
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
            }
        });

        const skillsContainer = document.getElementById('skillsContainer');
        const addSkillButton = document.getElementById('addSkillButton');

        addSkillButton.addEventListener('click', () => {
            const newSkillDiv = document.createElement('div');
            const newSkillInput = document.createElement('input');
            const newSkillLabel = document.createElement('label');

            newSkillInput.type = 'text';
            newSkillInput.name = 'skills';
            newSkillInput.placeholder = 'Enter skill';
            newSkillInput.required = true;

            newSkillLabel.appendChild(newSkillInput);
            newSkillDiv.appendChild(newSkillLabel);
            skillsContainer.appendChild(newSkillDiv);
        });
    </script>
    <?php include 'footer.inc'; ?>

<?php include("footer.inc"); ?>