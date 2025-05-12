<?php
// PHP Section: Handle form submission and save to DB
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $phone = $_POST["phone"];
    $role = $_POST["user_role"];

    // Connect to MySQL
    $conn = new mysqli("localhost", "root", "", "jobportal");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert user info
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, phone, user_role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $full_name, $email, $password, $phone, $role);

    if ($stmt->execute()) {
        if ($role == "HR Recruitment") {
            header("Location: http://localhost/jobportal/HR.php");
        } else {
            header("Location: login_page.php");
        }
        exit();
    } else {
        echo "<script>alert('Registration failed. Try again.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Job Portal - Register</title>
    <style>
        * {
            margin: 0; padding: 0; box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('https://wallpaperaccess.com/full/643528.jpg') no-repeat center center fixed;
            background-size: cover; height: 100vh; overflow-x: hidden;
        }
        .top-bar {
            display: flex; justify-content: space-between; align-items: center;
            padding: 15px 30px; background-color: rgba(0, 0, 0, 0.6); color: #fff;
        }
        .toggle-btn { font-size: 24px; cursor: pointer; }
        .page-title { font-size: 24px; text-align: center; flex: 1; }
        .user-section {
            display: flex; align-items: center; gap: 10px; position: relative;
        }
        .user-icon {
            width: 40px; height: 40px; border-radius: 50%; cursor: pointer;
        }
        .user-popup {
            display: none; position: absolute; top: 50px; right: 0;
            background: white; color: #000; padding: 15px; border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2); z-index: 10;
        }
        .logout-btn {
            background-color: crimson; color: #fff; border: none;
            padding: 8px 12px; border-radius: 4px; cursor: pointer;
        }
        .sidebar {
            width: 240px; background-color: #2d3436; color: white; height: 100vh;
            position: fixed; top: 0; left: -240px; transition: left 0.3s ease-in-out;
            padding-top: 60px; z-index: 9;
        }
        .sidebar.show { left: 0; }
        .sidebar a {
            display: block; padding: 15px 20px; color: white;
            text-decoration: none; transition: background 0.3s ease;
        }
        .sidebar a:hover { background-color: #636e72; transform: translateX(5px); }
        .container {
            max-width: 900px; margin: 80px auto; padding: 40px;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 20px; box-shadow: 0 6px 20px white;
            animation: fadeInUp 0.6s ease;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        h2 {
            text-align: center; margin-bottom: 30px; color: #333;
        }
        form {
            display: grid; grid-template-columns: 1fr 1fr; gap: 20px;
        }
        form input, form select {
            width: 100%; padding: 12px; border: 1px solid #ccc;
            border-radius: 8px; font-size: 16px;
        }
        .form-group { display: flex; flex-direction: column; }
        .form-group.full-width { grid-column: span 2; }
        button[type="submit"] {
            background-color: hsl(210, 4%, 10%); color: white;
            padding: 12px; border: none; border-radius: 8px;
            font-size: 16px; cursor: pointer;
        }
        button[type="submit"]:hover { background-color: hsl(0, 0%, 13%); }
        .message {
            grid-column: span 2; text-align: center; margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <div class="toggle-btn" onclick="toggleSidebar()">&#9776;</div>
        <div class="page-title">FIND YOUR DREAM JOB !! - suffers to succeed</div>
        <div class="user-section">
            <img src="https://www.creativefabrica.com/wp-content/uploads/2021/09/09/User-avatar-profile-icon-Graphics-17068385-1.jpg" class="user-icon" onclick="togglePopup()">
            <div class="user-popup" id="popup">
                <strong>Contact:</strong> info@dreamjob.com<br>
                <strong>Email:</strong> support@dreamjob.com
            </div>
            <button class="logout-btn" onclick="window.location.href='login_page.php'">Logout</button>
        </div>
    </div>

    <div class="sidebar" id="sidebar">
        <a href="about.php">About Us</a>
        <a href="add_company.php">Add Company</a>
        <a href="search_jobs.php">Search Jobs</a>
        <a href="application.php">Applications</a>
        <a href="view_resumes.php">View Resumes</a>
        <a href="saved_jobs.php">Saved Jobs</a>
        <a href="hr_panel.php">HR Panel</a>
    </div>

    <div class="container">
        <h2>User Registration</h2>
        <form method="POST" action="register2.php">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" required>
            </div>
            <div class="form-group full-width">
                <label>Role</label>
                <select name="user_role" required>
                    <option value="Job Seeker">Job Seeker</option>
                    <option value="Employer">Employer</option>
                    <option value="HR Recruitment">HR Recruitment</option>
                </select>
            </div>
            <div class="form-group full-width">
                <button type="submit" name="register">Register</button>
            </div>
        </form>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("show");
        }
        function togglePopup() {
            const popup = document.getElementById("popup");
            popup.style.display = popup.style.display === "block" ? "none" : "block";
        }
    </script>
</body>
</html>
