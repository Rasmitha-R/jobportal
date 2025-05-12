<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About Us - Job Portal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: url('https://wallpapercave.com/wp/wp2019259.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #fff;
      min-height: 100vh;
    }

    .overlay {
      background-color: rgba(0, 0, 0, 0.6);
      padding-bottom: 50px;
      min-height: 100vh;
    }

    header {
      background-color: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      color: #fff;
      padding: 40px 20px;
      text-align: center;
      font-size: 2.5rem;
      font-weight: bold;
      letter-spacing: 2px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    .container {
      max-width: 1100px;
      margin: 40px auto;
      padding: 30px;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 16px;
      backdrop-filter: blur(12px);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .highlight {
      background-color: rgba(255, 255, 255, 0.2);
      padding: 25px;
      text-align: center;
      font-size: 1.7rem;
      margin-bottom: 30px;
      border-left: 5px solid #f39c12;
      border-radius: 12px;
    }

    .section {
      background: rgba(255, 255, 255, 0.1);
      margin-bottom: 25px;
      padding: 25px;
      border-radius: 12px;
      transition: transform 0.3s ease;
    }

    .section:hover {
      transform: translateY(-5px);
    }

    h2 {
      color: #87cefa;
      margin-bottom: 15px;
      border-left: 5px solid #3498db;
      padding-left: 15px;
    }

    ul {
      padding-left: 30px;
      line-height: 1.8;
    }

    p {
      line-height: 1.6;
    }

    .back-button {
      position: fixed;
      bottom: 20px;
      right: 20px;
    }

    .back-button button {
      padding: 12px 22px;
      background-color: #3498db;
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      font-weight: bold;
      box-shadow: 0 5px 10px rgba(0,0,0,0.2);
    }

    .back-button button:hover {
      background-color: #2980b9;
    }

    @media (max-width: 768px) {
      .container {
        padding: 20px;
        margin: 20px;
      }
    }
  </style>
</head>
<body>

  <div class="overlay">
    <header>About Us</header>

    <div class="container">

      <div class="highlight">
        🌟 We are proud to offer <strong>3000+ job opportunities</strong> across multiple industries!
      </div>

      <div class="section">
        <h2>Top Hiring Companies</h2>
        <ul>
          <li>Google Inc.</li>
          <li>Amazon</li>
          <li>Infosys</li>
          <li>Tata Consultancy Services</li>
          <li>Wipro Technologies</li>
          <li>Accenture</li>
          <li>Capgemini</li>
          <li>Zoho Corporation</li>
        </ul>
      </div>

      <div class="section">
        <h2>Contact Information</h2>
        <p>📞 Phone: +91-9876543210</p>
        <p>📧 Email: support@jobportal.com</p>
        <p>📍 Location: 123 Career Lane, Bengaluru, India</p>
      </div>

      <div class="section">
        <h2>Training Scheme for Freshers</h2>
        <p>🎓 Our portal offers free and paid training programs including Resume Building, Interview Skills, Communication, and Technical Courses in Web Development, Python, Java, and more!</p>
        <p>🧑‍🏫 Trainers from top IT companies mentor candidates through live sessions and hands-on projects.</p>
      </div>

    </div>

    <div class="back-button">
      <form method="post">
        <button type="submit" name="back">⬅ Back</button>
      </form>
    </div>
  </div>

</body>
</html>

<?php
if (isset($_POST['back'])) {
  header("Location:register2.php"); // Update this path if needed
  exit();
}
?>
