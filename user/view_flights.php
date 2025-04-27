<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../database/db_connect.php'; // Make sure this path is correct
include_once '../models/user_functions.php';

// Fetch the logged-in user's details
$user_id = $_SESSION['user_id'] ?? null;  // Ensure the user_id is stored in the session
if ($user_id) {
  $userDetails = getUserDetails($user_id);
  $fullname = $userDetails['fullname'] ?? 'Guest';  // Default to 'Guest' if not found
  $loggedInEmail = $userDetails['email'] ?? 'guest@example.com';  // Default email
} else {
  $fullname = 'Guest';
  $loggedInEmail = 'guest@example.com';
}

// 1. Fetch all flights
$flights = [];
$sql = "SELECT id, departure_city, destination_city, flight_used, seating_capacity FROM flights";  // Fixed the SQL query
if ($res = $conn->query($sql)) {
  while ($row = $res->fetch_assoc()) {
    $flights[$row['id']] = $row;
  }
  $res->free();
}

// 2. Fetch available seat counts per flight and type
$seatInfo = [];
$sql2 = "
    SELECT fst.flight_id, fst.seat_type, fst.seat_price,
           COUNT(s.id) AS available
    FROM flight_seat_types fst
    JOIN seats s ON s.seat_type_id = fst.id AND s.status = 'available'
    GROUP BY fst.flight_id, fst.seat_type, fst.seat_price /* Fixed the GROUP BY clause */
";

if ($res2 = $conn->query($sql2)) {
  while ($r = $res2->fetch_assoc()) {
    $seatInfo[$r['flight_id']][] = $r;
  }
  $res2->free();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Available Flights | Ricksen Flights</title>
  <style>
    /* Reset & Base */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, sans-serif;
      background: #eef2f5;
      color: #333;
    }

    a {
      text-decoration: none;
      color: inherit;
    }

    /* Header */
    header {
      background: linear-gradient(90deg, #004080, #0066cc);
      padding: 1rem 2rem;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .logo {
      font-size: 1.5rem;
      font-weight: bold;
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 1.5rem;
    }

    nav a {
      color: #fff;
      padding: 0.5rem 1rem;
      border-radius: 4px;
      transition: background 0.3s;
    }

    nav a:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    /* Container */
    .container {
      max-width: 1200px;
      margin: 2rem auto;
      padding: 0 1rem;
    }

    h2 {
      color: #004080;
      margin-bottom: 1.5rem;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    th,
    td {
      padding: 1rem;
      text-align: left;
    }

    th {
      background: #0066cc;
      color: #fff;
    }

    tr:nth-child(even) td {
      background: #f7f9fb;
    }

    td {
      border-bottom: 1px solid #ececec;
    }

    .seat-breakdown {
      font-size: 0.9rem;
      color: #555;
    }

    .seat-breakdown div {
      margin: 0.5rem 0;
    }

    .back-button {
      display: inline-block;
      margin-top: 2rem;
      padding: 0.75rem 1.5rem;
      background: #004080;
      color: #fff;
      border-radius: 4px;
      transition: background 0.3s;
    }

    .back-button:hover {
      background: #0056b3;
    }

    /* Footer */
    footer {
      text-align: center;
      background: #004080;
      color: #fff;
      padding: 1rem 0;
      margin-top: 3rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      header {
        flex-direction: column;
        text-align: center;
      }

      .container {
        padding: 0 2rem;
      }

      table th,
      table td {
        padding: 0.75rem;
      }

      table td {
        font-size: 0.9rem;
      }
    }
  </style>
</head>

<body>
  <header>
    <?php include '../includes/navbar.php'; ?>
  </header>

  <div class="container">
    <h2>All Available Flights</h2>
    <table>
      <thead>
        <tr>
          <th>From</th>
          <th>To</th>
          <th>Airline</th>
          <th>Capacity</th>
          <th>Seat Breakdown</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($flights): ?>
          <?php foreach ($flights as $f): ?>
            <tr>
              <td><?= htmlspecialchars($f['departure_city']) ?></td>
              <td><?= htmlspecialchars($f['destination_city']) ?></td>
              <td><?= htmlspecialchars($f['flight_used']) ?></td>
              <td><?= (int)$f['seating_capacity'] ?></td>
              <td class="seat-breakdown">
                <?php if (!empty($seatInfo[$f['id']])): ?>
                  <?php foreach ($seatInfo[$f['id']] as $si): ?>
                    <div>
                      <strong><?= htmlspecialchars($si['seat_type']) ?>:</strong>
                      <?= (int)$si['available'] ?> @ KES <?= number_format($si['seat_price'], 2) ?>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <div>No seat data</div>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="6">No flights found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

  </div>

  <footer>&copy; <?= date('Y') ?> Ricksen Flights</footer>
</body>

</html>