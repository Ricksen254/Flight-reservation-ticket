<?php
// approve_booking.php – Approve a pending booking and redirect back
session_start();
require_once 'db_connect.php'; // DB connection

// Ensure an ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['flash_error'] = 'Invalid booking ID.';
    header('Location: view_bookings.php');
    exit;
}
$bookingId = (int)$_GET['id'];

// Update booking status
$stmt = $conn->prepare("UPDATE bookings SET status = 'approved' WHERE id = ?");
$stmt->bind_param('i', $bookingId);
if ($stmt->execute()) {
    $_SESSION['flash_success'] = "Booking #{$bookingId} has been approved.";
} else {
    $_SESSION['flash_error'] = 'Failed to update booking status.';
}
$stmt->close();
$conn->close();

// Redirect back to bookings list
header('Location: view_bookings.php');
exit;
