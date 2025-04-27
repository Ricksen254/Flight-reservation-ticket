<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Flight Booking System</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: white;
            background: #f0f0f0;
        }

        /* Navigation Bar Styling */
        header {
            background: linear-gradient(to right, #003366, #004080);
            padding: 20px;
            text-align: center;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
            gap: 30px;
        }

        nav ul li {
            display: inline;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            font-size: 18px;
            padding: 10px 15px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            transition: 0.3s;
        }

        nav ul li a:hover, nav ul li a.active {
            background: rgba(255, 255, 255, 0.5);
            font-weight: bold;
        }

        /* Keep "Services" on the same page */
        nav ul li a#services-link {
            cursor: pointer;
        }

        /* Contact Section Styling */
        .contact-section {
            background: url('images/search.jpg') no-repeat center center fixed;
            background-size: cover;
            padding: 80px 20px;
            text-align: center;
        }

        .contact-section h2 {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .contact-section p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .contact-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
        }

        .contact-info, .contact-form {
            background: rgba(0, 0, 50, 0.8);
            padding: 20px;
            border-radius: 10px;
            width: 40%;
            min-width: 300px;
            text-align: left;
        }

        .contact-info p {
            font-size: 18px;
            margin: 10px 0;
        }

        .contact-form input, .contact-form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .contact-form button {
            background: red;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            font-size: 18px;
            border-radius: 5px;
            transition: 0.3s;
        }

        .contact-form button:hover {
            background: darkred;
        }

        /* Directions Section */
        .directions {
            background: rgba(0, 0, 50, 0.8);
            padding: 20px;
            border-radius: 10px;
            color: white;
            text-align: center;
            margin: 20px auto;
            width: 80%;
            max-width: 600px;
        }

        .directions p {
            font-size: 16px;
            margin: 10px 0;
        }

        .map-link {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            background: red;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .map-link:hover {
            background: darkred;
        }

        /* Footer Styling */
        footer {
            background: #003366;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 50px;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 10px;
        }

        .social-links a {
            color: white;
            font-size: 24px;
            transition: 0.3s;
        }

        .social-links a:hover {
            color: #ffcc00;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            nav ul {
                flex-direction: column;
                gap: 10px;
            }

            .contact-container {
                flex-direction: column;
                align-items: center;
            }

            .contact-info, .contact-form {
                width: 80%;
            }
        }
    </style>
</head>
<body>

<!-- Navigation -->
<header>
    <nav>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a id="services-link" class="active" onclick="return false;">Services</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="contact.php" class="active">Contact</a></li>
        </ul>
    </nav>
</header>

<!-- Contact Section -->
<section class="contact-section">
    <h2>Contact Us</h2>
    <p>We'd love to hear from you! Get in touch using the form below or visit us.</p>

    <div class="contact-container">
        <!-- Contact Info -->
        <div class="contact-info">
            <p><strong>Address:</strong> 123 Aviation Street, Nairobi, Kenya</p>
            <p><strong>Phone:</strong> +254 791 440 496</p>
            <p><strong>Email:</strong> support@skyreserve.com</p>
            <p><strong>Working Hours:</strong> Mon-Fri, 9AM - 6PM</p>
        </div>

        <!-- Contact Form -->
        <div class="contact-form">
            <form>
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <input type="text" name="subject" placeholder="Subject" required>
                <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
                <button type="submit">Send Message</button>
            </form>
        </div>
    </div>
</section>

<!-- Directions Section -->
<div class="directions">
    <h3>How to Find Us</h3>
    <p>We are located at <strong>123 Aviation Street, Nairobi, Kenya</strong>.</p>
    <p>📍 <strong>From Nairobi CBD:</strong> Take Uhuru Highway towards the airport, turn left onto Aviation Street, and continue straight for 500 meters.</p>
    <p>🚍 <strong>By Public Transport:</strong> Take Matatu Route 34 from the city center and alight at the Aviation Street junction.</p>
    <p>🚗 <strong>By Car:</strong> Search for "<strong>SkyReserve Office</strong>" on Google Maps or use the link below.</p>
    
    <a href="https://www.google.com/maps/place/123+Aviation+Street,Nairobi" target="_blank" class="map-link">
        📌 Open in Google Maps
    </a>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2025 SkyReserve. All rights reserved.</p>
    <div class="social-links">
        <a href="#"><i class="fab fa-facebook"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
    </div>
</footer>

</body>
</html>
