<?php
// Database connection settings
$host = "localhost";
$username = "root";
$password = ""; // XAMPP default
$dbname = "jobportal";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Insert data if form is submitted
if (isset($_POST['submit'])) {
  $company_name = $_POST['name'];
  $email = $_POST['email'];
  $pass = $_POST['password'];
  $phone = $_POST['phone'];
  $location = $_POST['location'];
  $website = $_POST['website'];

  $sql = "INSERT INTO companies (company_name, email, password, phone, location, website)
          VALUES ('$company_name', '$email', '$pass', '$phone', '$location', '$website')";

  if ($conn->query($sql) === TRUE) {
    $message = "Company info added successfully!";
    header("refresh:1;url=http://localhost/jobportal/search_jobs.php");
  } else {
    $message = "Error: " . $conn->error;
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Company</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: url('https://wallpaperheart.com/wp-content/uploads/2018/04/Professional-Man-Silly-Walks-Play-3D-HD-Widescreen-Wallpapers-professional-background-images-hd.jpg?w=998') repeat-x;
      background-size: cover;
      background-position: center;
      animation: bgScroll 60s linear infinite;
      overflow-x: hidden;
    }

    @keyframes bgScroll {
      from { background-position: 0 0; }
      to { background-position: -2000px 0; }
    }

    .container {
      width: 80%;
      max-width: 800px;
      margin: 60px auto;
      background: rgba(255, 255, 255, 0.95);
      padding: 25px 30px;
      border-radius: 12px;
      box-shadow: 0 6px 20px #e6e9e5;
      animation: fadeIn 1.2s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #222;
      font-size: 26px;
    }

    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
    }

    .form-group label {
      font-weight: 600;
      margin-bottom: 5px;
      color: #333;
    }

    .form-group input {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
    }

    .form-group.full-width {
      grid-column: 1 / -1;
    }

    .form-group button {
      padding: 12px;
      border: none;
      background-color:rgb(32, 32, 32);
      color: white;
      font-size: 16px;
      border-radius: 6px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .form-group button:hover {
      background-color:hsl(210, 2.40%, 16.10%);
    }

    .message {
      margin-top: 15px;
      background-color: #e0f9e0;
      color: #333;
      padding: 10px;
      border-radius: 5px;
      text-align: center;
      font-weight: bold;
    }

    .next-button {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color:hsl(136, 11.60%, 81.40%);
      color: white;
      padding: 14px 24px;
      border-radius: 50px;
      text-decoration: none;
      font-weight: bold;
      box-shadow: 0 8px 16px rgba(0,0,0,0.3);
      transition: transform 0.3s, background-color 0.3s;
      animation: slideIn 1s ease-out 1.5s forwards;
      opacity: 0;
      font-size: 16px;
    }

    .next-button:hover {
      background-color:hsl(120, 1.40%, 27.50%);
      transform: scale(1.05);
    }

    @keyframes slideIn {
      from { transform: translateY(50px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    @media (max-width: 768px) {
      .form-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>

  <div class="container">
    <h2>Add Company Information</h2>
    <form method="POST" action="">
      <div class="form-grid">
        <div class="form-group">
          <label for="name">Company Name</label>
          <input type="text" name="name" required>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" name="password" required>
        </div>
        <div class="form-group">
          <label for="phone">Phone</label>
          <input type="text" name="phone" required>
        </div>
        <div class="form-group">
          <label for="location">Location</label>
          <input type="text" name="location" required>
        </div>
        <div class="form-group">
          <label for="website">Website</label>
          <input type="text" name="website" required>
        </div>
        <div class="form-group full-width">
          <button type="submit" name="submit">Add Company</button>
        </div>
      </div>
    </form>

    <?php if (isset($message)): ?>
      <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
  </div>

  <!-- Floating "Next ➜" button -->
  <a class="next-button" href="http://localhost/jobportal/search_jobs.php">Next ➜</a>

</body>
</html>
