<?php
session_start();
require_once 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Get user info
$userQuery = $conn->prepare("SELECT fullname FROM users WHERE id = ?");
$userQuery->bind_param("i", $userId);
$userQuery->execute();
$userResult = $userQuery->get_result();
$user = $userResult->fetch_assoc();
$fullname = $user['fullname'] ?? 'Customer';

// Get bookings
$bookingsQuery = $conn->prepare("SELECT * FROM bookings WHERE user_id = ? ORDER BY booking_date DESC");
$bookingsQuery->bind_param("i", $userId);
$bookingsQuery->execute();
$bookings = $bookingsQuery->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f9f9f9;
        }

        header {
            background: #004080;
            color: white;
            padding: 20px;
            text-align: center;
        }

        nav {
            background: #0066cc;
            text-align: center;
            padding: 10px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            padding: 10px;
        }

        nav a:hover {
            background: #003d66;
            border-radius: 5px;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 2px 10px rgba(0,0,0,0.1);
        }

        h2 {
            color: #004080;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th {
            background-color: #004080;
            color: white;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        footer {
            background: #004080;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<header>
    Welcome, <?= htmlspecialchars($fullname) ?>!
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="bookflight.php">Book a Flight</a>
    <a href="profile.php">My Profile</a>
    <a href="logout.php">Logout</a>
</nav>

<div class="container">
    <h2>My Bookings</h2>

    <?php if ($bookings->num_rows > 0): ?>
        <table>
            <tr>
                <th>#</th>
                <th>Departure</th>
                <th>Destination</th>
                <th>Flight</th>
                <th>Price</th>
                <th>Date</th>
            </tr>
            <?php $i = 1; while($row = $bookings->fetch_assoc()): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($row['departure_city']) ?></td>
                    <td><?= htmlspecialchars($row['destination_city']) ?></td>
                    <td><?= htmlspecialchars($row['flight_used']) ?></td>
                    <td>$<?= number_format($row['price'], 2) ?></td>
                    <td><?= htmlspecialchars($row['booking_date']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>You have no bookings yet. <a href="bookflight.php">Book one now!</a></p>
    <?php endif; ?>
</div>

<footer>
    &copy; <?= date('Y') ?> Ricksen Flights | All Rights Reserved
</footer>

</body>
</html>
