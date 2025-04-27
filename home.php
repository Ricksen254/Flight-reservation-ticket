<?php
session_start(); // Start session to track user login

// Check if the user is logged in and store their role in session

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Flight Booking System</title>
  <style>
    /* General Styles */
    *,
    *::before,
    *::after {
      box-sizing: border-box;
    }

    body,
    html {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: 'Segoe UI', sans-serif;
    }

    /* Header + Navigation */
    header {
      position: fixed;
      top: 0;
      width: 100%;
      background: rgba(0, 0, 0, 0.6);
      /* Semi-transparent background for header */
      z-index: 1000;
    }

    .header-inner {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px 10px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    header h1 {
      color: rgba(50, 10, 248, 0.868);
      font-size: 24px;
      margin: 0;
    }

    nav {
      display: flex;
      gap: 15px;
    }

    nav a {
      color: #fff;
      text-decoration: none;
      font-size: 18px;
      padding: 8px 12px;
      transition: background 0.3s;
    }

    nav a:hover {
      background: #0056b3;
      border-radius: 4px;
    }

    /* Dropdown Menu */
    .dropdown {
      position: relative;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #333;
      min-width: 160px;
      z-index: 1;
    }

    .dropdown:hover .dropdown-content {
      display: block;
    }

    .dropdown-content a {
      color: white;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
      text-align: left;
    }

    .dropdown-content a:hover {
      background-color: #575757;
    }

    /* Hero Section */
    .hero {
      height: 100vh;
      background: url('images/take off.jpg') no-repeat center center/cover;
      background-size: cover;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: white;
      text-align: center;
      padding: 0 20px;
      backdrop-filter: blur(5px);
      /* Apply blur effect to improve readability */
    }

    .hero h2 {
      font-size: 40px;
      margin-bottom: 20px;
      text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.5);
      font-weight: bold;
    }

    .hero p {
      font-size: 22px;
      margin-bottom: 30px;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
      line-height: 1.5;
      max-width: 800px;
    }

    .hero button {
      padding: 15px 25px;
      background-color: #007bff;
      color: white;
      font-size: 18px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .hero button:hover {
      background-color: #0056b3;
    }

    /* Footer */
    footer {
      background: #003366;
      color: #fff;
      text-align: center;
      padding: 20px 15px;
      position: relative;
      bottom: 0;
      width: 100%;
      font-size: 14px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    footer a {
      color: #fff;
      text-decoration: none;
      margin: 0 10px;
    }

    footer a:hover {
      text-decoration: underline;
    }

    .footer-inner {
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .footer-social-icons {
      display: flex;
      gap: 15px;
    }

    .footer-social-icons a {
      font-size: 20px;
      color: #fff;
      transition: color 0.3s ease;
    }

    .footer-social-icons a:hover {
      color: #1abc9c;
    }
  </style>
</head>

<body>

  <!-- Header -->
  <header>
    <div class="header-inner">
      <h1>Ricksen Flights</h1>
      <nav>
        <a href="#">Home</a>
        <a href="flight.php">Book Flight</a>
        <a href="about.html">About Us</a>
        <a href="contact.php">Contact</a>


        <!-- Account Dropdown -->
        <div class="dropdown">
          <a href="#">Account</a>
          <div class="dropdown-content">

            <a href="admin_login.php">Admin Dashboard</a>

            <a href="index.php">User Home</a>

          </div>
        </div>
      </nav>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <h2>Welcome to Ricksen Flights!</h2>
    <p>Your next unforgettable adventure is just a click away. Whether you're planning a relaxing beach getaway or an adventurous exploration, we offer the best flight deals to suit your needs. Book with us today and experience comfort, affordability, and exceptional service.</p>
    <p>Let us take you to your dream destination, hassle-free. Your journey starts right here!</p>
    <a href="view_flights.php">
      <button>Explore Available Flights</button>
    </a>
  </section>

  <!-- Footer -->
  <footer>
    <div class="footer-inner">
      <p>&copy; 2025 Ricksen Flights | All Rights Reserved | Ricksen Investments</p>
      <div class="footer-social-icons">
        <a href="https://facebook.com" target="_blank">Facebook</a>
        <a href="https://twitter.com" target="_blank">Twitter</a>
        <a href="https://instagram.com" target="_blank">Instagram</a>
      </div>
    </div>
  </footer>

</body>

</html>