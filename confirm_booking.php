<?php
session_start();
include 'connect.php'; // Database connection

// Check if user is logged in and email is in session
if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in or session expired. Please log in again.");
}
$user_email = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $flight_id = filter_input(INPUT_POST, 'flight_id', FILTER_SANITIZE_NUMBER_INT);
    $seat_type_id = filter_input(INPUT_POST, 'seat_type_id', FILTER_SANITIZE_NUMBER_INT);
    $seat_number = filter_input(INPUT_POST, 'seat_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $fullname = filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_NUMBER_INT);
    $passport = filter_input(INPUT_POST, 'passport', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $payment_method = filter_input(INPUT_POST, 'payment_method', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $booking_datetime = filter_input(INPUT_POST, 'booking_datetime', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (empty($flight_id) || empty($seat_type_id) || empty($seat_number) || empty($fullname) || empty($email) || empty($phone) || empty($passport) || empty($payment_method) || empty($booking_datetime)) {
        die("Missing required fields. Please go back and complete the form.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    $conn->begin_transaction();

    try {
        // 1. Check seat availability
        $checkSeatStmt = $conn->prepare("SELECT id FROM seats WHERE flight_id = ? AND seat_type_id = ? AND seat_number = ? AND status = 'available' LIMIT 1");
        if (!$checkSeatStmt) {
            throw new Exception("Database error preparing statement: " . $conn->error);
        }
        $checkSeatStmt->bind_param("iis", $flight_id, $seat_type_id, $seat_number);
        $checkSeatStmt->execute();
        $checkSeatResult = $checkSeatStmt->get_result();

        if ($checkSeatResult->num_rows === 0) {
            throw new Exception("Seat not available.");
        }

        $seat = $checkSeatResult->fetch_assoc();
        $seat_id = $seat['id'];
        $checkSeatStmt->close();

        // 2. Mark seat as booked
        $updateSeatStmt = $conn->prepare("UPDATE seats SET status = 'booked' WHERE id = ?");
        if (!$updateSeatStmt) {
            throw new Exception("Database error preparing statement: " . $conn->error);
        }
        $updateSeatStmt->bind_param("i", $seat_id);
        $updateSeatStmt->execute();
        $updateSeatStmt->close();

        // 3. Insert booking WITH user's email from session AND booking_datetime
        $insertBookingStmt = $conn->prepare("INSERT INTO bookings (flight_id, seat_id, fullname, email, phone, passport, payment_method, created_at, booking_datetime) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?)");
        if (!$insertBookingStmt) {
            throw new Exception("Database error preparing statement: " . $conn->error);
        }
        $insertBookingStmt->bind_param("iissssss", $flight_id, $seat_id, $fullname, $user_email, $phone, $passport, $payment_method, $booking_datetime);
        $insertBookingStmt->execute();
        
        $insertBookingStmt->close();

        $conn->commit();

        // Fetch flight details for display
        $flightQueryStmt = $conn->prepare("SELECT departure_city, destination_city, flight_used FROM flights WHERE id = ?");
        if (!$flightQueryStmt) {
            throw new Exception("Database error preparing statement: " . $conn->error);
        }
        $flightQueryStmt->bind_param("i", $flight_id);
        $flightQueryStmt->execute();
        $flightRes = $flightQueryStmt->get_result();
        $flight = $flightRes->fetch_assoc();
        $flightQueryStmt->close();

        // Fetch seat type for display
        $seatTypeQueryStmt = $conn->prepare("SELECT seat_type FROM flight_seat_types WHERE id = ?");
        if (!$seatTypeQueryStmt) {
            throw new Exception("Database error preparing statement: " . $conn->error);
        }
        $seatTypeQueryStmt->bind_param("i", $seat_type_id);
        $seatTypeQueryStmt->execute();
        $seatTypeRes = $seatTypeQueryStmt->get_result();
        $seatType = $seatTypeRes->fetch_assoc()['seat_type'];
        $seatTypeQueryStmt->close();

        // Display booking review information
        echo "<div class='container'>";
        echo "<h2>Review Your Booking</h2>";
        echo "<div class='confirmation-details'>";
        echo "<ul>";
        echo "<li><strong>Flight:</strong> " . htmlspecialchars($flight['departure_city'] ?? 'N/A') . " → " . htmlspecialchars($flight['destination_city'] ?? 'N/A') . " (" . htmlspecialchars($flight['flight_used'] ?? 'N/A') . ")</li>";
        echo "<li><strong>Seat Type:</strong> " . htmlspecialchars($seatType ?? 'N/A') . "</li>";
        echo "<li><strong>Seat Number:</strong> " . htmlspecialchars($seat_number ?? 'N/A') . "</li>";
        echo "<li><strong>Full Name:</strong> " . htmlspecialchars($fullname ?? 'N/A') . "</li>";
        echo "<li><strong>Email:</strong> " . htmlspecialchars($email ?? 'N/A') . "</li>";
        echo "<li><strong>Phone:</strong> " . htmlspecialchars($phone ?? 'N/A') . "</li>";
        echo "<li><strong>Passport:</strong> " . htmlspecialchars($passport ?? 'N/A') . "</li>";
        echo "<li><strong>Booking Date/Time:</strong> " . htmlspecialchars($booking_datetime ?? 'N/A') . "</li>";
        echo "<li><strong>Payment Method:</strong> " . htmlspecialchars($payment_method ?? 'N/A') . "</li>";
        echo "</ul>";
        echo "</div>";

        // Confirmation message and actions
        echo "<h2>Booking Confirmed</h2>";
        echo "<p>Thank you, <strong>" . htmlspecialchars($fullname ?? 'Guest') . "</strong>! Your booking has been successfully processed.</p>";
        echo "<button class='print-btn' onclick='window.print()'>Print Booking</button>";
        echo "<p><a href='home.php'>Return to Home</a></p>";
         echo "<p><a href='login.php'>Confirm my Bookings</a></p>";
        echo "</div>";

    } catch (Exception $e) {
        $conn->rollback();
        echo "<div class='container'>";
        echo "<h2>Booking Failed</h2>";
        echo "<p>" . $e->getMessage() . "</p>";
        echo "<p><a href='flight.php'>Try Again</a></p>";
        echo "</div>";
    }
} else {
    header('Location: flight.php');
    exit;
}
?>

<style>
    body, html {
        margin: 0;
        padding: 0;
        height: 100%;
        font-family: Arial, sans-serif;
        background: url('images/take off.jpg') no-repeat center center/cover;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #fff;
    }

    .container {
        max-width: 900px;
        margin: 50px auto;
        padding: 30px;
        background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        animation: fadeIn 1s ease-in-out;
    }

    @keyframes fadeIn {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    h2 {
        color: #4CAF50;
        font-size: 28px;
        margin-bottom: 15px;
        font-weight: bold;
        text-align: center;
    }

    .confirmation-details {
        background-color: #ffffff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        color: #333;
        margin-bottom: 20px;
    }

    .confirmation-details ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .confirmation-details li {
        font-size: 18px;
        line-height: 1.6;
        margin: 8px 0;
        padding-left: 10px;
        position: relative;
    }

    .confirmation-details li strong {
        color: #007bff;
        font-weight: bold;
    }

    .print-btn {
        background-color: #007bff;
        color: white;
        padding: 15px 30px;
        border: none;
        border-radius: 50px;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-top: 20px;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    .print-btn:hover {
        background-color: #0056b3;
    }

    a {
        text-decoration: none;
        color: #28a745;
        font-weight: bold;
    }

    a:hover {
        text-decoration: underline;
    }

    p {
        font-size: 16px;
        color: #333;
        text-align: center;
        margin-top: 15px;
    }
</style>