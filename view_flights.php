<?php
// view_flights.php – Display all flights with seat-type and available seat counts
include 'connect.php';

// 1. Fetch all flights
$flights = [];
$sql = "SELECT id, departure_city, destination_city, flight_used, price, seating_capacity FROM flights";
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
    GROUP BY fst.id
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
    body {
      background: url('images/take_off.jpg') no-repeat center center/cover;
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-size: cover;
      color: white;
    }
    header {
      background: rgba(0, 51, 102, 0.8); /* Semi-transparent header */
      padding: 20px;
      text-align: center;
      color: #fff;
    }
    nav {
      background: #003366;
      display: flex;
      justify-content: center;
      padding: 12px;
    }
    nav a {
      color: #fff;
      text-decoration: none;
      margin: 0 15px;
      font-size: 16px;
    }
    nav a:hover {
      color: #1abc9c;
    }
    .container {
      max-width: 900px;
      margin: 30px auto;
      background: rgba(0, 0, 0, 0.5); /* Semi-transparent background for better contrast */
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
    }
    h2 {
      color: #fff;
      margin-bottom: 20px;
      text-align: center;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 12px;
      text-align: center;
      color: #fff;
    }
    th {
      background: #004080;
    }
    td {
      background: rgba(0, 0, 0, 0.4); /* Slightly darker for better readability */
    }
    .seat-breakdown div {
      margin: 4px 0;
    }
    .back-button {
      display: block;
      width: 200px;
      margin: 20px auto;
      padding: 12px;
      background: #004080;
      color: #fff;
      text-align: center;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
    }
    .back-button:hover {
      background: #0066cc;
    }
    footer {
      background: #003366;
      padding: 16px;
      text-align: center;
      color: #fff;
    }
  </style>
</head>
<body>
  <header>
    <h1>Ricksen Flights</h1>
    <nav>
      <a href="home.php">Home</a>
      <a href="flight.php">Book Flight</a>
      <a href="contact.php">Contact Us</a>
    </nav>
  </header>
  
  <div class="container">
    <h2>All Available Flights</h2>
    <table>
      <thead>
        <tr>
          <th>From</th>
          <th>To</th>
          <th>Airline</th>
          <th>Price (KES)</th>
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
            <td><?= number_format($f['price'], 2) ?></td>
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
          <tr><td colspan="6">No flights found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
    <a href="flight.php" class="back-button">Back to Booking</a>
  </div>
  
  <footer>&copy; <?= date('Y') ?> Ricksen Flights</footer>
</body>
</html>
