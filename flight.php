<?php
// flight.php – Select Flight and Book
include 'connect.php';

// Fetch Flights
$flights = [];
$res = $conn->query("SELECT id, departure_city, destination_city, flight_used FROM flights");
while ($row = $res->fetch_assoc()) {
    $flights[$row['id']] = $row;
}
$res->free();

// Fetch Seat Types
$seatTypes = [];
$res = $conn->query("SELECT id AS seat_type_id, flight_id, seat_type, seat_price FROM flight_seat_types ORDER BY FIELD(seat_type,'Economy','Business','First Class')");
while ($row = $res->fetch_assoc()) {
    $seatTypes[$row['flight_id']][] = $row;
}
$res->free();

// Fetch Available Seats (only show 'available' seats)
$seatNumbers = [];
$res = $conn->query("SELECT flight_id, seat_type_id, seat_number FROM seats WHERE status='available' ORDER BY seat_number");
while ($row = $res->fetch_assoc()) {
    $seatNumbers[$row['flight_id']][$row['seat_type_id']][] = $row['seat_number'];
}
$res->free();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Book a Flight | Ricksen Flights</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; margin: 0; background: url('images/take off.jpg') no-repeat center center/cover; }
    header, footer { background: #003366; color: #fff; text-align: center; padding: 20px; }
    nav { background: #002244; display: flex; justify-content: center; padding: 10px; }
    nav a { color: #fff; margin: 0 12px; text-decoration: none; font-size: 16px; }
    nav a:hover { color: #1abc9c; }
    .container { max-width: 800px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    select, input, button { width: 100%; padding: 12px; margin-bottom: 16px; border: 1px solid #ccc; border-radius: 6px; font-size: 15px; }
    button { background: #004080; color: white; font-weight: bold; border: none; cursor: pointer; }
    button:hover { background: #0066cc; }
    .info { font-size: 14px; color: #444; margin-bottom: 16px; }
    .payment-details { display: none; background: #f9f9f9; padding: 10px; border-radius: 6px; margin-bottom: 16px; }
    h2 { text-align: center; color: #003366; }
  </style>
</head>
<body>
<header>Ricksen Flights | Book Your Trip</header>
<nav>
  <a href="home.php">Home</a>
  <a href="flight.php">Book Flight</a>

  <a href="contact.php">Contact</a>
</nav>

<div class="container">
  <h2>Book a Flight</h2>
  <form action="confirm_booking.php" method="POST" id="bookingForm">
    <!-- Flight -->
    <select name="flight_id" id="flightSelect" required>
      <option value="" disabled selected>Select Flight</option>
      <?php foreach ($flights as $id => $f): ?>
        <option value="<?= $id ?>"><?= htmlspecialchars("{$f['departure_city']} → {$f['destination_city']} ({$f['flight_used']})") ?></option>
      <?php endforeach; ?>
    </select>

    <!-- Seat Type -->
    <select name="seat_type_id" id="typeSelect" required disabled>
      <option value="" disabled selected>Select Seat Type</option>
    </select>

    <!-- Seat Number -->
    <select name="seat_number" id="numberSelect" required disabled>
      <option value="" disabled selected>Select Seat Number</option>
    </select>

    <p class="info" id="priceInfo"></p>

    <!-- Passenger Info -->
    <input type="text" name="fullname" placeholder="Full Name" required />
    <input type="email" name="email" placeholder="Email Address" required />
    <input type="tel" name="phone" placeholder="Phone Number (10 digits)" pattern="\d{10}" required />
    <input type="text" name="passport" placeholder="Passport Number" required />

    <input type="datetime-local" name="booking_datetime" id="bookingDatetime" required />

    <!-- Payment Method -->
    <select name="payment_method" id="paymentMethod" required>
      <option value="" disabled selected>Select Payment Method</option>
      <optgroup label="Bank Transfers">
        <option value="bank_xyz">XYZ Bank</option>
        <option value="bank_abc">ABC Bank</option>
        <option value="bank_def">DEF Bank</option>
      </optgroup>
      <optgroup label="Mobile Money">
        <option value="mpesa">M-Pesa</option>
        <option value="airtel_money">Airtel Money</option>
      </optgroup>
    </select>

    <!-- Payment Details -->
    <div id="bankDetails" class="payment-details">
      <div><strong>XYZ Bank:</strong> Acc: 123456789, Branch: Nairobi</div>
      <div><strong>ABC Bank:</strong> Acc: 987654321, Branch: Mombasa</div>
      <div><strong>DEF Bank:</strong> Acc: 555666777, Branch: Kisumu</div>
    </div>
    <div id="mpesaDetails" class="payment-details">
      <div><strong>Paybill:</strong> 123456</div>
      <div><strong>Account:</strong> FlightBooking</div>
    </div>
    <div id="airtelDetails" class="payment-details">
      <div><strong>Airtel No:</strong> 254700123456</div>
      <div><strong>Name:</strong> RicksenFlights</div>
    </div>

    <button type="submit">Book Flight</button>
  </form>
</div>

<footer>&copy; <?= date('Y') ?> Ricksen Flights</footer>

<script>
// Flight and Seat Logic
const seatTypes = <?= json_encode($seatTypes) ?>;
const seatNumbers = <?= json_encode($seatNumbers) ?>;
const flightEl = document.getElementById('flightSelect');
const typeEl = document.getElementById('typeSelect');
const numberEl = document.getElementById('numberSelect');
const priceEl = document.getElementById('priceInfo');
const paymentEl = document.getElementById('paymentMethod');
const bookingDatetime = document.getElementById('bookingDatetime');

// Prevent past bookings
bookingDatetime.min = new Date().toISOString().slice(0,16);

// Update seat types when flight changes
flightEl.addEventListener('change', () => {
  const fid = flightEl.value;
  typeEl.innerHTML = '<option disabled selected>Select Seat Type</option>';
  numberEl.innerHTML = '<option disabled selected>Select Seat Number</option>';
  typeEl.disabled = numberEl.disabled = true;
  priceEl.textContent = '';

  (seatTypes[fid] || []).forEach(st => {
    const opt = new Option(`${st.seat_type} – KES ${parseFloat(st.seat_price).toFixed(2)}`, st.seat_type_id);
    opt.dataset.price = st.seat_price;
    typeEl.append(opt);
  });
  typeEl.disabled = false;
});

// Update seat numbers when type changes
typeEl.addEventListener('change', () => {
  const fid = flightEl.value;
  const tid = typeEl.value;
  numberEl.innerHTML = '<option disabled selected>Select Seat Number</option>';
  priceEl.textContent = `Price: KES ${parseFloat(typeEl.selectedOptions[0].dataset.price).toFixed(2)}`;
  // Update available seat numbers when seat type changes
  (seatNumbers[fid]?.[tid] || []).forEach(sn => numberEl.append(new Option(sn, sn)));
  numberEl.disabled = false;
});

// Show payment details based on selected payment method
paymentEl.addEventListener('change', () => {
  ['bankDetails', 'mpesaDetails', 'airtelDetails'].forEach(id => document.getElementById(id).style.display = 'none');
  if (paymentEl.value.startsWith('bank_')) document.getElementById('bankDetails').style.display = 'block';
  if (paymentEl.value === 'mpesa') document.getElementById('mpesaDetails').style.display = 'block';
  if (paymentEl.value === 'airtel_money') document.getElementById('airtelDetails').style.display = 'block';
});
</script>
</body>
</html>
