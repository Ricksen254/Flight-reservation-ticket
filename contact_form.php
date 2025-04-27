<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (!$name || !$email || !$subject || !$message) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
        exit;
    }

    // Here you would typically:
    // 1. Save to database (if needed)
    // 2. Send email notification
    // For now, we'll just return success
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Thank you for your message. We will get back to you soon!'
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
