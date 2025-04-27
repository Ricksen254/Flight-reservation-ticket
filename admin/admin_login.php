<?php
session_start();

// Hardcoded admin credentials for testing purposes
$admin_email = 'admin@example.com';
$admin_password = 'adminpassword'; // Plaintext password for testing (you should hash this in real applications)

$errors = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Check if the entered email and password match the hardcoded admin credentials
    if ($email === $admin_email && $password === $admin_password) {
        // Successful login
        $_SESSION['user_id'] = 1; // Hardcoded admin ID
        $_SESSION['fullname'] = 'Admin User'; // Hardcoded name
        $_SESSION['email'] = $admin_email;
        $_SESSION['role'] = 'admin';

        // Redirect to admin dashboard
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $errors = "Invalid email or password.";  // Error if login fails
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Flight Booking System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            text-align: center;
            margin-bottom: 10px;
        }
        .home-link {
            text-align: center;
            margin-top: 20px;
        }
        .home-link a {
            color: #007bff;
            text-decoration: none;
            font-size: 16px;
        }
        .home-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Admin Login</h2>
        
        <!-- Display error message if login fails -->
        <?php if ($errors): ?>
            <div class="error"><?= $errors ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <button type="submit">Login</button>
        </form>

        <!-- Home Link -->
        <div class="home-link">
            <a href="home.php">Back to Home</a>
        </div>
    </div>

</body>
</html>
