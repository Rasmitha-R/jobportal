<?php
session_start();

$username = "";
$loginSuccess = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "jobportal");

    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT name FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION["email"] = $email;
        $_SESSION["username"] = $row['name'];
        $username = $row['name'];
        $loginSuccess = true;
    } else {
        $error = "Invalid email or password.";
    }
} elseif (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Job Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
        body {
            background-image: url('https://wallpaperaccess.com/full/2441943.png');
            background-size: cover;
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
        }

        .avatar-wrapper {
            width: 100%;
            text-align: center;
            margin-top: 50px;
            margin-bottom: -60px;
            position: relative;
        }

        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            background-color: #fff;
            cursor: pointer;
        }

        .tooltip {
            display: none;
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            background: hsl(350, 73.10%, 76.70%);
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 14px;
            white-space: nowrap;
        }

        .login-container {
            background: white;
            width: 400px;
            margin: 80px auto 0;
            padding: 30px 20px 80px;
            border-radius: 15px;
            box-shadow: 0 4px 12px #f8f6f6;
            position: relative;
        }

        .login-header {
            background-color: #131212;
            padding: 15px;
            text-align: center;
            border-radius: 15px 15px 0 0;
            font-size: 24px;
            color: #fff;
            font-weight: bold;
            margin: -30px -20px 30px -20px;
        }

        .form-group {
            margin: 20px 0;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .next-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #171617;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .login-button {
            display: block;
            margin: 20px auto 0;
            padding: 10px 20px;
            background-color:hsl(227, 90.00%, 3.90%);
            color:  #fff;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            width: 30%;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        .success {
            color: green;
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- Avatar with Tooltip -->
    <div class="avatar-wrapper">
        <div class="tooltip" id="userTooltip"><?php echo htmlspecialchars($username); ?></div>
        <img class="avatar" src="https://tse1.mm.bing.net/th?id=OIP.8xC-Y-eFY84Set5-4ubV5AHaE7&pid=Api&P=0&h=180" alt="User Icon" onclick="showTooltip()">
    </div>

    <div class="login-container">
        <div class="login-header">User Login</div>

        <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
        <?php if ($loginSuccess) echo "<div class='success'>Login successful! Redirecting...</div>"; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required placeholder="Enter your email">
            </div>
                <label>Password:</label>
                <input type="password" name="password" required placeholder="Enter your password">
        </form>
        
    </div>

    <button class="login-button" onclick="window.location.href='http://localhost/jobportal/add_company.php';">login</button>

    <script>
        function showTooltip() {
            const tooltip = document.getElementById("userTooltip");
            tooltip.style.display = "inline-block";
            setTimeout(() => {
                tooltip.style.display = "none";
            }, 3000);
        }

        // Redirect after login success
        <?php if ($loginSuccess): ?>
            setTimeout(function () {
                window.location.href = "http://localhost/jobportal/add_company.php";
            }, 2000);
        <?php endif; ?>
    </script>
</body>
</html>
