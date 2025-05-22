<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jobs</title>
  <link rel="stylesheet" href="styles/indexstyles.css">
</head>
<body>
  <?php include 'header.inc'; ?>
  <?php include 'menu.inc'; ?>

  <section>
    <h1>Job Opportunities</h1>
    <p>Explore the latest job opportunities at our company. We are looking for talented individuals to join our team.</p>

    <div class="job-container">
      <div class="job-box">
        <img src="styles/images/cybersecurity.jpg" alt="Cyber Security Analyst">
        <h2>Cyber Security Analyst</h2>
        <p>Location: New York, NY</p>
        <p>Experience: 3+ years</p>
        <button>Read More</button>
      </div>
      <div class="job-box">
        <img src="styles/images/softwaredeveloper.jpg" alt="Software Developer">
        <h2>Software Developer</h2>
        <p>Location: San Francisco, CA</p>
        <p>Experience: 2+ years</p>
        <button>Read More</button>
      </div>
    </div>
  </section>

  <?php include 'footer.inc'; ?>
</body>
</html>