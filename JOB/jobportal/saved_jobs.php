<?php
// Database Connection
$host = "localhost";
$user = "root";
$password = "";
$database = "jobportal";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch User IDs for Dropdown
$userQuery = "SELECT user_id FROM users";
$userResult = $conn->query($userQuery);

// Fetch Job IDs for Dropdown
$jobQuery = "SELECT job_id FROM jobs";
$jobResult = $conn->query($jobQuery);

// Handle Form Submission
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $job_id = $_POST['job_id'];

    $sql_insert = "INSERT INTO saved_jobs (user_id, job_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("ii", $user_id, $job_id);

    if ($stmt->execute()) {
        $message = "Job saved successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch Saved Jobs Data
$sql = "SELECT * FROM saved_jobs";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Jobs</title>
    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: url('http://www.pixelstalk.net/wp-content/uploads/2016/04/Grey-backgrounds-wallpapers-HD.png');
            background-size: cover;
            background-repeat: repeat;
            animation: moveBackground 30s linear infinite;
        }

        @keyframes moveBackground {
            from { background-position: 0 0; }
            to { background-position: 1000px 0; }
        }

        .container {
            width: 70%;
            margin: 80px auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            box-shadow: 0 0 20px white;
            border-radius: 15px;
            transition: transform 0.5s ease-in-out;
            animation: slideIn 1.2s ease-out forwards;
            opacity: 0;
        }

        @keyframes slideIn {
            0% { transform: translateY(40px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #444;
        }

        th, td {
            padding: 10px;
            text-align: center;
            background-color: #f9f9f9;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            font-weight: bold;
        }

        select, .btn {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            font-size: 1rem;
        }

        select {
            border: 1px solid #aaa;
        }

        .btn {
            background:rgb(54, 54, 54);
            color: white;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background:rgb(72, 72, 72);
        }

        .logout-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 10px 15px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .logout-btn:hover {
            background: #a81e2f;
        }

        h2 {
            background:rgb(63, 63, 63);
            color: white;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        p.message {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <a href="http://localhost/jobportal/register2.php?page=login">
        <button class="logout-btn">Logout</button>
    </a>
    <div class="container">
        <h2>Saved Jobs</h2>

        <?php if (!empty($message)) { echo "<p class='message'>$message</p>"; } ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="user_id">User ID:</label>
                <select id="user_id" name="user_id" required>
                    <option value="">Select User ID</option>
                    <?php while ($userRow = $userResult->fetch_assoc()) { ?>
                        <option value="<?php echo $userRow['user_id']; ?>"><?php echo $userRow['user_id']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="job_id">Job ID:</label>
                <select id="job_id" name="job_id" required>
                    <option value="">Select Job ID</option>
                    <?php while ($jobRow = $jobResult->fetch_assoc()) { ?>
                        <option value="<?php echo $jobRow['job_id']; ?>"><?php echo $jobRow['job_id']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn">Save Job</button>
        </form>

        <h2>Saved Jobs List</h2>
        <table>
            <tr>
                <th>Saved ID</th>
                <th>User ID</th>
                <th>Job ID</th>
                <th>Saved At</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['saved_id']); ?></td>
                <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                <td><?php echo htmlspecialchars($row['job_id']); ?></td>
                <td><?php echo htmlspecialchars($row['saved_at']); ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
