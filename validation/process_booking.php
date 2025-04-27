<?php
session_start();
require_once '../database/db_connect.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: login.php');  // Redirect to login if the user is not logged in
    exit;
}

// Handle the form submission to create a new booking
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $flight_id = $_POST['flight_id'];
    $seat_type = $_POST['seat_type'];
    $number_of_people = $_POST['number_of_people'];

    // Fetch the seat price for the selected flight and seat type
    $query = "SELECT seat_price FROM flight_seat_types WHERE flight_id = ? AND seat_type = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $flight_id, $seat_type);
    $stmt->execute();
    $result = $stmt->get_result();
    $seat_price = $result->fetch_assoc()['seat_price'];

    if ($seat_price) {
        // Calculate total payment
        $payment = $seat_price * $number_of_people;

        // Insert the booking into the database
        $query = "INSERT INTO bookings (user_id, flight_id, type, payment) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iisd", $user_id, $flight_id, $seat_type, $payment);

        if ($stmt->execute()) {
            echo "<p>Booking created successfully!</p>";
            header('Location: view_bookings.php'); // Redirect to the bookings page after successful creation
            exit;
        } else {
            echo "<p>There was an error creating your booking. Please try again.</p>";
        }
    } else {
        echo "<p>Selected seat type is not available for the selected flight. Please try again.</p>";
    }
}

// Fetch available flights for the booking form
$flightsQuery = "SELECT id, departure_city, destination_city FROM flights";
$flightsResult = $conn->query($flightsQuery);
$flights = [];
while ($flight = $flightsResult->fetch_assoc()) {
    $flights[] = $flight;
}
