<?php
// Database Connection
$host = "localhost";
$user = "root";
$password = "";
$database = "jobportal";

$conn = new mysqli($host, $user, $password, $database);

// Check Connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Ensure the 'approval_status' column exists in the resumes table
$columnCheckQuery = "SHOW COLUMNS FROM resumes LIKE 'approval_status'";
$columnResult = $conn->query($columnCheckQuery);
if ($columnResult->num_rows == 0) {
    $alterQuery = "ALTER TABLE resumes ADD COLUMN approval_status ENUM('Pending', 'Selected', 'Rejected') DEFAULT 'Pending'";
    if (!$conn->query($alterQuery)) {
        die("Error adding approval_status column: " . $conn->error);
    }
}

$message = "";

// Handle Resume Deletion
if (isset($_GET['delete_id'])) {
    $resume_id = intval($_GET['delete_id']);
    $deleteQuery = "DELETE FROM resumes WHERE resume_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    if ($stmt) {
        $stmt->bind_param("i", $resume_id);
        if ($stmt->execute()) {
            $message = "Resume deleted successfully!";
        } else {
            $message = "Error deleting resume: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Error preparing delete query: " . $conn->error;
    }
}

// Handle Approval Status Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $resume_id = intval($_POST['resume_id']);
    $approval_status = $_POST['approval_status'];
    $updateQuery = "UPDATE resumes SET approval_status = ? WHERE resume_id = ?";
    $stmt = $conn->prepare($updateQuery);
    if ($stmt) {
        $stmt->bind_param("si", $approval_status, $resume_id);
        if ($stmt->execute()) {
            $message = "Status updated successfully!";
        } else {
            $message = "Error updating status: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Error preparing update query: " . $conn->error;
    }
}

// Fetch resumes
$sql = "SELECT * FROM resumes WHERE resume_link IS NOT NULL AND resume_link != ''";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>HR - Resume Management & Job Posting</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #a8e8e7;
      padding: 0;
      margin: 0;
    }

    .top-image {
      width: 100%;
      max-height: 300px;
      object-fit: cover;
      display: block;
    }

    .container {
      width: 90%;
      margin: 40px auto;
      background: white;
      padding: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      border-radius: 10px;
      animation: slideIn 1s ease-in-out;
    }

    @keyframes slideIn {
      from {
        transform: translateY(100px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    h2 {
      background:rgb(5, 79, 110);
      color: white;
      padding: 10px;
      border-radius: 5px;
      text-align: center;
    }

    .logout-btn {
      position: absolute;
      top: 20px;
      right: 20px;
      background: red;
      color: white;
      padding: 8px 15px;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    table, th, td {
      border: 1px solid black;
    }

    th, td {
      padding: 10px;
      text-align: center;
    }

    .btn-remove {
      background: red;
      color: white;
      border: none;
      padding: 5px 10px;
      cursor: pointer;
    }

    .btn-update {
      background: #007bff;
      color: white;
      border: none;
      padding: 5px 10px;
      cursor: pointer;
      margin-top: 5px;
    }

    form.inline {
      display: inline;
    }

    input, select, button {
      padding: 8px;
      width: 100%;
      margin-bottom: 10px;
      box-sizing: border-box;
    }

    button {
      background-color: #007bff;
      color: white;
      border: none;
      cursor: pointer;
    }

    button:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>

  <img class="top-image" src="https://www.sutisoft.com/blog/wp-content/uploads/2015/04/HR-Management-Systems-scaled.jpeg" alt="HR Banner"/>

  <a href="http://localhost/jobportal/login_page.php" class="logout-btn">Logout</a>

  <div class="container">
    <h2>Uploaded Resumes & Approval Status</h2>
    <?php if (!empty($message)) { echo "<p style='color: green;'>$message</p>"; } ?>

    <table>
      <tr>
        <th>Resume ID</th>
        <th>User ID</th>
        <th>Resume Link</th>
        <th>Uploaded At</th>
        <th>Approval Status</th>
        <th>Actions</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()) {
        $status = $row['approval_status'] ?? 'Pending';
      ?>
      <tr>
        <td><?php echo htmlspecialchars($row['resume_id']); ?></td>
        <td><?php echo htmlspecialchars($row['user_id']); ?></td>
        <td><a href="<?php echo htmlspecialchars($row['resume_link']); ?>" target="_blank">View Resume</a></td>
        <td><?php echo htmlspecialchars($row['uploaded_at']); ?></td>
        <td>
          <form method="POST" class="inline">
            <input type="hidden" name="resume_id" value="<?php echo $row['resume_id']; ?>">
            <select name="approval_status" required>
              <option value="Pending" <?php if ($status === "Pending") echo "selected"; ?>>Pending</option>
              <option value="Selected" <?php if ($status === "Selected") echo "selected"; ?>>Selected</option>
              <option value="Rejected" <?php if ($status === "Rejected") echo "selected"; ?>>Rejected</option>
            </select>
            <button type="submit" name="update_status" class="btn-update">Update</button>
          </form>
        </td>
        <td>
          <a href="?delete_id=<?php echo $row['resume_id']; ?>" onclick="return confirm('Are you sure?');">
            <button class="btn-remove">Remove</button>
          </a>
        </td>
      </tr>
      <?php } ?>
    </table>
  </div>

  <div class="container">
    <h2>Post Jobs</h2>
    <form id="jobForm">
      <label>Name of Post:</label>
      <input type="text" id="postName" required>

      <label>No. of Vacancies:</label>
      <input type="number" id="vacancies" required>

      <label>Posting Due Date:</label>
      <input type="date" id="dueDate" required>

      <label>Minimum Salary:</label>
      <input type="number" id="salary" required>

      <label>Contact Info:</label>
      <input type="text" id="contactInfo" required>

      <label>Location of Company:</label>
      <input type="text" id="location" required>

      <button type="button" onclick="addJob()">Add Job</button>
    </form>

    <h3>Posted Jobs:</h3>
    <table id="jobTable">
      <thead>
        <tr>
          <th>Name of Post</th>
          <th>Vacancies</th>
          <th>Due Date</th>
          <th>Salary</th>
          <th>Contact Info</th>
          <th>Location</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>

  <script>
    function addJob() {
      const postName = document.getElementById('postName').value;
      const vacancies = document.getElementById('vacancies').value;
      const dueDate = document.getElementById('dueDate').value;
      const salary = document.getElementById('salary').value;
      const contactInfo = document.getElementById('contactInfo').value;
      const location = document.getElementById('location').value;

      if (!postName || !vacancies || !dueDate || !salary || !contactInfo || !location) {
        alert("Please fill all fields.");
        return;
      }

      const table = document.getElementById('jobTable').getElementsByTagName('tbody')[0];
      const row = table.insertRow();

      row.innerHTML = `
        <td>${postName}</td>
        <td>${vacancies}</td>
        <td>${dueDate}</td>
        <td>${salary}</td>
        <td>${contactInfo}</td>
        <td>${location}</td>
        <td><button onclick="this.closest('tr').remove()">Delete</button></td>
      `;

      document.getElementById('jobForm').reset();
    }
  </script>

</body>
</html>
