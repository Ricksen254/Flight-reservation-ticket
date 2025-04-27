<?php
session_start();
require_once 'db_connect.php'; // Include the database connection file

// Query to fetch all records from the bookings table
$query = "SELECT * FROM bookings";
$result = $conn->query($query);

// Check if the query was successful
if (!$result) {
    // If the query fails, display an error message
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bookings - Ricksen Flights</title>
    <link rel="stylesheet" href="styles.css"> <!-- Make sure to include your styles -->
</head>
<body>

<header>
    <h1>Admin Dashboard - Ricksen Flights</h1>
</header>

<nav>
    <ul>
        <li><a href="admin_dashboard.php">Dashboard</a></li>
        <li><a href="manage_users.php">Manage Users</a></li>
        <li><a href="manage_flights.php">Manage Flights</a></li>
        <li><a href="view_bookings.php">View Bookings</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<div class="container">
    <h2>View Bookings</h2>

    <!-- Table to display booking details -->
    <table border="1">
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>User ID</th>
                <th>Flight ID</th>
                <th>Seat ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Passport</th>
                <th>Payment Method</th>
                <th>Created At</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Loop through the results and display each booking in a table row
            while ($booking = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($booking['id']) . "</td>";
                echo "<td>" . htmlspecialchars($booking['user_id']) . "</td>";
                echo "<td>" . htmlspecialchars($booking['flight_id']) . "</td>";
                echo "<td>" . htmlspecialchars($booking['seat_id']) . "</td>";
                echo "<td>" . htmlspecialchars($booking['fullname']) . "</td>";
                echo "<td>" . htmlspecialchars($booking['email']) . "</td>";
                echo "<td>" . htmlspecialchars($booking['phone']) . "</td>";
                echo "<td>" . htmlspecialchars($booking['passport']) . "</td>";
                echo "<td>" . htmlspecialchars($booking['payment_method']) . "</td>";
                echo "<td>" . htmlspecialchars($booking['created_at']) . "</td>";
                echo "<td>" . htmlspecialchars($booking['status']) . "</td>";
                echo "<td>
                        <a href='approve_booking.php?id=" . $booking['id'] . "'>Approve</a> | 
                        <a href='cancel_booking.php?id=" . $booking['id'] . "'>Cancel</a>
                    </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<footer>
    &copy; 2025 Ricksen Flight System | Admin Panel
</footer>

</body>
</html>
