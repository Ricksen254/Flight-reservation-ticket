<?php
require_once 'db_connect.php';

// Fetch total users
$userResult = $conn->query("SELECT COUNT(*) as total_users FROM users");
$totalUsers = $userResult->fetch_assoc()['total_users'];

// Fetch total flights
$flightResult = $conn->query("SELECT COUNT(*) as total_flights FROM flights");
$totalFlights = $flightResult->fetch_assoc()['total_flights'];

// Fetch total bookings
$bookingResult = $conn->query("SELECT COUNT(*) as total_bookings FROM bookings");
$totalBookings = $bookingResult->fetch_assoc()['total_bookings'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Flight Booking System</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f1f4f9;
            color: #333;
        }

        header {
            background-color: #003366;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
        }

        nav {
            background: #005b96;
            padding: 10px;
            text-align: center;
        }

        nav a {
            color: white;
            margin: 0 15px;
            font-size: 16px;
            text-decoration: none;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .container {
            padding: 30px;
            max-width: 1000px;
            margin: auto;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
            text-align: center;
            flex: 1;
            margin: 15px;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card h2 {
            margin: 10px 0;
            font-size: 28px;
            color: #003366;
        }

        .card p {
            color: #666;
            font-size: 16px;
        }

        .dashboard {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        footer {
            text-align: center;
            padding: 20px;
            background: #003366;
            color: white;
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .dashboard {
                flex-direction: column;
                align-items: center;
            }

            nav a {
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>

<header>
    Admin Dashboard - Ricksen Flights
</header>

<nav>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_flights.php">Manage Flights</a>
    <a href="view_bookings.php">View Bookings</a>
    <a href="admin_login.php">Logout</a>
</nav>

<div class="container">
    <div class="dashboard">
        <div class="card">
            <h2><?= $totalUsers ?></h2>
            <p>Total Registered Users</p>
        </div>
        <div class="card">
            <h2><?= $totalFlights ?></h2>
            <p>Total Flights Available</p>
        </div>
        <div class="card">
            <h2><?= $totalBookings ?></h2>
            <p>Total Bookings Made</p>
        </div>
    </div>
</div>

<footer>
    &copy; 2025 Ricksen Flight System | Admin Panel
</footer>

</body>
</html>
