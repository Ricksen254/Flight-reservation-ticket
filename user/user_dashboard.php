<?php
include '../models/user_functions.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Retrieve user details
$user_id = $_SESSION['user_id'];
$user = getUserDetails($user_id);

// If user is not found, handle the error
if (!$user) {
    // Handle case where user is not found
    session_destroy();
    header("Location: ../index.php?error=usernotfound");
    exit();
}

// Ensure user details are available
$loggedInEmail = isset($user['email']) ? $user['email'] : '';
$fullname = isset($user['fullname']) ? $user['fullname'] : 'Unknown User';

// Fetch bookings
$bookings = getBookings($user_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Dashboard | Flight Booking System</title>
    <style>
        /* Reset & Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: #eef2f5;
            color: #333;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Header */
        header {
            background: linear-gradient(90deg, #004080, #0066cc);
            padding: 1rem 2rem;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 1.5rem;
        }

        nav a {
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background 0.3s;
        }

        nav a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Container */
        .dashboard-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .welcome {
            background: #fff;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 2rem;
        }

        .welcome h2 {
            margin-bottom: 0.5rem;
        }

        .welcome p {
            color: #555;
        }

        /* Bookings */
        .bookings-container {
            margin-top: 2rem;
        }

        .bookings-container h3 {
            margin-bottom: 1rem;
            color: #004080;
        }

        .booking-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .booking-table th,
        .booking-table td {
            padding: 0.75rem 1rem;
            text-align: left;
        }

        .booking-table th {
            background: #0066cc;
            color: #fff;
        }

        .booking-table tr:nth-child(even) td {
            background: #f7f9fb;
        }

        .booking-table td {
            border-bottom: 1px solid #ececec;
        }

        .booking-table td.status {
            font-weight: bold;
        }

        .status-pending {
            color: orange;
        }

        .status-confirmed {
            color: green;
        }

        .status-cancelled {
            color: red;
        }

        .no-bookings {
            text-align: center;
            padding: 2rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            color: #777;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.75rem 1.5rem;
            background: #004080;
            color: #fff;
            border-radius: 4px;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #0056b3;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                text-align: center;
            }

            .dashboard-container {
                padding: 0 2rem;
            }

            .bookings-container {
                margin-top: 1.5rem;
            }

            .booking-table th,
            .booking-table td {
                padding: 0.5rem;
            }

            .booking-table td {
                font-size: 0.9rem;
            }

            .no-bookings {
                margin-top: 2rem;
            }
        }
    </style>
</head>

<body>
    <header>
        <?php include '../includes/navbar.php'; ?>
    </header>

    <div class="dashboard-container">
        <div class="welcome">
            <h2>Welcome, <?= htmlspecialchars($fullname) ?>!</h2>
            <p>Your email: <strong><?= htmlspecialchars($loggedInEmail) ?></strong></p>
        </div>

        <div class="bookings-container">
            <h3>Your Bookings</h3>
            <?php if (!empty($bookings)): ?>
                <table class="booking-table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Flight #</th>
                            <th>Route</th>
                            <th>Flight Type</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Booked On</th>
                            <th>Booking Date/Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $b): ?>
                            <tr>
                                <td><?= htmlspecialchars($b['booking_id']) ?></td>
                                <td><?= htmlspecialchars($b['flight_number']) ?></td>
                                <td><?= htmlspecialchars("{$b['departure_city']} → {$b['destination_city']}") ?></td>
                                <td><?= htmlspecialchars($b['seat_type']) ?></td> <!-- Seat -->
                                <td class="status status-<?= htmlspecialchars(strtolower($b['booking_status'] ?? 'unknown')) ?>">
                                    <?= htmlspecialchars($b['booking_status'] ?? 'Unknown') ?>
                                </td>
                                <td><?= htmlspecialchars($b['payment_amount']) ?></td>
                                <td><?= htmlspecialchars(date('Y-m-d', strtotime($b['created_at']))) ?></td> <!-- Booked On (date) -->
                                <td><?= htmlspecialchars(date('H:i:s', strtotime($b['created_at']))) ?></td> <!-- Booking Time -->
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            <?php else: ?>
                <div class="no-bookings">
                    <p>No bookings found.</p>
                    <a href="view_flights.php" class="btn">Book a Flight Now</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>