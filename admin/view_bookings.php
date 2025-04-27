<?php
session_start();
require_once '../database/db_connect.php';

// Fetch all bookings
$query = "SELECT * FROM bookings ORDER BY created_at DESC";
$result = $conn->query($query);
if (!$result) die("Query failed: " . $conn->error);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>View Bookings - Ricksen Flights</title>
  <style>
    /* Reset & Base */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f6f9;
      color: #333;
    }

    /* Header */
    header {
      background: #003366;
      padding: 20px;
      text-align: center;
      color: #fff;
      position: sticky;
      top: 0;
      z-index: 10;
    }

    header h1 {
      font-size: 24px;
    }

    /* Navigation */
    nav {
      background: #005080;
      display: flex;
      justify-content: center;
      padding: 10px 0;
    }

    nav a {
      color: #fff;
      text-decoration: none;
      margin: 0 15px;
      font-size: 16px;
      transition: background 0.3s;
      padding: 8px;
      border-radius: 4px;
    }

    nav a:hover {
      background: #0074b4;
    }

    /* Container */
    .container {
      max-width: 1200px;
      margin: 30px auto;
      padding: 0 15px;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #003366;
    }

    /* Table Styles */
    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    th,
    td {
      padding: 12px 15px;
      text-align: center;
    }

    th {
      background: #004080;
      color: #fff;
      font-weight: 600;
    }

    tr:nth-child(even) td {
      background: #f9f9f9;
    }

    tr:hover td {
      background: #e0f0ff;
    }

    /* Action Buttons */
    .btn {
      padding: 6px 12px;
      border: none;
      border-radius: 4px;
      color: #fff;
      font-size: 14px;
      cursor: pointer;
      transition: opacity 0.3s;
    }

    .btn-approve {
      background: #28a745;
    }

    .btn-cancel {
      background: #dc3545;
    }

    .btn:hover {
      opacity: 0.8;
    }

    /* Mobile */
    @media (max-width: 768px) {

      th,
      td {
        padding: 10px;
        font-size: 14px;
      }

      nav {
        flex-wrap: wrap;
      }
    }

    /* Footer */
    footer {
      background: #003366;
      color: #fff;
      text-align: center;
      padding: 15px;
      margin-top: 30px;
    }
  </style>
</head>

<body>

  <header>
    <?php include '../includes/navbar.php'; ?>
  </header>
  <div class="container">
    <h2>Bookings List</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>User ID</th>
          <th>Flight ID</th>
          <th>Seat ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Passport</th>
          <th>Payment</th>
          <th>Created At</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($b = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($b['id']) ?></td>
            <td><?= htmlspecialchars($b['user_id']) ?></td>
            <td><?= htmlspecialchars($b['flight_id']) ?></td>
            <td><?= htmlspecialchars($b['seat_id']) ?></td>
            <td><?= htmlspecialchars($b['fullname']) ?></td>
            <td><?= htmlspecialchars($b['email']) ?></td>
            <td><?= htmlspecialchars($b['phone']) ?></td>
            <td><?= htmlspecialchars($b['passport']) ?></td>
            <td><?= htmlspecialchars($b['payment_method']) ?></td>
            <td><?= htmlspecialchars($b['created_at']) ?></td>
            <td><?= htmlspecialchars($b['status']) ?></td>
            <td>
              <a class="btn btn-approve" href="approve_booking.php?id=<?= $b['id'] ?>">Approve</a>
              <a class="btn btn-cancel" href="cancel_booking.php?id=<?= $b['id'] ?>">Cancel</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <footer>&copy; <?= date('Y') ?> Ricksen Flights | Admin Panel</footer>

</body>

</html>