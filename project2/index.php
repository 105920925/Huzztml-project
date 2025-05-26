<?php include("includes/header.inc"); ?>
<?php include("includes/menu.inc"); ?>

<!-- Correct way to link CSS -->
<link rel="stylesheet" href="styles/indexstyles.css">

<header>
    <div class="header-content">
        <h1>Welcome to Huzztml</h1>
        <p>Your gateway to your dream job</p>
        <div class="logo-container">
            <img src="styles/images/logo.png" alt="Huzztml Logo">
            <p>
                At Huzztml, we connect talented individuals with top employers across the globe. Our mission is to empower job seekers by providing a seamless platform to explore opportunities, apply effortlessly, and achieve career success.
            </p>
        </div>
    </div>
</header>

<section>
    <h1>&#8226; Find Your Next Opportunity</h1>
    <p class="intro-text">&#9702; Search thousands of job listings and apply online today!</p>
    <button onclick="location.href='jobs.php'">Get Started</button>
    <div class="featured-jobs">
        <h2>Featured Jobs</h2>
        <div class="job-container">
            <div class="job-box">
                <img src="styles/images/softwaredev.jpeg" alt="Software Developer">
                <h3>Software Developer</h3>
                <p>Join a dynamic team to design, develop, and maintain cutting-edge software solutions. Work with modern technologies and collaborate with talented professionals to create impactful applications.</p>
                <p>Ideal for problem solvers and creative thinkers who thrive in a fast-paced environment.</p>
                <button onclick="location.href='jobs.php'">Read More</button>
            </div>
            <div class="job-box">
                <img src="styles/images/cybersec.png" alt="Cyber Security Analyst">
                <h3>Cyber Security Analyst</h3>
                <p>Protect organizations from cyber threats by analyzing risks and implementing robust security measures. Stay ahead of evolving threats and ensure the safety of critical systems and data.</p>
                <p>Perfect for detail-oriented professionals with a passion for cybersecurity and problem-solving.</p>
                <button onclick="location.href='jobs.php'" style="background-color: #0066cc; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;">Read More</button>
            </div>
        </div>
    </div>
</section>

<section>
    <div>
        <h2>&#8226; Search Jobs</h2>
        <p style="margin-left: 20px;">&#9702; Find jobs that match your skills and interests.</p>
        <p style="margin-left: 20px;">&#9702; Use advanced filters to narrow down your search and discover opportunities tailored to you.</p>
    </div>
    <div>
        <h2>&#8226; Apply Online</h2>
        <p style="margin-left: 20px;">&#9702; Submit your applications with just a few clicks.</p>
        <p style="margin-left: 20px;">&#9702; Save time by applying to multiple jobs with a single profile.</p>
    </div>
    <div> 
        <h2>&#8226; Get Hired</h2>
        <p style="margin-left: 20px;">&#9702; Connect with top employers and land your dream job.</p>
        <p style="margin-left: 20px;">&#9702; Receive personalized recommendations and interview tips to boost your chances.</p>
    </div>
</section>

<?php include("includes/footer.inc"); ?>
