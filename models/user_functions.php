<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once '../database/db_connect.php'; // Make sure this path is correct!

// Function to fetch user details
function getUserDetails($user_id)
{
    global $conn;

    $stmt = $conn->prepare("SELECT email, fullname FROM users WHERE id = ?");
    if (!$stmt) {
        die("Database error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    return $user;
}

// Function to fetch bookings with flight details
function getBookings($user_id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT
            b.id AS booking_id,
            f.flight_used AS flight_number,
            f.departure_city,
            f.destination_city AS destination_city,
            b.type AS seat_type,
            b.status AS booking_status,
            b.total AS payment_amount,
            b.number_of_people,
            b.created_at
        FROM bookings b
        JOIN flights f ON b.flight_id = f.id
        WHERE b.user_id = ?
        ORDER BY b.created_at DESC
    ");

    if (!$stmt) {
        die("Database error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $bookings = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $bookings;
}


function getUserFlights($user_id)
{
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM bookings WHERE user_id = ?");
    if (!$stmt) {
        die("Database error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $flightsResult = $stmt->get_result();
    $flights = $flightsResult->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $flights;
}

// Function to get seat types and prices for a specific flight
function getSeatTypes($flight_id, $conn)
{
    $query = "SELECT seat_type, seat_price FROM flight_seat_types WHERE flight_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $flight_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $seatTypes = [];
    while ($row = $result->fetch_assoc()) {
        $seatTypes[] = $row;
    }

    return $seatTypes;
}
