<?php
session_start();
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'jobportal';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$companies_result = $conn->query("SELECT company_id, company_name FROM Companies");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_job'])) {
    $company_id = $_POST['company_id'];
    $job_title = $_POST['job_title'];
    $job_description = $_POST['job_description'];
    $location = $_POST['location'];
    $salary = $_POST['salary'];
    $job_type = $_POST['job_type'];

    $sql = "INSERT INTO Jobs (company_id, job_title, job_description, location, salary, job_type) 
            VALUES ('$company_id', '$job_title', '$job_description', '$location', '$salary', '$job_type')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Job posted successfully!'); window.location.href='http://localhost/jobportal/application.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Search Jobs!!</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: url('https://wallpapercave.com/wp/wp6056707.jpg') repeat-x;
      background-size: cover;
      animation: moveBg 60s linear infinite;
      color: #fff;
    }

    @keyframes moveBg {
      from { background-position: 0 0; }
      to { background-position: -2000px 0; }
    }

    .header {
      text-align: center;
      font-size: 2.5rem;
      margin: 40px 0 30px;
      font-weight: bold;
      letter-spacing: 1.5px;
      text-shadow: 2px 2px 8px #000;
    }

    /* Slide animation */
    @keyframes slideUp {
      0% {
        transform: translateY(50px);
        opacity: 0;
      }
      100% {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .container {
      max-width: 700px;
      margin: 0 auto;
      padding: 30px;
      background-color: #ffffff;
      border-radius: 12px;
      box-shadow: 0 10px 30px #f2f3f1;
      color: #000;
      animation: slideUp 1s ease-out;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      font-size: 1.8rem;
      color: #1a1a1a;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    input, select, textarea, button {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
      outline: none;
    }

    select, input, textarea {
      background: #f2f2f2;
      color: #000;
    }

    textarea {
      resize: vertical;
      min-height: 100px;
    }

    button {
      background-color: #333;
      color: white;
      font-weight: bold;
      transition: background 0.3s;
      cursor: pointer;
    }

    button:hover {
      background-color: #000;
    }

    .next-btn {
      position: fixed;
      bottom: 20px;
      right: 20px;
      padding: 12px 24px;
      background-color: rgb(90, 93, 97);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    .next-btn:hover {
      background-color: rgb(82, 83, 84);
    }

    @media (max-width: 768px) {
      .container {
        padding: 20px;
      }

      .next-btn {
        padding: 10px 20px;
        font-size: 14px;
      }
    }
  </style>
</head>
<body>
  <div class="header">Search Jobs!!</div>

  <div class="container">
    <h2>Post New Job</h2>
    <form method="POST">
      <label>Select Company:</label>
      <select name="company_id" required>
        <option value="">-- Select Company --</option>
        <?php while ($company = $companies_result->fetch_assoc()) { ?>
          <option value="<?= $company['company_id'] ?>"><?= $company['company_name'] ?></option>
        <?php } ?>
      </select>

      <input type="text" name="job_title" placeholder="Job Title" required>
      <textarea name="job_description" placeholder="Job Description" required></textarea>
      <input type="text" name="location" placeholder="Location" required>
      <input type="number" name="salary" step="0.01" placeholder="Salary (Optional)">

      <select name="job_type" required>
        <option value="">-- Select Job Type --</option>
        <option value="Full-Time">Full-Time</option>
        <option value="Part-Time">Part-Time</option>
        <option value="Contract">Contract</option>
        <option value="Internship">Internship</option>
      </select>

      <button type="submit" name="submit_job">Submit Job</button>
    </form>
  </div>

  <!-- Next Button -->
  <a href="http://localhost/jobportal/application.php" class="next-btn">Next</a>
</body>
</html>
