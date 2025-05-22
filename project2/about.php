<?php include("includes/header.inc"); ?>
<link rel="stylesheet" href="styles/about.css">
<?php include("includes/menu.inc"); ?>

<header>
    <h1>Huzztml</h1>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="jobs.php">Positions</a></li>
        <li><a href="apply.php">Apply</a></li>
        <li><a href="about.php" class="active">About</a></li>
        <li><a href="mailto:info@huzztml.com">Email</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <section>
      <h2>Group Profile</h2>
      <p>We are <strong>Huzztml</strong>, a group of creative students from the Thursday 12:30PM class working together to build a complete recruitment website.</p>
      <ul>
        <li>
          <strong>Group Info:</strong>
          <ul>
            <li>Class Time: Thursday, 2PM</li>
            <li>Group Name: Huzztml</li>
            <li>
              <strong>All Members:</strong>
              <ul>
                <li>Hayyan <span class="student-id">105931299</span></li>
                <li>Noah <span class="student-id">105920925</span></li>
                <li>Mujtaba <span class="student-id">105927968</span></li>
                <li>Zan <span class="student-id">105927968</span></li>
              </ul>
            </li>
          </ul>
        </li>
        <li>Tutor: Rahul man</li>
      </ul>
    </section>

    <section>
      <h2>Contributions</h2>
      <dl>
        <dt>Hayyan</dt>
        <dd>About page, group content, summaries, and structure</dd>

        <dt>Noah</dt>
        <dd>Main CSS layout, final design tweaks</dd>

        <dt>Mujtaba</dt>
        <dd>Job page and form back-end PHP logic</dd>

        <dt>Zan</dt>
        <dd>Apply page styling and design</dd>

        </dl>
    </section>

    <section>
      <h2>Our Team Photo</h2>
      <figure>
        <img src="styles/images/group.webp" alt="Group Photo of Huzztml Team" width="3000" />
        <figcaption>Huzztml Team — April 2025</figcaption>
      </figure>
    </section>

    <section>
      <h2>Member Interests</h2>
      <table>
        <caption>Our Interests</caption>
        <thead>
          <tr>
            <th>Name</th>
            <th>Music</th>
            <th>Books</th>
            <th colspan="2">Movies & Hobbies</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Hayyan</td>
            <td>Hip-Hop</td>
            <td>Fantasy</td>
            <td colspan="2">Sci-Fi Films, Football</td>
          </tr>
          <tr>
            <td>Noah</td>
            <td>Huzz</td>
            <td>Science</td>
            <td>Action Movies</td>
            <td>Gym</td>
          </tr>
          <tr>
            <td>Zan</td>
            <td>Pop</td>
            <td>Comics</td>
            <td>Drama</td>
            <td>Skating</td>
          </tr>
          <!-- Adding more interests to each member -->
          <tr>
            <td>Hayyan</td>
            <td>Classical</td>
            <td>Historical Fiction</td>
            <td>Adventure Movies</td>
            <td>Cycling</td>
          </tr>
          <tr>
            <td>Noah</td>
            <td>Indie</td>
            <td>Non-fiction</td>
            <td>Documentaries</td>
            <td>Photography</td>
          </tr>
          <tr>
            <td>Mujtaba</td>
            <td>Rock</td>
            <td>Thrillers</td>
            <td>Horror Movies</td>
            <td>Gaming</td>
          </tr>
          <tr>
            <td>Zan</td>
            <td>Electronic</td>
            <td>Mystery</td>
            <td>Fantasy Movies</td>
            <td>Traveling</td>
          </tr>
        </tbody>
      </table>
    </section>
  </main>

  <?php include("includes/footer.inc"); ?>