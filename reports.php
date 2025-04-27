<?php
// reports.php
session_start();

// Check if user is logged in and has the right role (admin)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
</head>
<body>

<h1>Flight Ticket System - Reports</h1>

<!-- Links to different reports -->
<ul>
    <li><a href="sales_report.php">Sales Report</a></li>
    <li><a href="customer_report.php">Customer Report</a></li>
    <li><a href="flight_performance_report.php">Flight Performance Report</a></li>
    <li><a href="financial_report.php">Financial Report</a></li>
    <li><a href="promotion_report.php">Promotion Report</a></li>
    <li><a href="bookings_report.php">Bookings Data Report</a></li>
</ul>

</body>
</html>

