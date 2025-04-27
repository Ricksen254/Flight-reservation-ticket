<?php
session_start();
require_once 'db_connect.php'; // Ensure your DB connection is correct

// Check if the user ID is passed in the URL
if (!isset($_GET['id'])) {
    die('User ID is missing.');
}

$userId = $_GET['id'];

// Fetch user data based on the user ID
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// If user not found, exit the script
if ($result->num_rows === 0) {
    die('User not found.');
}

$user = $result->fetch_assoc();

// Handle the form submission to update user data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    // Update the user details in the database
    $updateQuery = "UPDATE users SET fullname = ?, email = ?, phone_number = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("sssi", $fullname, $email, $phone_number, $userId);
    
    if ($updateStmt->execute()) {
        header("Location: manage_users.php"); // Redirect back to the user management page
        exit();
    } else {
        $errorMessage = "Error updating user: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Ricksen Flights</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            color: #333;
        }

        header {
            background-color: #003366;
            color: white;
            padding: 20px 0;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        nav {
            background-color: #005b96;
            padding: 12px 0;
            text-align: center;
        }

        nav a {
            color: white;
            margin: 0 15px;
            font-size: 16px;
            text-decoration: none;
            text-transform: uppercase;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .container {
            width: 50%;
            margin: 40px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #003366;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            color: #333;
        }

        .form-group input:focus {
            border-color: #005b96;
            outline: none;
            background-color: #fff;
        }

        .form-group input[type="submit"] {
            background-color: #003366;
            color: white;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-group input[type="submit"]:hover {
            background-color: #005b96;
        }

        .form-group input[type="submit"]:focus {
            outline: none;
        }

        .cancel-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            text-align: center;
            background-color: #ff5733;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            text-decoration: none;
        }

        .cancel-btn:hover {
            background-color: #e84c2a;
        }

        footer {
            background-color: #003366;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 50px;
            font-size: 14px;
        }

    </style>
</head>
<body>

<header>
    Admin Dashboard - Ricksen Flights
</header>

<nav>
    <a href="admin_dashboard.php">Dashboard</a>
    
    <a href="manage_flights.php">Manage Flights</a>
    <a href="view_bookings.php">View Bookings</a>
   
</nav>

<div class="container">
    <h2>Edit User</h2>

    <?php if (isset($errorMessage)): ?>
        <div class="error"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <form method="POST" action="edit_user.php?id=<?= $userId ?>">
        <div class="form-group">
            <label for="fullname">Full Name</label>
            <input type="text" name="fullname" id="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>

        <div class="form-group">
            <label for="phone_number">Phone Number</label>
            <input type="text" name="phone_number" id="phone_number" value="<?= htmlspecialchars($user['phone_number']) ?>" required>
        </div>

        <button type="submit">Update User</button>
    </form>
</div>

<footer>
    &copy; 2025 Ricksen Flight System | Admin Panel
</footer>

</body>
</html>
