<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once 'database/db_connect.php'; // Make sure this path is correct!

$errors = '';
$success = '';

function sanitize($input)
{
    return htmlspecialchars(strip_tags(trim($input)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'register') {
        handleRegister($conn);
    } elseif ($action === 'login') {
        handleLogin($conn);
    }
}

function handleRegister($conn)
{
    global $errors, $success;

    $fullname = sanitize($_POST['fullname']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = sanitize($_POST['phone_number']);
    $password = $_POST['password'];
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors = "Email already exists.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, phone_number, password_hash) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $fullname, $email, $phone, $password_hash);

        if ($stmt->execute()) {
            $success = "Registration successful! Please log in.";
        } else {
            $errors = "Registration failed. Please try again.";
        }
    }
    $stmt->close();
}

function handleLogin($conn)
{
    global $errors;

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        header("Location: user/user_dashboard.php");
        exit();
    } else {
        $errors = "Invalid email or password.";
    }
    $stmt->close();
}
