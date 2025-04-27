<?php
include '../models/user_functions.php';
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: ../index.php');  // Redirect to login if the user is not logged in
    exit;
}

// Handle the form submission to create a new booking
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $flight_id = $_POST['flight_id'];
    $seat_type = $_POST['seat_type'];
    $total_price = $_POST['total_price'];
    $number_of_people = $_POST['number_of_people'];

    $status = 'booked';

    $query = "INSERT INTO bookings (user_id, flight_id, type, total, number_of_people, status) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iisdis", $user_id, $flight_id, $seat_type, $total_price, $number_of_people, $status);

    if ($stmt->execute()) {
        echo "<p>Booking created successfully!</p>";
        header('Location: user_dashboard.php');
        exit;
    } else {
        echo "<p>There was an error creating your booking. Please try again.</p>";
    }
}
$seat_types = [];
// Fetch available flights for the booking form
$flightsQuery = "SELECT id, departure_city, destination_city FROM flights";
$flightsResult = $conn->query($flightsQuery);
$flights = [];
while ($flight = $flightsResult->fetch_assoc()) {
    $flights[] = $flight;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Booking - Ricksen Flights</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Full-width Header */
        header {
            background: linear-gradient(90deg, #004080, #0066cc);
            padding: 1rem 2rem;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 100%;
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

        .container {
            width: 100%;
            max-width: 800px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 16px;
            font-weight: bold;
            color: #555;
        }

        select,
        input[type="number"],
        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 5px;
            width: 100%;
            box-sizing: border-box;
        }

        input[type="number"] {
            max-width: 200px;
        }

        button {
            padding: 12px;
            font-size: 16px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .price-display {
            font-size: 16px;
            color: #333;
            margin-top: 10px;
            font-weight: bold;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }

            label,
            input[type="number"],
            select,
            input[type="text"],
            button {
                font-size: 14px;
            }

            nav ul {
                flex-direction: column;
                align-items: center;
            }

            nav a {
                padding: 1rem;
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <header>
        <?php include '../includes/navbar.php'; ?>
    </header>

    <div class="container">
        <h2>Create New Booking</h2>

        <form method="POST">
            <label for="flight_id">Flight:</label>
            <select name="flight_id" id="flight_id" required>
                <option value="">Select a flight</option>
                <?php
                foreach ($flights as $flight) {
                    echo '<option value="' . htmlspecialchars($flight['id']) . '">' . htmlspecialchars($flight['departure_city']) . ' to ' . htmlspecialchars($flight['destination_city']) . '</option>';
                }
                ?>
            </select>

            <label for="seat_type">Seat Type:</label>
            <select name="seat_type" id="seat_type" required>
                <option value="">Select Seat Type</option>
                <?php
                // Only show seat types if the flight has been selected
                if (!empty($seat_types)) {
                    foreach ($seat_types as $seat_type) {
                        echo '<option value="' . htmlspecialchars($seat_type['seat_type']) . '" data-price="' . htmlspecialchars($seat_type['seat_price']) . '">' . htmlspecialchars($seat_type['seat_type']) . ' - $' . htmlspecialchars($seat_type['seat_price']) . '</option>';
                    }
                }
                ?>
            </select>
            <label for="number_of_people">Number of People:</label>
            <input type="number" id="number_of_people" name="number_of_people" value="1" min="1" required>


            <!-- Displayed field -->
            <label for="total_price">Total Price:</label>
            <input type="text" id="total_price_display" disabled>
            <input type="hidden" id="total_price" name="total_price">


            <button type="submit">Book Flight</button>
        </form>

    </div>

    <script>
        const flightSelect = document.getElementById('flight_id');
        const seatTypeSelect = document.getElementById('seat_type');
        const numberOfPeople = document.getElementById('number_of_people');
        const totalPriceDisplay = document.getElementById('total_price');
        document.addEventListener('DOMContentLoaded', function() {
            const flightSelect = document.getElementById('flight_id');
            const seatTypeSelect = document.getElementById('seat_type');
            const numberOfPeopleInput = document.getElementById('number_of_people');
            const totalPriceDisplay = document.getElementById('total_price_display');
            const totalPriceHidden = document.getElementById('total_price');

            flightSelect.addEventListener('change', function() {
                const flightId = flightSelect.value;
                if (flightId) {
                    fetch(`get_seat_types.php?flight_id=${flightId}`)
                        .then(response => response.json())
                        .then(data => {
                            seatTypeSelect.innerHTML = '<option value="">Select Seat Type</option>';
                            data.forEach(seat => {
                                const option = document.createElement('option');
                                option.value = seat.seat_type;
                                option.dataset.price = seat.seat_price;
                                option.textContent = `${seat.seat_type} - $${seat.seat_price}`;
                                seatTypeSelect.appendChild(option);
                            });
                        });
                }
            });
            seatTypeSelect.addEventListener('change', updatePrice);
            numberOfPeopleInput.addEventListener('input', updatePrice);

            function updatePrice() {
                const selectedOption = seatTypeSelect.selectedOptions[0];
                if (!selectedOption) return;

                const seatPrice = parseFloat(selectedOption.dataset.price);
                const peopleCount = parseInt(numberOfPeopleInput.value) || 1;

                const totalPrice = seatPrice * peopleCount;

                totalPriceDisplay.value = `$${totalPrice.toFixed(2)}`;
                totalPriceHidden.value = totalPrice.toFixed(2);
            }

        });

        seatTypeSelect.addEventListener('change', updatePrice);
        numberOfPeople.addEventListener('input', updatePrice);

        seatTypeSelect.addEventListener('change', updatePrice);
        document.getElementById('number_of_people').addEventListener('input', updatePrice);

        // Initial price update if already selected flight/seat
        updatePrice();
    </script>
</body>

</html>