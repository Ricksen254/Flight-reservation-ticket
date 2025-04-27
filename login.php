<?php
session_start();
require 'connect.php'; // This includes your database connection

$errors = '';
$success = '';

// Sanitize input to avoid XSS or other vulnerabilities
function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

// Handle registration or login actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Registration Handling
    if ($action === 'register') {
        $fullname = sanitize($_POST['fullname']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $phone = sanitize($_POST['phone_number']);
        $password = $_POST['password'];
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        // Check if email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors = "Email already exists.";
        } else {
            // Prepare SQL statement for inserting user into the database
            $stmt = $conn->prepare("INSERT INTO users (fullname, email, phone_number, password_hash) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $fullname, $email, $phone, $password_hash);

            try {
                $stmt->execute();
                $success = "Registration successful! You can now log in.";
            } catch (mysqli_sql_exception $e) {
                $errors = "Registration failed. Please try again.";
            }
        }

        // Close statement
        $stmt->close();
    }

    // Login Handling
    elseif ($action === 'login') {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        // Prepare SQL statement for selecting the user from the database
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            
            // Redirect to user_dashboard.php after successful login
            header("Location: user_dashboard.php");
            exit();
        } else {
            $errors = "Invalid email or password.";
        }

        // Close statement
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login/Register | Flight Booking System</title>
    <style>
        body, html {
            margin: 0; padding: 0; height: 100%;
            font-family: Arial, sans-serif;
            background: url('images/take off.jpg') no-repeat center center/cover;
            display: flex; justify-content: center; align-items: center;
            color: #fff;
        }

        .container {
            width: 350px;
            background: rgba(0, 0, 0, 0.85);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.6);
            text-align: center;
        }

        input, button {
            width: 100%; padding: 10px; margin: 10px 0;
            border: none; border-radius: 5px;
            font-size: 16px;
        }

        input {
            background: #fff; color: #000;
        }

        button {
            background: #004080; color: #fff; cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        .toggle-link {
            color: #ffcc00; cursor: pointer;
            text-decoration: none;
        }

        .toggle-link:hover {
            text-decoration: underline;
        }

        .form-section { display: none; }
        .form-section.active { display: block; }

        .message { margin: 10px 0; font-weight: bold; }
        .error { color: red; }
        .success { color: lightgreen; }
    </style>
</head>
<body>
<div class="container">
    <?php if ($errors): ?>
        <div class="message error"><?= $errors ?></div>
    <?php elseif ($success): ?>
        <div class="message success"><?= $success ?></div>
    <?php endif; ?>

    <div id="login-section" class="form-section <?= empty($_POST['action']) || $_POST['action'] === 'login' ? 'active' : '' ?>">
        <h2>User Login</h2>
        <form method="POST">
            <input type="hidden" name="action" value="login">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Enter Password" minlength="5" required>
            <button type="submit">Login</button>
            <p><a class="toggle-link" onclick="toggleForm('register')">Don't have an account? Register</a></p>
        </form>
    </div>

    <div id="register-section" class="form-section <?= isset($_POST['action']) && $_POST['action'] === 'register' ? 'active' : '' ?>">
        <h2>User Registration</h2>
        <form method="POST">
            <input type="hidden" name="action" value="register">
            <input type="text" name="fullname" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="tel" name="phone_number" placeholder="Phone Number" pattern="[0-9+]+" required>
            <input type="password" name="password" placeholder="Create Password" minlength="8" required>
            <button type="submit">Register</button>
            <p><a class="toggle-link" onclick="toggleForm('login')">Already have an account? Login</a></p>
        </form>
    </div>
</div>

<script>
    function toggleForm(section) {
        document.getElementById('login-section').classList.remove('active');
        document.getElementById('register-section').classList.remove('active');
        document.getElementById(section + '-section').classList.add('active');
    }
</script>
</body>
</html>
