<?php
require_once '../database/db_connect.php';
require_once '../models/user_functions.php';

// Check if flight_id is set in the GET request
if (isset($_GET['flight_id'])) {
    $flight_id = $_GET['flight_id'];
    error_log("Flight ID: " . $flight_id);
    $seat_types = getSeatTypes($flight_id, $conn);
    error_log("Seat Types: " . print_r($seat_types, true));

    echo json_encode($seat_types);
} else {
    echo json_encode([]);
}
