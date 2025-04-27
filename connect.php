<?php
$servername = "localhost";
$username = "root"; // Or your DB username
$password = "";     // Or your DB password
$dbname = "project_index"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>