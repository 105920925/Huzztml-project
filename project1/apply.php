<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Apply</title>
  <link rel="stylesheet" href="styles/indexstyles.css">
</head>
<body>
  <?php include 'header.inc'; ?>
  <?php include 'menu.inc'; ?>
  <section>
    <h1>Apply for a Job</h1>
    <form action="submit_application.php" method="post">
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required>
      
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required>
      
      <label for="phone">Phone:</label>
      <input type="tel" id="phone" name="phone" required>
      
      <label for="position">Position:</label>
      <select id="position" name="position" required>
        <option value="cyber_security_analyst">Cyber Security Analyst</option>
        <option value="software_developer">Software Developer</option>
      </select>
      
      <label for="resume">Resume:</label>
      <input type="file" id="resume" name="resume" required>
      
      <button type="submit">Submit Application</button>
    </form>
  </section>
  <?php include 'footer.inc'; ?>
</body>
</html>