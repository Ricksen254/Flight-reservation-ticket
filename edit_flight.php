<?php
session_start();
require_once 'db_connect.php'; // Ensure your DB connection is correct

// Check if the flight ID is passed in the URL
if (!isset($_GET['id'])) {
    die('Flight ID is missing.');
}

$flightId = $_GET['id'];

// Fetch flight data based on the flight ID
$query = "SELECT * FROM flights WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $flightId);
$stmt->execute();
$result = $stmt->get_result();

// If flight not found, exit the script
if ($result->num_rows === 0) {
    die('Flight not found.');
}

$flight = $result->fetch_assoc();

// Handle the form submission to update flight data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $flight_used = $_POST['flight_used'];
    $departure_city = $_POST['departure_city'];
    $destination_city = $_POST['destination_city'];
    $price = $_POST['price'];
    $seating_capacity = $_POST['seating_capacity'];

    // Update the flight details in the database
    $updateQuery = "UPDATE flights SET flight_used = ?, departure_city = ?, destination_city = ?, price = ?, seating_capacity = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("sssdis", $flight_used, $departure_city, $destination_city, $price, $seating_capacity, $flightId);
    
    if ($updateStmt->execute()) {
        header("Location: manage_flights.php"); // Redirect back to the flight management page
        exit();
    } else {
        $errorMessage = "Error updating flight: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Flight - Ricksen Flights</title>
    <style>
        /* Add your styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            color: #333;
        }

        header {
            background-color: #003366;
            color: white;
            padding: 20px 0;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        nav {
            background-color: #005b96;
            padding: 12px 0;
            text-align: center;
        }

        nav a {
            color: white;
            margin: 0 15px;
            font-size: 16px;
            text-decoration: none;
            text-transform: uppercase;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .container {
            width: 70%;
            margin: 40px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #003366;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-group input:focus {
            border-color: #005b96;
        }

        button {
            padding: 10px 20px;
            background-color: #003366;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #005b96;
        }

        footer {
            background-color: #003366;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 50px;
            font-size: 14px;
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
    <a href="logout.php">Logout</a>
</nav>

<div class="container">
    <h2>Edit Flight</h2>

    <?php if (isset($errorMessage)): ?>
        <div class="error"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <form method="POST" action="edit_flight.php?id=<?= $flightId ?>">
        <div class="form-group">
            <label for="flight_used">Flight Used</label>
            <input type="text" name="flight_used" id="flight_used" value="<?= htmlspecialchars($flight['flight_used']) ?>" required>
        </div>

        <div class="form-group">
            <label for="departure_city">Departure City</label>
            <input type="text" name="departure_city" id="departure_city" value="<?= htmlspecialchars($flight['departure_city']) ?>" required>
        </div>

        <div class="form-group">
            <label for="destination_city">Destination City</label>
            <input type="text" name="destination_city" id="destination_city" value="<?= htmlspecialchars($flight['destination_city']) ?>" required>
        </div>

        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" name="price" id="price" value="<?= htmlspecialchars($flight['price']) ?>" required>
        </div>

        <div class="form-group">
            <label for="seating_capacity">Seating Capacity</label>
            <input type="number" name="seating_capacity" id="seating_capacity" value="<?= htmlspecialchars($flight['seating_capacity']) ?>" required>
        </div>

        <button type="submit">Update Flight</button>
    </form>
</div>

<footer>
    &copy; 2025 Ricksen Flight System | Admin Panel
</footer>

</body>
</html>
