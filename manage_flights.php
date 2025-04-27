<?php
session_start();
require_once 'db_connect.php'; // Ensure your DB connection is correct

// Fetch all flights from the database
$query = "SELECT * FROM flights";
$result = $conn->query($query);

if ($result->num_rows === 0) {
    $noFlightsMessage = "No flights available.";
} else {
    // Check if the flight columns are correct and available
    $columns = $result->fetch_fields();
    $column_names = [];
    foreach ($columns as $column) {
        $column_names[] = $column->name;
    }
    // var_dump($column_names);  // Uncomment to debug column names
}

// Handle deletion of a flight
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $deleteQuery = "DELETE FROM flights WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $deleteId);
    
    if ($deleteStmt->execute()) {
        header("Location: manage_flights.php"); // Redirect back to the flights page
        exit();
    } else {
        $errorMessage = "Error deleting flight: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Flights - Ricksen Flights</title>
    <style>
        /* Basic Styling for the Manage Flights Page */
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

        .table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .table th {
            background-color: #003366;
            color: white;
        }

        .table td a {
            color: #ff5733;
            text-decoration: none;
        }

        .table td a:hover {
            text-decoration: underline;
        }

        .add-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 12px;
            text-align: center;
            background-color: #003366;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            text-decoration: none;
        }

        .add-button:hover {
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
    
    <a href="view_bookings.php">View Bookings</a>
  
</nav>

<div class="container">
    <h2>Manage Flights</h2>

    <!-- Display any error message -->
    <?php if (isset($errorMessage)): ?>
        <div class="error"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <!-- Display a message if no flights are found -->
    <?php if (isset($noFlightsMessage)): ?>
        <div class="no-flights"><?= $noFlightsMessage ?></div>
    <?php endif; ?>

    <table class="table">
        <thead>
            <tr>
                <th>Flight ID</th>
                <th>Flight Used</th>
                <th>Departure City</th>
                <th>Destination City</th>
                <th>Price</th>
                <th>Seating Capacity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Reset the result pointer and fetch the data again
            $result->data_seek(0);
            while ($flight = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($flight['id']) ?></td>
                    <td><?= htmlspecialchars($flight['flight_used']) ?></td>
                    <td><?= htmlspecialchars($flight['departure_city']) ?></td>
                    <td><?= htmlspecialchars($flight['destination_city']) ?></td>
                    <td><?= htmlspecialchars($flight['price']) ?></td>
                    <td><?= htmlspecialchars($flight['seating_capacity']) ?></td>
                    <td>
                        <a href="edit_flight.php?id=<?= $flight['id'] ?>">Edit</a> |
                        <a href="manage_flights.php?delete_id=<?= $flight['id'] ?>" onclick="return confirm('Are you sure you want to delete this flight?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="add_flight.php" class="add-button">Add New Flight</a>
</div>

<footer>
    &copy; 2025 Ricksen Flight System | Admin Panel
</footer>

</body>
</html>
