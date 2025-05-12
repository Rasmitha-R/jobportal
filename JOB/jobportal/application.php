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

// Fetch Job IDs for Dropdown
$jobQuery = "SELECT job_id FROM jobs";
$jobResult = $conn->query($jobQuery);

// Fetch User IDs for Dropdown
$userQuery = "SELECT user_id FROM users";
$userResult = $conn->query($userQuery);

// Handle Form Submission
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_id = $_POST['job_id'];
    $user_id = $_POST['user_id'];
    $cover_letter = $_POST['cover_letter'];
    $application_status = $_POST['application_status'];

    $checkJob = "SELECT job_id FROM jobs WHERE job_id = ?";
    $stmtJob = $conn->prepare($checkJob);
    $stmtJob->bind_param("i", $job_id);
    $stmtJob->execute();
    $stmtJob->store_result();

    if ($stmtJob->num_rows > 0) {
        $sql_insert = "INSERT INTO applications (job_id, user_id, cover_letter, application_status) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("iiss", $job_id, $user_id, $cover_letter, $application_status);

        if ($stmt->execute()) {
            $message = "Application submitted successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Error: Selected Job ID does not exist!";
    }
    $stmtJob->close();
}

$sql = "SELECT * FROM applications";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application Portal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('https://karolo.com/wp-content/uploads/2022/04/informed-1-1-1.jpg') repeat-x;
            background-size: cover;
            animation: moveBg 30s linear infinite;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        @keyframes moveBg {
            0% { background-position: 0 0; }
            100% { background-position: -1000px 0; }
        }

        .container {
            display: flex;
            flex-direction: row;
            gap: 30px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 18px;
            padding: 40px 60px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.2);
            animation: slideIn 1s ease-in-out;
            max-width: 70%;
            min-height: 500px;
            margin: 20px;
            backdrop-filter: blur(10px);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .title {
            background: #1a1a1a;
            color: white;
            padding: 12px;
            font-size: 22px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            font-size: 14px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #333;
            color: white;
        }

        .form-group {
            margin-bottom: 10px;
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        input, select, textarea {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
        }

        .btn {
            padding: 12px 20px;
            background: #333;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #555;
        }

        .message {
            color: green;
            font-size: 16px;
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .next-btn {
            position: fixed;
            right: 30px;
            bottom: 30px;
            background: #222;
        }

        .next-btn:hover {
            background: #555;
        }

    </style>
</head>
<body>

<div class="container">
    <div class="section">
        <div class="title">Submitted Applications</div>
        <?php if (!empty($message)) { echo "<p class='message'>$message</p>"; } ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Job</th>
                <th>User</th>
                <th>Letter</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['application_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['job_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['cover_letter']); ?></td>
                    <td><?php echo htmlspecialchars($row['applied_at']); ?></td>
                    <td><?php echo htmlspecialchars($row['application_status']); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <div class="section">
        <div class="title">Apply for a Job</div>
        <form action="" method="POST">
            <div class="form-group">
                <label for="job_id">Job ID:</label>
                <select id="job_id" name="job_id" required>
                    <option value="">Select Job ID</option>
                    <?php while ($jobRow = $jobResult->fetch_assoc()) { ?>
                        <option value="<?php echo $jobRow['job_id']; ?>"><?php echo $jobRow['job_id']; ?></option>
                    <?php } ?>
                </select>
            </div>
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
                <label for="cover_letter">Cover Letter:</label>
                <textarea id="cover_letter" name="cover_letter" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="application_status">Application Status:</label>
                <select id="application_status" name="application_status">
                    <option value="Pending">Pending</option>
                    <option value="Reviewed">Reviewed</option>
                    <option value="Accepted">Accepted</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>
            <button type="submit" class="btn">Submit Application</button>
        </form>
    </div>
</div>

<a href="http://localhost/jobportal/view_resumes.php">
    <button class="btn next-btn">Next</button>
</a>

</body>
</html>
