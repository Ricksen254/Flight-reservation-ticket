<?php
session_start();
require 'connect.php'; // Ensure this path is correct

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve user details
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT email, fullname FROM users WHERE id = ?");
if (!$stmt) {
    die("Database error preparing statement: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    // Handle case where user is not found
    session_destroy();
    header("Location: login.php?error=usernotfound");
    exit();
}

$loggedInEmail = $user['email'];
$fullname = $user['fullname'];

// Fetch bookings with flight details and seat type
$stmt = $conn->prepare("
    SELECT
        b.id AS booking_id,
        f.flight_used AS flight_number,
        f.departure_city,
        f.destination_city AS destination,
        b.status AS booking_status,
        s.seat_number,
        fst.seat_type,
        b.payment_method,
        b.created_at,
        b.booking_datetime
    FROM bookings b
    JOIN flights f ON b.flight_id = f.id
    JOIN seats s ON b.seat_id = s.id
    JOIN flight_seat_types fst ON s.flight_id = fst.flight_id AND s.seat_type_id = fst.id
    WHERE LOWER(b.email) = LOWER(?)
    ORDER BY b.created_at DESC
");
if (!$stmt) {
    die("Database error preparing statement: " . $conn->error);
}
$stmt->bind_param("s", $loggedInEmail);
$stmt->execute();
$bookingsResult = $stmt->get_result();
$bookings = $bookingsResult->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard | Flight Booking System</title>
    <style>
        /* Reset & Base */
        * { box-sizing: border-box; margin:0; padding:0; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #eef2f5; color: #333; }
        a { text-decoration: none; color: inherit; }

        /* Header & Nav */
        header { background: linear-gradient(90deg, #004080, #0066cc); padding: 1rem 2rem; color: #fff; display: flex; align-items: center; justify-content: space-between; }
        .logo { font-size: 1.5rem; font-weight: bold; }
        nav ul { list-style: none; display: flex; gap: 1rem; }
        nav a { color: #fff; padding: 0.5rem 1rem; border-radius: 4px; transition: background 0.3s; }
        nav a:hover { background: rgba(255,255,255,0.2); }

        /* Container */
        .dashboard-container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .welcome { background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center; }
        .welcome h2 { margin-bottom: 0.5rem; }
        .welcome p { color: #555; }

        /* Bookings */
        .bookings-container { margin-top: 2rem; }
        .bookings-container h3 { margin-bottom: 1rem; color: #004080; }
        .booking-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .booking-table th, .booking-table td { padding: 0.75rem 1rem; text-align: left; }
        .booking-table th { background: #0066cc; color: #fff; }
        .booking-table tr:nth-child(even) td { background: #f7f9fb; }
        .booking-table td { border-bottom: 1px solid #ececec; }
        .booking-table td.status {
            font-weight: bold;
        }
        .status-pending { color: orange; }
        .status-confirmed { color: green; }
        .status-cancelled { color: red; }
        /* Add more status colors as needed */

        .no-bookings { text-align: center; padding: 2rem; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); color: #777; }

        /* Buttons */
        .btn { display: inline-block; margin-top: 1rem; padding: 0.75rem 1.5rem; background: #004080; color: #fff; border-radius: 4px; transition: background 0.3s; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <header>
        <div class="logo">Flight Booking</div>
        <nav>
            <ul>
                <li><a href="user_dashboard.php">Dashboard</a></li>
                <li><a href="view_flights.php">Book Flight</a></li>
                <li><a href="login.php">Logout</a></li>
            </ul>
        </nav>
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
                            <th>Seat</th>
                            <th>Seat Type</th>
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
                                <td><?= htmlspecialchars("{$b['departure_city']} → {$b['destination']}") ?></td>
                                <td><?= htmlspecialchars($b['seat_number']) ?></td>
                                <td><?= htmlspecialchars($b['seat_type']) ?></td>
                                <td class="status status-<?= htmlspecialchars(strtolower($b['booking_status'])) ?>">
                                    <?= htmlspecialchars($b['booking_status']) ?>
                                </td>
                                <td><?= htmlspecialchars($b['payment_method']) ?></td>
                                <td><?= htmlspecialchars($b['created_at']) ?></td>
                                <td><?= htmlspecialchars($b['booking_datetime']) ?></td>
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