<?php
session_start();
require_once 'db_connect.php'; // Ensure your DB connection is correct

// Fetch users from the database
$query = "SELECT * FROM users ORDER BY fullname ASC";
$userResult = $conn->query($query);

if (!$userResult) {
    die("Error fetching users: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Manage Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #003366;
            color: white;
            padding: 20px;
            text-align: center;
        }

        nav {
            background-color: #005b96;
            padding: 10px;
            text-align: center;
        }

        nav a {
            color: white;
            margin: 0 15px;
            font-size: 16px;
            text-decoration: none;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .container {
            padding: 30px;
            max-width: 1000px;
            margin: auto;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 15px;
        }

        .card h2 {
            margin: 10px 0;
            font-size: 28px;
            color: #003366;
        }

        .dashboard {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #003366;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            margin: 0 5px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .pagination a:hover {
            background-color: #0056b3;
        }

        footer {
            background-color: #003366;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<header>
    Admin Dashboard - Ricksen Flights
</header>

<nav>
    <a href="admin_dashboard.php">Dashboard</a>
   
    <a href="manage_flights.php">Manage Flights</a>
    <a href="view_bookings.php">View Bookings</a>
 
</nav>

<div class="container">
    <h2>Users List</h2>

    <?php if ($userResult->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $userResult->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['fullname']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= isset($row['phone_number']) ? htmlspecialchars($row['phone_number']) : 'Not Available' ?></td> <!-- Use the correct column name -->
                        <td>
                            <a href="edit_user.php?id=<?= $row['id'] ?>">Edit</a> |
                            <a href="delete_user.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>

</div>

<footer>
    &copy; 2025 Ricksen Flight System | Admin Panel
</footer>

</body>
</html>
