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

// Handle Resume Upload
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload_resume'])) {
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;
    $resume_link = "";

    if (!empty($_FILES["resume"]["name"])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $resume_link = $targetDir . basename($_FILES["resume"]["name"]);
        move_uploaded_file($_FILES["resume"]["tmp_name"], $resume_link);
    }

    if (!empty($user_id) && !empty($resume_link)) {
        $insertQuery = "INSERT INTO resumes (user_id, resume_link, uploaded_at) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($insertQuery);
        if ($stmt) {
            $stmt->bind_param("is", $user_id, $resume_link);
            if ($stmt->execute()) {
                $message = "Resume uploaded successfully!";
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Error preparing SQL query: " . $conn->error;
        }
    } else {
        $message = "All fields are required!";
    }
}

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
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Error preparing delete query: " . $conn->error;
    }
}

// Fetch Resumes Data
$sql = "SELECT * FROM resumes";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume Table</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('https://designshack.net/wp-content/uploads/Magazine-Template-.jpeg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 800px;
            background: rgba(255, 255, 255, 0.85);
            box-shadow: 0 0 20px white;
            border-radius: 15px;
            padding: 20px;
            animation: slideIn 1s ease-out;
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
            background:hsl(0, 0.80%, 24.10%);
            color: white;
            padding: 12px;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            font-weight: bold;
        }

        select, input[type="file"], button {
            width: 100%;
            padding: 8px;
        }

        .btn {
            padding: 10px;
            background:rgb(79, 80, 79);
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn-remove {
            background: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .next-btn {
            float: right;
            margin-top: 20px;
            background:rgb(53, 53, 53);
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Resume Table</h2>

        <?php if (!empty($message)) { echo "<p style='color: green;'>$message</p>"; } ?>

        <h3>Upload Resume</h3>
        <form action="" method="POST" enctype="multipart/form-data">
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
                <label for="resume">Upload Resume:</label>
                <input type="file" id="resume" name="resume" required>
            </div>

            <button type="submit" name="upload_resume" class="btn">Upload Resume</button>
        </form>

        <h3>Uploaded Resumes</h3>
        <table>
            <tr>
                <th>Resume ID</th>
                <th>User ID</th>
                <th>Resume Link</th>
                <th>Uploaded At</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['resume_id']); ?></td>
                <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                <td><a href="<?php echo htmlspecialchars($row['resume_link']); ?>" target="_blank">View Resume</a></td>
                <td><?php echo htmlspecialchars($row['uploaded_at']); ?></td>
                <td>
                    <a href="?delete_id=<?php echo $row['resume_id']; ?>" onclick="return confirm('Are you sure?');">
                        <button class="btn-remove">Remove</button>
                    </a>
                </td>
            </tr>
            <?php } ?>
        </table>

        <a href="saved_jobs.php" class="next-btn">Next</a>
    </div>
</body>
</html>
